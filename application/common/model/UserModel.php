<?php

namespace app\common\model;

use think\Model;

class UserModel extends Model
{
    public function complaints()
    {
        return $this->hasMany('ComplaintModel', 'user_id');
    }


    public function update_user($id, $input)
    {
        $this->where('id',$id)->update($input);
        echo "succesfully updated";
    }

    public static  function getAlluserInformation()
    {
        $user_data = array_values(UserModel::where('id',session('user_id'))->column('*'));
        return $user_data;
    }

}
