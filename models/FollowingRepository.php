<?php

class FollowingRepository extends DbRepository {

    public function insert($user_id, $following_id) {
        $sql = "insert into following values(:user_id, :following_id)";

        $stmt = $this->execute($sql, [
            ':user_id' => $user_id,
            ':following_id' => $following_id,
        ]);
    }

    public function isFollowing($user_id, $following_id) {
        $sql = "
            select count(user_id) as count
                from following
            where user_id = :user_id
                and following_id = :following_id
        ";

        $row = $this->fetch($sql, [
            ':user_id' => $user_id,
            ':following_id' => $following_id,
        ]);

        if ($row['count'] !== '0') {
            return true;
        }

        return false;
    }

}