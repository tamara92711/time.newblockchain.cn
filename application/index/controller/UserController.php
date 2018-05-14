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

        $this->assign('region_level_1', $regions_level_1);
        $this->assign('header_nav', '');
        $this->assign("nav_type", 1);
        return $this->fetch();
    }

    public function submit_login(Request $request)
    {
        $mobile = $request->post('phone_number');
        $password = $request->post('password');
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

    public function submit_register(Request $request)
    {
        $user_id = $request->post('id');
        $input = $request->post();
        $user_model = new UserModel();
        $user_record = UserModel::where("id",$user_id)->find();

        $input['password'] = md5($input['password']);
        unset($input['id']);
        unset($input['captcha_code']);
        if ($input['mobile_verify_code'] == $user_record->mobile_verify_code)
        {
            $user_id = $user_record->update_user($user_id, $input);
            return redirect('/login_form');
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
        if (UserModel::where('mobile',$phone_number)->select()->count() > 0)
        {
            echo "error";
            // There will be resend verify code login
        }
        else
        {
            $user_model = new UserModel();
            $user_model->mobile = $phone_number;
            $user_model->mobile_verify_code = "12345678";
            $user_model->save();
            echo "$user_model->id";
        }
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


    public function verify($code='')
    {
        $code =input( 'post.captcha' );
        $captcha = new Captcha();
        $captcha->length = 4;
        $captcha->imageW = 120;
        $captcha->imageH = 35;
        $captcha->fontSize = 16;
        $captcha->expire = 30;  //有效期
        $captcha->useNoise = true;  //不添加杂点

        if (!$captcha->check($code)) {
//            return false;
        } else {
//            return true;
        }
        return $captcha->entry($code);
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
}
