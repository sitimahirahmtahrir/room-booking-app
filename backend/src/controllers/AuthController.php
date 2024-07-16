<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController {
    private $adminUsername = 'admin';
    private $adminPassword = 'password'; // In practice, use a hashed password.

    public function login(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if ($username === $this->adminUsername && $password === $this->adminPassword) {
            // Simple session-based authentication, better with JWT or similar in real apps.
            $_SESSION['admin_logged_in'] = true;
            return $response->withJson(['message' => 'Logged in successfully']);
        }

        return $response->withStatus(401)->withJson(['message' => 'Unauthorized']);
    }

    public function logout(Request $request, Response $response) {
        unset($_SESSION['admin_logged_in']);
        return $response->withJson(['message' => 'Logged out successfully']);
    }

    public function check(Request $request, Response $response) {
        if (!empty($_SESSION['admin_logged_in'])) {
            return $response;
        }

        return $response->withStatus(401)->withJson(['message' => 'Unauthorized']);
    }
}
