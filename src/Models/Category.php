<?php

namespace Models;

class Category extends Database
{
    public function __construct()
    {

    }

    public function createCategory(array $data, $id): void
    {
        $this->table = 'todo';
        $this->getConnection();
        $this->edit($data, $id);
        $response =
            [
                "message" => "Categorie cree"
            ];
        echo json_encode($response);
    }

    public function getCategory()
    {
        $this->table = "categorydefault";
        $this->getConnection();
        return $this->getAll();
    }
}