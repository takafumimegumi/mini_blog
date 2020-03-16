<?php

class StatusController extends Controller {

    protected $auth_actions = ['index', 'post'];

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

    public function userAction($params) {
        $user = $this->db_manager->get('User')->fetchByUserName($params['user_name']);
        if (!$user) {
            $this->forward404();
        }

        // フォローしているかどうかの状態をboolで保持
        $following = null;
        // ログイン済みの場合
        if ($this->session->isAuthenticated()) {
            // セッションに格納されている自分のデータを取得
            $my = $this->session->get('user');
            // アクセスしたユーザの投稿一覧が自分のものじゃない場合
            if ($my['id'] !== $user['id']) {
                // フォローしてればtrue、していなければfalseが代入される
                $following = $this->db_manager->get('Following')->isFollowing($my['id'], $user['id']);
            }
        }

        $statuses = $this->db_manager->get('Status')->fetchAllByUserId($user['id']);

        return $this->render([
            'user' => $user,
            'statuses' => $statuses,
            'following' => $following,
            '_token' => $this->generateCsrfToken('account/follow')
        ]);
    }

    public function showAction($params) {
        $status = $this->db_manager->get('Status')->fetchPersonalArchiveById($params['id']);
        
        if (!$status) {
            $this->forward404();
        }

        return $this->render([
            'status' => $status
        ]);
    }

}