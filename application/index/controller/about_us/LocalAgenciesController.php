<?php

namespace app\index\controller\about_us;

use think\Controller;
use think\Request;
use app\common\model\LocalInstitutionModel;

class LocalAgenciesController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $institutions = LocalInstitutionModel::all()->toArray();
        $this->assign('institutions', $institutions);
        $this->assign('header_nav', 'about_us');
        $this->assign('side_nav', 'local_agencies');
        $this->assign("nav_type", 1);
        return $this->fetch();
    }

    public function getInstitutions()
    {
        $search_key = request()->param('search_key');
        // $institutions = LocalInstitutionModel::where('name', 'like', '%' . $search_key . '%')
        //                 ->whereOr('address', 'like', '%' . $search_key . '%')->select();
        // $institutions = LocalInstitutionModel::where('is_deleted', 0)
        //                 ->where(function ($query) {
        //                     $query->where(['address', 'like', '%'.$search_key.'%'], ['name', 'like', '%'.$search_key.'%'], 'or');
        //                 })->select();
        $institutions = LocalInstitutionModel::where('(address like :address or name like :name) and is_deleted=0',
                ['address' => '%'.$search_key.'%', 'name'=>'%'.$search_key.'%'])->select();
        // $institutions = LocalInstitutionModel::all();
        return json_encode(["data" => $institutions]);
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
    public function read($id)
    {
        //
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
}
