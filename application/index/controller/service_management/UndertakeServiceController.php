<?php

namespace app\index\controller\service_management;

use app\common\model\DemandModel;
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
        $demand_status     = array('2'=>'发布中','3'=>'已承接','4'=>'已完成','5'=>'已失效');
        $release_time      = array('1'=>'今日发布','2'=>'昨日发布','3'=>'近3天发布','4'=>'3天以上');

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
        $temp_query = DemandModel::getFieldsJobList_1();


        $demand_type                = $request->param('detail_demand_type');
        $demand_state               = $request->param('demand_status');
        $time_currency              = $request->param('time_currency');
        $release_time               = $request->param('release_time');
        $time_currency_from         = $request->param('time_currency_from');
        $time_currency_to           = $request->param('time_currency_to');

        $temp_query = DemandModel::jobListWhereClause($temp_query,$demand_type,$time_currency,$time_currency_from,$time_currency_to,$release_time);

        if ($demand_state >= 0)
        {
           if($demand_state == 0)
           {
               // only show unbid list
               $temp_query    = $temp_query->where('d.state','>',1);//all   未发布 don't display
               $temp_query    = $temp_query->whereNotin('d.id',DemandModel::getToUnderkenList());

               //only show bid list
               $temp_query_2  = DemandModel::getFieldsJobList_2();
               $temp_query_2  = $temp_query_2->whereIn('d.id',DemandModel::getToUnderkenList());
               $temp_query_2  = DemandModel::jobListWhereClause($temp_query_2,$demand_type,$time_currency,$time_currency_from,$time_currency_to,$release_time);

               $data_1 = DemandModel::jobListConditionJoin($temp_query);
               $data_2 = DemandModel::jobListConditionJoin($temp_query_2);

               $data = array_merge($data_1,$data_2);

               return json_encode($data);
           }
           else if($demand_state == 2)
           {
               $temp_query = $temp_query->where('d.state',2)->where('is_reviewed',0);//发布中
               $temp_query = $temp_query->whereNotin('d.id',DemandModel::getToUnderkenList());
           }
           else if($demand_state == 3)
           {
               $temp_query =$temp_query->where('d.state',3)->where('is_reviewed',0);//已承接
           }
           else if($demand_state == 4)
           {
               $temp_query =$temp_query->where('d.state',3)->where('is_reviewed',3);//已完成
           }
           else if($demand_state == 5)
           {
               $temp_query =$temp_query->where('is_reviewed',4);//cancled  已失效 wrong so change
           }
        }
//        $today = date('Y-m-d');
//        $temp_query = $temp_query->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')<='."'$today'");
        $data = DemandModel::jobListConditionJoin($temp_query);

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

    public function getBuilderProject($demand_id,$state_id,$uid,$is_reviewed,$display_id)
    {
        //发布中 display
        if ($state_id == 2 && $is_reviewed == 0 )
        {
            if ($uid == session('user_id'))
                return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 0,"display_id"=>'unbid']);
            else
            {
                if($display_id == 'unbid')
                    return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 1,"display_id"=>'unbid']);
                else
                    return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 1,"display_id"=>'bid']);
            }
        }
        else if ($state_id == 3 && $is_reviewed == 0)
        {
            return redirect('/index/project/project_processing/project_accepted', ["id" => $demand_id, "mode" => 0]);
        }
        else if($state_id == 3 && $is_reviewed == 3)
        {
            return redirect('/index/project/project_processing/project_completed',["id" => $demand_id, "mode" => 0]);
        }
        else if($state_id == 2 && $is_reviewed == 4)
        {
            return redirect('/index/project/project_processing/project_cancled',["id" => $demand_id, "mode" => 0]);
        }


//            case 5:
//                return redirect('/index/project/project_processing/accepter_cancled',["id" => $demand_id,"mode" => 0]);
//                break;
//            case 6:
//                if($uid == session('user_id'))
//                {
//                    return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 0]);
//                    break;
//                }
//                else
//                {
//                    return redirect("/index/project/project_processing/project_published", ["id" => $demand_id, "mode" => 1]);
//                    break;
//                }

    }



}
