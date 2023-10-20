<?php

namespace Models;

use \PDO;

class Database
{
    private string $host = "localhost";
    private string $dbname = "todo";
    private string $username = "root";
    private string $password = "";

    protected $connection;

    public $table;
    public $id;

    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbname, $this->username, $this->password);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table;
        $query = $this->connection->prepare($sql);
        $query->execute();
        return $query->fetchAll();

    }

    public function getOne($id)
    {
        if (empty($this->table)) {
            throw new \Exception("Table name not set.");
        }

        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $query = $this->connection->prepare($sql);

        $query->bindParam(':id', $id);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }


    public function insertData($data): bool
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $query = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $query->bindValue(':' . $key, $value);
        }

        $response = $query->execute();
        if ($response) {
            return true;
        } else {
            return false;
        }


    }


    public function insert($data)
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $query = $this->connection->prepare($sql);
        $query->bindParam(':username', $data['username']);
        $query->bindParam(':password', $data['password']);
        $query->bindParam(':email', $data['email']);

        $query->execute();
    }
}