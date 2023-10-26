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
            $sql = "SELECT todo.*, users.*, categorydefault.name AS categoryName FROM todo INNER JOIN users ON todo.userID = users.ID LEFT JOIN categorydefault ON todo.category = categorydefault.ID WHERE todo.userID = :userID";
            $query = $this->connection->prepare($sql);
            $query->bindParam(':userID', $userID, \PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        }


    }
}