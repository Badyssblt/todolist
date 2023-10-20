<?php

namespace Models;

class User extends Database
{
    public function __construct()
    {
        $this->table = "users";
        $this->getConnection();
    }
    public function register($data): void
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $this->insert($data);

    }

    public function login($email, $password): bool
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnu");
        }

        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $query = $this->connection->prepare($sql);

        $query->bindParam(':email', $email);


        $query->execute();

        $user = $query->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($_POST['password'], $user['password'])) {
            session_start();
            $_SESSION['ID'] = $user['ID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            return true;
        }

        return false;
    }
}