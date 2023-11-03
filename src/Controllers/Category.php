<?php

namespace Controllers;

class Category
{
    public function index()
    {
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "category" . ".php");

    }
}