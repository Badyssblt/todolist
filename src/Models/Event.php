<?php

namespace Models;

class Event extends Database
{
    private const STATE_CHECK = 1;
    public $table = "todo";

    public function __construct()
    {

    }

    public function createEvent($data)
    {
        $this->getConnection();
        $this->insertData($data);
        $response =
            [
                "message" => "Event envoyé",
            ];

        echo json_encode($response);
    }

    public function checkEvent($data, $id)
    {
        self::getConnection();
        $this->edit($data, $id);
        $response =
            [
                "message" => "Event modifié"
            ];
        echo json_encode($response);
    }

}