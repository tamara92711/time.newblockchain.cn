<?php

namespace app\common\model;

use think\Model;
use think\Db;

class DemandTypeModel extends Model
{

    public static function isPid($demand_type)
    {
        $isPid = Db::query('SELECT COUNT(1) as cnt FROM qkl_demand_type dt WHERE dt.pid=(SELECT MIN(pid) AS pid FROM qkl_demand_type)  AND dt.id='.$demand_type);
//        $isPid = Db::table('qkl_demand_type')
//            ->where('pid',function ($query){
//                $query->table('qkl_demand_type')->min('pid');
//            })
//            ->where('id', $demand_type)
//            ->count('id');

        if ($isPid[0]['cnt'] == 1) return true;
        if ($isPid[0]['cnt'] == 0) return false;
    }


}
