<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //TV dùng database

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();


use App\Models\Social; //sử dụng model Social
use Socialite; //sử dụng Socialite
use App\Models\Login; //sử dụng model Login


class AdminController extends Controller
{
    public function AuthLogin(){ //bảo mật, chưa login thì ko thể truy cập các đường dẫn của admin
        $admin_id = Session::get('admin_id');
        if($admin_id){
            Redirect::to('dashboard');

        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function index(){
    	return view('admin_login');
    }


    public function show_dashboard(){
        $this->AuthLogin(); //gọi hàm bảo mật
    	return view('admin.dashboard');
    }


    //Đăng nhập vào admin
    public function dashboard(Request $request){
    	
        // code này theo kiểu MVC
        $data = $request->all();
        $admin_email = $data['admin_email'];
        $admin_password = md5($data['admin_password']);

        $login = Login::where('admin_email',$admin_email)->where('admin_password',$admin_password)->first();
        


        if($login){

            $login_count = $login->count();

            if($login_count > 0){

                Session::put('admin_name',$login->admin_name);
                Session::put('admin_id',$login->admin_id);

                return Redirect::to('/dashboard');
            }
        }else{

            Session::put('message','Mật khẩu hoặc Email sai. Hãy nhập lại!');

            return Redirect::to('/admin');

        }
        
    }




    public function logout(){
        $this->AuthLogin(); //gọi hàm bảo mật
    	Session::put('admin_name',null);
    	Session::put('admin_id',null);
    	return Redirect::to('admin');	
    }



    


}
