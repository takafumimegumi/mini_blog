<?php

class StatusController extends Controller {

    public function indexAction() {
        // ユーザー情報を$_SESSION['user']から取得
        $user = $this->session->get('user');
        // ユーザIDに合致したstatusテーブルのrowとuserテーブルのユーザ名を$statusesに格納
        $statuses = $this->db_manager->get('Status')->fetchAllPersonalArchivesByUserId($user['id']);

        return $this->render([
            'statuses' => $statuses,
            'body' => '',
            '_token' => $this->generateCsrfToken('status/post'),
        ]);
    }

}