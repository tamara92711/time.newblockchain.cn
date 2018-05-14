<?php

namespace app\index\controller\data_management;

use app\common\model\AddressModel;
use think\Controller;
use think\Db;
use think\Request;
use app\common\model\RegionModel;

class AddressManageController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function index()
    {
        $regions_level_1 = RegionModel::where('level', 1)->column('name', 'id');

        $this->assign('region_level_1', $regions_level_1);
        $this->assign('header_nav', 'home');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', 'address_manage');
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return "create";
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $link = new AddressModel();
        $link->user_id = session('user_id');
        $link->region_id_1 = $request->param('region1');
        $link->region_id_2 = $request->param('region2');
        $link->detail = $request->param('address');
        $link->name = $request->param('contacts');
        $link->phone = $request->param('contact_number');
        $link->save();
//        return redirect('/index/data_management.address_manage/index');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */

    public function read($id)
    {

        if($id == 0 )
        {
            $data = AddressModel::getAddressList(session('user_id'));
        }
        else
        {
            $data = AddressModel::get($id);
        }
        return json_encode(["data" => $data]);
//        return json_encode($data);

    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
//        $data = AddressModel::get($id);
//        $this->assign([
//            'address' =>$data->detail
//        ]);
//        return $this->fetch();
        return "edit";
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
        $link = AddressModel::get($id);
        $link->region_id_1 = $request->param('region1');
        $link->region_id_2 = $request->param('region2');
        $link->detail = $request->param('address');
        $link->name = $request->param('contacts');
        $link->phone = $request->param('contact_number');
        $link->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $link = AddressModel::get($id);
        $link->delete();
//        return redirect('/index/data_management.address_manage/index');

    }

    public function getRegion2ByRegion1($region1)
    {
        $regions_level_2 = RegionModel::where('parent_id',$region1)->column('name','id');
        return json_encode(["data" => $regions_level_2]);
    }

    public function setDefault($current_id)
    {
        $user_id = 0;
        $link = AddressModel::where('user_id' , $user_id)->where('address_value' , 1)->update(['address_value' => 0]);
        $link = AddressModel::where('id', $current_id)->update(['address_value' => 1]);

    }
}
