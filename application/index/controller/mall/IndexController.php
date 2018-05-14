<?php

namespace app\index\controller\mall;

use think\Controller;
use think\Request;
use app\common\model\ProductModel;
use app\common\model\ProductTypeModel;
use app\common\model\ProductReviewModel;
use app\common\model\CartModel;
use think\facade\Session;

class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->assign('header_nav', 'mall');
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

    public function getProductRootTypes()
    {
        $result = ProductTypeModel::where("pid", 0)->select();
        return json_encode(["data" => $result]);
    }
    public function search($product_name=null)
    {
        $result = ProductModel::where("is_deleted", 0);
        if ($product_name != null)
            $result = $result->where('name',$product_name);
        $result = $result->select()->toArray();
        foreach ($result as $key => $value)
        {
            $result[$key]['comments'] = ProductReviewModel::where([/*'user_id' => 1,*/ 'product_id' => $value['id']])->count();
            if (Session::has('user_id')){
                $result[$key]['state'] = CartModel::where(['state'=>1,'product_id'=>$value['id'],'user_id'=>session('user_id')])->select()->count() > 0 ? false : true ;
                $result[$key]['auth'] = true;
            }
            else {
                $result[$key]['state'] = true;
                $result[$key]['auth'] = false;
            }
            
            
        }
        return json_encode(["items" => $result]);
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
