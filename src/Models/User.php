<?php

namespace Models;

class User extends Database
{
    public function __construct()
    {
        $this->table = "users";
        $this->getConnection();
    }

    public function register($data): array
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $res = $this->validateUserData($data);
        if ($res) {
            return $res;
        } else {
            $this->insert($data);
            $response = [
                "message" => "Inscription enregistrée",
                "type" => "ok"
            ];
            return $response;
        }

    }

    public function login($email, $password): bool
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $query = $this->connection->prepare($sql);

        $query->bindParam(':email', $email);

        $query->execute();

        $user = $query->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['ID'] = $user['ID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            return true;
        }

        return false;
    }

    private function validateUserData($data)
    {

        if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
            $response =
                [
                    "message" => "Veuillez remplir tous les champs",
                    "type" => "cancel"
                ];
            return $response;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $response =
                [
                    "message" => "L'adresse email n'est pas valide",
                    "type" => "cancel"
                ];
            return $response;
        }

        if (empty($data['password'])) {
            $response = [
                "message" => "Veuillez entrer un mot de passe",
                "type" => "cancel"
            ];
            return $response;
        }


    }

}