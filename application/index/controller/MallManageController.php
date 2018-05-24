<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

use app\common\model\CollectionModel;

class MallManageController extends Controller
{
    public function myOrders()//26订单管理
    {
        $this->assign('header_nav', 'home');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'orders');
        return $this->fetch();
    }

    public function my_collection()//27商品收藏
    {
        $collection = CollectionModel::where(['user_id' => session('user_id')])->select();
        $this->assign('header_nav', 'home');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'collection');
        $this->assign('collection', $collection);
        return $this->fetch();
    }

    public function productDetail()//28商品详情页
    {
        $this->assign('header_nav', 'home');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'product_detail');
        return $this->fetch();
    }
}
