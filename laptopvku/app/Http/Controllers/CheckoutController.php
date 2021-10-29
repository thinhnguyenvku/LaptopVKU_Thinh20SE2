<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //TV dùng database

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

use Cart;

use App\Models\City;
use App\Models\Province;
use App\Models\Wards;
use App\Models\Feeship;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\OrderDetails;

use App\Models\Banner;


class CheckoutController extends Controller
{
    
    public function AuthLogin(){ //bảo mật, chưa login thì ko thể truy cập các đường dẫn của admin
        $admin_id = Session::get('admin_id');
        if($admin_id){
            Redirect::to('dashboard');

        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function login_checkout(Request $request){

    	
        $banner = Banner::orderby('banner_id','DESC')->where('banner_status','1')->take(3)->get();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','1')->orderby('brand_id','desc')->get(); 

        return view('pages.checkout.login_checkout')->with('category',$cate_product)->with('brand',$brand_product)->with('banner',$banner);
        
    }

    public function add_customer(Request $request){


    	$data = array();

    	$data['customer_name'] = $request->customer_name;
    	$data['customer_phone'] = $request->customer_phone;
    	$data['customer_email'] = $request->customer_email;
    	$data['customer_password'] = md5($request->customer_password);


    	$customer_id = DB::table('tbl_customer')->insertGetId($data);

    	Session::put('customer_id',$customer_id);
    	Session::put('customer_name',$request->customer_name);



    	return Redirect::to('/checkout');

    }


    public function checkout(Request $request){ // thanh toán 1

    	
        $banner = Banner::orderby('banner_id','DESC')->where('banner_status','1')->take(3)->get();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','1')->orderby('brand_id','desc')->get(); 


        //tính phí vận chuyển
        $city = City::orderby('matp','ASC')->get();


        
        return view('pages.checkout.checkout')->with('category',$cate_product)->with('brand',$brand_product)->with('city',$city)->with('banner',$banner);
        
    }


    public function save_checkout_customer(Request $request){


    	$data = array();

    	$data['shipping_name'] = $request->shipping_name;
    	$data['shipping_phone'] = $request->shipping_phone;
    	$data['shipping_email'] = $request->shipping_email;
    	$data['shipping_notes'] = $request->shipping_notes;
    	$data['shipping_address'] = $request->shipping_address;


    	$shipping_id = DB::table('tbl_shipping')->insertGetId($data);

    	Session::put('shipping_id',$shipping_id);
    	



    	return Redirect::to('/payment');


    }

    

    public function logout_checkout(){


        Session::flush();

        return Redirect::to('/login-checkout');
    }

    public function login_customer(Request $request){

        $email = $request->email_account;

        $pass = md5($request->password_account);

        $result = DB::table('tbl_customer')->where('customer_email',$email)->where('customer_password',$pass)->first();

        if($result){

            Session::put('customer_id',$result->customer_id);

            return Redirect::to('/checkout');

        }else{

            return Redirect::to('/login-checkout');

        }
    }


    


    public function select_delivery_user(Request $request){

        $data = $request->all();
        if($data['action']){
            $output = '';
            if($data['action'] == "city"){

                $select_province = Province::where('matp',$data['ma_id'])->orderby('maqh','ASC')->get();
                $output.='<option>--- Chọn Quận/Huyện ---</option>';
                foreach ($select_province as $key => $p) {
                    $output.='<option value="'.$p->maqh.'">'.$p->name_quanhuyen.'</option>';
                }

            }else{
                $select_wards = Wards::where('maqh',$data['ma_id'])->orderby('xaid','ASC')->get();
                $output.='<option>--- Chọn Xã/Phường/Thị trấn ---</option>';
                foreach ($select_wards as $key => $w) {
                    $output.='<option value="'.$w->xaid.'">'.$w->name_xaphuongthitran.'</option>';
                }
            }
        }

        echo $output;
    }

    public function calculate_fee(Request $request){

        $data = $request->all();
        if($data['matp']){
            $feeship = Feeship::where('fee_matp',$data['matp'])->where('fee_maqh',$data['maqh'])->where('fee_xaid',$data['xaid'])->get();

            if($feeship){
                $cout_feeship = $feeship->count();
                if($cout_feeship > 0){
                    foreach ($feeship as $key => $fee) {
                        Session::put('fee',$fee->fee_feeship);
                        Session::save();
                    }
                }else{
                    Session::put('fee',1);
                    Session::save();
                }
            }
            
        }
    }

    public function del_fee(){
        Session::forget('fee');

        return Redirect()->back();
    }

    public function confirm_order(Request $request){

        $data = $request->all();

        //lưu thông tin đặt hành vào tbl_shipping
         $shipping = new Shipping();
         $shipping->shipping_name = $data['shipping_name'];
         $shipping->shipping_email = $data['shipping_email'];
         $shipping->shipping_phone = $data['shipping_phone'];
         $shipping->shipping_address = $data['shipping_address'];
         $shipping->shipping_notes = $data['shipping_notes'];
         $shipping->shipping_method = $data['shipping_method'];
         $shipping->save();


         //lưu thông tin vào tbl_order dựa vào shipping_id
         $shipping_id = $shipping->shipping_id;

         $order = new Order;
         $order->customer_id = Session::get('customer_id'); // đã login sẽ có
         $order->shipping_id = $shipping_id;
         $order->order_status = 1;
         $checkout_code = substr(md5(microtime()),rand(0,26),5); //random tạo mã đơn
         $order->order_code = $checkout_code;
         date_default_timezone_set('Asia/Ho_Chi_Minh'); //tạo times đặt hàng -- theo form giờ HCM
         $order->created_at = now();

         $order->save();


         //lưu thông tin vào tbl_order_details dựa vào order_code
         if(Session::get('cart')==true){
            foreach(Session::get('cart') as $key => $cart){
                $order_details = new OrderDetails;
                $order_details->order_code = $checkout_code;
                $order_details->product_id = $cart['product_id'];
                $order_details->product_name = $cart['product_name'];
                $order_details->product_price = $cart['product_price'];
                $order_details->product_sales_quantity = $cart['product_qty'];
                $order_details->product_coupon =  $data['order_coupon'];
                $order_details->product_feeship = $data['order_fee'];
                $order_details->save();
            } 
        }

        Session::forget('coupon');
        Session::forget('fee');
        Session::forget('cart');
    }

}
