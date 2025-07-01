<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JWTLibrary
{
    private $secretKey;
    private $algorithm = 'HS256';
    private $expiration = 86400; // 24 hours

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET_KEY') ?: 'your-secret-key-here-change-in-production';
    }

    /**
     * Generate JWT token
     */
    public function generateToken(array $payload): string
    {
        $payload['iat'] = time(); // Issued at
        $payload['exp'] = time() + $this->expiration; // Expiration time
        
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    /**
     * Verify and decode JWT token
     */
    public function verifyToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            throw new \Exception('Token has expired');
        } catch (SignatureInvalidException $e) {
            throw new \Exception('Invalid token signature');
        } catch (\Exception $e) {
            throw new \Exception('Invalid token');
        }
    }

    /**
     * Get token from request header
     */
    public function getTokenFromHeader(): ?string
    {
        $request = service('request');
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {
            return null;
        }

        // Extract token from "Bearer {token}"
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Validate request token and return user data
     */
    public function validateRequest(): ?array
    {
        $token = $this->getTokenFromHeader();
        
        if (!$token) {
            return null;
        }

        try {
            return $this->verifyToken($token);
        } catch (\Exception $e) {
            return null;
        }
    }
}
