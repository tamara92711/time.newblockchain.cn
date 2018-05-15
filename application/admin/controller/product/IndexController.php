<?php

namespace app\admin\controller\product;

use think\Controller;
use think\Request;
use app\common\model\ProductTypeModel;
use app\common\model\ProductModel;

class IndexController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $product_types = array_values(ProductTypeModel::where('is_deleted', 0)->column(['id', 'pid', 'name', 'description']));
        $product_types1 = ProductTypeModel::where('is_deleted', 0)->column(['id', 'name']);
        $this->assign('product_types', $product_types);
        $this->assign('product_types1', json_encode($product_types1));

        $this->assign('root_nav', 'product');
        $this->assign('sub_nav', 'product_index');
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
        $product = new ProductModel;
        $product->name = $request->param('name');
        $product->description = $request->param('description');
        $product->is_show = $request->param('is_show');
        $product->price = $request->param('price');
        $product->in_stock = $request->param('in_stock');
        $product->is_deleted = 0;
        $product->type = $request->param('type');
        $product->save();
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
            $result = ProductModel::where('is_deleted', 0)->select();
            $result = $result->toArray();
            return json_encode(["data" => $result]);
        }
        $data = ProductModel::get($id);
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
        $product = ProductModel::get($id);
        $product->name = $request->param('name');
        $product->description = $request->param('description');
        $product->is_show = $request->param('is_show');
        $product->price = $request->param('price');
        $product->in_stock = $request->param('in_stock');
        $product->is_deleted = 0;
        $product->type = $request->param('type');
        $product->save();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $product = ProductModel::get($id);
        $product->is_deleted = 1;
        $product->save();
    }
}
