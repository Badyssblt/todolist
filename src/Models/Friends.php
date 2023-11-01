<?php

namespace Models;

class Friends extends Database
{
    private const STATE_ACCEPT = 2;
    private const STATE_WAITING = 1;
    private const STATE_CANCELLED = 0;

    public $table = 'friends';

    public function getFriends($userID)
    {
        $columns =
            [
                'user1' => $userID,
                'user2' => $userID,
                'state' => 2
            ];
        $sql = "SELECT friends.*, u1.username AS user1_name, u2.username AS user2_name
            FROM friends
            INNER JOIN users u1 ON friends.user1 = u1.id
            INNER JOIN users u2 ON friends.user2 = u2.id
            WHERE (friends.user1 = :user1 OR friends.user2 = :user2) AND friends.state = :state";
        $this->getConnection();
        $friends = $this->getByColumns($sql, $columns);
        $output = [];
        foreach ($friends as $row) {
            $friendName = $row['user1'] == $columns['user1'] ? $row['user2_name'] : $row['user1_name'];
            $output[] = [
                'id' => $row['id'],
                'friend_name' => $friendName,
                'state' => $row['state'],
            ];
        }

        return $output;
    }
}