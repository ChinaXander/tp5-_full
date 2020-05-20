<?php
namespace app\index\controller;
use think\Controller;

class WxPushSp extends Controller
{
    const TOKEN = 'xds19941111';
    const ENCODINGAESKEY = '7RMmgF2T7MMxV1863Ods9YlpFiwzjdzpRqLdCLoNgS3';

    private $data = array();
    private $dataSign = array();

    public function index(){
        if(isset($_GET['echostr'])){
            $this->checkSignature();
        }else{
            $this->data = json_decode(file_get_contents('php://input'),true);
            //$this->data = json_decode('{"ToUserName":"gh_efc86143a46a","FromUserName":"oKNb60JdPwUfEopaGvAHnRWQuD0c","CreateTime":1589957477,"MsgType":"text","Content":"445","MsgId":22762717327845256,"Encrypt":"ZiFHCf9qpI0SvgREkZ8woxf+Zenzy2RQlXRMuApHBS8+8F121oX8vNwfoWqP6JbgXl7IWcRweMfP4QMGJH5yG38OVoTehH6xVA1bjVuCpE5xRyD9Ivk7gVoYMnyxBEkbuaWkGh0lKLAwoPRfDH7zcCsblrAR0tuAxI8dqtb54UBVc7n0hEbKL1jSQmf5gz9+qNpJdSyAUknX8fKrfHje+DMQx9gaxjs0IQO2EbnLBtaHtEIyf7H9nSn2DNCl2+IFlIZpzybk1Pno8cjhN2mBwbWwfdmfRmAlj07DRH89eic="}',true);
            $this->dataSign = $_GET;
            $this->datacheck();
        }
    }


    //校验token
    private function checkSignature() {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET['echostr'];
        $token = self::TOKEN;

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if ($tmpStr == $signature ) {
            echo $echostr;
        } else {
            return false;
        }
    }

    private function datacheck(){

        if($this->data['MsgType'] == 'event'){
            //调起客服回话时

            $token = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
            $param = [
                'msgtype' => 'text',
                'text' => ['content'=>'测试'],
                'touser' => $this->data['FromUserName']
            ];
            $res = http_post($url,json_encode($param,JSON_UNESCAPED_UNICODE));
            $res = json_decode($res,true);
            if($res['errcode'] > 0){
                write_log('-------------------------日志开启---------------------------','WechatePushApplet');
                write_log('get:'.json_encode($_GET),'WechatePushApplet');
                write_log('input:'.file_get_contents('php://input'),'WechatePushApplet');
                write_log('自动回复:'.$res,'WechatePushApplet');
            }
            exit('success');
        }else{
            //客户发送消息，直接转发至微信客服（小程序客服可在小程序公众平台设置）
            $arr = [
                'MsgType' => 'transfer_customer_service',
                'ToUserName' => $this->data['FromUserName'],
                'FromUserName' => $this->data['ToUserName'],
                // 'CreateTime' => $this->data['CreateTime']
                'CreateTime' => time()
            ];
            exit(json_encode($arr));
        }


    }

    /**
     * 获取小程序的access_token
     * @return mixed
     */
    private function getAccessToken(){
        $token = cache('sp_access_token');

        if(!$token){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".config('wx_config.appid_sp')."&secret=".config('wx_config.appsecret_sp');
            $res = http_get($url);
            if($res) $res = json_decode($res,true);
            $token = $res['access_token'];
            cache('sp_access_token',$res['access_token'],7000);
        }
        return $token;
    }
}