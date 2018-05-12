<?php

namespace app\common\model;

use think\Model;
use app\common\model\UserModel;

class TransactionUserModel extends Model
{
    public function doUserTransaction($user1_id, $user2_id, $amount, $action, $transaction_type, $rate, $currency_type)
    {
        $response = [];
        // if (empty(UserModel::get($user1_id))) {
        //     $response['is_error'] = true;
        //     $response['error_message'] = "User1 doesn't exist";
        //     return $response;
        // }
        // if (empty(UserModel::get($user2_id))) {
        //     $response['is_error'] = true;
        //     $response['error_message'] = "User2 doesn't exist";
        //     return $response;
        // }
        // if (!in_array($action, [0,1]))
        // {
        //     $response['is_error'] = true;
        //     $response['error_message'] = "Action is incorrect";
        //     return $response;
        // }
        // if (!in_array($transaction_type, [1, 2, 3]))
        // {
        //     $response['is_error'] = true;
        //     $response['error_message'] = "Transaction Type is incorrect";
        //     return $response;
        // }
        // if ($rate <= 0)
        // {
        //     $response['is_error'] = true;
        //     $response['error_message'] = "Exchange rate is incorrect";
        //     return $response;
        // }
        // if (!in_array($currency_type, [0, 1]))
        // {
        //     $response['is_error'] = true;
        //     $response['error_message'] = "Currency type is incorrect";
        //     return $response;
        // }
        $user = UserModel::get($user1_id);
        if (!$action) $amount = -$amount;
        if ($currency_type) $user->total_amount = $user->total_amount + $amount;
        // $user->save();

        $this->user1_id = $user1_id;
        $this->user2_id = $user2_id;
        $this->amount = $amount;
        $this->action = $action;
        $this->transaction_type = $transaction_type;
        $this->rate = $rate;
        $this->currency_type = $currency_type;
        $this->balance = UserModel::get($user1_id)->total_amount;
        // $this->save();

        $response['is_error'] = false;
        return $response;
    }
}
