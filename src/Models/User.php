<?php

namespace Models;

use PHPMailer\PHPMailer\PHPMailer;



class User extends Database
{
    public function __construct()
    {
        $this->table = "users";
        $this->getConnection();
    }

    public function emailExists($email): bool
    {
        $sql = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE email = :email";
        $this->getConnection();
        $query = self::$connection->prepare($sql);
        $query->bindParam(':email', $email);
        $query->execute();
        $result = $query->fetch(\PDO::FETCH_ASSOC);


        return $result && $result['count'] > 0;

    }


    public function register($data): mixed
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->Username = "badyss.blt@gmail.com";
        $mail->Password = "ccvypqjxlenmnuxk";
        $mail->CharSet = 'UTF-8';
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $res = $this->validateUserData($data);
        if ($res) {
            return $res;
        } else {
            $emailExist = $this->emailExists($data['email']);
            if ($emailExist) {
                $response =
                    [
                        "message" => "Une adresse email existe déjà",
                        "type" => "cancel"
                    ];
                return $response;
            }
            $verificationCode = sprintf('%05d', rand(0, 99999));
            $data['verification_code'] = $verificationCode;
            $activationToken = bin2hex(random_bytes(16));
            $data['activation_token'] = $activationToken;
            $this->insert($data);
            $mail->From = 'badyss.blt@gmail.com';
            $mail->FromName = 'TaskManager';
            $mail->Subject = 'Vérification du compte';
            $mail->Subject = 'Vérification du compte';
            $mail->isHTML(true);
            $mail->Body = 'Votre code de vérification: <b> ' . $verificationCode . ' </b> ';
            $mail->AltBody = 'Votre code de vérification :';
            $mail->smtpConnect();
            $mail->addAddress($data['email']);
            if (!$mail->send()) {
                return null;
            } else {
                $response = [
                    "message" => "Email envoyé",
                    "type" => "verif"
                ];
            }
            return $response;
        }
    }


    public function login($email, $password): bool
    {
        if (empty($this->table)) {
            throw new \Exception("Table inconnue");
        }

        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $query = self::$connection->prepare($sql);

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
            $_SESSION['token'] = $user['activation_token'];
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

    public function verifyCode()
    {
        $code = $_POST['code'];
        $token = $_POST['token'];
        $sql = "SELECT verification_code FROM users WHERE activation_token = :token";
        $query = self::$connection->prepare($sql);
        $query->bindParam(":token", $token);
        $query->execute();
        $codeInDatabase = $query->fetch();
        if ($code == $codeInDatabase[0]) {
            $updateSQL = 'UPDATE users SET state = 1 WHERE activation_token = :token';
            $queryUpdate = self::$connection->prepare($updateSQL);
            $queryUpdate->bindParam(":token", $token);
            $queryUpdate->execute();
            $response =
                [
                    "message" => "Compte vérifié",
                    "type" => "ok"
                ];
        } else {
            $response =
                [
                    "message" => "Le code rentré est incorrect",
                    "type" => $code,
                    "code" => $codeInDatabase[0]
                ];
        }

        return $response;
    }

    public function getToken()
    {
        $email = $_POST["email"];
        $sql = "SELECT activation_token FROM users WHERE email = :email";
        $query = self::$connection->prepare($sql);
        $query->bindParam(":email", $email);
        $query->execute();
        $token = $query->fetch(\PDO::FETCH_COLUMN);
        return $token;
    }

    public function accountValidate($token): bool
    {
        $sql = "SELECT state FROM users WHERE activation_token = :token";
        $query = self::$connection->prepare($sql);
        $query->bindParam(":token", $token);
        $query->execute();

        $result = $query->fetch(\PDO::FETCH_ASSOC);

        if ($result && $result['state'] == 1) {
            return true;
        } else {
            return false;
        }
    }

}