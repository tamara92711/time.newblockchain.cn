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

        $this->assign('user_data',$user_data);
//        $this->assign('user_id',session('user_id'));
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
        if(strlen($oldPass)>=1 && strlen($newpass)>=1 && strlen($verifypass)>=1)
        {
            $checkPass = UserModel::where('id',session('user_id'))->where('password',md5($oldPass))->count();
            if ($checkPass == 0)
            {
                return "failure";
            }
        }
        else
        {
            $link=UserModel::get(session('user_id'));
            $link->real_name = $request->param("realname");
            $link->sex = $request->param("gender");
            $link->password = md5($request->param("newpass"));
            $link->job_type = $request->param("occupation");
            $link->education_type = $request->param("education");
            $link->mobile = $request->param("cellphone");
            $link->fixed = $request->param("fixed");
            $link->email = $request->param("email");
            $link->save();
            return "ok";
        }

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

        $response['text'] = config('sms.send_status')[$result];
        if ($result != 0)
        {
            $response['error'] = true;
        }

        $response['code'] = $code;


        return json_encode($response);
    }
}
