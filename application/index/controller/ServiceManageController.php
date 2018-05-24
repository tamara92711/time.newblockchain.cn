<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class ServiceManageController extends Controller
{
    public function serviceUndertaken()//21承接需求待评价
    {
        return $this->fetch();
    }

    public function publisherEvaluationOnRequest()//19发布者需求待评价
    {
        return $this->fetch();
    }

    public function undertakerDetails()//22承接人详情
    {
        return $this->fetch();
    }

    public function publish()//10发布需求
    {
        return $this->fetch();
    }

    public function undertake()//13服务分类
    {
        return $this->fetch();
    }

    public function showPublished()//18发布的需求
    {
        return $this->fetch();
    }

    public function showUndertaken()//20承接的服务
    {
        return $this->fetch();
    }

    public function demandDetailOne()//43需求明细1 承接服务   Demand Details 1 Undertaking Services
    {
        return $this->fetch();
    }

    public function releaseRequirement()//42需求明细 发布需求   42 Demand Details Release Requirements
    {
        echo session('user_id');
            echo "abc";
        if (!session()->has('user_id'))
        {
            return redirect('/login_form');
        }
        else 
            return $this->fetch();
    }

    public function message_details()//41消息详情   41 message details
    {
        $this->assign("header_nav", "message");
        $this->assign("side_nav", "");
        $this->assign("nav_type", 0);
        return $this->fetch();
    }
}
