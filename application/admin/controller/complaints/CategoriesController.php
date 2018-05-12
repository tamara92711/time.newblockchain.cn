<?php

namespace app\admin\controller\complaints;

use think\Controller;
use think\Request;
use app\common\model\ComplaintTypeModel;

class CategoriesController extends Controller
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
        $complaintType = new ComplaintTypeModel;
        $complaintType->name = $request->post('name');
        $complaintType->description = $request->post('description');
        $complaintType->is_show = $request->param('is_show');
        $complaintType->is_deleted = 0;
        $complaintType->save();
        return $complaintType->id;
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
            $data_array = ComplaintTypeModel::where('is_deleted', 0)->select();
            return json_encode([ "data" => $data_array]);
        }
        return json_encode(ComplaintTypeModel::get($id));
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
        $complaintType = ComplaintTypeModel::get($id);
        $complaintType->name = $request->param('name');
        $complaintType->description = $request->param('description');
        $complaintType->is_show = $request->param('is_show');
        $complaintType->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $complaintType = ComplaintTypeModel::get($id);
        $complaintType->is_deleted = 1;
        $complaintType->save();
    }
}
