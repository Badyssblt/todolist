<?php

namespace Models;

class Project extends Database
{
    public $table = "todo_projects";
    public function getProjectByUser()
    {
        if ($_SESSION) {
            $userID = $_SESSION['ID'];
            if ($_SESSION) {
                $userID = $_SESSION['ID'];
                $sql = "SELECT 
            projects.name AS project_name, 
            projects.ID AS project_id, 
            COUNT(todo_projects.ID) AS task_count,
            COUNT(CASE WHEN todo_projects.state = 1 THEN todo_projects.ID END) AS task_finish
        FROM users 
        JOIN participation ON users.ID = participation.userID 
        JOIN projects ON participation.projectID = projects.ID 
        LEFT JOIN todo_projects ON projects.ID = todo_projects.projectID 
        WHERE users.ID = :userID 
        GROUP BY projects.ID;";

                $this->getConnection();
                $query = $this->connection->prepare($sql);
                $query->bindParam(':userID', $userID, \PDO::PARAM_INT);
                $query->execute();
                return $query->fetchAll();
            }
        }
    }

    private function checkUserInProject($userID, $projectID)
    {
        $sql = "SELECT COUNT(*) FROM participation WHERE userID = :userID AND projectID = :projectID;";
        $this->getConnection();
        $query = $this->connection->prepare($sql);
        $query->bindParam(':userID', $userID, \PDO::PARAM_INT);
        $query->bindParam(':projectID', $projectID, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchColumn();

        return $result > 0;
    }

    public function getTodoProject()
    {
        $userID = $_SESSION['ID'];
        $projectID = $_POST['id'];
        $isUserInProject = $this->checkUserInProject($userID, $projectID);
        if ($isUserInProject) {
            $userID = $_SESSION['ID'];
            $sql = "SELECT 
            todo_projects.name AS task_name, 
            todo_projects.ID AS task_id, 
            todo_projects.state AS task_state, 
            todo_projects.description AS task_description, 
            todo_projects.owner AS task_owner, 
            users.username AS owner_username,
            categorydefault.name AS task_category
        FROM todo_projects 
        LEFT JOIN categorydefault ON todo_projects.category = categorydefault.ID 
        INNER JOIN users ON todo_projects.owner = users.ID 
        WHERE todo_projects.projectID = :projectID;";
            $this->getConnection();
            $query = $this->connection->prepare($sql);
            $query->bindParam(':projectID', $projectID, \PDO::PARAM_INT);
            $query->execute();
            $todos = $query->fetchAll();

            if ($todos) {
                echo json_encode($todos);
            } else {
                $response = [
                    "message" => "Aucune tâche à faire",
                    "type" => "noTodo"
                ];
                echo json_encode($response);
            }
        } else {
            $response = [
                "message" => "Pas autorisé",
                "type" => "NotAllowed"
            ];
            echo json_encode($response);
        }

    }

    public function postEventProject($data)
    {
        $this->getConnection();
        $this->insertData($data);
        $response =
            [
                "message" => "Event envoyé",
            ];

        echo json_encode($response);
    }

    public function getUserInProject()
    {
        $this->getConnection();
        $projectID = $_POST['projectID'];
        $sql = "SELECT * FROM participation INNER JOIN users ON participation.userID = users.ID WHERE projectID = :projectID";
        $query = $this->connection->prepare($sql);
        $query->bindParam(':projectID', $projectID);
        $query->execute();
        $users = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $users;

    }

    public function checkEvent($data, $id)
    {
        $this->getConnection();
        $this->edit($data, $id);
        $response =
            [
                "message" => "Event modifié"
            ];
        echo json_encode($response);
    }

    public function createProject($name)
    {
        $this->getConnection();

        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO projects VALUES(0, :name, :description, :owner)";
            $query = $this->connection->prepare($sql);
            $req = $query->execute(array("name" => $name, "description" => "", "owner" => $_SESSION['ID']));

            if ($req) {
                $projectId = $this->connection->lastInsertId();

                $sqlParticipation = "INSERT INTO participation VALUES(0, :userID, :projectID)";
                $queryParticipation = $this->connection->prepare($sqlParticipation);

                $reqParticipation = $queryParticipation->execute(array("userID" => $_SESSION['ID'], "projectID" => $projectId));

                if ($reqParticipation) {
                    $this->connection->commit();
                    $response = [
                        'message' => 'Projet créé',
                        "ok" => true
                    ];
                    return $response;
                } else {
                    $this->connection->rollBack();
                    return false;
                }
            } else {
                $this->connection->rollBack();
                return false;
            }
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            return false;
        }
    }

}