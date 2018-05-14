<?php

namespace app\admin\controller\article;

use think\Controller;
use think\Request;
use app\common\model\LocalInstitutionModel;

class LocalInstitutionsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'article');
        $this->assign('sub_nav', 'article_local');
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
        $institution = new LocalInstitutionModel;
        $institution->name = $request->param('name');
        $institution->is_show = $request->param('is_show');
        $institution->phone = $request->param('phone');
        $institution->is_deleted = 0;
        $institution->address = $request->param('address');
        $institution->lat = $request->param('latitude');
        $institution->lng = $request->param('longtitude');  
        $institution->save();   
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
            $result = LocalInstitutionModel::where('is_deleted', 0)->select();
            $result = $result->toArray();
            return json_encode(["data" => $result]);
        }
        $data = LocalInstitutionModel::get($id);
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
        $institution = LocalInstitutionModel::get($id);
        $institution->name = $request->param('name');
        $institution->is_show = $request->param('is_show');
        $institution->phone = $request->param('phone');
        $institution->is_deleted = 0;
        $institution->address = $request->param('address');
        $institution->lat = $request->param('latitude');
        $institution->lng = $request->param('longtitude');  
        $institution->save();   
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $institution = LocalInstitutionModel::get($id);
        $institution->is_deleted = 1;
        $institution->save();
    }
}
