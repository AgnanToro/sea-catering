<?php

namespace App\Controllers;

class DebugController extends BaseController
{
    public function subscriptionDetail($id = null)
    {
        $subscriptionModel = new \App\Models\SubscriptionModel();
        
        try {
            if (!$id) {
                $id = 4; // Default test ID
            }
            
            $subscription = $subscriptionModel->getSubscriptionWithUser($id);
            
            if (!$subscription) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Subscription not found',
                    'data' => null
                ]);
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Subscription detail retrieved successfully',
                'data' => $subscription
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
    
    public function sessionData()
    {
        $session = session();
        
        $sessionData = [
            'isLoggedIn' => $session->get('isLoggedIn'),
            'user_id' => $session->get('user_id'),
            'email' => $session->get('email'),
            'name' => $session->get('name'),
            'is_admin' => $session->get('is_admin'),
            'all_session_data' => $session->get()
        ];
        
        return $this->response->setJSON($sessionData);
    }
}
