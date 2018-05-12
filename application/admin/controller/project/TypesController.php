<?php

namespace app\admin\controller\project;

use think\Controller;
use think\Request;
use app\common\model\DemandTypeModel;


class TypesController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
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
        $demandType = new DemandTypeModel;
        $demandType->name = $request->post('name');
        $demandType->description = $request->post('description');
        $demandType->pid = $request->post('pid');
        $demandType->save();
        return $demandType->id;
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
            $data_array = array_values(DemandTypeModel::where('is_deleted', 0)->column(['id', 'pid', 'name', 'description']));
            return json_encode([ "data" => $data_array]);
        }
        return json_encode(DemandTypeModel::where('id', $id)->column(['id', 'pid', 'name', 'description']));
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
        $demandType = DemandTypeModel::get($id);
        $demandType->name = $request->param('name');
        $demandType->description = $request->param('description');
        $demandType->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $demandType = DemandTypeModel::get($id);
        $demandType->is_deleted = 1;
        $demandType->save();
    }
}
