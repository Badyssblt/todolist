<?php

namespace Controllers;

use Models\Category;
use Models\Event;
use Models\Todo;

session_start();

class Post
{
    private const STATE_CHECK = 1;
    private const STATE_UNCHECK = 0;


    // DECLARATION DES MODELS
    private Event $event;
    private Category $category;
    private Todo $todo;
    public function __construct()
    {
        $this->event = new Event();
        $this->category = new Category();
        $this->todo = new Todo();

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

    public function changeOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $orderData = $_POST['orderData'];

            if (!empty($orderData)) {
                $this->todo->changeOrder($orderData);
            } else {
                // Gestion d'une éventuelle erreur si aucune donnée n'est reçue
                $response = [
                    "error" => "Aucune donnée d'ordre reçue."
                ];
                echo json_encode($response);
            }
        } else {
            // Gestion d'une éventuelle erreur si la requête n'est pas une requête POST
            header('HTTP/1.1 405 Method Not Allowed');
            echo 'Méthode non autorisée';
        }

    }
}