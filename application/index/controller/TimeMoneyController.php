<?php

namespace app\index\controller;

use think\Controller;
use app\common\model\UserModel;
use app\common\model\TransactionUserModel;
use app\common\model\TransactionAdminModel;
use app\common\model\PayModel;
use app\common\model\PayTypeModel;
use think\Request;

class TimeMoneyController extends Controller
{
    public function initialize()
    {
    }

    public function getTransactionHistory()
    {
        $time_from = request()->param('time_from');
        $time_to = request()->param('time_to');
        $transaction_type = request()->param('transaction_type');
        $user_name = request()->param('user_name');
        $user2 = UserModel::where('name', $user_name)->find();
        $user2_id = 0;
        if (!empty($user2)) $user2_id = $user2->id;

        $user1_id = session('user_id');
        $history = TransactionUserModel::where('user1_id', $user1_id)->order('create_time desc')->select()->toArray();
        foreach($history as $key => $value)
        {
            $history[$key]['user_name'] = UserModel::get($value['user2_id'])->name;
        }
        return json_encode(["items" => $history]);
    }
    public function transactionHistory() //23时间币明细
    {
        $user1_id = session('user_id');
        $this->assign('header_nav', 'project_publish');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'transaction_history');

        $history = TransactionUserModel::where('user1_id', $user1_id)->order('create_time desc')->select();
        $this->assign('history', json_encode(["items" => $history]));
        return $this->fetch();
    }

    public function buy() //24时间币充值
    {
        $this->assign('header_nav', 'project_publish');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'buy');
        $this->assign('pay_image',config('pay.pay_type_img'));
        $this->assign('payment_methods',PayTypeModel::all('2,3'));//tempcode
        $this->assign('total_amount', UserModel::get(session('user_id'))->total_amount);
        return $this->fetch();
    }

    public function confirmBuy()
    {
        $user1_id = session('user_id');
        $amount = request()->param("amount");

        $user1 = UserModel::get($user1_id);
        $user1->total_amount += $amount;
        $user1->save();

        $trans2 = new TransactionUserModel;
        $trans2->user1_id = $user1_id;
        $trans2->user2_id = 1;
        $trans2->amount = $amount;
        $trans2->action = 1;
        $trans2->transaction_type = 1;
        $trans2->state = 1;
        $trans2->rate = 1;
        $trans2->balance = UserModel::get($user1_id)->total_amount;
        $trans2->currency_type = 1;
        // $trans2->doUserTransaction($user1_id, 1, $amount, 1, 1, 1, 1);

        $trans1 = new TransactionUserModel;
        $trans1->user2_id = $user1_id;
        $trans1->user1_id = 1;
        $trans1->amount = $amount;
        $trans1->action = 1;
        $trans1->transaction_type = 1;
        $trans1->state = 1;
        $trans1->rate = 1;
        $trans1->balance = UserModel::get(1)->total_amount;
        $trans1->currency_type = 0;
        // $trans1->doUserTransaction(1, $user1_id, $amount, 1, 1, 1, 0);
        
        $trans1->save();
        $trans2->save();
        return UserModel::get($user1_id)->total_amount;
    }


    public function transfer() //25时间币捐赠
    {
        $user1_id = session('user_id');
        $this->assign('header_nav', 'project_publish');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'transfer');
        $this->assign('total_amount', UserModel::get($user1_id)->total_amount);
        return $this->fetch();
    }

    public function confirmTransfer()
    {
        $response = [];
        $user1_id = session('user_id');
        $user_name = request()->param('user_name');
        $real_name = request()->param('real_name');
        $mobile = request()->param('mobile');
        $amount = request()->param('amount');
        $count = UserModel::where(['name' => $user_name, 'real_name' => $real_name, 'mobile' => $mobile])->count();        
        
        if ($count == 0)
        {
            $response['is_error'] = true;
            $response['error_message'] = "用户不存在!!!";
            return json_encode($response);
        }
        
        $user1 = UserModel::get($user1_id);
        $user1->total_amount -= $amount;
        $user1->save();

        $user2 = UserModel::where(['name'=>$user_name, 'real_name'=>$real_name, 'mobile'=>$mobile])->find();
        $user2_id = $user2->id;
        $user2->total_amount += $amount;
        $user2->save();
        
        $trans1 = new TransactionUserModel;
        $trans1->user1_id = $user1_id;
        $trans1->user2_id = $user2_id;
        $trans1->amount = $amount;
        $trans1->action = 0;
        $trans1->transaction_type = 2;
        $trans1->state = 1;
        $trans1->rate = 1;
        $trans1->currency_type = 1;
        $trans1->balance = UserModel::get($user1_id)->total_amount;
        
        // $trans1->doUserTransaction($user1_id, $user2_id, $amount, 0, 2, 1, 1);

        $trans2 = new TransactionUserModel;
        $trans2->user2_id = $user1_id;
        $trans2->user1_id = $user2_id;
        $trans2->amount = $amount;
        $trans2->action = 1;
        $trans2->transaction_type = 2;
        $trans2->state = 1;
        $trans2->rate = 1;
        $trans2->currency_type = 1;
        $trans2->balance = UserModel::get($user2_id)->total_amount;
        
        // $trans2->doUserTransaction($user2_id, $user1_id, $amount, 1, 2, 1, 1);
        $trans1->save();
        $trans2->save();
        
        $response['total_amount'] = UserModel::get($user1_id)->total_amount;
        return json_encode($response);
    }
}
