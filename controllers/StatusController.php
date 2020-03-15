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

    public function postAction() {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('status/post', $token)) {
            return $this->redirect('/');
        }

        $body = $this->request->getPost('body');

        $errors = [];

        if (!strlen($body)) {
            $errors[] = 'ひとことを入力してください';
        } elseif (mb_strlen($body) > 200) {
            $errors[] = 'ひとことは200文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $user = $this->session->get('user');
            $this->db_manager->get('Status')->insert($user['id'], $body);

            return $this->redirect('/');
        }

        $user = $this->session->get('user');
        $statuses = $this->db_manager->get('Status')->fetchAllPersonalArchivesByUserId($user['id']);

        return $this->render([
            'errors' => $errors,
            'body' => $body,
            'statuses' => $statuses,
            '_token' => $this->generateCsrfToken('status/post'),
        ], 'index');
    }

}