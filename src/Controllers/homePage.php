<?php

namespace Controllers;

use Models\Todo;

class homePage
{
    public function index()
    {
        $todo = new Todo();
        $lists = $todo->getUserTodo();
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "home" . ".php");
    }
}