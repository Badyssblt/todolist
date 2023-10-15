<?php
namespace Controllers;

use Source\Renderer;

class HomeControllers
{
    public function index()
    {
        $renderer = new Renderer('Home/index');
        return $renderer->view();
    }
}