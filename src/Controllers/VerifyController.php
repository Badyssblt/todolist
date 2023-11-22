<?php

namespace Controllers;

use Models\User;

class VerifyController
{
    public function index()
    {
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "verify" . ".php");

    }

    public function verifyCode()
    {
        $user = new User();
        $res = $user->verifyCode();
        echo json_encode($res);

    }

    public function getToken()
    {
        $user = new User();
        $token = $user->getToken();
        $response =
            [
                "token" => $token
            ];
        echo json_encode($response);
    }
}