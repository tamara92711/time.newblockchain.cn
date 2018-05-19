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

use app\Common\Model\PayModel;
use app\PlugInUnit\PCAlipay\MD5\Lib\AlipaySubmit;
use app\Common\TraitClass\PayTrait;

class Alipay extends AlipaySubmit
{
    use PayTrait;

    /**
     * 支付宝支付
     */
    public function pay($obj)
    {
        /*  if (! is_object($obj)) {
                throw new \Exception('参数错误');
            }
        */
        
        // 获取支付宝配置
        $alipay_config = config('pay.ALIPAY_CONFIG');

        if (empty($alipay_config)) {
            $obj->error('参数错误');
            die();
        }
        $amount = $obj->amount;
        $conf = $obj->conf;
        //echo $obj->amount;
        
        //$data = $this->getPayData();
        
        //if (empty($data)) {

        //    $obj->showMessage($data, '参数错误');
        //}
        
        $alipay_config['partner'] = '2088031407906170';//$conf['pay_account'];
        $alipay_config['seller_id'] = 'forex_646917@alitest.com';//$conf['mchid'];
        $alipay_config['key'] = 'u6yuq5xgebmu6axnen498toilp10jmbk';//$conf['pay_key'];
        
        $alipay_config['private_key'] =  "";//$conf['private_pem'];
        
        $alipay_config['alipay_public_key'] = $conf['public_pem'];
        
        //$urlNofity = $obj->getNofityURL();
        
       // $urlNofity = empty($urlNofity) ? $alipay_config['return_url'] : U('RechargeNofity/nofity', [
       //     'callBack' => 'rechargeAl'
       // ], true, true);
            
        $parameter = [
            "service" => $alipay_config['service'],
            "partner" => '2088031407906170',//$conf['pay_account'],
            "seller_email" => 'forex_646917@alitest.com',////$conf['mchid'],
            "payment_type" => $alipay_config['payment_type'],
            "notify_url" => $alipay_config['notify_url'],
            "return_url" => 'http://www.test.com/alipay/return_url.asp',/*$urlNofity*/
            "anti_phishing_key" => $alipay_config['anti_phishing_key'],
            "exter_invoke_ip" => $alipay_config['exter_invoke_ip'],//"out_trade_no" => $info['order_sn_id'],
            "subject" => 'test',
            "total_fee" => $amount,
            "body" => $conf['id'],//$data['id'],
            "_input_charset" => trim(strtolower($alipay_config['input_charset'])),
            "out_trade_no"=>"6741334835157966"
            //'sys_service_provider_id' => $conf['id'],      
        ];
        // 其他业务参数根据在线开发文档，添加参数.文档地址:
        // https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
        // 如"参数名"=>"参数值"
        
        $this->setAlipay_config($alipay_config);
        // 建立请求
        $html_text = $this->buildRequestForm($parameter, "get", "确认");
        echo $html_text;
        die();
    }
}