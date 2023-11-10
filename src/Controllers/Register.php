<?php
namespace Controllers;

use Models\User;

class Register
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token']) {

            }

            $this->processRegistration();
            header("/login");
            exit;
        }

        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "register" . ".php");
    }

    public function processRegistration()
    {
        $userData = [
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'email' => $_POST['email']
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

        // if (empty($userData['password'])) {
        //     $response = [
        //         "message" => "Veuillez entrer un mot de passe",
        //         "type" => "cancel"
        //     ];
        //     echo json_encode($response);
        //     return;
        // }

        $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData["password"] = $passwordHash;

        $userModel = new User();

        try {
            $response = $userModel->register($userData);
        } catch (\PDOException $e) {
            // Gestion des erreurs PDO
            $response = [
                "message" => "Erreur lors de l'inscription",
                "type" => "cancel"
            ];
        }
        echo json_encode($response);
    }
}