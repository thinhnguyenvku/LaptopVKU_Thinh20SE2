@extends('admin_layout')
@section('admin_content')


<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm thương hiệu sản phẩm
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
                                <form role="form" action="{{URL::to('save-brand-product')}}" method="post">

                                	{{csrf_field()}}


	                                <div class="form-group">
	                                    <label for="exampleInputEmail1">Tên thương hiệu</label>
	                                    <input type="text" name="brand_product_name" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên thương hiệu sản phẩm">
	                                </div>


	                                <div class="form-group">
	                                    <label for="exampleInputPassword1">Mô tả thương hiệu</label>
	                                    <textarea style="resize: none;" rows="5" name="brand_product_desc" class="form-control" id="ck1" placeholder="Nhập mô tả thương hiệu sản phẩm"></textarea>
	                                </div>

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Hiển thị</label>
	                                    <select name="brand_product_status" class="form-control input-sm m-bot15">
	                                    	<option value="0">Ẩn</option>
	                                    	<option value="1">Hiện</option>
	                                    </select>
	                                </div>
                               		
	                                <button type="submit" name="add_brand_product" class="btn btn-info">Thêm thương hiệu</button>

                            	</form>
                            </div>

                        </div>
                    </section>

            </div>


@endsection