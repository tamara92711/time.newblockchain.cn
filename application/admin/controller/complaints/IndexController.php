<?php

namespace app\admin\controller\complaints;

use think\Controller;
use think\Request;
use app\common\model\ComplaintModel;
use app\common\model\ComplaintTypeModel;
use app\common\model\UserModel;

class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'complaints');
        $this->assign('sub_nav', 'complaints_index');
        $complaint_types = ComplaintTypeModel::where('is_deleted', 0)->column(['id', 'name']);
        $this->assign("complaint_types", $complaint_types);
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
        $complaint = new ComplaintModel;
        $complaint->type = $request->post('complaint_type');
        $complaint->title = $request->post('title');
        $complaint->content = $request->param('content');
        $complaint->contact_name = $request->post('contact_name');
        $complaint->contact_phone = $request->param('contact_phone');
        $complaint->is_show = $request->param('is_show');
        $complaint->is_deleted = 0;
        $complaint->save();
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
            $data_array = ComplaintModel::where('is_deleted', 0)->select();
            $data_array = $data_array->toArray();
            foreach ($data_array as $key => $complaint)
            {
                $data_array[$key]['user_name'] = UserModel::get($complaint['user_id'])->value('name');
                $data_array[$key]['type_name'] = ComplaintTypeModel::where('id', $complaint['type'])->value('name');
            }
            return json_encode([ "data" => $data_array]);
        }
        return json_encode(ComplaintModel::get($id));
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
        $complaint = ComplaintModel::get($id);
        $complaint->type = $request->put('complaint_type');
        $complaint->title = $request->put('title');
        $complaint->content = $request->param('content');
        $complaint->contact_name = $request->put('contact_name');
        $complaint->contact_phone = $request->param('contact_phone');
        $complaint->is_show = $request->param('is_show');
        $complaint->is_deleted = 0;
        $complaint->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $complaint = ComplaintModel::get($id);
        $complaint->is_deleted = 1;
        $complaint->save();
    }
}
