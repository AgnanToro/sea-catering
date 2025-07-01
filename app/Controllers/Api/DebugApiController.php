<?php

namespace App\Controllers\Api;

use App\Controllers\Api\BaseApiController;
use App\Models\SubscriptionModel;

class DebugApiController extends BaseApiController
{
    protected $subscriptionModel;

    public function __construct()
    {
        $this->subscriptionModel = new SubscriptionModel();
    }

    /**
     * Debug endpoint - get subscription without auth
     */
    public function getSubscription($id = null)
    {
        try {
            if (!$id) {
                return $this->error('ID is required', 400);
            }

            $subscription = $this->subscriptionModel->getSubscriptionWithUser($id);
            
            if (!$subscription) {
                return $this->error('Subscription not found', 404);
            }

            return $this->success($subscription, 'Subscription retrieved successfully');

        } catch (\Exception $e) {
            log_message('error', 'Debug get subscription error: ' . $e->getMessage());
            return $this->error('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Debug endpoint - check session
     */
    public function checkSession()
    {
        $session = session();
        
        $sessionData = [
            'isLoggedIn' => $session->get('isLoggedIn'),
            'user_id' => $session->get('user_id'),
            'email' => $session->get('email'),
            'name' => $session->get('name'),
            'is_admin' => $session->get('is_admin'),
            'all_data' => $session->get()
        ];
        
        return $this->success($sessionData, 'Session data retrieved');
    }

    /**
     * Debug endpoint - test auth filter
     */
    public function testAuth()
    {
        // This should be called with auth filter
        $userData = $this->request->userData ?? null;
        
        if (!$userData) {
            return $this->error('No user data in request', 401);
        }

        return $this->success($userData, 'Authentication working');
    }
}
