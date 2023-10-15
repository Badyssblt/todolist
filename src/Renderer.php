<?php

namespace Source;

class Renderer
{
    public function __construct(private string $viewPath)
    {

    }

    public function view()
    {
        ob_start();

        require BASE_VIEW_PATH . $this->viewPath . '.php';

        return ob_get_clean();
    }
}