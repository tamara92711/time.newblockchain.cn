<?php
// +----------------------------------------------------------------------
// | OnlineRetailers [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2003-2023 www.yisu.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 亿速网络（http://www.yisu.cn）
// +----------------------------------------------------------------------
// | Author: 王强 <opjklu@126.com>
// +----------------------------------------------------------------------

namespace app\Common\Pay;

use app\Home\Model\OrderModel;
use app\PlugInUnit\UpacpApp\SDK\AcpService;
use app\Common\Model\BaseModel;
use app\Common\Model\PayModel;
use app\Common\TraitClass\NoticeTrait;
use app\Common\TraitClass\PayTrait;

/**
 * 银联 
 */
class UnionPay extends AcpService
{
    use NoticeTrait;
    use PayTrait;

    /**
     * 银联支付 
     */
    public function pay($obj, $info = null) {
        
       /* if (!is_object($obj)) {
            throw new \Exception('参数错误');
        }
        
        if (empty($info[OrderModel::$orderSn_id_d])) {
            $obj->showMessage('参数错误');die();
        }
        if (empty($info[OrderModel::$priceSum_d]) || $info[OrderModel::$priceSum_d] < 0) {
            $obj->showMessage('参数错误');die();
        }
        
        $payModel = BaseModel::getInstance(PayModel::class);
         
        $data = $payModel->getPayInfo($info[OrderModel::$payType_d], $info[OrderModel::$platform_d]);
        
        if (empty($data)) {
            $obj->showMessage($data, '参数有误');
        }
        
        $config = C('UnionPay');

        if (empty($data)) {
            $obj->showMessage($data, '参数有误');
        }
        */
        
        $amount = $obj->amount;
        $data = $obj->conf;

        $config = config('pay.UnionPay');

        if (empty($alipay_config)) {
            $obj->error('参数错误');
            die();
        }
        $config['merId']   = $data[PayModel::$payAccount_d];
        
        $config['orderId'] = $info[OrderModel::$orderSn_id_d];
        
        $config['txnAmt']  = $info[OrderModel::$priceSum_d] * 100;
        self::sign($config);
        $html = self::createAutoFormHtml($config, self::SDK_FRONT_TRANS_URL);
        die($html);
    }

}