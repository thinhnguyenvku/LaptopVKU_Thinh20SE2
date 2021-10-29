@extends('admin_layout')
@section('admin_content')


<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm sản phẩm
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
                                <form role="form" action="{{URL::to('save-product')}}" method="post" enctype="multipart/form-data">   
                                	{{--  enctype phải thêm khi form có chọn ảnh ,,, --}}


                                	{{csrf_field()}}  {{-- tạo token bảo mật form --}}


	                                <div class="form-group">
	                                    <label for="exampleInputEmail1">Tên sản phẩm</label>
	                                    <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Nhập tên sản phẩm" data-validation="length" data-validation-length="max10" data-validation-error-msg="Không được để trống!">
	                                </div>


	                                {{-- <div class="form-group">
	                                    <label for="exampleInputEmail1">Slug</label>
	                                    <input type="text" name="product_slug" class="form-control" id="exampleInputEmail1" placeholder="Tên danh mục">
                                	</div> --}}

	                                <div class="form-group">
	                                    <label for="exampleInputEmail1">Giá sản phẩm</label>
	                                    <input type="text" name="product_price" class="form-control" id="exampleInputEmail1" placeholder="Nhập Giá sản phẩm">
	                                </div>

	                                <div class="form-group">
	                                    <label for="exampleInputEmail1">Số lượng sản phẩm</label>
	                                    <input type="text" name="product_quantity" class="form-control" id="exampleInputEmail1" placeholder="Nhập số lượng sản phẩm">
	                                </div>

	                                <div class="form-group">
	                                    <label for="exampleInputEmail1">Số lượng sản phẩm đã bán</label>
	                                    <input type="text" name="product_sold" class="form-control" id="exampleInputEmail1" placeholder="Nhập số lượng sản phẩm đã bán">
	                                </div>

	                                <div class="form-group">
	                                    <label for="exampleInputEmail1">Hình ảnh sản phẩm</label>
	                                    <input type="file" name="product_image" class="form-control" id="exampleInputEmail1" placeholder="Nhập hình ảnh sản phẩm">
	                                </div>

	                                <div class="form-group">
	                                    <label for="exampleInputPassword1">Mô tả sản phẩm</label>
	                                    <textarea style="resize: none;" rows="5" name="product_desc" class="form-control" id="ck1" placeholder="Nhập mô tả sản phẩm"></textarea>
	                                </div>

	                                <div class="form-group">
	                                    <label for="exampleInputPassword1">Nội dung sản phẩm</label>
	                                    <textarea style="resize: none;" rows="5" name="product_content" class="form-control" id="ck2" placeholder="Nhập nội dung sản phẩm"></textarea>
	                                </div>

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Danh mục sản phẩm</label>
	                                    <select name="product_cate" class="form-control input-sm m-bot15">
	                                    	
	                                    @foreach($cate_product as $key => $cate)
	                                    	<option value="{{$cate->category_id}}">{{$cate->category_name}}</option>
	                                    @endforeach

	                                    </select>
	                                </div>

									<div class="form-group">
	                                	<label for="exampleInputPassword1">Thương hiệu sản phẩm</label>
	                                    <select name="product_brand" class="form-control input-sm m-bot15">

	                                    @foreach($brand_product as $key => $brand)
	                                    	<option value="{{$brand->brand_id}}">{{$brand->brand_name}}</option>
	                                    	
	                                    @endforeach

	                                    </select>
	                                </div>

	                                <div class="form-group">
	                                	<label for="exampleInputPassword1">Hiển thị</label>
	                                    <select name="product_status" class="form-control input-sm m-bot15">
	                                    	<option value="0">Ẩn</option>
	                                    	<option value="1">Hiện</option>
	                                    </select>
	                                </div>
                               		
	                                <button type="submit" name="add_product" class="btn btn-info">Thêm sản phẩm</button>

                            	</form>
                            </div>

                        </div>
                    </section>

            </div>


@endsection