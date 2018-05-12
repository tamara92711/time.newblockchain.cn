<?php

namespace app\index\controller\data_management;

use app\common\model\RealNameVerifyModel;
use think\Controller;
use think\Request;

class VerifyController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('side_nav', 'real_name_verify');
        $this->assign('header_nav', 'home');
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
        $link = new RealNameVerifyModel();
        $link->real_name = $request->param('name');
        $link->id_card_number = $request->param('iden_number');

        if (!empty($request->file('positive_id')))
        {
            $image = $request->file('positive_id');
            $info = $image->move('./uploads/');
            $link->card_front_image = $info->getSaveName();
        }
        if (!empty($request->file('negative_id')))
        {
            $image = $request->file('negative_id');
            $info = $image->move('./uploads/');
            $link->card_back_image = $info->getSaveName();
        }
        if (!empty($request->file('handheld_id')))
        {
            $logo_image = $request->file('handheld_id');
            $info = $logo_image->move('./uploads/');
            $link->card_handled_image = $info->getSaveName();
        }
        $link->save();
        return redirect('/index/data_management.verify');
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
