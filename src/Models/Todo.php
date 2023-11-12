<?php

namespace Models;

class Todo extends Database
{
    public function __construct()
    {
        $this->table = 'todo';
        $this->getConnection();
    }

    public function getUserTodo()
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }
        if ($_SESSION) {
            $userID = $_SESSION['ID'];
            $sql = "SELECT todo.*, users.*, categorydefault.name AS categoryName FROM todo INNER JOIN users ON todo.userID = users.ID LEFT JOIN categorydefault ON todo.category = categorydefault.ID WHERE todo.userID = :userID ORDER BY todo.orderTodo ASC";
            $query = self::$connection->prepare($sql);
            $query->bindParam(':userID', $userID, \PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        }
    }

    public function getTodo($category, $name)
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }
        if ($_SESSION['ID']) {
            $userID = $_SESSION['ID'];
            $sql = 'SELECT todo.*, categorydefault.name AS categoryName FROM todo LEFT JOIN categorydefault ON todo.category = categorydefault.ID WHERE userID = :userID';
            if($category !== null){
                $sql .= ' AND todo.category = :category';
            }
            if($name !== null){
                $sql .= ' AND todo.name LIKE :name ';
            }
            $query = $this->connection->prepare($sql);
            $query->bindValue(':userID', $userID, \PDO::PARAM_INT);
            if ($category !== null) {
                $query->bindValue(':category', $category, \PDO::PARAM_INT);
            }
            
            if ($name !== null) {
                $query->bindValue(':name', "%$name%", \PDO::PARAM_STR);
            }
            $query->execute();
            $res = $query->fetchAll(\PDO::FETCH_ASSOC);
            echo json_encode($res);
        }
    }

    public function changeOrder($orderData)
    {
        if (empty($this->table)) {
            throw new \Exception('Table inconnue');
        }
        foreach ($orderData as $taskId => $newOrder) {
            $this->edit(['orderTodo' => $newOrder], $taskId);
        }
        $response =
            [
                "message" => $orderData
            ];
        echo json_encode($response);

    }

    public function editTodo($id, $color)
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }
        $this->edit(["color" => $color], $id);
        $response =
            [
                "message" => "envoyé"
            ];
        echo json_encode($response);
    }

    public function deleteTodo($id)
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $this->delete($id);
        $response =
            [
                "message" => "Todo supprime"
            ];
        echo json_encode($response);
    }
}