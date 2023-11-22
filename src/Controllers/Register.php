<?php
namespace Controllers;

use Models\User;
use PHPMailer;

class Register
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                // Handle CSRF token mismatch
                $response = [
                    "message" => "CSRF token mismatch",
                    "type" => "cancel"
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }

            $this->processRegistration();
            return;
        }

        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            // AJAX request, return only JSON
            header('Content-Type: application/json');
            $response = [
                "message" => "Invalid request",
                "type" => "cancel"
            ];
            echo json_encode($response);
            return;
        }

        // Regular page load, render HTML form
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "register" . ".php");
    }


    public function processRegistration()
    {
        $userData = [
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'email' => $_POST['email'],
            "state" => 0,

        ];

        if (empty($userData['username']) || empty($userData['password']) || empty($userData['email'])) {
            $response =
                [
                    "message" => "Veuillez remplir tous les champs",
                    "type" => "cancel"
                ];
            echo json_encode($response);
            return;
        }


        $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData["password"] = $passwordHash;

        $userModel = new User();

        try {
            $response = $userModel->register($userData);
            if ($response["type"] !== "cancel" || $response['type'] !== "verif") {
                echo json_encode($response);
            }

        } catch (\PDOException $e) {
            $response = [
                "message" => "Erreur lors de l'inscription",
                "type" => "cancel"
            ];
        }
    }
}