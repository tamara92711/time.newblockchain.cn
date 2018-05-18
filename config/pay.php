<?php
return array(
    'pay_type_img' => [
      '/Public/Home/img/wx.png',
      '/Public/Home/img/alipay.png',
      '/Public/Home/img/union_pay.png',
      '/Public/Home/img/balance.png'
    ],
    'ALIPAY_CONFIG' => [
        'notify_url'        => 'http://localhost',
        'return_url'        => 'http://localhost',
        'sign_type'         => strtoupper( 'MD5' ),
        'input_charset'     => strtolower( 'utf-8' ),
        'cacert'            => Env::get('app_path') . 'PlugInUnit\PCAlipay\MD5\cacert.pem',
        'transport'         => 'https',
        'payment_type'      => "1",
        'service'           => "create_forex_trade",
        'anti_phishing_key' => "",
        'exter_invoke_ip'   => "",
    ],

    'UnionPay'        => [
        //-------------------------------------------------------------------------------------------
        // 前台通知地址
        'frontUrl'      => '',
        // 后台通知地址
        'backUrl'       => '',
        'RefundBackUrl' => '',
    ],
);
?>