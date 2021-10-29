<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //TV dùng database

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

use App\Models\Coupon;

class CouponController extends Controller
{
    
    public function add_coupon(){

    	return view('admin.coupon.add_coupon');

    }

    public function save_coupon(Request $request){

    	$data = $request->all();

    	$coupon = new Coupon;

    	$coupon->coupon_name = $data['coupon_name'];
    	$coupon->coupon_time = $data['coupon_time'];
    	$coupon->coupon_code = $data['coupon_code'];
    	$coupon->coupon_number = $data['coupon_number'];
    	$coupon->coupon_condition = $data['coupon_condition'];
    	$coupon->save();

    	Session::put('message','Thêm mã thành công!');

    	return Redirect::to('/add-coupon');

    }

    public function all_coupon(){


    	$coupon = Coupon::orderby('coupon_id','DESC')->paginate(5);

    	return view('admin.coupon.all_coupon')->with(compact('coupon'));
    }

    public function delete_coupon($coupon_id){

        $coupon = Coupon::find($coupon_id);
        $coupon->delete();

        Session::put('message','Xóa mã thành công!');

        return Redirect::to('/all-coupon');
    }

    public function unset_coupon(){

        $coupon = Session::get('coupon');

        if($coupon==true){
            
            Session::forget('coupon'); 

            return Redirect()->back()->with('message','Xóa mã giảm giá thành công!');
        }

    }


}
