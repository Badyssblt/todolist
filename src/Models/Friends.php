<?php

namespace Models;

class Friends extends Database
{

    public $table = 'friends';

    public function __construct()
    {
        parent::__construct();
    }

    public function updateOnlineStatus($userID, $isOnline)
    {
        self::getConnection();
        $sql = "UPDATE users SET is_online = :is_online WHERE id = :user_id";
        $query = self::$connection->prepare($sql);
        $query->bindParam(":is_online", $isOnline, \PDO::PARAM_BOOL);
        $query->bindParam(":user_id", $userID, \PDO::PARAM_INT);
        $query->execute();
    }


    public function areFriends($userID, $friendID)
    {
        $columns = [
            'user1' => $userID,
            'user2' => $friendID,
            'state' => 2,
        ];

        $sql = "SELECT * FROM friends 
            WHERE ((friends.user1 = :user1 AND friends.user2 = :user2) 
    OR (friends.user1 = :user2 AND friends.user2 = :user1))
    AND friends.state = :state
";

        self::getConnection();
        $result = $this->getByColumns($sql, $columns);

        return !empty($result);
    }


    public function searchFriends($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $columns =
            [
                "email" => $email
            ];
        self::getConnection();
        $search = $this->getByColumns($sql, $columns);
        return $search;
    }

    public function isFriendRequestPending($userID, $friendID)
    {
        $columns = [
            'user1' => $userID,
            'user2' => $friendID,
            'state' => 1,
        ];

        $sql = "SELECT * FROM friends 
            WHERE ((friends.user1 = :user1 AND friends.user2 = :user2) 
    OR (friends.user1 = :user2 AND friends.user2 = :user1))
    AND friends.state = :state";

        self::getConnection();
        $result = $this->getByColumns($sql, $columns);

        return !empty($result);
    }


    public function addFriends($data)
    {
        if (isset($_SESSION['ID'])) {
            $userID = $_SESSION['ID'];
            $friendID = $userID == $data['user1'] ? $data['user2'] : $data['user1'];
            echo json_encode($friendID);
        } else {
            session_start();
        }

        if ($this->areFriends($userID, $friendID) || $this->isFriendRequestPending($userID, $friendID)) {
            return false;
        } else {
            self::getConnection();
            $req = $this->insertData($data);
            if ($req) {
                return true;
            } else {
                return false;
            }
        }


    }


    public function getFriendsAndPending($userID)
    {
        self::getConnection();

        $sql = "SELECT 
                friends.*,
                u1.id AS user1_id, u1.username AS user1_name, u1.is_online AS user1_online,
                u2.id AS user2_id, u2.username AS user2_name, u2.is_online AS user2_online
            FROM friends
            INNER JOIN users u1 ON friends.user1 = u1.id
            INNER JOIN users u2 ON friends.user2 = u2.id
            WHERE (friends.user1 = :userID OR friends.user2 = :userID) AND (friends.state = 1 OR friends.state = 2)";

        $columns = ['userID' => $userID];

        $query = self::$connection->prepare($sql);
        $query->bindParam(':userID', $userID, \PDO::PARAM_INT);
        $query->execute();
        $friends = $query->fetchAll(\PDO::FETCH_ASSOC);

        $outputAccepted = [];
        $outputPending = [];

        foreach ($friends as $row) {
            $friendID = ($row['user1'] == $userID) ? $row['user2_id'] : $row['user1_id'];
            $friendName = ($row['user1'] == $userID) ? $row['user2_name'] : $row['user1_name'];
            $isOnlineColumn = ($row['user1'] == $userID) ? 'user2_online' : 'user1_online';
            $friendOnline = isset($row[$isOnlineColumn]) ? $row[$isOnlineColumn] : null;

            $friendData = [
                'id' => $row['id'],
                'friend_name' => $friendName,
                'state' => $row['state'],
                'friend_id' => $friendID,
                'is_online' => $friendOnline
            ];

            if ($row['state'] == 2) {
                // Ami déjà accepté
                $outputAccepted[] = $friendData;
            } elseif ($row['state'] == 1) {
                // Ami en attente
                $outputPending[] = $friendData;
            }
        }

        // Maintenant, vous avez deux tableaux distincts: $outputAccepted et $outputPending
        return ['accepted' => $outputAccepted, 'pending' => $outputPending];
    }


    public function acceptFriend($userID, $friendID, $state)
    {
        self::getConnection();
        $sql = "UPDATE {$this->table} SET state = :state WHERE (user1 = :user1 OR user2 = :user1) AND (user1 = :user2 OR user2 = :user2)";
        $query = self::$connection->prepare($sql);
        $query->bindParam(":state", $state);
        $query->bindParam(":user1", $userID);
        $query->bindParam(":user2", $friendID);
        $res = $query->execute();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

}