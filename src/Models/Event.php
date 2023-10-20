<?php

namespace Models;

class Event extends Database
{
    public $table = "todo";

    public function __construct($data)
    {
        $this->getConnection();
        $this->insertData($data);
        $response =
            [
                "message" => "Event envoyé",
            ];

        echo json_encode($response);
    }
}