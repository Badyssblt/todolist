<?php

namespace Controllers;

use Models\Category;
use Models\Friends;
use Models\Todo;
use Models\User;

class homePage
{

    public function index()
    {
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "index" . ".php");
    }
    public function home()
    {
        $user = new User();
        if (isset($_SESSION)) {
            if (isset($_SESSION['ID'])) {
                $token = $_SESSION['token'];
                $accountValidate = $user->accountValidate($token);
            } else {
                header("Location: /login");
            }

        } else {
            session_start();
        }


        if ($accountValidate) {
            require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "home" . ".php");
        } else {
            header("Location: /verify?token=$token");
        }
    }
}