<?php

namespace app\common\model;

use think\Db;
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
        $avarta_image = self::getImageUrl(RealNameVerifyModel::where('user_id',$user_id)->value('avarta_image'));
        return $avarta_image;
    }
}
