<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();


use App\Models\Banner; 

class BannerController extends Controller
{

	public function AuthLogin(){ //bảo mật, chưa login thì ko thể truy cập các đường dẫn của admin
        $admin_id = Session::get('admin_id');
        if($admin_id){
            Redirect::to('dashboard');

        }else{
            return Redirect::to('admin')->send();
        }
    }


    public function all_banner(){

    	$all_banner = Banner::orderby('banner_id','DESC')->paginate(5);

    	return view('admin.banner.all_banner')->with(compact('all_banner'));
    }

    public function add_banner(){

    	return view('admin.banner.add_banner');
    }

    public function save_banner(Request $request){

    	
    	$data = $request->all();

    	$this->AuthLogin();
   
        $get_image = $request->file('banner_image');
      
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.',$get_name_image));
            $new_image =  $name_image.rand(0,999).'.'.$get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/banner',$new_image);
            
            $banner = new Banner();
            $banner->banner_name = $data['banner_name'];
            $banner->banner_image = $new_image;
            $banner->banner_status = $data['banner_status'];
            $banner->banner_desc = $data['banner_desc'];
            $banner->save();

            Session::put('message','Thêm thành công');
            return Redirect::to('add-banner');
        }else{
			Session::put('message','Chưa tải ảnh lên!!!');
        	return Redirect::to('add-banner');
        }
    	
    }

    public function unactive_banner($banner_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	DB::table('tbl_banner')->where('banner_id',$banner_id)->update(['banner_status'=>0]);

    	Session::put('message','Ẩn banner thành công!');
    	return Redirect::to('all-banner');
    }
    public function active_banner($banner_id){

        $this->AuthLogin(); //gọi hàm bảo mật
    	DB::table('tbl_banner')->where('banner_id',$banner_id)->update(['banner_status'=>1]);

    	Session::put('message','Hiển thị banner thành công!');
    	return Redirect::to('all-banner');
    }

    public function delete_banner($banner_id){

    	$this->AuthLogin(); //gọi hàm bảo mật
    	
    	DB::table('tbl_banner')->where('banner_id',$banner_id)->delete();
    	Session::put('message','Xóa banner thành công!');
    	return Redirect::to('all-banner');

    }
}
