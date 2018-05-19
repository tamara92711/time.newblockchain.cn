<?php

namespace app\admin\controller\member;

use think\Controller;
use think\Request;
use app\common\model\RealNameVerifyModel;
use app\common\model\UserModel;

class RealNameVerifyController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('root_nav', 'member');
        $this->assign('sub_nav', 'member_verify');
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
            $result = RealNameVerifyModel::all();
            $result = $result->toArray();
            foreach ($result as $key => $val)
            {
                $result[$key]['real_name'] = $val['user_name'];
                $result[$key]['user_name'] = UserModel::get($val['user_id'])->name;
                $result[$key]['card_front_image'] = $this->getImageUrl($val['card_front_image']);
                $result[$key]['card_back_image'] = $this->getImageUrl($val['card_back_image']);
                $result[$key]['card_handled_image'] = $this->getImageUrl($val['card_handled_image']);
                $result[$key]['avarta_image'] = $val['avarta_image'];
            }
            return json_encode(["data" => $result]);
        }
        $data = RealNameVerifyModel::get($id);
        $data = $data->toArray();
        $data['real_name'] = $data['user_name'];
        $data['user_name'] = UserModel::get($data['user_id'])->name;
        $data['card_front_image'] = $this->getImageUrl($data['card_front_image']);
        $data['card_back_image'] = $this->getImageUrl($data['card_back_image']);
        $data['card_handled_image'] = $this->getImageUrl($data['card_handled_image']);
        $data['avarta_image'] = $data['avarta_image'];
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
        $verify = RealNameVerifyModel::get($id);
        $verify->user_name = $request->param('real_name');
        $verify->is_passed = $request->param('is_passed');
        $verify->save();

        $user = UserModel::get($verify->user_id);
        $user->real_name = $verify->user_name;
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
        $user = RealNameVerifyModel::get($id);
        $user->delete();
    }
}
