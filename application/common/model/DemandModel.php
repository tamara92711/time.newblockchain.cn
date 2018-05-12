<?php

namespace app\common\model;

use think\Model;
use think\Db;

class DemandModel extends Model
{
    public static function getFields()
    {
        $field_query = Db::table('qkl_demand')
            ->field('d.id,
                        d.user_id,
                        ds.id as ds_id,
                        d.`pay_amount`,
                        dt.`name` demand_type,
                        date_format(d.`published_time`,\'%Y-%m-%d\')  published_time,
                        DATE_FORMAT(d.`service_time_from`,\'%Y-%m-%d\') service_time_from,
                        DATE_FORMAT(d.`service_time_to`,\'%Y-%m-%d\') service_time_to,
                        d.`time_total`,
                        ds.`name` state_name,
                        d.title');
        return $field_query;
    }

    public static function conditionJoin($field_query)
    {
        $join_query = $field_query
                    ->alias('d')
                    ->join('qkl_demand_type dt','d.demand_type=dt.id')
                    ->join('qkl_demand_status ds','ds.id=d.state')
                    ->order('d.create_time','desc')
                    ->limit(0,100)
                    ->select();
        return $join_query;
    }

    public static function getProjectField($demand_id)
    {
        $project_query =  Db::table('qkl_demand')
                        ->field('u.real_name,u.name,
                                    dt.name as demand_type,
                                    d.pay_amount,
                                    DATE_FORMAT(d.published_time,\'%Y-%m-%d\') published_time,
                                    d.detail as project_detail,
                                    DATE_FORMAT(d.service_time_from,\'%Y-%m-%d\') service_time_from,
                                    DATE_FORMAT(d.service_time_to, \'%Y-%m-%d\') service_time_to,
                                    d.contact_name,
                                    d.contact_phone,
                                    DATE_FORMAT(d.valid_time,\'%Y-%m-%d\') valid_times,
                                    d.apply_requirement,
                                    r.name as region1,
                                    r1.name as region2,
                                    d.address_detail,
                                    d.`time_total`,
                                    concat(r.`name`,r1.`name`,d.`address_detail`) as total_detail,
                                    d.`complete_time`,
                                    u.`name`,
                                    d.`accepted_time`,
                                    d.`publisher_comment_text`,
                                    d.`accepter_comment_text`,
                                    d.`publisher_review_rate`,
                                    d.`accepter_review_rate`,
                                    d.`publisher_comment_image`,
                                    d.`accepter_comment_image`,
                                    d.title,
                                    d.id,
                                    u.id as user_id')
                        ->where('d.id',$demand_id);
        return $project_query;
    }
    /*
     *  join condition for get all list project without complete project
     * 1.0
     */
    public static function getProjectJoin($query)
    {
        $join_query = $query
            ->alias('d')
            ->join('qkl_user u','d.user_id=u.id')
            ->join('qkl_demand_type dt','d.demand_type=dt.id')
            ->join('qkl_region r','d.region_1=r.id')
            ->join('qkl_region r1','d.region_2=r1.id')
            ->select();
        return $join_query;
    }
    /*
     *  join condition for complete project because get apply user id for user's table
     * 1.0
     */
    public static function getProjectCompletedJoin($query)
    {
        $join_query = $query
            ->alias('d')
            ->join('qkl_user u','d.applied_user_id=u.id')
            ->join('qkl_demand_type dt','d.demand_type=dt.id')
            ->join('qkl_region r','d.region_1=r.id')
            ->join('qkl_region r1','d.region_2=r1.id')
            ->select();
        return $join_query;
    }

    /*
     * show published function
     */
    public static function getPublishedListField()
    {
        $field_query = Db::table('qkl_demand')
            ->field('dt.name demand_type,
                        d.title,
                        d.pay_amount,
                        DATE_FORMAT(d.published_time, \'%Y-%m-%d\') as published_time,
                        ds.name as state_name,
                        d.id,
                        d.applied_user_id,
                        d.state as ds_id');
        return $field_query;
    }

    public static function getPublishedListJoin($query)
    {
        $complete_query = $query
                ->alias('d')
                ->join('qkl_demand_type dt','d.demand_type=dt.id')
                ->join('qkl_demand_status ds','ds.id=d.state')
                ->select();

        return $complete_query;
    }

    public static function getPublishedListWhereClause($query,$demand_type,$time_from,$time_to,$demand_state)
    {
        $query
            ->where('DATE_FORMAT(d.service_time_from, \'%Y-%m-%d\')>='."'$time_from'" or
                'DATE_FORMAT(d.service_time_to, \'%Y-%m-%d\')<='."'$time_to'")
            ->where('d.state',$demand_state)
            ->where('dt.pid',$demand_type)
            ->where('d.user_id',session('user_id'));

        return $query;
    }

    public static function getUnderkenList()
    {
        $field_query = Db::table('qkl_demand')
        ->field('dt.name,
                    d.title,
                    u.`name` AS publisher,
                    d.pay_amount,
                    DATE_FORMAT(d.published_time, \'%Y-%m-%d\') as published_time,
                    ds.name as state,
                    d.id,
                    d.`state` as ds_id');
        return $field_query;
    }

    public static function getUnderkenListJoin($query)
    {
        $complete_query = $query
            ->alias('d')
            ->join('qkl_demand_type dt','d.demand_type=dt.id')
            ->join('qkl_user u','d.applied_user_id=u.id')
            ->join('qkl_demand_status ds','ds.id=d.state')
            ->select();
        return $complete_query;
    }

    public static function getEvaluationText($rate)
    {
        if($rate == 1)
            return "【差评】";
        if($rate == 3)
            return "【中评】";
        if($rate == 5)
            return "【好评】";
    }
    /*
     * show published-list completed list that added everyone's bid count
     */
    public static function getAllPublishedList(& $data)
    {
        if (!is_array($data))
        {
            return false;
        }

        foreach ($data as $key => $val)
        {
            $isApplyUser = DemandModel::get($val['id'])->applied_user_id;

            if (empty($isApplyUser))
            {
                $bidCount = ApplyModel::where('demand_id',$val['id'])->count();
                $data[$key]['bidCount'] = $bidCount;
                $data[$key]['applyUser'] = '';
            }
            else
            {
                $data[$key]['applyUser'] = $isApplyUser;
                // get user_name according to apply user id
                // $data[$key]['name'] = UserModel::where('id',$isApplyUser)->find()->real_name;
                $data[$key]['name'] = Db::table('qkl_user')->where('id',$isApplyUser)->column('name');
            }
        }
    }

}
