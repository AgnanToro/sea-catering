<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $data = [
            [
                'name' => 'Admin SEA Apps',
                'email' => 'admin@seaapps.com',
                'phone' => '081234567890',
                'password' => password_hash('Admin123!', PASSWORD_DEFAULT),
                'is_admin' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'User Demo',
                'email' => 'user@seaapps.com',
                'phone' => '081234567891',
                'password' => password_hash('User123!', PASSWORD_DEFAULT),
                'is_admin' => false,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Insert data
        $this->db->table('users')->insertBatch($data);
    }
}
