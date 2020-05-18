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
        $tokenArr = cache('token')?json_encode(cache('token')):[];
        $toekn = md5($userId . time() . rand(1000,9999));
        $tokenArr[] = [
            $toekn=>[
                'time'=>time(),
                'userId'=>$userId
            ]
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
        $key = 0;
        $tokenTemp = [];
        foreach ($tokenArr as $k => $v){
            if(isset($v[$token])){
                $key = $k;
                $tokenTemp = $v[$token];
            }
        }
        if(!$tokenTemp){
            //无效token
            return false;
        }

        if(time() - $tokenTemp['time'] > self::EXPIRATIONTIME){
            //token已过期
            return false;
        }

        //如果token在有效期内，则刷新time，并返回userId
        $tokenTemp['time'] = time();
        $tokenArr[$key][$token] = $tokenTemp;
        cache('token',json_encode($tokenArr));
        return $tokenTemp['userId'];
    }
}