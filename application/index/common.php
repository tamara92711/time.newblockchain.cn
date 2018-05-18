<?php
use \app\index\validate\User;
use \app\common\model\SmsLogModel;
use think\facade\Session;

function test()
{
    $content = "qukuailian sms test";
    $phone = "18988585563";
    echo get_sms_status_text(send_sms(config('sms.api'), config('sms.user'), config('sms.password'), $content, $phone));
}

function test1()
{
    $result = $this->validate(
    [
        'name'  => 'thinkphp',
        'email' => 'thinkphp@qq.com',
    ],
    'app\index\validate\User');
    $data = [
        'name'  => '',
        'email' => 'thinkphp@qq.com',
    ];

    $validate = new \app\index\validate\User;

    if (!$validate->check($data)) {
        dump($validate->getError());
    }
}

function log_register_code_sms($phone, $code)
{
    Session::delete("id");
    $id = session_create_id();    
    if (!session("?id")) 
    {
        session("id", $id);
    }
    $smsLog = new SmsLogModel;
    $smsLog->session_id = session('id');
    $smsLog->type = 1;
    $smsLog->content = $code;
    $smsLog->save();
}

function send_register_code_sms($phone, $code)
{
    $content = "您的区块链公益时间廊注册验证码是\n" . $code;
    $response_code = send_sms($phone, $content);
    if ($response_code == 0) log_register_code_sms($phone, $code);
    return $response_code;
}

function get_sms_status_text($status)
{
    return config('sms.send_status')[$status];
}

function send_sms($phone, $content)
{
    $smsapi = config('sms.api');
    $user = config('sms.user');
    $pass = md5(config('sms.password')); //短信平台密码
    $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
    $result =file_get_contents($sendurl) ;
    return $result;
}

function send_phone_verify_code()
{
    
}