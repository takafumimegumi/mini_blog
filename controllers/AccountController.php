<?php

class AccountController extends Controller {

    protected $auth_actions = ['index', 'signout'];

    public function signupAction() {
        // 既にログインしている場合はアカウント情報トップへリダイレクト
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        // $_SESSION['csrf_tokens/account/signup']にトークンが格納される
        // renderメソッドを実行しているので、ビューファイル内で$_token変数に格納されたトークンが利用可
        return $this->render([
            'user_name' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signup'),
        ]);
    }

    public function registerAction() {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        // HTTPメソッドのチェック（POSTメソッド以外のリクエストだった場合の処理）
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        // CSRFトークンのチェック
        $token = $this->request->getPost('_token');
        // 不正なリクエストだった場合、元のsignupページにリダイレクト
        if (!$this->checkCsrfToken('account/signup', $token)) {
            // 処理を中断するために「return」を記述
            return $this->redirect('/account/signup');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');

        $errors = [];

        // ユーザIDのバリデーション
        // 値がセットされていない場合 → 3~20文字以内じゃない場合 → ユーザ名が重複していた場合
        if (!strlen($user_name)) {
            $errors[] = 'ユーザIDを入力してください';
        } elseif (!preg_match('/^\w{3,20}$/', $user_name)) {
            $errors[] = 'ユーザIDは半角英数字およびアンダースコアを3~20文字以内で入力してください';
        } elseif (!$this->db_manager->get('User')->isUniqueUserName($user_name)) {
            $errors[] = 'ユーザIDは既に使用されています';
        }

        // パスワードのバリデーション
        // 値がセットされていない場合 → 4文字以下もしくは30文字以上の場合
        if (!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        } elseif (4 > strlen($password) || strlen($password) > 30) {
            $errors[] = 'パスワードは4~30文字以内で入力してください';
        }

        // エラーが1つもない場合の処理
        if (count($errors) === 0) {
            // レコードを登録
            $this->db_manager->get('User')->insert($user_name, $password);

            // ログイン状態を保持
            $this->session->setAuthenticated(true);

            // データベースから登録したユーザのrowを取得
            $user = $this->db_manager->get('User')->fetchByUserName($user_name);
            // $_SESSION['user']に登録情報を格納
            $this->session->set('user', $user);

            // ホームページへリダイレクト
            return $this->redirect('/');
        }

        // 入力エラーがある場合はsignup.phpを再度レンダリング
        return $this->render([
            'user_name' => $user_name,
            'password' => $password,
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('account/signup'),
        ], 'signup');
    }

    public function indexAction() {
        $user = $this->session->get('user');

        return $this->render(['user' => $user]);
    }

    public function signinAction() {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        return $this->render([
            'user_name' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signin'),
        ]);
    }

    public function authenticateAction() {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        // HTTPメソッドのチェック
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        // CSRFトークンのチェック
        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/signin', $token)) {
            return $this->redirect('/account/signin');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');

        $errors = [];

        // ユーザIDのバリデーション
        if (!strlen($user_name)) {
            $errors[] = 'ユーザIDを入力してください';
        }

        // パスワードのバリデーション
        if (!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        }

        // エラーが1つもない場合の処理
        if (count($errors) === 0) {
            
            $user_repository = $this->db_manager->get('User');
            $user = $user_repository->fetchByUserName($user_name);

            if (
                !$user ||
                ($user['password'] !== $user_repository->hashPassword($password))
            ) {
                $errors[] = 'ユーザIDかパスワードが不正です';
            } else {
                $this->session->setAuthenticated(true);
                $this->session->set('user', $user);

                return $this->redirect('/');
            }
        }

        return $this->render([
            'user_name' => $user_name,
            'password' => $password,
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('account/signin'),
        ], 'signin');
    }

    public function signoutAction() {
        $this->session->clear();
        $this->session->setAuthenticated(false);

        return $this->redirect('/account/signin');
    }

}