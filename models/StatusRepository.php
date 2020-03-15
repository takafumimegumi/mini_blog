<?php

class StatusRepository extends DbRepository {

    public function insert($user_id, $body) {
        $now = new Datetime();

        $sql = "insert into status(user_id, body, created_at) values(:user_id, :body, :created_at)";

        $this->execute($sql, [
            ':user_id' => $user_id,
            ':body' => $body,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ]);
    }

    public function fetchAllPersonalArchivesByUserId($user_id) {
        $sql = "
            select a.*, u.user_name
                from status as a
            left join user as u
                on a.user_id = u.id
            where u.id = :user_id
                order by a.created_at desc
        ";

        return $this->fetchAll($sql, [
            ':user_id' => $user_id
        ]);
    }

    public function fetchAllByUserId($user_id) {
        $sql = "
            select a.*, u.user_name
                from status as a
            left join user as u
                on a.user_id = u.id
            where u.id = :user_id
                order by a.created_at desc
        ";

        return $this->fetchAll($sql, [
            ':user_id' => $user_id
        ]);
    }

}