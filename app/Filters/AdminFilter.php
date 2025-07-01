<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JWTLibrary;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check session-based authentication first
        $session = session();
        if ($session->get('isLoggedIn') && $session->get('user_id')) {
            // Session-based authentication
            $userData = [
                'user_id' => $session->get('user_id'),
                'email' => $session->get('email'),
                'is_admin' => $session->get('is_admin') ?? false,
                'name' => $session->get('name')
            ];
            
            if (!$userData['is_admin']) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Access denied. Admin privileges required.',
                        'data' => null
                    ]);
            }
            
            $request->userData = $userData;
            return;
        }
        
        // Fallback to JWT authentication for API calls
        $jwt = new JWTLibrary();
        $userData = $jwt->validateRequest();
        
        if (!$userData) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized access. Please login first.',
                    'data' => null
                ]);
        }

        // Check if user is admin
        if (!isset($userData['is_admin']) || !$userData['is_admin']) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Access denied. Admin privileges required.',
                    'data' => null
                ]);
        }

        // Store user data in request for later use
        $request->userData = $userData;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
