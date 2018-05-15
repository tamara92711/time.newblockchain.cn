<?php
namespace app\index\validate;

use think\Validate;

class UserRegister extends Validate
{
    protected $rule = [
        'user_name'  =>  'require|max:10',
        'mobile'   => 'require|number|max:11|min:11',
        'password' =>  'require',
        'pwconfirm' => 'require',
        'region_1' => 'require|number',
        'region_2' => 'require|number',
    ];

    protected $message  =   [
        'user_name.require' => '名称必须',
        'name.max'     => '名称最多不能超过10个字符',
        'mobile.require' => '名称必须',
        'mobile.number'   => '手机号码必须是数字',
        'mobile.max'     => '手机号码最多不能超过11个字符',
        'mobile.min'     => '手机号码最少不能超过11个字符',
        'password.require' => '登录密码必须',
        'pwconfirm.require' => '确认密码必须',
        'region_1.require' => '名称必须',
        'region_1.number'   => '地区1必须是数字',
        'region_2.number'   => '地区2必须是数字',
        'region_2.require' => '名称必须',
        
        
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误',    
    ];
}