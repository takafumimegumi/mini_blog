<?php

class MiniBlogApplication extends Application {

    protected $login_action = ['account', 'signin'];

    public function getRootDir() {
        return __DIR__;
    }

    protected function registerRoutes() {
        return [

        ];
    }

    protected function configure() {
        $this->db_manager->connect('master', [
            'dsn' => 'mysql:host=localhost;dbname=mini_blog;charset=utf8',
            'user' => 'dbuser',
            'password' => 'password',
        ]);
    }

}