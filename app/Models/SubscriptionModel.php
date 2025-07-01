<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'user_id',
        'plan',
        'meals_per_day',
        'delivery_days',
        'price',
        'start_date',
        'end_date',
        'status',
        'allergies',
        'special_notes'
    ];

    protected $searchFields = ['plan', 'status'];

    // Validation rules
    protected $validationRules = [
        'user_id' => 'required|integer',
        'plan' => 'required|max_length[100]',
        'meals_per_day' => 'required|integer|greater_than[0]',
        'delivery_days' => 'required',
        'price' => 'required|decimal',
        'start_date' => 'required|valid_date',
        'status' => 'permit_empty|in_list[active,paused,cancelled,completed]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer'
        ],
        'plan' => [
            'required' => 'Plan is required'
        ],
        'meals_per_day' => [
            'required' => 'Meals per day is required',
            'greater_than' => 'Meals per day must be greater than 0'
        ],
        'price' => [
            'required' => 'Price is required',
            'decimal' => 'Price must be a valid decimal'
        ],
        'start_date' => [
            'required' => 'Start date is required',
            'valid_date' => 'Start date must be a valid date'
        ]
    ];

    /**
     * Get subscription with user data
     */
    public function getSubscriptionWithUser(int $id)
    {
        return $this->select('subscriptions.*, users.name as user_name, users.email as user_email, users.phone as user_phone')
            ->join('users', 'users.id = subscriptions.user_id')
            ->where('subscriptions.id', $id)
            ->first();
    }

    /**
     * Get subscriptions by user
     */
    public function getByUser(int $userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get active subscriptions
     */
    public function getActiveSubscriptions()
    {
        return $this->select('subscriptions.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = subscriptions.user_id')
            ->where('subscriptions.status', 'active')
            ->orderBy('subscriptions.start_date', 'ASC')
            ->findAll();
    }

    /**
     * Get subscription statistics for admin dashboard
     */
    public function getSubscriptionStats()
    {
        $stats = [];
        
        // Total subscriptions
        $stats['total'] = $this->countAll();
        
        // Active subscriptions
        $stats['active'] = $this->where('status', 'active')->countAllResults(false);
        
        // Paused subscriptions
        $stats['paused'] = $this->where('status', 'paused')->countAllResults(false);
        
        // Cancelled subscriptions
        $stats['cancelled'] = $this->where('status', 'cancelled')->countAllResults(false);
        
        // This month's subscriptions
        $stats['this_month'] = $this->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->countAllResults(false);
        
        // Revenue this month
        $revenue = $this->selectSum('price')
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->where('status !=', 'cancelled')
            ->first();
        
        $stats['revenue_this_month'] = $revenue['price'] ?? 0;
        
        return $stats;
    }

    /**
     * Create subscription with validation
     */
    public function createSubscription(array $data)
    {
        // Convert delivery_days array to JSON if it's an array
        if (isset($data['delivery_days']) && is_array($data['delivery_days'])) {
            $data['delivery_days'] = json_encode($data['delivery_days']);
        }

        if (!$this->validate($data)) {
            return [
                'success' => false,
                'errors' => $this->errors()
            ];
        }

        $id = $this->insert($data);
        
        if ($id) {
            return [
                'success' => true,
                'data' => $this->find($id)
            ];
        }

        return [
            'success' => false,
            'errors' => ['Failed to create subscription']
        ];
    }

    /**
     * Update subscription status
     */
    public function updateStatus(int $id, string $status)
    {
        $validStatuses = ['active', 'paused', 'cancelled', 'completed'];
        
        if (!in_array($status, $validStatuses)) {
            return [
                'success' => false,
                'errors' => ['Invalid status']
            ];
        }

        $updated = $this->update($id, ['status' => $status]);
        
        if ($updated) {
            return [
                'success' => true,
                'data' => $this->find($id)
            ];
        }

        return [
            'success' => false,
            'errors' => ['Failed to update subscription status']
        ];
    }
}
