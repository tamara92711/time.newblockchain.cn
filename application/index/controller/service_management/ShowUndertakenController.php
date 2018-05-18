<?php

namespace app\index\controller\service_management;

use app\common\model\DemandStatusModel;
use app\common\model\DemandTypeModel;
use think\Controller;
use think\Request;
use app\common\model\DemandModel;
use think\Db;

class ShowUndertakenController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function index()
    {
        $demand_type        = DemandTypeModel::where('pid',0)->field('id,name')->select();
        $demand_status = array('2'=>'待承接','3'=>'已承接','4'=>'已过期','5'=>'待评价','6'=>'已完成','7'=>'已失效');

        $this->assign('demand_type',$demand_type);
        $this->assign('demand_status',$demand_status);

        $this->assign('side_nav', 'project_applied');
        $this->assign('header_nav', 'project_apply');
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
        $today = date("Y-m-d");
        $demand_type = $request->param('demand_type');
        $time_from = $request->param('time_from');
        $time_to = $request->param('time_to');
        $demand_state = $request->param('demand_state');

        $temp_query = DemandModel::getUnderkenList();
        $temp_query = DemandModel::getUndertakenListWhereClause($temp_query,$demand_type,$time_from,$time_to);
        //待承接
        if ($demand_state == 2)
        {
            $temp_query = $temp_query->wherein('d.id',DemandModel::getToUnderkenList());
        }
        else
        {
            $temp_query = $temp_query ->where('d.applied_user_id',session('user_id'));
            switch ($demand_state)
            {
                case 3:
                    $temp_query->where('d.state',$demand_state)->where('is_reviewed',0);
                    break;
                case 4:
                    $temp_query =$temp_query->where('DATE_FORMAT(d.service_time_to, \'%Y-%m-%d\')>'."'$today'")
                        ->where('d.is_reviewed','<',3)->where('d.state',3);//已过期 expired task is running
                    break;
                case 5:
                    $temp_query->where('d.state',3)->where('is_reviewed',1);//待评价
                    break;
                case 6:
                    $temp_query->where('d.state',3)->where('is_reviewed',3);//已完成
                    break;
                case 7:
                    $temp_query =$temp_query->where('DATE_FORMAT(d.published_time, \'%Y-%m-%d\')>'."'$today'")
                        ->where('d.applied_user_id',0)->where('d.state','<=',2);// 已失效 expired at user don't bit until validation time
                    break;
            }

        }
//        ->where('d.state',$demand_state)
        $data       = DemandModel::getUnderkenListJoin($temp_query);

        return json_encode(["data"=>$data]);
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

    public function getBuilderProject($demand_id,$state_id,$is_reviewed)
    {
        //freelancer give review to publisher
        if ($state_id == 3 && $is_reviewed == 0)
            return redirect('/index/project/project_processing/accepter_completed',["id" => $demand_id,"mode" => 0]);
        //biding state publise select freelancer
        if ($state_id == 2 && $is_reviewed == 0)
            return redirect('/index/project/project_processing/project_published',["id" => $demand_id,"mode" => 0]);
        //waiting evaluated from publisher
        if ($state_id == 3 && $is_reviewed == 1)
            return redirect('/index/project/project_processing/accepter_completed',["id" => $demand_id,"mode" => 1]);
        //completed state
        if ($state_id == 3 && $is_reviewed == 3)
            return redirect('/index/project/project_processing/project_publish_complete',["id" => $demand_id]);


//            case 4:
//                return redirect('/index/project/project_processing/project_cancled',["id" => $demand_id,"mode" => 0]);
//                break;

    }
}
