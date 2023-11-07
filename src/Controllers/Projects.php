<?php

namespace Controllers;

use \Models\Project;

class Projects
{
    // Déclaration des constantes des états des todos
    private const STATE_CHECK = 1;
    private const STATE_UNCHECK = 0;

    private $project;
    public function __construct()
    {
        $this->project = new Project();
    }

    public function index()
    {
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Vue" . DIRECTORY_SEPARATOR . "project" . ".php");
    }

    public function getProject()
    {
        $project = $this->project->getProjectByUser();
        echo json_encode($project);
    }

    public function getTodoProject()
    {
        $this->project->getTodoProject();

    }

    public function postEventProject()
    {
        $data =
            [
                "name" => $_POST['name'],
                "description" => $_POST['description'],
                "projectID" => $_POST['projectID'],
                "owner" => $_POST['owner'],
                "state" => 0,
                "orderTodo" => 0
            ];
        $this->project->postEventProject($data);

    }

    public function checkEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $id = $_POST['id'];
            $data =
                [
                    'state' => self::STATE_CHECK
                ];
            $this->project->checkEvent($data, $id);
        }
    }

    public function uncheckEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $id = $_POST['id'];
            $data =
                [
                    'state' => self::STATE_UNCHECK
                ];
            $this->project->checkEvent($data, $id);
        }
    }

    public function createProject()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $name = $_POST['name'];
            $res = $this->project->createProject($name);
            if ($res) {
                echo json_encode($res);
            }
        }
    }




}