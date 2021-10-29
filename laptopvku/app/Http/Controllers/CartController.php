<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //TV dùng database

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

use Cart;
Use Alert;

use App\Models\Coupon;
use App\Models\Banner;



class CartController extends Controller
{

    public function AuthLogin(){ //bảo mật, chưa login thì ko thể truy cập các đường dẫn của admin
        $admin_id = Session::get('admin_id');
        if($admin_id){
            Redirect::to('dashboard');

        }else{
            return Redirect::to('admin')->send();
        }
    }
    
    
    public function add_cart_ajax(Request $request){
        $data = $request->all();
        $session_id = substr(md5(microtime()),rand(0,26),5);
        $cart = Session::get('cart');
        if($cart==true){

            $is_avaiable = 0;
            foreach($cart as $key => $val){
                if($val['product_id']==$data['cart_product_id']){
                    $is_avaiable++;
                }
            }
            if($is_avaiable == 0){
                $cart[] = array(
                'session_id' => $session_id,
                'product_name' => $data['cart_product_name'],
                'product_id' => $data['cart_product_id'],
                'product_image' => $data['cart_product_image'],
                'product_qty' => $data['cart_product_qty'],
                'product_price' => $data['cart_product_price'],
                'product_quantity' => $data['cart_product_quantity'],
                );
                Session::put('cart',$cart);
            }
        }else{
            $cart[] = array(
                'session_id' => $session_id,
                'product_name' => $data['cart_product_name'],
                'product_id' => $data['cart_product_id'],
                'product_image' => $data['cart_product_image'],
                'product_qty' => $data['cart_product_qty'],
                'product_price' => $data['cart_product_price'],
                'product_quantity' => $data['cart_product_quantity'],

            );

             Session::put('cart',$cart);
            
        }
       
       
        Session::save();

    }  


    public function show_cart(Request $request){

        $banner = Banner::orderby('banner_id','DESC')->where('banner_status','1')->take(3)->get();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','1')->orderby('brand_id','desc')->get();

        return view('pages.cart.cart_ajax')->with('category',$cate_product)->with('brand',$brand_product)->with('banner',$banner);


    }

    public function del_product($session_id){

        $cart = Session::get('cart');

        if($cart==true){
            foreach ($cart as $key => $val) {
                if($val['session_id']==$session_id){
                    unset($cart[$key]);
                }
            }
            Session::put('cart',$cart);
            return Redirect()->back()->with('message','Xóa sản phẩm khỏi giỏ thành công!');

        }else{
        return Redirect()->back()->with('error','Xóa sản phẩm khỏi giỏ thất bại!');
        }
    }


    public function update_cart(Request $request){


        $data = $request->all();
        $cart = Session::get('cart');

        if($cart==true){
            $message = '';
            foreach ($data['cart_qty'] as $key => $qty) {
                
                foreach ($cart as $session => $val) {
                    
                    if($val['session_id']==$key && $qty<=$cart[$session]['product_quantity']){

                        $cart[$session]['product_qty'] = $qty;
                        $message.='<p style="color:green;">Cập nhập số lượng: '.$cart[$session]['product_name'].' thành công</p>';
                    }elseif ($val['session_id']==$key && $qty>$cart[$session]['product_quantity']) {
                        $message.='<p style="color:red;">Cập nhập số lượng: '.$cart[$session]['product_name'].' thất bại</p>';
                    }
                }
                
            }
            Session::put('cart',$cart);
            return Redirect()->back()->with('message',$message);
        }else{
        return Redirect()->back()->with('error','Cập nhật giỏ thất bại!');
        }
    }

    public function del_all_product(){

        $cart = Session::get('cart');

        if($cart==true){
            //Session::destroy();

            Session::forget('cart');
            Session::forget('coupon'); //xóa cả mã giảm giá
            Session::forget('fee');

            return Redirect()->back()->with('message','Xóa tất cả sản phẩm trong giỏ thành công!');
        }

    }

    public function check_coupon(Request $request){

        $data = $request->all();

        $coupon = Coupon::where('coupon_code',$data['coupon'])->first();

        if($coupon){

            $count_coupon = $coupon->count();

            if($count_coupon > 0){
                $coupon_session = Session::get('coupon');

                if($coupon_session == true){

                    $is_avaiable = 0;

                    if($is_avaiable == 0){
                        $cou[] = array(

                            'coupon_code' => $coupon->coupon_code,
                            'coupon_condition' => $coupon->coupon_condition,
                            'coupon_number' => $coupon->coupon_number,

                        );
                        Session::put('coupon',$cou);

                    }
                }else{
                    $cou[] = array(

                            'coupon_code' => $coupon->coupon_code,
                            'coupon_condition' => $coupon->coupon_condition,
                            'coupon_number' => $coupon->coupon_number,

                        );
                        Session::put('coupon',$cou);
                }
                Session::save();
                return Redirect()->back()->with('message','Áp dụng mã giảm giá thành công!');
            }
        }else{
            return Redirect()->back()->with('error','Mã giảm giá không tồn tại!');
        }

    }





}

