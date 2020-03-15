<?php

class UserRepository extends DbRepository {

    public function insert($user_name, $password)
    {
        $password = $this->hashPassword($password);
        $now = new DateTime();

        $sql = "insert into user(user_name, password, created_at) values(:user_name, :password, :created_at)";

        $stmt = $this->execute($sql, [
            ':user_name' => $user_name,
            ':password' => $password,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ]);
    }

    public function hashPassword($password) {
        return sha1($password . 'SecretKey');
    }

    public function fetchByUserName($user_name) {
        $sql = "select * from user where user_name = :user_name";

        return $this->fetch($sql, [':user_name' => $user_name]);
    }

    public function isUniqueUserName($user_name) {
        $sql = "select count(id) as count from user where user_name = :user_name";

        $row = $this->fetch($sql, [':user_name' => $user_name]);
        if ($row['count'] === '0') {
            return true;
        }

        return false;
    }

    public function fetchAllFollowingsByUserId($user_id) {
        $sql = "
            select u.*
                from user as u
            left join following as f
                on u.id = f.following_id
            where f.user_id = :user_id
        ";

        return $this->fetchAll($sql, [
            ':user_id' => $user_id
        ]);
    }

}