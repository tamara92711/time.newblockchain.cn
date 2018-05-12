<?php
namespace app\index\controller;
use think\Controller;

class DataManageController extends Controller
{
    public function realNameVeri()//07实名认证
    {
        return $this->fetch();
    }

    public function qualification()//08专业证书
    {
        return $this->fetch();
    }

//    public function addressManage()//09地址管理
//    {
//        return $this->fetch();
//    }

    public function personalInfo()//06个人信息
    {
        return $this->fetch();
    }
}