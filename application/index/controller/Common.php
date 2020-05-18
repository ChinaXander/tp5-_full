<?php
namespace app\index\controller;
use \think\Controller;
use think\Request;
use token\tokenApi;

class Common extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    /**
     * 创建token
     * @param $userId
     * @return string
     */
    public function createToken($userId){
        return tokenApi::createToken($userId);
    }

    /**
     * 获取userId
     * @param $token
     * @return bool|int|mixed
     */
    public function getUserId($token){
        return tokenApi::getUserId($token);
    }
}