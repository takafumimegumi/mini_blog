<?php

class MiniBlogApplication extends Application {

    protected $login_action = ['account', 'signin'];

    public function getRootDir() {
        return __DIR__;
    }

    protected function registerRoutes() {
        return [
            '/'
                => ['controller' => 'status', 'action' => 'index'],
            '/status/post'
                => ['controller' => 'status', 'action' => 'post'],
            '/user/:user_name'
                => ['controller' => 'status', 'action' => 'user'],
            '/user/:user_name/status/:id'
                => ['controller' => 'status', 'action' => 'show'],
            '/account'
                => ['controller' => 'account', 'action' => 'index'],
            '/account/:action'
                => ['controller' => 'account'],
            '/follow'
                => ['controller' => 'account', 'action' => 'follow'],
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