<?php

class AccountController extends Controller {

    public function signupAction() {
        // $_SESSION['csrf_tokens/account/signup']にトークンが格納される
        // renderメソッドを実行しているので、ビューファイル内で$_token変数に格納されたトークンが利用可
        return $this->render([
            '_token' => $this->generateCsrfToken('account/signup'),
        ]);
    }

}