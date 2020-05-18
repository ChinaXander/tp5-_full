<?php

namespace token;
class tokenApi
{
    const EXPIRATIONTIME = 2592000;//默认token过期时间为30天

    public function __construct(){

    }

    /**
     * 创建token
     * @param $userId
     * @return string
     */
    static public final function createToken($userId){
        $tokenArr = cache('token')?json_decode(cache('token'),true):[];
        $toekn = md5($userId . time() . rand(1000,9999));
        $tokenArr[] = [
            'time'=>time(),
            'userId'=>$userId,
            'token'=>$toekn
        ];
        cache('token',json_encode($tokenArr));
        return $toekn;
    }

    /**
     * 通过token获取userId
     * @param $token
     * @return bool|int|mixed
     */
    static public final function getUserId($token){
        $tokenArr = json_decode(cache('token'),true);
        if(!$tokenArr){
            return false;
        }

        $tokenArrTemp = array_column($tokenArr,null,'token');
        if(!isset($tokenArrTemp[$token])){
            //无效token
            return false;
        }

        if(time() - $tokenArrTemp[$token]['time'] > self::EXPIRATIONTIME){
            //token已过期
            return false;
        }
        $userId = $tokenArrTemp[$token]['userId'];

        //如果token在有效期内，则刷新time，并返回userId
        $tokenArrTemp[$token]['time'] = time();
        cache('token',json_encode(array_values($tokenArrTemp)));
        return $userId;
    }
}