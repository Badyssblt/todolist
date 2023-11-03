<?php

namespace Models;

class Friends extends Database
{

    public $table = 'friends';


    public function areFriends($userID, $friendID)
    {
        $columns = [
            'user1' => $userID,
            'user2' => $friendID,
            'state' => 2
        ];

        $sql = "SELECT * FROM friends 
            WHERE ((friends.user1 = :user1 AND friends.user2 = :user2) 
    OR (friends.user1 = :user2 AND friends.user2 = :user1))
    AND friends.state = :state
";

        $this->getConnection();
        $result = $this->getByColumns($sql, $columns);

        return !empty($result);
    }

    public function getFriends($userID)
    {
        $columns =
            [
                'user1' => $userID,
                'user2' => $userID,
                'state' => 2
            ];
        $sql = "SELECT friends.*, u1.id AS user1_id, u1.username AS user1_name, u2.id AS user2_id, u2.username AS user2_name
            FROM friends
            INNER JOIN users u1 ON friends.user1 = u1.id
            INNER JOIN users u2 ON friends.user2 = u2.id
            WHERE (friends.user1 = :user1 OR friends.user2 = :user2) AND friends.state = :state";

        $this->getConnection();
        $friends = $this->getByColumns($sql, $columns);
        $output = [];
        foreach ($friends as $row) {
            $friendID = $row['user1'] == $columns['user1'] ? $row['user2_id'] : $row['user1_id'];
            $friendName = $row['user1'] == $columns['user1'] ? $row['user2_name'] : $row['user1_name'];
            $output[] = [
                'id' => $row['id'],
                'friend_name' => $friendName,
                'state' => $row['state'],
                'friend_id' => $friendID,
            ];
        }

        return $output;
    }

    public function searchFriends($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $columns =
            [
                "email" => $email
            ];
        $this->getConnection();
        $search = $this->getByColumns($sql, $columns);
        return $search;
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

        if ($this->areFriends($userID, $friendID)) {
            return false;
        } else {
            $this->getConnection();
            $req = $this->insertData($data);
            if ($req) {
                return true;
            } else {
                return false;
            }
        }


    }

    public function getFriendsWaiting($userID)
    {
        $columns = [
            'user' => $userID,
            'state' => 1
        ];
        $sql = "SELECT friends.*, u1.id AS user1_id, u1.username AS user1_name, u2.id AS user2_id, u2.username AS user2_name
        FROM friends
        INNER JOIN users u1 ON friends.user1 = u1.id
        INNER JOIN users u2 ON friends.user2 = u2.id
        WHERE (friends.user1 = :user OR friends.user2 = :user) AND friends.state = :state AND friends.requester != :user";



        $this->getConnection();
        $friends = $this->getByColumns($sql, $columns);
        $output = [];
        foreach ($friends as $row) {
            $friendID = $row['user1'] == $columns['user'] ? $row['user2_id'] : $row['user1_id'];
            $friendName = $row['user1'] == $columns['user'] ? $row['user2_name'] : $row['user1_name'];
            $output[] = [
                'id' => $row['id'],
                'friend_name' => $friendName,
                'state' => $row['state'],
                'friend_id' => $friendID,
            ];
        }

        return $output;
    }

    public function acceptFriend($userID, $friendID, $state)
    {
        $this->getConnection();
        $sql = "UPDATE {$this->table} SET state = :state WHERE (user1 = :user1 OR user2 = :user1) AND (user1 = :user2 OR user2 = :user2)";
        $query = $this->connection->prepare($sql);
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