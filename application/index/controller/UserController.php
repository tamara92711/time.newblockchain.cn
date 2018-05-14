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

    public function sms_log($mobile,$code,$session_id){
        //判断是否存在验证码
        $data = M('sms_log')->where(array('mobile'=>$mobile,'session_id'=>$session_id))->order('id DESC')->find();
        //获取时间配置
        $sms_time_out = tpCache('sms.sms_time_out');
        $sms_time_out = $sms_time_out ? $sms_time_out : 120;
        //120秒以内不可重复发送
        if($data && (time() - $data['add_time']) < $sms_time_out)
            return array('status'=>-1,'msg'=>$sms_time_out.'秒内不允许重复发送');
        $row = M('sms_log')->add(array('mobile'=>$mobile,'code'=>$code,'add_time'=>time(),'session_id'=>$session_id));
        if(!$row)
            return array('status'=>-1,'msg'=>'发送失败');
        //$send = sendSMS($mobile,'您好，你的验证码是：'.$code);
        $send = sendSMS($mobile,$code);
        return array('status'=>1,'msg'=>'发送成功');
        if(!$send)
            return array('status'=>-1,'msg'=>'发送失败');
        return array('status'=>1,'msg'=>'发送成功');
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
        if (UserModel::where('mobile',$phone_number)->select()->count() > 0)
        {
            echo "error";
            // There will be resend verify code login
        }
        else
        {
            $user_model = new UserModel();
            $user_model->mobile = $phone_number;
            
            $code = rand(1000, 9999);
            $this->sendSMS($phone_number,$code);
            $user_model->mobile_verify_code = $code;
            $user_model->save();
            echo "$user_model->id";
        }
    }

    public function sendSMS($mobile, $code)
    {
        $apikey = '6853533555ca8f58eb9e68d93eb060fe';
        $text = "【区块链公益时间廊】您的验证码是".$code."。如非本人操作，请忽略本短信";
        $url="http://yunpian.com/v2/sms/single_send.json";
        $post_string=['apikey'=>$apikey,'mobile'=>$mobile,'text'=>$text];

        $resp = $this->httpRequest($url,'POST',$post_string);
        $resp = json_decode($resp);

        // 短信发送成功返回True，失败返回false
        // if (!$resp)
        if ($resp && $resp->code=='0')   // if($resp->result->success == true)
        {
            // 从数据库中查询是否有验证码
            // $data = M('sms_log')->where("code = '$code' and add_time > ".(time() - 60*60))->find();
            // 没有就插入验证码,供验证用
            // empty($data) && M('sms_log')->add(array('mobile' => $mobile, 'code' => $code, 'add_time' => time(), 'session_id' => SESSION_ID));
            return true;
        }
        else
        {
            return false;
        }
    }

    public function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false) {
        $method = strtoupper($method);
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }
        $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
        if($ssl){
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
        }
        //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response = curl_exec($ci);
        $requestinfo = curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);
            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return $response;
        //return array($http_code, $response,$requestinfo);
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
