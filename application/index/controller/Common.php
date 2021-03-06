<?php
namespace app\index\controller;
use \think\Controller;
use think\Request;
use token\tokenApi;

class Common extends Controller
{
    public $userId;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $token = isset($_SERVER['HTTP_TOKEN'])?$_SERVER['HTTP_TOKEN']:null;
        $this->userId = tokenApi::getUserId($token);
        $nowUrl = strtolower($request->module().'/'.$request->controller().'/'.$request->action());
        if(!in_array($nowUrl,config('setLogin.notLogin'))){
            if(!$this->userId){
                echo responseJson(401,[],'登录过期');exit;
            }
        }

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