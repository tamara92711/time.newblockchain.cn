<?php
namespace app\index\controller;
use app\index\validate\User;
use think\Db;
use think\Controller;
use think\captcha\Captcha;

use app\common\model\CharitableOrganizationModel;
use app\common\model\DemandModel;
use app\common\model\DemandTypeModel;
use app\common\model\MessageModel;
use app\common\model\NewsModel;
use app\common\model\NewsTypeModel;
use app\common\model\RegionModel;
use app\common\model\UserModel;


class IndexController extends Controller
{
    var $captcha;

    public function initialize()
    {
        $this->captcha = new Captcha;
    }

    public function index()
    {
        // return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
        return redirect('/home');
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function home() //01首页
    {
        $latest_demands = DemandModel::where('demand_type', '<>', 3)->order('create_time', 'desc')->limit(5)->select()->toArray();
        foreach($latest_demands as $key=>$value)
        {
            if (!empty(RegionModel::get($value['region_1']))) $latest_demands[$key]['region_1'] = RegionModel::get($value['region_1'])->name;
            if (!empty(RegionModel::get($value['region_2']))) $latest_demands[$key]['region_2'] = RegionModel::get($value['region_2'])->name;
            if (!empty(DemandTypeModel::get($value['demand_type']))) $latest_demands[$key]['demand_type'] = DemandTypeModel::get($value['demand_type'])->name;
            $latest_demands[$key]['published_time'] = date('Y-m-d', strtotime($value['published_time']));
        }

        $news = NewsModel::where('is_deleted', 0)->order('create_time', 'desc')->limit(6)->select()->toArray();
        foreach($news as $key=>$value)
        {
            $news[$key]['type'] = NewsTypeModel::get($value['type'])->name;
        }

        $organizations = CharitableOrganizationModel::where('is_deleted', 0)->limit(6)->select()->toArray();
        foreach ($organizations as $key => $value)
        {
            $organizations[$key]['image'] = $this->getImageUrl($value['image']);
        }

        $users = UserModel::where(['is_deleted' => 0, 'real_name_verified' => 1])->limit(10)->select()->toArray();
        // $organizations = CharitableOrganizationModel::get($id);
        // if (!empty($organizations->image)) {
        //     $path = explode('\\',$data->image);
        //     $organizations->image = '/uploads/' . $path[0] . '/' . $path[1];
        // }
        
        $this->assign("organizations", $organizations);
        $this->assign("users", $users);
        $this->assign("latest_demands", $latest_demands);
        $this->assign("news", $news);
        $this->assign("organizations", $organizations);
        $this->assign("header_nav", "home");
        $this->assign("nav_type", 1);
        return $this->fetch();
    }

    private function getImageUrl($path)
    {
        $url = "";
        if (!empty($path)) {
            $path = explode('\\',$path);
            $url = '/uploads/' . $path[0] . '/' . $path[1];
        }
        return $url;
    }

    public function register()//02注册
    {
        $this->assign("header_nav", "");
        $this->assign("nav_type", 1);
        return $this->fetch();
    }

    public function forgotPassword()//03忘记密码
    {
        $this->assign("header_nav", "");
        $this->assign("nav_type", 1);
        $temp = json_decode(session('temp'));
        $this->assign("mobile", empty($temp)? '': $temp->mobile);
        return $this->fetch();
    }

    public function resetPassword()
    {
        $code = request()->param("captcha_code");
        $captcha = $this->captcha;

        if (!$captcha->check($code)) {
            return redirect('/forgot_password')->with('error', '验证码不正确');
        }

        $mvc = request()->param("mobile_verify_code");
        if ($mvc != session('mobile_verify_code')) {
            return redirect('/forgot_password')->with('error', '手机验证码不正确');
        }   

        $phone = request()->post("mobile");
        $user = UserModel::where('mobile', $phone)->find();
        if (empty($user)) return redirect('/forgot_password')->with('error', '手机号码不存在');

        $user->password = md5(request()->post('password'));
        $user->save();
        return redirect('/login_form');
    }

    public function login()//04登录
    {
        return $this->fetch();
    }

    public function memberCenter() //05会员中心
    {
        //show project list i posted
        $publish_query  = DemandModel::getPublishedListField();
        $publish_query  = $publish_query->where('d.user_id',session('user_id'));
        $published_data  = DemandModel::getPublishedListJoinForMemberCenter($publish_query);
        //show project list i accepted
        $underker_query = DemandModel::getUnderkenList();
        $underker_query = $underker_query ->where('d.applied_user_id',session('user_id'));
        $underker_data  = DemandModel::getUnderkenListJoinForMemberCenter($underker_query);
        //user's information
        $user_data      = UserModel::getAlluserInformation();
        //freelancer bided project that publisher offer,but count of publisher don't select biding freelancer
        $unAppliedCnt   = DemandModel::calculateFreelancerStatics_1();
        $freeStatics    = DemandModel::calculateFreelancerStatics_2();

        $unBidCnt       = DemandModel::calculatePublisherUnBidCount();
        $publi_Statics  = DemandModel::calculatePublisherStatics_2();
        $total_appliCnt = DemandModel::where(['user_id'=>session('user_id')],['applied_user_id','<>',0])->count();

        $this->assign(['unAppliedCnt'=>$unAppliedCnt,'freeStatics'=>$freeStatics,'unBidCnt'=>$unBidCnt,'publi_Statics'=>$publi_Statics,'total_appliCnt'=>$total_appliCnt]);

        $this->assign(['user_data'=>$user_data,"published_data"=>$published_data,"underker_data" =>$underker_data, 'header_nav' =>'personal_home',"side_nav"=>'personal_info','nav_type'=>0]);

        return $this->fetch();
    }

    public function projectPublishing()//14项目详细页(发布中)
    {
        return $this->fetch();
    }

    public function projectAccepted()//15项目详细页(已承接)
    {
        return $this->fetch();
    }

    public function projectComplete()//16项目详细页(已完成)
    {
        return $this->fetch();
    }

    public function projectCancel()//17项目详细页(已取消)
    {
        return $this->fetch();
    }

    public function timeCoinSchedule()//23时间币明细
    {
        return $this->fetch();
    }

    public function timeCurrency()//24时间币充值
    {
        return $this->fetch();
    }

    public function timeMoneyDonation()  //25时间币捐赠
    {
        return $this->fetch();
    }

    public function complaintSuggestions()  //29投诉与建议
    {
        return $this->fetch();
    }

    public function newsCenter()  //30NewsCenter
    {
        return $this->fetch();
    }
//done
    public function loveEnterprise()  //31 loveEnterprise
    {
        return $this->fetch();
    }

    public function chairtableOrganization()  //32 chairtableOrganization
    {
        return $this->fetch();
    }

    public function volunteerGrace()  //33volunteerGrace
    {
        return $this->fetch();
    }

    public function addNewCertificate()  //44新增证书
    {
        return $this->fetch();
    }

    public function message()  //40消息 message
    {
        $this->assign("header_nav", "message");
        $this->assign("side_nav", "");
        $this->assign("nav_type", 0);
        return $this->fetch();
    }

    public function get_messages()
    {
        $user_id = session('user_id');
        $state = request()->state;

        $search_criteria = [];
        $search_criteria['receiver_id'] = $user_id;
        if ($state != 2) $search_criteria['state'] = $state;
        // $messages = MessageModel::where($search_criteria)->order('create_time desc')->select();
        $messages = Db::table('qkl_message')
                        ->alias('m')
                        ->field('m.id, m.title, u.name, m.create_time ctime, m.state, m.content')
                        ->join('qkl_user u', 'u.id = m.sender_id')
                        ->where($search_criteria)
                        ->order('m.id desc')
                        ->select();

        return json_encode(['data' => $messages]);
    }

    public function message_count() {
        $user_id = session('user_id');
        $cnt = MessageModel::where(['receiver_id'=> $user_id, 'state' => 0])->select()->count();
        return $cnt;
    }

    public function purchaseInterface()  //39购买界面
    {
        return $this->fetch();
    }

    public function shoppingCart()  //38购物车
    {
        return $this->fetch();
    }

    public function clear_phone_verify_code_session()
    {
        session('code', null);
    }

    public function verify()
    {
        $captcha = $this->captcha;
        $captcha->length = 4;
        $captcha->imageW = 120;
        $captcha->imageH = 35;
        $captcha->fontSize = 16;
        $captcha->expire = 30;  //有效期
        $captcha->useNoise = true;  //不添加杂点

        return $captcha->entry();
    }
}
