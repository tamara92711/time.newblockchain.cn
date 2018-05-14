<?php

namespace app\admin\controller\member;

use think\Controller;
use think\Request;
use app\common\model\UserModel;
use app\common\model\RegionModel;
use app\common\model\ProfessionalCertificateModel;

class CertificateController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'member');
        $this->assign('sub_nav', 'member_certificate');
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

    private function getImageUrl($path)
    {
        $url = "";
        if (!empty($path)) {
            $path = explode('\\',$path);
            $url = '/uploads/' . $path[0] . '/' . $path[1];
        }
        return $url;
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
            $result = ProfessionalCertificateModel::all();
            $result = $result->toArray();
            foreach ($result as $key => $val)
            {
                $result[$key]['user_name'] = UserModel::get($val['user_id'])->name;
                $result[$key]['real_name'] = UserModel::get($val['user_id'])->real_name;
                $result[$key]['positive_image'] = $this->getImageUrl($val['positive_image']);
                $result[$key]['back_image'] = $this->getImageUrl($val['back_image']);
            }
            return json_encode(["data" => $result]);
        }
        $data = ProfessionalCertificateModel::get($id);
        $data = $data->toArray();
        $data['user_name'] = UserModel::get($data['user_id'])->name;
        $data['real_name'] = UserModel::get($data['user_id'])->real_name;
        $data['positive_image'] = $this->getImageUrl($data['positive_image']);
        $data['back_image'] = $this->getImageUrl($data['back_image']);
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
        $user = ProfessionalCertificateModel::get($id);
        $user->approval_status = $request->param('approval_status');
        $user->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $user = ProfessionalCertificateModel::get($id);
        $user->delete();
    }
}
