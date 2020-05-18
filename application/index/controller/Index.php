<?php
namespace app\index\controller;

class Index extends Common
{
    public function index()
    {
        // cache(null);
        // dump($this->createToken(1));
        // dump($this->createToken(2));
        // dump($this->getUserId('2bc4f39fa4377a456b3e06a47267b9e8'));
        echo '首页';
    }

    public function login(){
        echo '请登录';
    }
}
