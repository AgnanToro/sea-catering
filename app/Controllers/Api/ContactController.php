<?php

namespace App\Controllers\Api;

use App\Controllers\Api\BaseApiController;
use App\Models\ContactMessageModel;

class ContactController extends BaseApiController
{
    protected $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactMessageModel();
    }

    /**
     * Get all contact messages (admin only)
     * GET /api/contact
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
            
            if (!$userData || !$userData['is_admin']) {
                return $this->error('Access denied', 403);
            }

            // Get all contact messages for admin
            $messages = $this->contactModel
                ->orderBy('created_at', 'DESC')
                ->findAll();
            
            return $this->success($messages);

        } catch (\Exception $e) {
            log_message('error', 'Get contact messages error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengambil pesan kontak', 500);
        }
    }

    /**
     * Get contact message by ID (admin only)
     * GET /api/contact/{id}
     */
    public function show($id = null)
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData || !$userData['is_admin']) {
                return $this->error('Access denied', 403);
            }

            $message = $this->contactModel->find($id);
            
            if (!$message) {
                return $this->error('Message not found', 404);
            }

            // Mark as read if it's unread
            if ($message['status'] === 'unread') {
                $this->contactModel->markAsRead($id);
                $message['status'] = 'read';
            }

            return $this->success($message);

        } catch (\Exception $e) {
            log_message('error', 'Get contact message error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengambil pesan kontak', 500);
        }
    }

    /**
     * Create new contact message
     * POST /api/contact
     */
    public function create()
    {
        try {
            // Support both JSON and form data
            $input = $this->request->getJSON(true);
            if (!$input) {
                $input = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('phone'),
                    'subject' => $this->request->getPost('subject'),
                    'message' => $this->request->getPost('message')
                ];
            }

            log_message('info', 'Contact form data: ' . json_encode($input));

            // Validation rules - match the model validation
            $rules = [
                'name' => 'required|min_length[2]|max_length[255]',
                'email' => 'required|valid_email|max_length[255]',
                'phone' => 'permit_empty|regex_match[/^(\+62|62|0)8[1-9][0-9]{6,9}$/]',
                'subject' => 'permit_empty|min_length[3]|max_length[500]',
                'message' => 'required|min_length[10]|max_length[1000]'
            ];

            if (!$this->validate($rules, $input)) {
                $errors = $this->validator->getErrors();
                log_message('error', 'Contact validation failed: ' . json_encode($errors));
                return $this->response->setJSON([
                    'status' => 'error',
                    'error' => 'Validasi gagal',
                    'errors' => $errors
                ])->setStatusCode(400);
            }

            $data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'] ?? '',
                'subject' => $input['subject'] ?? '',
                'message' => $input['message'],
                'status' => 'unread',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            log_message('info', 'Contact data to insert: ' . json_encode($data));

            $contactId = $this->contactModel->insert($data);

            if (!$contactId) {
                log_message('error', 'Failed to insert contact message');
                log_message('error', 'Model errors: ' . json_encode($this->contactModel->errors()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'error' => 'Gagal menyimpan pesan'
                ])->setStatusCode(500);
            }

            log_message('info', 'Contact message saved with ID: ' . $contactId);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pesan berhasil dikirim!',
                'data' => ['id' => $contactId]
            ])->setStatusCode(201);

        } catch (\Exception $e) {
            log_message('error', 'Create contact message error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'error' => 'Terjadi kesalahan saat mengirim pesan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Update contact message status (admin only)
     * PATCH /api/contact/{id}/status
     */
    public function updateStatus($id = null)
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData || !$userData['is_admin']) {
                return $this->error('Access denied', 403);
            }

            $status = $this->request->getRawInput()['status'] ?? null;
            
            if (!$status) {
                return $this->error('Status is required', 400);
            }

            $message = $this->contactModel->find($id);
            
            if (!$message) {
                return $this->error('Message not found', 404);
            }

            if ($status === 'replied') {
                $updated = $this->contactModel->markAsReplied($id);
            } else {
                $updated = $this->contactModel->update($id, ['status' => $status]);
            }

            if ($updated) {
                $updatedMessage = $this->contactModel->find($id);
                return $this->success($updatedMessage, 'Status pesan berhasil diupdate!');
            }

            return $this->error('Gagal mengupdate status pesan', 500);

        } catch (\Exception $e) {
            log_message('error', 'Update contact message status error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengupdate status pesan', 500);
        }
    }

    /**
     * Delete contact message (admin only)
     * DELETE /api/contact/{id}
     */
    public function delete($id = null)
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData || !$userData['is_admin']) {
                return $this->error('Access denied', 403);
            }

            $message = $this->contactModel->find($id);
            
            if (!$message) {
                return $this->error('Message not found', 404);
            }

            $deleted = $this->contactModel->delete($id);

            if ($deleted) {
                return $this->success(null, 'Pesan berhasil dihapus!');
            }

            return $this->error('Gagal menghapus pesan', 500);

        } catch (\Exception $e) {
            log_message('error', 'Delete contact message error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat menghapus pesan', 500);
        }
    }

    /**
     * Get contact statistics (admin only)
     * GET /api/contact/stats
     */
    public function stats()
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData || !$userData['is_admin']) {
                return $this->error('Access denied', 403);
            }

            $stats = $this->contactModel->getContactStats();

            return $this->success($stats);

        } catch (\Exception $e) {
            log_message('error', 'Get contact stats error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengambil statistik kontak', 500);
        }
    }

    /**
     * Get unread messages count (admin only)
     * GET /api/contact/unread-count
     */
    public function unreadCount()
    {
        try {
            $userData = $this->request->userData ?? null;
            
            if (!$userData || !$userData['is_admin']) {
                return $this->error('Access denied', 403);
            }

            $count = $this->contactModel->getUnreadCount();

            return $this->success(['count' => $count]);

        } catch (\Exception $e) {
            log_message('error', 'Get unread count error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan saat mengambil jumlah pesan belum dibaca', 500);
        }
    }
}
