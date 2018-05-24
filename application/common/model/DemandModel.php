<?php

namespace app\common\model;

use think\Exception;
use think\facade\Log;
use think\Model;
use think\Db;

class DemandModel extends Model
{
    //unbid field add
    public static function getFieldsJobList_1()
    {
        $field_query = Db::table('qkl_demand')
            ->field('d.id,
                         d.user_id,
                         d.state,
                         d.`pay_amount`,
                         dt.`name` demand_type,
                         date_format(d.`published_time`,\'%Y-%m-%d\')  published_time,
                         DATE_FORMAT(d.`service_time_from`,\'%Y-%m-%d\') service_time_from,
                         DATE_FORMAT(d.`service_time_to`,\'%Y-%m-%d\') service_time_to,
                         d.`time_total`,
                         d.title,
                         d.is_reviewed,
                         "unbid" as display_id');
        return $field_query;
    }

    //bid field add

    public static function getFieldsJobList_2()
    {
        $field_query = Db::table('qkl_demand')
            ->field('d.id,
                         d.user_id,
                         d.state,
                         d.`pay_amount`,
                         dt.`name` demand_type,
                         date_format(d.`published_time`,\'%Y-%m-%d\')  published_time,
                         DATE_FORMAT(d.`service_time_from`,\'%Y-%m-%d\') service_time_from,
                         DATE_FORMAT(d.`service_time_to`,\'%Y-%m-%d\') service_time_to,
                         d.`time_total`,
                         d.title,
                         d.is_reviewed,
                         "bid" as display_id');
        return $field_query;
    }

    public static function jobListWhereClause($temp_query,$demand_type,$time_currency,$time_currency_from,$time_currency_to,$release_time)
    {
        $today = date("Y-m-d");
        if ($demand_type == 0)
            $temp_query =$temp_query->where('dt.id', '>',$demand_type);
        else if ($demand_type >= 1)
        {
            //parent id or child id
            $hasPid  = DemandTypeModel::isPid($demand_type);
            if($hasPid)
                $temp_query =$temp_query->where('dt.pid',$demand_type);
            else
                $temp_query =$temp_query->where('dt.id',$demand_type);
        }

        if ($time_currency >= 0)
        {
            switch ($time_currency)
            {
                case 1:
                    $temp_query =$temp_query->where('d.pay_amount','>','0');
                    break;
                case 2:
                    $temp_query =$temp_query->where('d.pay_amount','between','1,5');
                    break;
                case 3:
                    $temp_query =$temp_query->where('d.pay_amount','between','5,10');
                    break;
                case 4:
                    $temp_query =$temp_query->where('d.pay_amount','>','10');
                    break;
            }
            if ($time_currency_from > 0 and $time_currency_to > 0 )
            {
                $temp_query =$temp_query->where('d.pay_amount','>=',$time_currency_from)->where('d.pay_amount','<=',$time_currency_to);
            }
        }

        if ($release_time >= 0)
        {
            switch ($release_time)
            {
                case 1:
                    $temp_query =$temp_query->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')='."'$today'");
                    break;
                case 2:
                    $yesterday = date('Y-m-d',strtotime("-1 days"));
                    $temp_query =$temp_query->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')='."'$yesterday'");
                    break;
                case 3:
                    $threeDay = date('Y-m-d',strtotime("-3 days"));
                    $temp_query =$temp_query->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')>'."'$threeDay'")
                        ->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')<='."'$today'");
                    break;
                case 4:
                    $threeDay = date('Y-m-d',strtotime("-3 days"));
                    $temp_query =$temp_query->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')<='."'$threeDay'");
                    break;
            }
        }

        return $temp_query;
    }
    public static function jobListConditionJoin($field_query)
    {
        $join_query = $field_query
                    ->alias('d')
                    ->join('qkl_demand_type dt','d.demand_type=dt.id')
                    ->order('d.create_time','desc')
                    ->select();
        return $join_query;
    }

    public static function getProjectField($demand_id)
    {
       $project_query =  Db::table('qkl_demand')
                ->field('u.real_name,
                                    u.name as applied_name,
                                    u1.`name` AS publiser_name,
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
                                    d.title,
                                    d.id as demand_id,
                                    u.id,
                                    d.applied_user_id as applied_user_id,
                                    d.user_id as publisher_id
                                    ')
                ->where('d.id',$demand_id);


        return $project_query;
    }



    public static function getReviewInformation($demand_id,$user_id)
    {
        try
        {
            $data = Db::table('qkl_demand')
                ->where('pr.user_id',$user_id)
                ->where('d.id',$demand_id)
                ->alias('d')
                ->join('qkl_project_review pr','pr.demand_id = d.id ')
                ->field('review,description,project_image')
                ->select();
            $review_txt = array(0 => array('review' =>self::getEvaluationText($data[0]['review'])));
            if (!empty($data[0]['project_image']))
                $image = array(0 =>array('image' =>UserModel::getImageUrl($data[0]['project_image'])));
            $tmp = array_merge($review_txt , $image);
            $datas = array_merge($data,$tmp);
            return $datas;
        }
        catch (Exception $exception)
        {
            $exception->getMessage();
        }

    }
    public static function getProjectInformation($demand_id)
    {
        $project_query =  Db::table('qkl_demand')
                            ->field('u.real_name,
                                    u.name as publiser_name,
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
                                    d.title,
                                    d.id,
                                    u.id as user_id,
                                    dt.pid,
                                    dt.id as demand_id,
                                    d.region_1,
                                    d.region_2')
            ->where('d.id',$demand_id);
        return $project_query;
    }
    /*
     *  join condition for get all list project without complete project
     * 1.0
     */
    public static function getProjectJoin($query)
    {
        try
        {
            $join_query = $query
                ->alias('d')
                ->join('qkl_user u','d.user_id=u.id')
                ->join('qkl_demand_type dt','d.demand_type=dt.id')
                ->join('qkl_region r','d.region_1=r.id')
                ->join('qkl_region r1','d.region_2=r1.id')
                ->select();
        }
        catch (Exception $exception)
        {
            \think\facade\Log::write('getProjectJoin function error',$exception->getMessage());
        }

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
            ->join('qkl_user u1','d.user_id=u1.id')
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
                        d.id,
                        d.applied_user_id,
                        d.state,
                        d.is_reviewed');
        return $field_query;
    }

    public static function getPublishedListJoin($query)
    {
        $complete_query = $query
                ->alias('d')
                ->join('qkl_demand_type dt','d.demand_type=dt.id')
                ->order('d.create_time','desc')
                ->select();

        return $complete_query;
    }

    public static function getPublishedListJoinForMemberCenter($query)
    {
        $complete_query = $query
            ->alias('d')
            ->join('qkl_demand_type dt','d.demand_type=dt.id')
            ->order('d.create_time','desc')
            ->limit(5)
            ->select();

        return $complete_query;
    }

    public static function getPublishedListWhereClause($query,$demand_type,$time_from,$time_to)
    {
        $query
            ->where('DATE_FORMAT(d.service_time_from, \'%Y-%m-%d\')>='."'$time_from'" or
                'DATE_FORMAT(d.service_time_to, \'%Y-%m-%d\')<='."'$time_to'")
            ->where('dt.pid',$demand_type)
            ->where('d.user_id',session('user_id'));

        if ($demand_type == 0)
            $query->where('d.id','>=',0);
        else
            $query->where('dt.pid',$demand_type);
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
                    d.id,
                    d.state,
                    d.is_reviewed');
        return $field_query;
    }

    public static function getUnderkenListJoin($query)
    {
        $complete_query = $query
            ->alias('d')
            ->join('qkl_demand_type dt','d.demand_type=dt.id')
            ->join('qkl_user u','d.user_id=u.id')
            ->select();
        return $complete_query;
    }

    public static function getUnderkenListJoinForMemberCenter($query)
    {
        $complete_query = $query
            ->alias('d')
            ->join('qkl_demand_type dt','d.demand_type=dt.id')
            ->join('qkl_user u','d.user_id=u.id')
            ->order('d.create_time','desc')
            ->limit(5)
            ->select();

        return $complete_query;
    }

    public static function getUndertakenListWhereClause($query,$demand_type,$time_from,$time_to)
    {
         $query->where('DATE_FORMAT(d.service_time_from, \'%Y-%m-%d\')>='."'$time_from'")
                ->where('DATE_FORMAT(d.service_time_to, \'%Y-%m-%d\')<='."'$time_to'");

        if ($demand_type == 0)
            $query->where('d.id','>=',0);
        else
            $query->where('dt.pid',$demand_type);
        return $query;
    }

    public static  function getUnderkenAllist($demand_type,$time_from,$time_to)
    {
        $temp_query = DemandModel::getUnderkenList();
        $temp_query = $temp_query ->where('d.applied_user_id',session('user_id'));
        $temp_query = DemandModel::getUndertakenListWhereClause($temp_query,$demand_type,$time_from,$time_to);
        $data       = DemandModel::getUnderkenListJoin($temp_query);

        $temp_query_1 = DemandModel::getUnderkenList();
        $temp_query_1 = $temp_query_1->wherein('d.id',DemandModel::getToUnderkenList());
        $temp_query_1 = DemandModel::getUndertakenListWhereClause($temp_query_1,$demand_type,$time_from,$time_to);
        $data_1       = DemandModel::getUnderkenListJoin($temp_query_1);


        return array_merge($data,$data_1);
    }

    //待承接 get list function
    public static function getToUnderkenList()
    {
        try
        {
            $data = Db::table('qkl_demand d')
                ->alias('d')
                ->join('qkl_apply a','a.demand_id = d.id')
                ->where('a.user_id',session('user_id'))
                ->where('d.applied_user_id',0)
                ->column('a.demand_id');

        }catch (Exception $exception)
        {
            \think\facade\Log::write('getToUnderkenList function error',$exception->getMessage());
        }

        return $data;
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


    public static function getPublishState($state,$is_reviewed)
    {
        if($state == 1 && $is_reviewed == 0)
            return '未发布';
        else  if($state == 2 && $is_reviewed == 0)
            return '已发布';
        else  if($state == 3 && $is_reviewed == 0)
            return '未完成';
        else  if($state == 3 && $is_reviewed == 1)
            return '待评价';
        else  if($state == 3 && $is_reviewed == 3)
            return '已完成';
        else  if($state == 2 && $is_reviewed == 4)
            return '已失效';
        else  if($state == 3 && $is_reviewed == 5)
            return '未完成';
    }

    public static function getFreelancerState($state,$is_reviewed)
    {
        if($state == 2 && $is_reviewed == 0)
            return '待承接';
        else  if($state == 3 && $is_reviewed == 0)
            return '已承接';
        else  if($state == 3 && $is_reviewed == 1)
            return '待评价';
        else  if($state == 3 && $is_reviewed == 3)
            return '已完成';
        else  if($state == 2 && $is_reviewed == 4)
            return '已过期';
        else  if($state == 3 && $is_reviewed == 5)
            return '已失效';

    }

    public static function  makeMemberId($member_id)
    {
        $length = 7;
        $zeroLength = $length - strlen($member_id);
        $zeorString = str_repeat(0,$zeroLength);
        return $zeorString.$member_id;
    }

    /*
    * freelancer bided project that publisher offer,but count of publisher don't select biding freelancer
    */
    public static  function calculateFreelancerStatics_1()
    {
        $unAppliedCnt = Db::table('qkl_demand d')
            ->where('d.applied_user_id','<>',session('user_id'))
            ->where('a.user_id',session('user_id'))
            ->join('qkl_apply a','d.id = a.demand_id')
            ->count();

        return $unAppliedCnt;

    }
    /*
     *  complete ,progress , waiting count get
    */
    public static  function calculateFreelancerStatics_2()
    {
        $result = Db::query("SELECT   CASE WHEN (is_reviewed = 3) THEN COUNT(1) ELSE 0 END AS completeCount,
                                                CASE WHEN (is_reviewed = 0) THEN COUNT(1) ELSE 0 END AS progressCount,
                                                CASE WHEN (is_reviewed = 2) THEN COUNT(1) ELSE 0 END AS waitingCount
                                                FROM qkl_demand
                                                WHERE state = 3 AND applied_user_id =".session('user_id'));

        return $result;

    }

    public static  function calculatePublisherUnBidCount()
    {
        $unBidCnt = Db::table('qkl_demand d')
            ->where('d.user_id',session('user_id'))
            ->where('a.demand_id','null')
            ->leftJoin('qkl_apply a','d.id = a.demand_id')
            ->count();

        return $unBidCnt;

    }

    /*
     *  complete ,progress , waiting count get
    */
    public static  function calculatePublisherStatics_2()
    {
        $result = Db::query("SELECT CASE WHEN (is_reviewed = 3) THEN COUNT(1) ELSE 0 END AS completeCount,
                                        CASE WHEN (is_reviewed = 0) THEN COUNT(1) ELSE 0 END AS progressCount,
                                        CASE WHEN (is_reviewed = 1) THEN COUNT(1) ELSE 0 END AS waitingCount
                                        FROM qkl_demand
                                        WHERE state = 3 AND user_id =".session('user_id'));

        return $result;

    }

}
