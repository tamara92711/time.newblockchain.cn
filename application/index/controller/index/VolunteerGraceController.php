<?php

namespace app\index\controller\index;

use app\common\model\VolunteerModel;
use think\Controller;
use think\Request;
use think\Db;

class VolunteerGraceController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('header_nav', 'volunteers');
        $this->assign("nav_type", 1);
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

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        if($id == 0)
        {

            $sub_r = Db::table('qkl_project_review')
                ->field('user_id, avg(review) as avg_rv, count(review) as cnt_rv')
                ->group('user_id')
                ->buildSql();

            $sub_a = Db::table('qkl_demand')
                ->field('applied_user_id as user_id, COUNT(*) AS cnt_apply')
                ->where('state', 3)
                ->group('applied_user_id')
                ->buildSql();

            $sub_address = Db::table('qkl_user')
                ->alias('u')
                ->field('u.id user_id, CONCAT(r1.name, r2.name) address ')
                ->join('region r1 ','r1.id = u.region')
                ->join('region r2 ','r2.id = u.district')
                ->buildSql();

            $data = Db::table('qkl_user')
                ->alias('u')
                ->field('u.name, r.avg_rv, r.cnt_rv, a.cnt_apply, address.address, u.avarta_image image')
                ->join([$sub_r=> 'r'], 'r.user_id = u.id')
                ->join([$sub_a=> 'a'], 'a.user_id = r.user_id')
                ->join([$sub_address=> 'address'], 'address.user_id = u.id')
                ->select();
            return json_encode(["data" => $data]);
        }
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
