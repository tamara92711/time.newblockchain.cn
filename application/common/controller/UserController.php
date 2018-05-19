<?php

namespace app\common\controller;

use think\Controller;
use app\common\model\UserModel;
use app\common\model\RealNameVerifyModel;

class UserController extends Controller
{
    public function userExists1($user_name, $real_name, $mobile)
    {
        $response = [];
        $response['error'] = false;
        $user = UserModel::where('name', $user_name)->find();
        if (empty($user)) {
            $response['error'] = true;
            $response['content'][] = "用户名不存在！";
        }

        if ($user->mobile != $mobile) {
            $response['error'] = true;
            $response['content'][] = "手机号码不正确";
        }

        $real_user = RealNameVerifyModel::where(['user_id' => $user->id, 'is_passed' => true])->find();
        if (empty($real_user)) {
            $response['error'] = true;
            $response['content'][] = "用户没有实名认证";
        }

        return json_encode($response);
    }

}
