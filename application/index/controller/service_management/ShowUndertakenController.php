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
        $demand_status      = DemandStatusModel::all();

        $this->assign('demand_type',$demand_type);
        $this->assign('demand_status',$demand_status);

        $this->assign('side_nav', 'project_applied');
        $this->assign('header_nav', 'project_apply');

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
        $user_id = session('user_id');
        $demand_type = $request->param('demand_type');
        $time_from = $request->param('time_from');
        $time_to = $request->param('time_to');
        $demand_state = $request->param('demand_state');

        $temp_query = DemandModel::getUnderkenList();
        $temp_query = DemandModel::getPublishedListWhereClause($temp_query,$demand_type,$time_from,$time_to,$demand_state,$user_id);
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

    public function getBuilderProject($demand_id,$state_id)
    {
        switch ($state_id)
        {
            case 1:
                return redirect('/index/project/project_processing/project_published',["id" => $demand_id,"mode" => 0]);
                break;
            case 2:
                return redirect('/index/project/project_processing/project_accepted',["id" => $demand_id,"mode" => 0]);
                break;
            case 3:
                return redirect('/index/project/project_processing/accepter_completed',["id" => $demand_id]);
                break;
            case 4:
                return redirect('/index/project/project_processing/project_cancled',["id" => $demand_id,"mode" => 0]);
                break;
        }
    }
}
