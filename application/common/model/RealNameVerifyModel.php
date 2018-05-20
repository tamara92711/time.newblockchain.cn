<?php

namespace app\common\model;

use think\Db;
use think\Exception;
use think\Model;

class RealNameVerifyModel extends Model
{
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
            $avarta_image = substr(RealNameVerifyModel::where('user_id',$user_id)->value('avarta_image'),1);
            return $avarta_image;
        }catch (Exception $exception){
            \think\facade\Log::write('getAvarta function error of realNameVerifyController',$exception->getMessage());
        }
    }
}
