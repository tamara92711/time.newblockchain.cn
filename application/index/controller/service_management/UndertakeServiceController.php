<?php

namespace app\index\controller\service_management;

use app\common\model\DemandModel;
use app\common\model\DemandStatusModel;
use app\common\model\DemandTypeModel;
use app\common\model\ReleaseTimeModel;
use think\Controller;
use think\Request;



class UndertakeServiceController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $demand_type       = DemandTypeModel::where('pid',0)->field('id,name')->select();

        $demand_detail     = DemandTypeModel::where('pid',1)->field('id,name')->select();
        $demand_status     = DemandStatusModel::all();
        $release_time      = ReleaseTimeModel::all();

        $this->assign("demand_type",$demand_type);
        $this->assign("demand_detail",$demand_detail);
        $this->assign("demand_status",$demand_status);
        $this->assign("release_time",$release_time);
        $this->assign('header_nav', 'project_apply');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'project_apply');

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
        //

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(Request $request,$id)
    {

        $temp_query = DemandModel::getFields();
//        ->where('d.user_id','<>',session('user_id'));

        $demand_type = $request->param('demand_type');
        $demand_state = $request->param('demand_status');
        $time_currency = $request->param('time_currency');
        $release_time = $request->param('release_time');
        $time_currency_from = $request->param('time_currency_from');
        $time_currency_to = $request->param('time_currency_to');

        if ($demand_type >= 0)
        {
            //parent id or child id
            $hasPid  = DemandTypeModel::isPid($demand_type);
            if($hasPid)
                $temp_query =$temp_query->where('dt.pid',$demand_type);
            else
                $temp_query =$temp_query->where('dt.id',$demand_type);
        }
        if ($demand_state >= 0)
            if($demand_state == 0)
                $temp_query =$temp_query->where('ds.id','>=',$demand_state);
            else if ($demand_state >=1)
            $temp_query =$temp_query->where('ds.id',$demand_state);

        if ($time_currency >= 0)
        {

            switch ($time_currency)
            {
                case 1:
                    $temp_query =$temp_query->where('d.pay_amount','>',0);
                    break;
                case 2:
                    $temp_query =$temp_query->whereBetween('d.pay_amount','0,5');
                    break;
                case 3:
                    $temp_query =$temp_query->whereBetween('d.pay_amount','5,10');
                    break;
                case 4:
                    $temp_query =$temp_query->where('d.pay_amount','>',10);
                    break;
            }
            if ($time_currency_from > 0 and $time_currency_to > 0 )
            {
                $temp_query =$temp_query->where('d.pay_amount','between',$time_currency_from,$time_currency_to);
            }
        }

        if ($release_time >= 0)
        {
            $today = date("Y-m-d");
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
        $today = date('Y-m-d');
        $temp_query = $temp_query->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')<='."'$today'");
        $data = DemandModel::conditionJoin($temp_query);

        return json_encode($data);
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

    public function getDetailByDemandType($demandType)
    {
        $detailDemandType = DemandTypeModel::where('pid',$demandType)->field('id,name')->select();
        return json_encode($detailDemandType);
    }

    public function getBuilderProject($demand_id,$state_id,$uid)
    {
        switch ($state_id)
        {
            //已发布
            case 1:
                if($uid == session('user_id'))
                {
                    return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 0]);
                    break;
                }
                else
                {
                    return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 1]);
                    break;
                }
//                return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 1]);
//                break;
            case 2:
                return redirect('/index/project/project_processing/project_accepted',["id" => $demand_id,"mode" => 0]);
                break;
            case 3:
                return redirect('/index/project/project_processing/project_completed',["id" => $demand_id, "mode" => 0]);
                break;
            case 4:
                return redirect('/index/project/project_processing/accepter_cancled',["id" => $demand_id,"mode" => 0]);
                break;
            case 6:
                if($uid == session('user_id'))
                {
                    return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 0]);
                    break;
                }
                else
                {
                    return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 1]);
                    break;
                }

        }
    }



}
