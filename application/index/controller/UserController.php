<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use think\captcha\Captcha;

use app\common\model\UserModel;
use app\common\model\RegionModel;

class UserController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    var $captcha;

    public function initialize()
    {
        $this->captcha = new Captcha;
    }
    public function index()
    {
        login_form();
    }

    public function login_form() 
    {
        $this->assign('header_nav', '');
        $this->assign("nav_type", 1);
        return $this->fetch();
    }

    public function register_form() 
    {
        $regions_level_1 = RegionModel::where('level', 1)->column('name', 'id');
        $ip = request()->ip();

        $this->assign('region_level_1', $regions_level_1);
        $this->assign('header_nav', '');
        $this->assign("nav_type", 1);
        $this->assign("ip", $ip);
        return $this->fetch();
    }

    public function send_sms_reg_code(){
        $mobile = request()->param('mobile');
        $userModel = new UsersModel();
        if(!check_mobile($mobile))
            exit(json_encode(array('status'=>-1,'msg'=>'手机号码格式有误')));
        $code =  rand(1000,9999);
        $send = sms_log($mobile,$code,$this->session_id);
        if($send['status'] != 1)
            exit(json_encode(array('status'=>-1,'msg'=>$send['msg'])));
        exit(json_encode(array('status'=>1,'msg'=>'验证码已发送，请注意查收')));
    }

    function check_mobile($mobile){
        if(preg_match('/1[34578]\d{9}$/',$mobile))
            return true;
        return false;
    }

    public function submit_login(Request $request)
    {
        $mobile = $request->post('phone_number');
        $password = $request->post('password');
        $captcha_code = $request->post('verify_code');

        $captcha = $this->captcha;
        if (!$captcha->check($captcha_code)) {
           return redirect('/login_form');
        }

        if (UserModel::where('mobile',$mobile)->count() == 0)
        {
            Session::flash('error',"This phone is not registered");
            return redirect('/login_form');
        }
        else {
            $user_record = UserModel::where('mobile',$mobile)->find();
            if ($user_record->password != md5($password))
            {
                Session::flash('error',"password is incorrect");
                return redirect('/login_form');
            }
            else {
                session('user_id',$user_record->id);
                session('user_name',$user_record->name);
                session('user_mobile',$user_record->mobile);
                return redirect('/');
            }
        }
    }

    public function test_common_file(Request $request)
    {
        return log_register_code_sms('111111', '1234');
    }
    public function submit_register(Request $request)
    {
        $input = $request->post();
        $user_name = $request->post('name');
        $captcha_code = $input['captcha_code'];
        
        // $captcha_pass = $this->verify($captcha_code);
        $captcha = $this->captcha;
        if (!$captcha->check($captcha_code)) {
           return redirect('/register_form')->with($input);
        }

        if ($input['mobile_verify_code'] == session('mobile_verify_code'))
        {
            $user_model = new UserModel();
            $user_model->name = $user_name;
            $user_model->mobile = $input['mobile'];
            $user_model->password = md5($input["password"]);
            $user_model->save();
            return redirect('/login_form');
        }
        else 
        {
            return redirect('/register_form');
        }
    }

    public function sign_out()
    {
        Session::clear();
        return redirect('/');
    }
    
    public function create_phone_verify_code(Request $request)
    {
        $phone_number = $request->post('phone_number');
        $response = [];
        $response['error'] = false;
        if (UserModel::where('mobile',$phone_number)->select()->count() > 0)
        {
            $response['error'] = true;
            $response['text'] = "手机号码已经存在";
            return json_encode(["response" => $response]);
        }
        else
        {
            $code = rand(1000, 9999);
            // session("id", session_create_id());
            session("mobile_verify_code", $code);

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
        }
        return json_encode($response);
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
    
    public function get_balance()
    {
        $current_user = UserModel::get(session('user_id'));
        echo $current_user->total_amount;
    }


    public function verify()
    {
        // $code =input( 'get.captcha_code' );
        // $captcha = new Captcha;
        $captcha = $this->captcha;
        $captcha->length = 4;
        $captcha->imageW = 120;
        $captcha->imageH = 35;
        $captcha->fontSize = 16;
        $captcha->expire = 30;  //有效期
        $captcha->useNoise = true;  //不添加杂点


        // $result1 = $captcha->entry($type);
        // $result2 = $captcha->entry($type);
        // $result3 = $captcha->entry("other type");

        return $captcha->entry();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
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
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function name_exists($name)
    {
        $exists = UserModel::where('name', $name)->count();
        return ($exists)? true: false;
    }

    public function mobile_exists($mobile)
    {
        $exists = UserModel::where('mobile', $mobile)->count();
        return ($exists)? true: false;
    }
}
