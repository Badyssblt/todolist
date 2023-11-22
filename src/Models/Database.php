<?php
// Déclaration du parent des Models
namespace Models;

use \PDO;

class Database
{
    private const host = "mysql";
    private const dbname = "todo";
    private const username = "test";
    private const password = "pass";

    protected static $connection;
    private static $instance;

    public $table;
    public $id;


    protected function __construct()
    {
        $this->getConnection();
    }

    // Connexion à la base de donnée
    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO('mysql:host=' . self::host . '; dbname=' . self::dbname, self::username, self::password);
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return self::$connection;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // Obtient toutes les lignes d'une table
    public function getAll(): array
    {
        $sql = "SELECT * FROM " . $this->table;
        $query = self::$connection->prepare($sql);
        $query->execute();
        return $query->fetchAll();

    }

    // Obtient les données d'une table sur une table
    public function getByColumns($sql, $columns)
    {
        if (empty($this->table)) {
            throw new \Exception("Table name not set.");
        }

        $query = self::getConnection()->prepare($sql);

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
        $query = self::$connection->prepare($sql);

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
        $query = self::$connection->prepare($sql);

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
        $query = self::$connection->prepare($sql);
        $query->bindParam(':username', $data['username']);
        $query->bindParam(':password', $data['password']);
        $query->bindParam(':email', $data['email']);
        $query->bindParam(":state", $data["state"]);
        $query->bindParam(":activation_token", $data["activation_token"]);

        if (array_key_exists('verification_code', $data)) {
            $query->bindParam(':verification_code', $data['verification_code']);
        }
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
        $query = self::$connection->prepare($sql);

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
        $query = self::$connection->prepare($sql);
        $query->bindParam('id', $id);
        $query->execute();

    }
}