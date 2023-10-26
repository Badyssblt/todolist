<?php

namespace Controllers;

use Models\Category;
use Models\Event;

session_start();

class Post
{
    private const STATE_CHECK = 1;
    private const STATE_UNCHECK = 0;

    private Event $event;
    private Category $category;
    public function __construct()
    {
        $this->event = new Event();
        $this->category = new Category();
    }

    public function index()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data =
                [
                    "name" => $_POST["name"],
                    "description" => $_POST["description"],
                    "userID" => $_SESSION['ID'],
                    "date" => $_POST["data"]
                ];
            $event = new Event();
            $event->createEvent($data);
        }
    }

    public function check()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $id = $_POST['id'];
            $data =
                [
                    'state' => self::STATE_CHECK
                ];
            $this->event->checkEvent($data, $id);
        }
    }

    public function uncheck()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $id = $_POST['id'];
            $data =
                [
                    'state' => self::STATE_UNCHECK
                ];
            $this->event->checkEvent($data, $id);
        }
    }

    public function createCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $id = $_POST['todoID'];
            $data =
                [
                    'category' => $_POST['categoryID'],
                ];
            $this->category->createCategory($data, $id);
        }
    }

    public function getCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            echo json_encode($this->category->getCategory());
        }
    }
}