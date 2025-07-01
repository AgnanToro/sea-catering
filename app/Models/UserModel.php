<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
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
        'password',
        'is_admin',
        'email_verified_at',
        'remember_token'
    ];

    protected $searchFields = ['name', 'email', 'phone'];

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'phone' => 'permit_empty|regex_match[/^(\+62|62|0)8[1-9][0-9]{6,9}$/]',
        'password' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Email sudah terdaftar',
            'valid_email' => 'Format email tidak valid'
        ],
        'phone' => [
            'regex_match' => 'Format nomor HP tidak valid. Gunakan format 08xxxxxxxxxx'
        ],
        'password' => [
            'min_length' => 'Password minimal 8 karakter'
        ]
    ];

    // Hash password before saving
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    /**
     * Verify user password
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email)
    {
        $result = $this->where('email', $email)->first();
        return $result ? (array) $result : null;
    }

    /**
     * Create user with validation
     */
    public function createUser(array $data)
    {
        // Enhanced password validation sesuai requirement Level 4
        if (isset($data['password'])) {
            $password = $data['password'];
            
            if (strlen($password) < 8) {
                return [
                    'success' => false,
                    'errors' => ['Password minimal 8 karakter!']
                ];
            }

            // Password harus mengandung: uppercase, lowercase, number, dan special character
            $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/';
            if (!preg_match($passwordRegex, $password)) {
                return [
                    'success' => false,
                    'errors' => ['Password harus mengandung minimal 8 karakter dengan huruf kecil, huruf besar, angka, dan karakter khusus (!@#$%^&*)']
                ];
            }
        }

        if (!$this->validate($data)) {
            return [
                'success' => false,
                'errors' => $this->errors()
            ];
        }

        $id = $this->insert($data);
        
        if ($id) {
            $user = $this->find($id);
            return [
                'success' => true,
                'data' => $user ? (array) $user : null
            ];
        }

        return [
            'success' => false,
            'errors' => ['Failed to create user']
        ];
    }

    /**
     * Get user subscriptions
     */
    public function getUserSubscriptions(int $userId)
    {
        return $this->db->table('subscriptions')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
