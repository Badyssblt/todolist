<?php

namespace Controllers;

use Models\Category;
use Models\Friends;
use Models\Todo;

class homePage
{

    public function index()
    {
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "index" . ".php");
    }
    public function home()
    {
        $todo = new Todo();
        $lists = $todo->getUserTodo();
        $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($isAjaxRequest) {
            header('Content-Type: application/json');
            echo json_encode($lists);
            exit;
        }
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "home" . ".php");
    }
}