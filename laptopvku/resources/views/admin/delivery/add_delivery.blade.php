@extends('admin_layout')
@section('admin_content')


<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm thông tin phí vận chuyển
                        </header>
                        <?php

						$message = Session::get('message');
						if($message){
							echo '<span class="text-alert">'.$message.'</span>';
							Session::put('message',null);
						}

						?>
                        <div class="panel-body">
                            <div class="position-center">
                                <form>

                                	{{csrf_field()}}

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Chọn Tỉnh/Thành phố</label>
	                                    <select name="city" id="city" class="form-control input-sm m-bot15 choose city">
	                                    	<option value="">--- Chọn Tỉnh/Thành phố ---</option>

	                                    	@foreach($city as $key => $c)
	                                    		<option value="{{$c->matp}}">{{$c->name_city}}</option>
	                                    	@endforeach

	                                    </select>
	                                </div>

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Chọn Quận/Huyện</label>
	                                    <select name="province" id="province" class="form-control input-sm m-bot15 choose province">
	                                    	<option value="">--- Chọn Quận/Huyện ---</option>
	                                    	
	                                    </select>
	                                </div>

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Chọn Xã/Phường/Thị trấn</label>
	                                    <select name="wards" id="wards" class="form-control input-sm m-bot15 wards">
	                                    	<option value="">--- Chọn Xã/Phường/Thị trấn ---</option>
	                                    	
	                                    </select>
	                                </div>

	                                <div class="form-group">
	                                    <label for="exampleInputEmail1">Phí vận chuyển</label>
	                                    <input type="text" name="fee_ship" class="form-control fee_ship" id="exampleInputEmail1">
	                                </div>
                               		
	                                <button type="button" name="add_delivery" class="btn btn-info add_delivery">Thêm phí</button>

                            	</form>
                            </div>
                            <br>
                            <div id="load_delivery">



                            </div>

                            {{-- <footer class="panel-footer">
						      <div class="row">
						        
						        
						        <div class="col-sm-7 text-right text-center-xs">                
						          <ul class="pagination">
						            
						            <li>{!!$fee_ship->links()!!}</li>
						            
						            
						          </ul>
						        </div>
						      </div>
						    </footer> --}}

                        </div>
                    </section>

            </div>


@endsection