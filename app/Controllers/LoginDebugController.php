<?php

namespace App\Controllers;

class LoginDebugController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
        $this->session = \Config\Services::session();
    }

    public function testLogin()
    {
        $email = 'admin@seaapps.com';
        $password = 'admin123';
        
        $response = ['debug_steps' => []];
        
        try {
            $response['debug_steps'][] = '1. Starting login test';
            
            // Step 1: Find user by email
            $user = $this->userModel->findByEmail($email);
            $response['debug_steps'][] = '2. User lookup: ' . ($user ? 'Found' : 'Not found');
            
            if (!$user) {
                $response['error'] = 'User not found';
                return $this->response->setJSON($response);
            }
            
            $response['user_data'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'is_admin' => $user['is_admin'],
                'password_hash' => substr($user['password'], 0, 20) . '...'
            ];
            
            // Step 2: Verify password
            $passwordVerified = $this->userModel->verifyPassword($password, $user['password']);
            $response['debug_steps'][] = '3. Password verification: ' . ($passwordVerified ? 'Success' : 'Failed');
            
            if (!$passwordVerified) {
                // Test manual password verification
                $manualVerify = password_verify($password, $user['password']);
                $response['debug_steps'][] = '4. Manual password verify: ' . ($manualVerify ? 'Success' : 'Failed');
            }
            
            // Step 3: Test session setting
            $sessionData = [
                'isLoggedIn' => true,
                'user_id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'is_admin' => $user['is_admin'] ? true : false,
                'userRole' => $user['is_admin'] ? 'admin' : 'user'
            ];
            
            $this->session->set($sessionData);
            $response['debug_steps'][] = '5. Session data set';
            
            // Step 4: Verify session was set
            $sessionCheck = [
                'isLoggedIn' => $this->session->get('isLoggedIn'),
                'user_id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'is_admin' => $this->session->get('is_admin'),
                'userRole' => $this->session->get('userRole')
            ];
            
            $response['session_check'] = $sessionCheck;
            $response['debug_steps'][] = '6. Session verification complete';
            
            $response['success'] = true;
            $response['message'] = 'Login test successful';
            
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            $response['debug_steps'][] = 'ERROR: ' . $e->getMessage();
        }
        
        return $this->response->setJSON($response);
    }
}
