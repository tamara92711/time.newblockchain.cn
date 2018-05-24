<?php

namespace app\index\controller\data_management;


use app\common\model\UserModel;
use think\Controller;
use think\Request;

class PersonalInformationController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function index()
    {
        $this->assign('header_nav', 'project_publish');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'personal_info');
        $user_data = UserModel::getAlluserInformation();

        if(!empty($user_data[0]['real_name']))
            $user_real_name = $user_data[0]['real_name'];
        else
            $user_real_name = '';

        if(!empty($user_data[0]['fixed']))
            $user_fixed = $user_data[0]['fixed'];
        else
            $user_fixed = '';

        if(!empty($user_data[0]['email']))
            $user_email = $user_data[0]['email'];
        else
            $user_email = '';

        $this->assign(['user_real_name'=>$user_real_name,'user_fixed'=>$user_fixed,'user_email'=>$user_email]);
        $this->assign('user_data',$user_data);

        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {


    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {

    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function personalUpdate(Request $request)
    {

        $oldPass = $request->param("beforepass");
        $newpass = $request->param("newpass");
        $verifypass = $request->param("verifypass");
        $user = UserModel::get(session('user_id'));

        if(strlen($oldPass)>=1 && strlen($newpass)>=1 && strlen($verifypass)>=1 && $user->password != md5($oldPass))
        {
            return "failure";
        } else {
            $user->password = md5($request->param("newpass"));
        }

        $user->real_name = $request->param("realname");
        $user->sex = $request->param("gender");
        
        $user->job_type = $request->param("occupation");
        $user->education_type = $request->param("education");
        $user->mobile = $request->param("cellphone");
        $user->fixed = $request->param("fixed");
        $user->email = $request->param("email");
        $user->save();
        return "ok";

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    public function confirmOldMobileVerifyCode(Request $request)
    {
        $verify_code = $request ->param('oldMobileVerifyCode');
        if ($verify_code == session('mobile_verify_code'))
            echo "ok";
        else
            echo "wrong";

    }

    public function confirmNewMobileVerifyCode(Request $request)
    {
        $verify_code = $request ->param('newMobileVerifyCode');
        if ($verify_code == session('new_mobile_verify_code'))
            echo "ok";
        else
            echo "wrong";

    }


    public function existMobile(Request $request)
    {
        $new_mobile = $request->post('new_mobile');
        if (UserModel::where('mobile',$new_mobile)->select()->count() > 0)
            echo "exist";
        else
            echo "not exist";
    }

    public function phoneChangeVerifyCode(Request $request)
    {
        $send_status = [
            "0" => "短信发送成功",
            "-1" => "参数不全",
            "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
            "30" => "密码错误",
            "40" => "账号不存在",
            "41" => "余额不足",
            "42" => "帐户已过期",
            "43" => "IP地址限制",
            "50" => "内容含有敏感词"
        ];

        $response = [];
        $response['error'] = false;
        $code = rand(1000, 9999);

        $mode             = $request->post('mode');
        if ($mode == 1)
        {
            $phone_number = $request->post('phone_number');
            session("mobile_verify_code", $code);
        }
        if ($mode == 2)
        {
            $phone_number = $request->post('phone_number');
            session("new_mobile_verify_code", $code);
        }
            //sms content
        $content = "您的区块链公益时间廊注册验证码是\n" . $code;

        // sms config
        $smsapi = 'http://api.smsbao.com/';
        $user = 'gdbc';
        $pass = md5('gdqkl2018'); //短信平台密码

        //send sms
        $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone_number."&c=".urlencode($content);
        $result =file_get_contents($sendurl) ;

        if (!in_array($result, array_keys($send_status))) {
            $response['text'] = "Unknown Error Occured while sending SMS!";
        } else {
            $response['text'] = $send_status[$result];
        }
        
        if ($result != 0)
        {
            $response['error'] = true;
        }

        $response['code'] = $code;


        return json_encode($response);
    }
}
