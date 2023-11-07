<?php

namespace Controllers;

class ProjectPage
{
    public function index()
    {
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "projectPage" . ".php");
    }
}