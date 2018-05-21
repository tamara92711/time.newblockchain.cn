<?php

namespace app\index\controller\mall_management;

use think\Controller;
use think\Request;

use app\common\model\CartModel;
use app\common\model\AddressModel;
use app\common\model\OrdersModel;
use think\facade\Session;

class MyCollectionController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $this->assign('header_nav', 'mall');
        $this->assign("nav_type", 0);
        $this->assign('side_nav', '');
        $this->assign('products',CartModel::alias('cart')->where(['state'=>1,'user_id'=>session('user_id')])->join('qkl_product','qkl_product.id = cart.product_id')->select());
        return $this->fetch();
    }

    public function order_view()
    {
        $this->assign('header_nav', 'mall');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', '');
        $this->assign('products',CartModel::alias('cart')->where(['state'=>3,'user_id'=>session('user_id')])->join('qkl_product','qkl_product.id = cart.product_id')->select());
        return $this->fetch();
    }

    public function purchase_view(Request $request)
    {
        $data = $request->post('ids');
        $ids = explode(',',$data);
       // foreach ($ids as $key => $id) {
       //     CartModel::where('state','1')->whereIn('product_id',$ids)->where('user_id', session('user_id'))->select();
       // }

        $this->assign('header_nav', 'mall');
        $this->assign("nav_type", 1);
        $this->assign('side_nav', '');
        $address_model = new AddressModel();
        $address_data = $address_model->getAddressList(session('user_id'));
        $this->assign('product_ids',$data);
        $this->assign('products',CartModel::alias('cart')->where('state','1')->whereIn('product_id',$ids)->where('user_id', session('user_id'))->join('qkl_product','qkl_product.id = cart.product_id')->select());
        $this->assign('addresses',$address_data);
        return $this->fetch();
    }


    public function order(Request $request) 
    {
        $address_id = $request->post('address');
        $data = $request->post('ids');
        $ids = explode(',',$data);
        $carts = CartModel::alias('cart')->field('cart.*, qkl_product.type, qkl_product.name, qkl_product.price,qkl_product.description')->where('state','1')->whereIn('product_id',$ids)->where('user_id', session('user_id'))->join('qkl_product','qkl_product.id = cart.product_id')->select();
        foreach ($carts as $key => $cart) {
            $address = AddressModel::get($address_id);
            $new_order = new OrdersModel;
            $new_order->user_id = session('user_id');
            $new_order->cart_id = $cart->id;
            $new_order->product_id = $cart->product_id;
            $new_order->product_price = $cart->price;
            $new_order->amount = $cart->amount;
            $new_order->contact_id = $address['id'];
            $new_order->address = $address['detail'];
            $new_order->phone = $address['phone'];
            $new_order->status = 1;
            $new_order->save();

            $cart->state = 4;
            $cart->save();
        }
        return redirect('/order_published');
    }

    public function published_orders_view()
    {
        $this->assign('header_nav', 'mall');
        $this->assign("nav_type", 0);
        $this->assign('side_nav', 'orders');
        $this->assign('orders',OrdersModel::alias('order')->field('order.*,qkl_address.name as person_name, qkl_product.thumbnail,qkl_product.description')->where(['order.user_id'=>session('user_id')])->join('qkl_product','qkl_product.id = order.product_id')->join('qkl_address','qkl_address.id = order.contact_id')->select());
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

    public function add_to_cart(Request $request)
    {
        $product_id = $request->post('id');
        $cart_product = new CartModel;
        if (Session::has('user_id'))
        {
            if (CartModel::where(['state'=>1,'product_id'=>$product_id])->select()->count() == 0)
            {

                $cart_product->user_id = session('user_id');
                $cart_product->product_id = $product_id;
                $cart_product->amount = 1;
                $cart_product->save();
                echo ('added');
            }
            else
            {
                echo ('already exist');
            }
        }
        else
        {
            echo ('need_auth');
        }
    }

    public function update_amount(Request $request)
    {
        $product_id = $request->post('id');
        $amount = $request->post('value');
        if (CartModel::where(['state'=>1,'product_id'=>$product_id])->select()->count() > 0)
        {
            $cart_product = CartModel::where(['state'=>1,'product_id'=>$product_id])->find();
            $cart_product->amount = $amount;
            $cart_product->save();
            echo ("success");
        }
        else {
            echo ("doesn't exist");
        }
    }
    
    public function apply_payment(Request $request)
    {
        $data = $request->post('data');
        $ids = explode(',',$data);
        foreach ($ids as $key => $id) {
            CartModel::where(['state'=>'1','product_id'=>$id,'user_id' => session('user_id')])->setField('state',3);
        }
    }

    public function delete_from_cart(Request $request)
    {
        $product_id = $request->post('id');
        if (CartModel::where(['state'=>1,'product_id'=>$product_id])->select()->count() > 0)
        {
            $cart_product = CartModel::where(['state'=>1,'product_id'=>$product_id])->find();
            $cart_product->state = 2;
            $cart_product->save();
            echo ('deleted');
        }
        else {
            echo ("doesn't exist");
        }
    }

    public function get_pending_cart_count()
    {
        echo CartModel::where(['state'=>1,'user_id'=>session('user_id')])->select()->count();
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
