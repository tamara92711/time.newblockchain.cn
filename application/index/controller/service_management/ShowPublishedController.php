<?php

namespace app\index\controller\service_management;

use app\common\model\ApplyModel;
use app\common\model\DemandModel;
use app\common\model\DemandStatusModel;
use app\common\model\DemandTypeModel;
use app\common\model\ProjectReviewModel;
use think\Controller;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Log;
use think\Request;

class ShowPublishedController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $demand_type = DemandTypeModel::where('pid',0)->field('id,name')->select();
        $demand_status = array('1'=>'未发布','2'=>'已发布','3'=>'已承接','4'=>'未完成','5'=>'待评价','6'=>'已完成','7'=>'已过期','8'=>'已失效');
        $this->assign('demand_type',$demand_type);
        $this->assign('demand_status',$demand_status);
        $this->assign('side_nav', 'project_published');
        $this->assign('header_nav', 'project_publish');
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request,$mode)
    {

    }

    public static function gaveReviewPA(Request $request)
    {
        $link = new ProjectReviewModel();
        $mode= $request->param('mode');
        $user_id= $request->param('user_id');
        $demand_id= $request->param('demand_id');
        $review = $request->param('review');
        $review_txt= $request->param('review_txt');
        if (!empty($request->file('publiher-image')))
        {
            $image = $request->file('publiher-image');
            $info = $image->move('./uploads/');
            $link->project_image = $info->getSaveName();
        }

        if (!empty($request->file('accepter-image')))
        {
            $image = $request->file('accepter-image');
            $info = $image->move('./uploads/');
            $link->project_image = $info->getSaveName();
        }
        $link->demand_id     = $demand_id;
        $link->user_id       = $user_id;
        $link->description   = $review_txt;
        $link->review        = $review;

        //已承接->已完成 3 freelancer->employeer to riview so update review field as 2
        $link->save();
        //employeer -> freelance to review
        if($mode == 0)
        {
            //state 3 : 已承接 is review 1 so freelance gave review employeer to  set 1 as default
            //check if freelancer give review to employeer
            $count = DemandModel::where('id',$demand_id)->where('state',3)->where('is_reviewed',1)->count();
            //if freelancer give review to employer project compelted so update is_review field as 3
            if ($count > 0)
                DemandModel::where('id',$demand_id)->where('state',3)->update(['is_reviewed'=>3,'complete_time'=>date('Y-m-d H:i:s')]);
            //if freelancer don't give review to employer project compelted so update is_review field as 1
            else if ($count == 0)
                DemandModel::where('id',$demand_id)->where('state','=',3)->update(['is_reviewed'=>2,'complete_time'=>date('Y-m-d H:i:s')]);

            //if success go to success page
            return redirect('/index/service_management.show_published');
        }
        //freelancer -> employeer to review
        else if ($mode ==1 )
        {
            //state 3 : 已承接  so employeer gave review to freelancer set 2 as default
            //check if employeer give review to freelancer
            $count = DemandModel::where('id',$demand_id)->where('state',3)->where('is_reviewed',2)->count();
            //if employer give review to freelancer project compelted so update is_review field as 3
            if ($count > 0)
                DemandModel::where('id',$demand_id)->where('state',3)->update(['is_reviewed'=>3,'complete_time'=>date('Y-m-d H:i:s')]);
            //if employer don't give review to freelancer project compelted so update is_review field as 1
            else if ($count == 0)
                DemandModel::where('id',$demand_id)->where('state',3)->update(['is_reviewed'=>1,'complete_time'=>date('Y-m-d H:i:s')]);

            return redirect('/index/service_management.show_undertaken');
        }


    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(Request $request,$id)
    {
        try
        {
            if($id == 1)
            {
                $today = date("Y-m-d");
                $demand_type = $request->param('demand_type');
                $time_from = $request->param('time_from');
                $time_to = $request->param('time_to');
                $demand_state = $request->param('demand_state');


                $query = DemandModel::getPublishedListField();
                $temp_query = DemandModel::getPublishedListWhereClause($query,$demand_type,$time_from,$time_to);
                switch ($demand_state)
                {
                    case 1:
                        $temp_query->where('d.state',$demand_state)->where('is_reviewed',0);//未发布
                        break;
                    case 2:
                        $temp_query->where('d.state',$demand_state)->where('is_reviewed',0);//已发布
                        break;
                    case 3:
                        $temp_query->where('d.state',$demand_state)->where('is_reviewed=0' or 'is_reviewed=1');//已承接
                        break;
                    case 4:
                        $temp_query->where('DATE_FORMAT(d.service_time_to, \'%Y-%m-%d\')>'."'$today'")
                            ->where('state',3);//未完成
                        break;
                    case 5:
                        $temp_query->where('d.state',3)->where('is_reviewed',2);//待评价
                        break;
                    case 6:
                        $temp_query->where('d.state',3)->where('is_reviewed',3);//已完成
                        break;
                    case 7:
                        $temp_query =$temp_query->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')>'."'$today'")
                            ->where('d.applied_user_id',0)->where('d.state','<=',2);// 已失效 expired at user don't bit until validation time
                        break;
                    case 8:
                        $temp_query =$temp_query->where('DATE_FORMAT(d.service_time_to, \'%Y-%m-%d\')>'."'$today'")
                            ->where('d.is_reviewed','<',3)->where('d.state',3);//已过期 expired task is running
                        break;
                }
                $tmpData    = DemandModel::getPublishedListJoin($temp_query);
                DemandModel::getAllPublishedList($tmpData);

                return json_encode(["data"=>$tmpData]);

            }
        }
        catch(ValidateException $exception)
        {
            return json($exception->getError());
        }
        catch (\Exception $exception)
        {
            return json($exception->getMessage());
        }


    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
    /*
     * modal function to show personal rate and detail information
     */
    public function getModalContent(Request $request)
    {
        $demand_id = $request->param('demand_id');
        $user_id = ApplyModel::where('demand_id',$demand_id)->column('user_id');

        $data = ApplyModel::getUserReviewList($user_id);
        return $data;

    }

    public function acceptBid(Request $request)
    {
        try
        {
            $checkId = $request->param('checkId');
            $demandId = $request->param('demandId');
            DemandModel::where('id',$demandId)->update(['applied_user_id'=>$checkId,'state' => 3,'accepted_time'=> date('Y-m-d H:i:s')]);
            return "ok";
        }catch (Exception $exception)
        {
            \think\facade\Log::write('acceptBid function error',$exception->getMessage());
        }
    }

    public function getBuilderProject($demand_id,$state_id,$is_reviewed)
    {
        //drafts projet send to editable state
        if ($state_id == 1 && $is_reviewed == 0)
            return redirect('/index/service_management.release_requirement/draftsEdit',["demand_id" => $demand_id,"state_id" => $state_id]);
        //biding state publise select freelancer
        if ($state_id == 2 && $is_reviewed == 0)
            return redirect('/index/project/project_processing/project_published',["id" => $demand_id,"mode" => 0]);
        //biding state publise select freelancer
        if ($state_id == 3 && $is_reviewed == 0)
            return redirect('/index/project/project_processing/project_accepted',["id" => $demand_id,"mode" => 0]);
        //waiting evaluated from freelancer so give evaluated goto completed step
        if ($state_id == 3 && $is_reviewed == 1)
            return redirect('/index/project/project_processing/publish_completed',["id" => $demand_id,"mode" => 1]);
        //publisher give review to freelancer
        if ($state_id == 3 && $is_reviewed == 2)
            return redirect('/index/project/project_processing/publish_completed',["id" => $demand_id,"mode" => 0]);
        //completed state
        if ($state_id == 3 && $is_reviewed == 3)
            return redirect('/index/project/project_processing/project_publish_complete',["id" => $demand_id]);

//
//            //已发布
//            case 6:
//                return redirect('/index/project/project_processing/project_published',["id" => $demand_id,"mode" => 0]);
//                break;
    }
}
