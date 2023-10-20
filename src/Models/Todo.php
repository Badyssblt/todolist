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

        $userID = $_SESSION['ID'];

        $sql = "SELECT todo.*, users.* FROM todo INNER JOIN users ON todo.userID = users.ID WHERE todo.userID = :userID";
        $query = $this->connection->prepare($sql);
        $query->bindParam(':userID', $userID, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }
}