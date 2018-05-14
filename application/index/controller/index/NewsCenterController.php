<?php

namespace app\index\controller\index;

use think\Controller;
use think\Request;
use think\Db;
use app\common\model\NewsModel;
use app\common\model\NewsTypeModel;
class NewsCenterController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('header_nav', 'news_center');
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
        $data = Db::table('qkl_news')
                ->alias('n')
                ->field('n.title title, n.content content, nt.name type_name, date_format(n.published_time ,\'%Y-%m-%d\') ptime, n.image image')
                ->join('qkl_news_type nt', 'nt.id = n.type')
                ->select();
        if($id == 0)
        {
            // $data = NewsModel::getNewsCenterList();
            return json_encode(["data"=>$data]);
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
