<?php

namespace app\common\controller;

use \stdClass;
use think\Controller;
use think\Request;
use app\common\model\PayModel;

class PayController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function charge(Request $request)
    {
        $pay_type = $request->post('pay_type');
        $amount = $request->post('amount');
        $payment_conf = PayModel::where(['pay_type_id'=>($pay_type+1),'type'=>'0'])->find();
        $payclass = str_replace('/', '\\', $payment_conf['pay_name']);
        
        $obj = new \ReflectionClass("app\\".$payclass);

        echo $obj->getName();

        $instance = $obj->newInstance();
    //    $obj->getMethod('test_payment')->invoke($instance, null);//设置支付数据

   //     $obj->getMethod('setPayData')->invoke($instance, $data);//设置支付数据
//            showData($instance,1);


        $data = new stdClass;
        $data->amount = $amount;
        $data->conf = $payment_conf;
        
        $obj->getMethod('pay')->invokeArgs($instance, [$data]);//发起支付
    }

}

