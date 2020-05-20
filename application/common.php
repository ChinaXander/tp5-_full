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

/**
 * @param string $msg 日志内容
 * @param string $type 文件夹名称
 * @param string $filename 日志文件名
 * @return int
 */
function write_log($msg='',$type="",$filename=''){
    if(!$filename){
        $filename=date('Y-m-d').'.txt';
    }
    $root = $_SERVER['DOCUMENT_ROOT'];
    $log_path = $root.DS."log".DS.$type.DS;
    if(!is_dir($log_path)){
        mk_dir($log_path);
    }
    $filename = $log_path . $filename;
    $division = date('H:i:s');
    $msg = $division.'  '.$msg . "\r\n";
    $msg = iconv('utf-8', 'gbk', $msg);
    $handle = fopen($filename, 'a+');
    $true = fwrite($handle, $msg);
    fclose($handle);
    return $true;
}

/** * 根据路径创建目录 *
 * @param string $dir 目录路径 *
 * @return bool 成功返回true
 */
function mk_dir($dir){
    if (!file_exists($dir))    {
        mk_dir(dirname($dir));
        @mkdir($dir, 0777);
    }else {
        return true;
    }
}


/**
 * curl get 请求
 * @param $url
 * @param array $header
 * @return bool|mixed
 */
function http_get($url,$header=[]){
    $oCurl = curl_init();
    if (stripos($url, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    if($header){
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);//设置请求头
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if (intval($aStatus["http_code"]) == 200) {
        return $sContent;
    } else {
        return false;
    }
}

/**
 * POST 请求
 * @param string $url
 * @param array $param
 * @param array $header
 * @param boolean $post_file 是否文件上传
 * @param boolean $is_init
 * @return string content
 */
function http_post($url,$param,$header=[],$post_file=false,$is_init=false){
    $oCurl = curl_init();
    if (stripos($url, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    if (is_string($param) || $post_file) {
        $strPOST = $param;
    } else {
        $aPOST = array();
        foreach ($param as $key => $val) {
            $aPOST[] = $key . "=" . urlencode($val);
        }
        $strPOST = join("&", $aPOST);
    }
    if($header){
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);//设置请求头
    }
    curl_setopt($oCurl, CURLINFO_HEADER_OUT, TRUE);
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POST, true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    if (intval($aStatus["http_code"]) == 200) {
        return $sContent;
    } else {
        if($is_init) return $sContent;
        return false;
    }
}
