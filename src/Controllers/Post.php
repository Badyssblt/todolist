<?php

namespace Controllers;

session_start();
use Models\Event;

class Post
{
    public function __construct()
    {

    }

    public function index()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data =
                [
                    "name" => $_POST["name"],
                    "description" => $_POST["description"],
                    "userID" => $_SESSION['ID'],
                    "date" => "today"
                ];
            $event = new Event($data);
        }
    }
}