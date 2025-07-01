<?php

namespace App\Controllers;

class TestController extends BaseController
{
    public function index()
    {
        return 'Test endpoint working!';
    }
    
    public function dbTest()
    {
        $db = \Config\Database::connect();
        
        try {
            $query = $db->query("SELECT COUNT(*) as count FROM users");
            $result = $query->getRow();
            
            return "Database connection OK. Users count: " . $result->count;
        } catch (\Exception $e) {
            return "Database error: " . $e->getMessage();
        }
    }
    
    public function loginTest()
    {
        $userModel = new \App\Models\UserModel();
        
        try {
            $user = $userModel->findByEmail('admin@seaapps.com');
            
            if ($user) {
                return "User found: " . $user['name'];
            } else {
                return "User not found";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    
    public function sessionTest()
    {
        $session = session();
        
        $sessionData = [
            'isLoggedIn' => $session->get('isLoggedIn'),
            'user_id' => $session->get('user_id'),
            'email' => $session->get('email'),
            'name' => $session->get('name'),
            'is_admin' => $session->get('is_admin')
        ];
        
        return json_encode($sessionData, JSON_PRETTY_PRINT);
    }
    
    public function apiTest()
    {
        // Test API endpoint without filter
        $subscriptionModel = new \App\Models\SubscriptionModel();
        
        try {
            $subscriptions = $subscriptionModel->findAll();
            
            $response = [
                'success' => true,
                'count' => count($subscriptions),
                'data' => $subscriptions
            ];
            
            return $this->response->setJSON($response);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
