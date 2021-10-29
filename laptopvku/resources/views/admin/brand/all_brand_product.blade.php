@extends('admin_layout')
@section('admin_content')


<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Liệt kê thương hiệu sản phẩm
    </div>
    <div class="row w3-res-tb">
      <div class="col-sm-5 m-b-xs">
        <select class="input-sm form-control w-sm inline v-middle">
          <option value="0">Bulk action</option>
          <option value="1">Delete selected</option>
          <option value="2">Bulk edit</option>
          <option value="3">Export</option>
        </select>
        <button class="btn btn-sm btn-default">Apply</button>                
      </div>
      <div class="col-sm-4">
      </div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control" placeholder="Search">
          <span class="input-group-btn">
            <button class="btn btn-sm btn-default" type="button">Go!</button>
          </span>
        </div>
      </div>
    </div>
    <div class="table-responsive">


    	<?php

		$message = Session::get('message');
		if($message){
			echo '<span class="text-alert">'.$message.'</span>';
			Session::put('message',null);
		}

		?>


      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th style="width:20px;">
              <label class="i-checks m-b-none">
                <input type="checkbox"><i></i>
              </label>
            </th>
            <th>Tên thương hiệu</th>
            {{-- <th>Brand Slug</th> --}}
            <th>Hiển thị</th>
            
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>

        	@foreach($all_brand_product as $key => $cate_pro)

          <tr>
            <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
            <td>
            	{{ $cate_pro->brand_name }}
            </td>
            {{-- <td>{{ $brand_pro->brand_slug }}</td> --}}
            <td><span class="text-ellipsis">
            	
            	<?php

            	if($cate_pro->brand_status==1){

            	?>
            		 <a href="{{URL::to('/unactive-brand-product/'.$cate_pro->brand_id)}}"><span class="fa-thumb-styling fa fa-thumbs-up"></span></a>
            	<?php
            	}else{
            	?>
            		<a href="{{URL::to('/active-brand-product/'.$cate_pro->brand_id)}}"><span class="fa-thumb-styling fa fa-thumbs-down"></span></a>
            	<?php
            	}


            	?>

            </span></td>


            <td>
              <a href="{{URL::to('/edit-brand-product/'.$cate_pro->brand_id)}}" class="active styling-edit" ui-toggle-class=""><i class="fa fa-pencil-square-o text-success text-active"></i></a>

              <a onclick="return confirm('Bạn chắc chắn muốn xóa thương hiệu này?')" href="{{URL::to('/delete-brand-product/'.$cate_pro->brand_id)}}" class="active styling-edit" ui-toggle-class=""><i class="fa fa-times text-danger text"></i></a>
            </td>



          </tr>
          

          @endforeach

        </tbody>
      </table>


      <form action="{{URL::to('/import-brand')}}" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="file" name="file_brand" accept=".xlsx" required=""><br>
          <input type="submit" value="Import" name="import_csv" class="btn btn-warning">
      </form>
      <form action="{{URL::to('/export-brand')}}" method="POST">
          {{ csrf_field() }}
          <input type="submit" value="Export" name="export_csv" class="btn btn-success">
      </form>


    </div>
    <footer class="panel-footer">
      <div class="row">
        
        
        <div class="col-sm-7 text-right text-center-xs">                
          <ul class="pagination">
            
            <li>{!!$all_brand_product->links()!!}</li>
            
            
          </ul>
        </div>
      </div>
    </footer>
  </div>
</div>


@endsection