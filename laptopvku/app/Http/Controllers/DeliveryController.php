<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //TV dùng database

use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

use App\Models\City;
use App\Models\Province;
use App\Models\Wards;
use App\Models\Feeship;

class DeliveryController extends Controller
{
    public function delivery(Request $request){


    	$city = City::orderby('matp','ASC')->get(); // ASC tăng , DESC giảm

    	return view('admin.delivery.add_delivery')->with(compact('city'));

    }

    public function select_delivery(Request $request){

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


    public function insert_delivery(Request $request){


    	$data = $request->all();

    	$f = new Feeship();

    	$f->fee_matp = $data['city'];
    	$f->fee_maqh = $data['province'];
    	$f->fee_xaid = $data['wards'];
    	$f->fee_feeship = $data['fee_ship'];
    	$f->save();
    }

    public function select_feeship(){

        $fee_ship = Feeship::orderby('fee_id','DESC')->get();
        $output = '';
        $output .= '<div class="table-responsive">
                        <table class="table table-bordered">
                            <thread>
                                <tr>
                                    <th>Tên tỉnh/tp</th>
                                    <th>Tên quận/huyện</th>
                                    <th>Tên xã/phường/thị trấn</th>
                                    <th>Phí vận chuyển ($)</th>
                                </tr>
                            </thread>
                            <tbody>
                            ';
                                foreach ($fee_ship as $key => $f) {

                                    $output.='

                                    <tr>
                                        <td>'.$f->city->name_city.'</td>
                                        <td>'.$f->province->name_quanhuyen.'</td>
                                        <td>'.$f->wards->name_xaphuongthitran.'</td>
                                        <td contenteditable data-feeship_id="'.$f->fee_id.'" class="fee_feeship_edit">'.$f->fee_feeship.'</td>
                                    </tr>
                                    ';
                                    
                                }
                                $output.='
                                    
                            </tbody>
                        </table>
                    </div>';

                    echo $output;

    }

    public function update_feeship(Request $request){

        $data = $request->all();

        $fee_ship = Feeship::find($data['feeship_id']);
        $fee_ship->fee_feeship = $data['fee_value'];
        
        
        $fee_ship->save();

    }


}
