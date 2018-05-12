<?php
return array(
    'pay_type_img' => [
      '/Public/Home/img/wx.png',
      '/Public/Home/img/alipay.png',
      '/Public/Home/img/union_pay.png',
      '/Public/Home/img/balance.png'
    ],
    'ALIPAY_CONFIG' => [
        'notify_url'        => '',
        'return_url'        => '',
        'sign_type'         => strtoupper( 'MD5' ),
        'input_charset'     => strtolower( 'utf-8' ),
        'cacert'            => Env::get('app_path') . 'app/PlugInUnit/PCAlipay/RSA/cacert.pem',
        'transport'         => 'http',
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