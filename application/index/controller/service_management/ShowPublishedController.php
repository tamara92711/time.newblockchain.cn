<?php

namespace app\index\controller\service_management;

use app\common\model\ApplyModel;
use app\common\model\DemandModel;
use app\common\model\DemandStatusModel;
use app\common\model\DemandTypeModel;
use app\common\model\ProjectReviewModel;
use think\Controller;
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
        $demand_status = DemandStatusModel::all();
        $this->assign('demand_type',$demand_type);
        $this->assign('demand_status',$demand_status);
        $this->assign('side_nav', 'project_published');
        $this->assign('header_nav', 'project_publish');
        $this->assign("nav_type", 1);
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
    public function save(Request $request)
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



        $link->save();


        return redirect('/index/service_management.show_published');
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

        //已承接->已完成 2->3
        $link->save();
        if($mode == 0)
        {
            DemandModel::where('id',$demand_id)->where('state','=',2)->update(['state'=>3,'complete_time'=>date('Y-m-d H:i:s')]);
        }

        //if success go to success page
        return redirect('/index/project/project_processing/project_publish_complete',["id" => $demand_id,"mode" => 0]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(Request $request,$id)
    {
        if($id == 1)
        {
            $demand_type = $request->param('demand_type');
            $time_from = $request->param('time_from');
            $time_to = $request->param('time_to');
            $demand_state = $request->param('demand_state');

            $query = DemandModel::getPublishedListField();
            $temp_query = DemandModel::getPublishedListWhereClause($query,$demand_type,$time_from,$time_to,$demand_state);
            $tmpData    = DemandModel::getPublishedListJoin($temp_query);
            DemandModel::getAllPublishedList($tmpData);

            return json_encode(["data"=>$tmpData]);
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
        $checkId = $request->param('checkId');
        $demandId = $request->param('demandId');
        DemandModel::where('id',$demandId)->update(['applied_user_id'=>$checkId,'state' => 2,'accepted_time'=> date('Y-m-d H:i:s')]);
        return "ok";
    }

    public function getBuilderProject($demand_id,$state_id)
    {
        switch ($state_id)
        {
            case 1:
                return redirect('/index/project/project_processing/project_published',["id" => $demand_id,"mode" => 0]);
                break;
            case 2:
                return redirect('/index/project/project_processing/publish_completed',["id" => $demand_id]);
//                return redirect('/index/project/project_processing/project_accepted',["id" => $demand_id,"mode" => 0]);
                break;
            case 3:
                return redirect('/index/project/project_processing/project_publish_complete',["id" => $demand_id,"mode" => 0]);
//                return redirect('/index/project/project_processing/publish_completed',["id" => $demand_id]);
                break;
            case 4:
                return redirect('/index/project/project_processing/project_cancled',["id" => $demand_id,"mode" => 0]);
                break;
            //已发布
            case 6:
                return redirect('/index/project/project_processing/project_published',["id" => $demand_id,"mode" => 0]);
                break;
        }
    }



}
