<?php

namespace app\admin\controller\project;

use think\Controller;
use think\Request;

use app\common\model\DemandModel;
use app\common\model\DemandTypeModel;
use app\common\model\DemandStatusModel;
use app\common\model\UserModel;
use app\common\model\RegionModel;

class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $demand_root_types = array_values(DemandTypeModel::where(['is_deleted'=> 0, 'pid' => 0])->column(['id', 'pid', 'name', 'description']));
        $root_regions = RegionModel::where(['is_deleted'=> 0, 'level' => 1])->column(['id', 'name']);
        $demand_types1 = DemandTypeModel::where('is_deleted', 0)->column(['id', 'name']);
        $demand_status = DemandStatusModel::where('is_deleted', 0)->column(['id', 'name']);
        $this->assign('demand_types1', json_encode($demand_types1));
        $this->assign('demand_root_types', $demand_root_types);
        $this->assign('root_regions', $root_regions);
        $this->assign('state', $demand_status);
        return $this->fetch();
    }

    public function getChildDemandTypes($pid)
    {
        $result = DemandTypeModel::where(['is_deleted' => 0, 'pid' => $pid])->column(['id', 'name']);
        return json_encode($result);
    }

    public function getRegion2($pid)
    {
        $result = RegionModel::where(['is_deleted' => 0, 'parent_id' => $pid])->column(['id', 'name']);
        return json_encode($result);
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
        $demand = new DemandModel;
        $demand->user_id = $request->param('user_id');
        $demand->demand_type = $request->param('demand_type');
        $demand->title = $request->param('title');
        $demand->detail = $request->param('detail');
        $demand->apply_requirement = $request->param('apply_requirement');
        $demand->service_time_from = $request->param('service_time_from');
        $demand->service_time_to = $request->param('service_time_to');
        $demand->time_total = $request->param('time_total');
        $demand->valid_time = $request->param('valid_time');
        $demand->pay_amount = $request->param('pay_amount');
        $demand->region_1 = $request->param('region_1');
        $demand->region_2 = $request->param('region_2');
        $demand->address_detail = $request->param('address_detail');
        $demand->contact_name = $request->param('contact_name');
        $demand->contact_phone = $request->param('contact_phone');
        $demand->published_time = $request->param('published_time');
        $demand->state = $request->param('state');
        $demand->is_show = $request->param('is_show');
        $demand->is_deleted = 0;    
        $demand->save();
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        if ($id == 0)
        {
            $result = DemandModel::where('is_deleted', 0)->select();
            $result = $result->toArray();
            foreach ($result as $key => $item)
            {
                $result[$key]['user_name'] = UserModel::where('id', $item['user_id'])->value('name');
            }
            return json_encode(["data" => $result]);
        }
        $data = DemandModel::get($id);
        $data = $data->toArray();
        $data['user_name'] = UserModel::where('id', $data['user_id'])->value('name');
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
        $demand = DemandModel::get($id);
        $demand->user_id = $request->param('user_id');
        $demand->demand_type = $request->param('demand_type');
        $demand->title = $request->param('title');
        $demand->detail = $request->param('detail');
        $demand->apply_requirement = $request->param('apply_requirement');
        $demand->service_time_from = $request->param('service_time_from');
        $demand->service_time_to = $request->param('service_time_to');
        $demand->time_total = $request->param('time_total');
        $demand->valid_time = $request->param('valid_time');
        $demand->pay_amount = $request->param('pay_amount');
        $demand->region_1 = $request->param('region_1');
        $demand->region_2 = $request->param('region_2');
        $demand->address_detail = $request->param('address_detail');
        $demand->contact_name = $request->param('contact_name');
        $demand->contact_phone = $request->param('contact_phone');
        $demand->published_time = $request->param('published_time');
        $demand->state = $request->param('state');
        $demand->is_show = $request->param('is_show');
        $demand->is_deleted = 0;    
        $demand->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $demand = DemandModel::get($id);
        $demand->is_deleted = 1;
        $demand->save();
    }
}
