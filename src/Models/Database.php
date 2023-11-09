<?php
// Déclaration du parent des Models
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

    // Connexion à la base de donnée
    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbname, $this->username, $this->password);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    // Obtient toutes les lignes d'une table
    public function getAll(): array
    {
        $sql = "SELECT * FROM " . $this->table;
        $query = $this->connection->prepare($sql);
        $query->execute();
        return $query->fetchAll();

    }

    // Obtient les données d'une table sur une table
    public function getByColumns($sql, $columns)
    {
        if (empty($this->table)) {
            throw new \Exception("Table name not set.");
        }

        $query = $this->connection->prepare($sql);

        foreach ($columns as $columnName => $columnValue) {
            if (is_int($columnValue)) {
                $query->bindValue(":$columnName", $columnValue, \PDO::PARAM_INT);
            } else {
                $query->bindValue(":$columnName", $columnValue);
            }
        }

        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }




    // Obtient un élément d'une table
    public function getOne($id): array
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


    // Gère l'inscription de l'utilisateur
    public function insert($data): void
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

    public function edit($data, $id): void
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $updateValues = implode(', ', array_map(function ($key) {
            return "$key=:$key";
        }, array_keys($data)));

        $sql = "UPDATE {$this->table} SET $updateValues WHERE id = :id";
        $query = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $query->bindValue(':' . $key, $value);
        }
        $query->bindParam(':id', $id);

        $query->execute();
    }

    public function delete($id)
    {
        if (empty($this->table)) {
            throw new \Exception('Table inconnue');
        }

        $sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $query = $this->connection->prepare($sql);
        $query->bindParam('id', $id);
        $query->execute();

    }
}