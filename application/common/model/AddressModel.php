<?php

namespace app\common\model;
use think\Db;
use think\Model;

class AddressModel extends Model
{
    public static function getAddressList($user_id)
    {

        $data = Db::table('qkl_address')
            ->field('concat(r.name, r1.name,a.detail)  as detail, 
            r1.name as region2,
            a.detail as onlyDetail,
            a.name,a.phone,
            a.region_id_1,
            a.region_id_2,
            a.id as id,
            a.address_value')
            ->where('a.user_id',$user_id)
            ->alias('a')
            ->join('qkl_region r','a.region_id_1=r.id')
            ->join('qkl_region r1','a.region_id_2=r1.id')
            ->order('a.address_value','desc')
            ->select();

        return $data;
    }
}


