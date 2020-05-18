<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 响应数据格式化
 * @param int $code
 * @param array $data
 * @param string $msg
 * @return false|string
 */
function responseJson($code=200,$data=[],$msg='success'){
    $res['code'] = $code;
    $res['data'] = $data;
    $res['msg'] = $msg;
    return json_encode($res);
}