<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\VolunteerModel;

class VolunteersController extends Controller
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
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $vol = new VolunteerModel;
        $vol->name = $request->param('vol_name');
        $vol->accepted_requests = 0;
        $vol->region = $request->param('vol_region');
        $vol->review = 0;
        $vol->mark = 0;
        $vol->is_show = $request->param('is_show');
        $vol->is_deleted = 0;
        
        if (!empty($request->file('vol_photo')))
        {
            $image = $request->file('vol_photo');
            $info = $image->move('./uploads/');
            $vol->image = $info->getSaveName();
        }
        $vol->order_by = $request->param('vol_order');
        $vol->save();
        return redirect('/admin/volunteers');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        if ($id == 0) {
            $data = VolunteerModel::where('is_deleted', 0)->select();
            return json_encode(["data" => $data]);
        }
        $data = VolunteerModel::get($id);
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
        $data = VolunteerModel::get($id);
        if (!empty($data->image)) {
            $path = explode('\\',$data->image);
            $data->image = '/uploads/' . $path[0] . '/' . $path[1];
        }
        $this->assign("data", $data);
        return $this->fetch();
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
        $vol = VolunteerModel::get($id);
        $vol->name = $request->param('vol_name');
        $vol->accepted_requests = 0;
        $vol->region = $request->param('vol_region');
        $vol->review = 0;
        $vol->mark = 0;
        $vol->is_show = $request->param('is_show');
        $vol->is_deleted = 0;
        if (!empty($request->param('vol_photo')))
        {
            $logo_image = $request->file('vol_photo');
            $info = $logo_image->move('./uploads/');
            $vol->image = $info->getSaveName();
        }
        $vol->order_by = $request->param('vol_order');
        $vol->save();
        return redirect('/admin/volunteers');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $vol = VolunteerModel::get($id);
        $vol->is_deleted = 1;
        $vol->save();
        return redirect('/admin/volunteers');
    }
}
