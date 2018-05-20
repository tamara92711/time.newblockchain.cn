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

    public static function getImageUrl($path)
    {
        $url = "";
        if (!empty($path)) {
            $path = explode('\\',$path);
            $url = '/uploads/' . $path[0] . '/' . $path[1];
        }
        return $url;
    }


    public static function getAvarta($user_id)
    {
        try{
            $avarta_image = UserModel::where('id',$user_id)->value('avarta_image');
            return $avarta_image;
        }catch (Exception $exception){
            \think\facade\Log::write('getAvarta function error of UserModel',$exception->getMessage());
        }
    }



}
