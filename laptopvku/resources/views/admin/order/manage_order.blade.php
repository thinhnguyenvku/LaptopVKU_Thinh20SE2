@extends('admin_layout')
@section('admin_content')


<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Liệt kê đơn hàng
    </div>
    <div class="row w3-res-tb">
      
     
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
            
            <th>Thứ tự</th>
            <th>Mã đơn</th>
            <th>Thời gian đăt</th>
            <th>Tình trạng đơn</th>
            
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>

          @php
            $i=0;
          @endphp

          @foreach($order as $key => $o)
          @php
            $i++;
          @endphp
          <tr>
            <td>{{ $i }}</td>
            <td>{{ $o->order_code }}</td>
            <td>{{ $o->created_at }}</td>
            <td>
                @if($o->order_status==1)
                  Đợi duyệt
                @elseif($o->order_status==2)
                  Đang giao
                @else
                  Đã hủy đơn
                @endif
            </td>
            


            <td>
              <a href="{{URL::to('/view-order/'.$o->order_code)}}" class="active styling-edit" ui-toggle-class=""><i class="fa fa-eye text-success text-active"></i></a>

              <a onclick="return confirm('Bạn chắc chắn muốn xóa đơn hàng này?')" href="{{URL::to('/delete-order')}}" class="active styling-edit" ui-toggle-class=""><i class="fa fa-times text-danger text"></i></a>
            </td>



          </tr>
          

          @endforeach

        </tbody>
      </table>
    </div>
    <footer class="panel-footer">
      <div class="row">
        
        
        <div class="col-sm-7 text-right text-center-xs">                
          <ul class="pagination">
            
            <li>{!!$order->links()!!}</li>
            
            
          </ul>
        </div>
      </div>
    </footer>
  </div>
</div>


@endsection