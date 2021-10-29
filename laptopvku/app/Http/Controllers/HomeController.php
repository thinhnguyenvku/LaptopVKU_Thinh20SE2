<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //TV dùng database

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();




use Mail; //có sẵn trong Laravel
use App\Models\Banner;


class HomeController extends Controller
{
    public function index(){

        
        $banner = Banner::orderby('banner_id','DESC')->where('banner_status','1')->take(3)->get();

        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get(); //trạng thái = 1 thì hiển thị danh mục ra
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','1')->orderby('brand_id','desc')->get();


        $all_product = DB::table('tbl_product')->where('product_status','1')->orderby('product_id','desc')->paginate(9); 

        return view('pages.home')->with('category',$cate_product)->with('brand',$brand_product)->with('all_product',$all_product)->with('banner',$banner);
        
    }

    public function search(Request $request){


        $keywords = $request->keywords_submit;
        

        $banner = Banner::orderby('banner_id','DESC')->where('banner_status','1')->take(3)->get();

        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','1')->orderby('brand_id','desc')->get(); 


        $search_product = DB::table('tbl_product')->where('product_name','like','%'.$keywords.'%')->paginate(6); 


        return view('pages.product.search')->with('category',$cate_product)->with('brand',$brand_product)->with('search_product',$search_product)->with('banner',$banner);
        
    }

    public function send_mail(){



         //send mail
                $to_name = "FLap Shop";
                $to_email = "npthinh.20it2@vku.udn.vn"; // người nhận
               
             
                $data = array("name"=>"Laptop VKU","body"=>'Laptop VKU xin chào!'); 
                
                Mail::send('pages.mail.send_mail',$data,function($message) use ($to_name,$to_email){

                    $message->to($to_email)->subject('Gửi GoogleMail từ Laravel 8x');
                    $message->from($to_email,$to_name);

                });
              
    }

    public function error_page(){

        return view('errors.404');
    }
}
