<?php

namespace Controllers;

use Models\User;

class Register
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegistration();
            header("/login");
            // Redirige l'utilisateur vers une page de confirmation ou une autre action
            exit;
        }
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "register" . ".php");
    }

    public function processRegistration()
    {

        $userData =
            [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'email' => $_POST['email']
            ];

        $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData["password"] = $passwordHash;
        $userModel = new User();
        $userModel->register($userData);
    }
}