<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactMessageModel extends Model
{
    protected $table = 'contact_messages';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'replied_at'
    ];

    protected $searchFields = ['name', 'email', 'subject', 'message'];

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email',
        'phone' => 'permit_empty|regex_match[/^(\+62|62|0)8[1-9][0-9]{6,9}$/]',
        'subject' => 'permit_empty|min_length[3]|max_length[500]',
        'message' => 'required|min_length[10]|max_length[1000]',
        'status' => 'permit_empty|in_list[unread,read,replied]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 2 karakter'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid'
        ],
        'phone' => [
            'regex_match' => 'Format nomor HP tidak valid. Gunakan format 08xxxxxxxxxx'
        ],
        'subject' => [
            'required' => 'Subject harus diisi',
            'min_length' => 'Subject minimal 5 karakter'
        ],
        'message' => [
            'required' => 'Pesan harus diisi',
            'min_length' => 'Pesan minimal 10 karakter'
        ]
    ];

    /**
     * Get messages by status
     */
    public function getByStatus(string $status = 'unread')
    {
        return $this->where('status', $status)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get unread messages count
     */
    public function getUnreadCount(): int
    {
        return $this->where('status', 'unread')->countAllResults();
    }

    /**
     * Mark message as read
     */
    public function markAsRead(int $id)
    {
        return $this->update($id, ['status' => 'read']);
    }

    /**
     * Mark message as replied
     */
    public function markAsReplied(int $id)
    {
        return $this->update($id, [
            'status' => 'replied',
            'replied_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get contact message statistics
     */
    public function getContactStats()
    {
        $stats = [];
        
        // Total messages
        $stats['total'] = $this->countAll();
        
        // Unread messages
        $stats['unread'] = $this->where('status', 'unread')->countAllResults(false);
        
        // Read messages
        $stats['read'] = $this->where('status', 'read')->countAllResults(false);
        
        // Replied messages
        $stats['replied'] = $this->where('status', 'replied')->countAllResults(false);
        
        // This month's messages
        $stats['this_month'] = $this->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->countAllResults(false);
        
        return $stats;
    }

    /**
     * Create contact message with validation
     */
    public function createMessage(array $data)
    {
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
            'errors' => ['Failed to save contact message']
        ];
    }

    /**
     * Get recent messages for dashboard
     */
    public function getRecentMessages(int $limit = 5)
    {
        return $this->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
