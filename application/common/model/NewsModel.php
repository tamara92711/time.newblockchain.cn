<?php

namespace app\common\model;

use think\Model;
use think\Db;
class NewsModel extends Model
{
    public static function getNewsCenterList()
    {
        $data = Db::table('qkl_news')
                ->where('is_show',1)
                ->where('is_deleted',0)
                ->where('type',1)
                ->field('title,
                        content,
                        date_format(create_time,\'%Y-%m-%d\') as publishTime')
                ->select();

        return json_encode(["data" => $data]);
    }
}


