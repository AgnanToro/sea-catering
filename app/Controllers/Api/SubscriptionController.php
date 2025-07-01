<?php

namespace App\Controllers\Api;

use App\Controllers\Api\BaseApiController;
use App\Models\SubscriptionModel;

class SubscriptionController extends BaseApiController
{
    protected $subscriptionModel;

    public function __construct()
    {
        $this->subscriptionModel = new SubscriptionModel();
    }

    /**
     * Get all subscriptions (admin) or user's subscriptions
     * GET /api/subscriptions
     */
    public function index()
    {
        try {
            $userData = $this->request->userData ?? null;
            
            // Fallback to session if userData not available
            if (!$userData) {
                $session = session();
                if ($session->get('isLoggedIn')) {
                    $userData = [
                        'user_id' => $session->get('user_id'),
                        'email' => $session->get('email'),
                        'is_admin' => $session->get('is_admin') ?? false,
                        'name' => $session->get('name')
                    ];
                }
            }
            
            if (!$userData) {
                return $this->error('Unauthorized access', 401);
            }

            $page = $this->request->getGet('page') ?? 1;
            $limit = $this->request->getGet('limit') ?? 10;
            $search = $this->request->getGet('search') ?? '';

            if ($userData['is_admin']) {
                // Admin can see all subscriptions with user details
                $subscriptions = $this->subscriptionModel
                    ->select('subscriptions.*, users.name as user_name, users.email as user_email')
                    ->join('users', 'users.id = subscriptions.user_id')
                    ->orderBy('subscriptions.created_at', 'DESC')
                    ->findAll();
                
                return $this->success($subscriptions);
            } else {
                // User can only see their own subscriptions
                $subscriptions = $this->subscriptionModel->getByUser($userData['user_id']);
                
                return $this->success($subscriptions);
            }

        } catch (\Exception $e) {
            log_message('error', 'Get subscriptions error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengambil data subscription', 500);
        }
    }

