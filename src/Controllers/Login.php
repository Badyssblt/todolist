<?php

namespace Controllers;

use Models\Friends;
use Models\User;


class Login
{
    private Friends $friends;
    public function __construct()
    {
        $this->friends = new Friends();
    }
    public function index()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->processLogin();
        }
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "Login" . ".php");
    }

    public function userLoggedIn($userID)
    {
        $this->friends->updateOnlineStatus($userID, true);
    }

    public function userLoggedOut($userID)
    {
        $this->friends->updateOnlineStatus($userID, false);
    }

    public function processLogin()
    {
        $email = $_POST["email"];
        $password = $_POST['password'];
        $user = new User();
        $connection = $user->login($email, $password);

        if ($connection) {
            if (isset($_SESSION['ID'])) {
                $this->userLoggedIn($_SESSION['ID']);
            }
            header('Location: /home');
        }

        echo "Connexion echoué";


    }

    public function logout()
    {
        if (isset($_SESSION['ID'])) {
            $this->userLoggedOut($_SESSION['ID']);
            session_destroy();
        }
    }
}