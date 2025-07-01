<?php

namespace App\Controllers\Api;

use App\Controllers\Api\BaseApiController;
use App\Models\UserModel;
use App\Libraries\JWTLibrary;

class AuthController extends BaseApiController
{
    protected $userModel;
    protected $jwt;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->jwt = new JWTLibrary();
    }

    /**
     * User login
     * POST /api/auth/login
     */
    public function login()
    {
        // Handle both JSON and form data
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        
        $email = $input['email'] ?? $this->request->getPost('email');
        $password = $input['password'] ?? $this->request->getPost('password');

        // Validate input
        if (empty($email) || empty($password)) {
            return $this->error('Email dan password harus diisi!', 400);
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('Format email tidak valid!', 400);
        }

        try {
            // Find user by email
            $user = $this->userModel->findByEmail($email);
            
            if (!$user) {
                return $this->error('Email atau password salah!', 401);
            }

            // Verify password
            if (!$this->userModel->verifyPassword($password, $user['password'])) {
                return $this->error('Email atau password salah!', 401);
            }

            // Generate JWT token
            $tokenPayload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'is_admin' => (bool)$user['is_admin']
            ];

            $token = $this->jwt->generateToken($tokenPayload);

            // Remove password from response
            unset($user['password']);

            return $this->success([
                'message' => 'Login berhasil!',
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'isAdmin' => (bool)$user['is_admin']
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat login', 500);
        }
    }

    /**
     * User registration
     * POST /api/auth/register
     */
    public function register()
    {
        // Handle both JSON and form data
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        
        $name = $input['name'] ?? $this->request->getPost('name');
        $email = $input['email'] ?? $this->request->getPost('email');
        $phone = $input['phone'] ?? $this->request->getPost('phone');
        $password = $input['password'] ?? $this->request->getPost('password');

        // Validate input
        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            return $this->error('Semua field harus diisi!', 400);
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('Format email tidak valid!', 400);
        }

        // Validate phone format (Indonesian phone number)
        if (!preg_match('/^(\+62|62|0)8[1-9][0-9]{6,9}$/', $phone)) {
            return $this->error('Format nomor HP tidak valid! Gunakan format 08xxxxxxxxxx', 400);
        }

        // Enhanced password validation sesuai requirement Level 4
        if (strlen($password) < 8) {
            return $this->error('Password minimal 8 karakter!', 400);
        }

        // Password harus mengandung: uppercase, lowercase, number, dan special character
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/', $password)) {
            return $this->error('Password harus mengandung minimal 8 karakter dengan huruf kecil, huruf besar, angka, dan karakter khusus (!@#$%^&*)', 400);
        }

        try {
            // Create user
            $result = $this->userModel->createUser([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'is_admin' => false
            ]);

            if (!$result['success']) {
                return $this->validationError($result['errors']);
            }

            $user = $result['data'];

            // Generate JWT token
            $tokenPayload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'is_admin' => (bool)$user['is_admin']
            ];

            $token = $this->jwt->generateToken($tokenPayload);

            // Remove password from response
            unset($user['password']);

            return $this->success([
                'message' => 'Registrasi berhasil!',
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'isAdmin' => (bool)$user['is_admin']
                ]
            ], 201);

        } catch (\Exception $e) {
            log_message('error', 'Registration error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat registrasi', 500);
        }
    }

    /**
     * User logout
     * POST /api/auth/logout
     */
    public function logout()
    {
        // In JWT, logout is handled client-side by removing the token
        // But we can log the logout action here if needed
        return $this->success(null, 'Logout berhasil!');
    }

    /**
     * Get user profile
     * GET /api/auth/profile
     */
    public function profile()
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData) {
                return $this->error('Unauthorized access', 401);
            }

            $user = $this->userModel->find($userData['user_id']);
            
            if (!$user) {
                return $this->error('User not found', 404);
            }

            // Remove password from response
            unset($user['password']);

            return $this->success([
                'user' => $user
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Profile error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengambil profil', 500);
        }
    }
}