    /**
     * Get subscription by ID
     * GET /api/subscriptions/{id}
     */
    public function show($id = null)
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData) {
                return $this->error('Unauthorized access', 401);
            }

            $subscription = $this->subscriptionModel->getSubscriptionWithUser($id);
            
            if (!$subscription) {
                return $this->error('Subscription not found', 404);
            }

            // Check if user can access this subscription
            if (!$userData['is_admin'] && $subscription['user_id'] != $userData['user_id']) {
                return $this->error('Access denied', 403);
            }

            return $this->success($subscription);

        } catch (\Exception $e) {
            log_message('error', 'Get subscription error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengambil data subscription', 500);
        }
    }

    /**
     * Create new subscription
     * POST /api/subscriptions
     */
    public function create()
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData) {
                return $this->error('Unauthorized access', 401);
            }
            // Ambil data dari JSON atau form
            $input = $this->request->getJSON(true) ?? $this->request->getPost();
            $data = [
                'user_id' => $userData['user_id'],
                'plan' => $input['plan'] ?? $this->request->getPost('plan'),
                'meals_per_day' => $input['mealsPerDay'] ?? $this->request->getPost('mealsPerDay'),
                'delivery_days' => $input['deliveryDays'] ?? $this->request->getPost('deliveryDays'),
                'price' => $input['price'] ?? $this->request->getPost('price'),
                'start_date' => $input['startDate'] ?? $this->request->getPost('startDate'),
                'allergies' => $input['allergies'] ?? $this->request->getPost('allergies'),
                'special_notes' => $input['specialNotes'] ?? $this->request->getPost('specialNotes'),
            ];

            $result = $this->subscriptionModel->createSubscription($data);

            if (!$result['success']) {
                return $this->validationError($result['errors']);
            }

            return $this->success($result['data'], 'Subscription berhasil dibuat!', 201);

        } catch (\Exception $e) {
            log_message('error', 'Create subscription error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat membuat subscription', 500);
        }
    }

    /**
     * Update subscription
     * PUT /api/subscriptions/{id}
     */
    public function update($id = null)
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData) {
                return $this->error('Unauthorized access', 401);
            }

            $subscription = $this->subscriptionModel->find($id);
            
            if (!$subscription) {
                return $this->error('Subscription not found', 404);
            }

            // Check if user can update this subscription
            if (!$userData['is_admin'] && $subscription['user_id'] != $userData['user_id']) {
                return $this->error('Access denied', 403);
            }

            $data = [
                'plan' => $this->request->getRawInput()['plan'] ?? $subscription['plan'],
                'meals_per_day' => $this->request->getRawInput()['mealsPerDay'] ?? $subscription['meals_per_day'],
                'delivery_days' => $this->request->getRawInput()['deliveryDays'] ?? $subscription['delivery_days'],
                'price' => $this->request->getRawInput()['price'] ?? $subscription['price'],
                'start_date' => $this->request->getRawInput()['startDate'] ?? $subscription['start_date'],
                'allergies' => $this->request->getRawInput()['allergies'] ?? $subscription['allergies'],
                'special_notes' => $this->request->getRawInput()['specialNotes'] ?? $subscription['special_notes']
            ];

            // If admin is updating, allow status change
            if ($userData['is_admin'] && isset($this->request->getRawInput()['status'])) {
                $data['status'] = $this->request->getRawInput()['status'];
            }

            $updated = $this->subscriptionModel->update($id, $data);

            if ($updated) {
                $updatedSubscription = $this->subscriptionModel->find($id);
                return $this->success($updatedSubscription, 'Subscription berhasil diupdate!');
            }

            return $this->error('Gagal mengupdate subscription', 500);

        } catch (\Exception $e) {
            log_message('error', 'Update subscription error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengupdate subscription', 500);
        }
    }

    /**
     * Delete subscription
     * DELETE /api/subscriptions/{id}
     */
    public function delete($id = null)
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData) {
                return $this->error('Unauthorized access', 401);
            }

            $subscription = $this->subscriptionModel->find($id);
            
            if (!$subscription) {
                return $this->error('Subscription not found', 404);
            }

            // Check if user can delete this subscription
            if (!$userData['is_admin'] && $subscription['user_id'] != $userData['user_id']) {
                return $this->error('Access denied', 403);
            }

            $deleted = $this->subscriptionModel->delete($id);

            if ($deleted) {
                return $this->success(null, 'Subscription berhasil dihapus!');
            }

            return $this->error('Gagal menghapus subscription', 500);

        } catch (\Exception $e) {
            log_message('error', 'Delete subscription error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat menghapus subscription', 500);
        }
    }

    /**
     * Update subscription status (admin only)
     * PATCH /api/subscriptions/{id}/status
     */
    public function updateStatus($id = null)
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData || !$userData['is_admin']) {
                return $this->error('Access denied', 403);
            }

            // Support both POST and PATCH methods
            $input = null;
            if ($this->request->getMethod() === 'post') {
                $input = $this->request->getJSON(true) ?? $this->request->getPost();
            } else {
                $input = $this->request->getRawInput();
            }
            
            $status = $input['status'] ?? null;
            
            if (!$status) {
                return $this->error('Status is required', 400);
            }

            $result = $this->subscriptionModel->updateStatus($id, $status);

            if (!$result['success']) {
                return $this->error($result['errors'][0], 400);
            }

            return $this->success($result['data'], 'Status subscription berhasil diupdate!');

        } catch (\Exception $e) {
            log_message('error', 'Update subscription status error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengupdate status subscription', 500);
        }
    }

    /**
     * Get subscription statistics (admin only)
     * GET /api/subscriptions/stats
     */
    public function stats()
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData || !$userData['is_admin']) {
                return $this->error('Access denied', 403);
            }

            $stats = $this->subscriptionModel->getSubscriptionStats();

            return $this->success($stats);

        } catch (\Exception $e) {
            log_message('error', 'Get subscription stats error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengambil statistik subscription', 500);
        }
    }
}
