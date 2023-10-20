<?php

namespace Controllers;

use Models\User;

class Login
{
    public function index()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->processLogin();
        }
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "Login" . ".php");
    }

    public function processLogin()
    {
        $email = $_POST["email"];
        $password = $_POST['password'];
        $user = new User();
        $connection = $user->login($email, $password);
        if ($connection) {
            header('Location: /');
        }

        echo "Connexion echoué";


    }
}