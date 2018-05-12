<?php

namespace app\common\model;

use think\Model;
use think\Db;

class ApplyModel extends Model
{
    /*
     * according to condition get reviews list
     */
    public static function getUserReviewList($user_id)
    {
        $subquery = Db::table('qkl_user')
            ->field('name,mobile,id')
            ->where('id','in',$user_id)
            ->buildSql();

        $data = Db::table('qkl_project_review')
            ->alias('reviews')
            ->rightJoin($subquery.' users', 'reviews.user_id = users.id')
            ->field('users.name,users.mobile AS mobile,users.id AS user_id, IFNULL(SUM(reviews.review),0) AS rate')
            ->group('users.id')
            ->order('reviews.create_time')
            ->select();

        return $data;
    }
}
