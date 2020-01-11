@extends('adminlte::page')

@section('title', 'Punch Dashboard')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">Punch {{ $punch_status }}</div>
  <div class="panel-body text-center">
    @if ($errors->has('punch'))
    <div class="alert alert-warning alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ $errors->first('punch') }}</strong>
    </div>
    @endif
    <form action="{{ $p_url }}" method="post">
      @csrf
      <button type="submit" class="btn btn-{{ $btncol }}">Punch {{ $punch_status }}</button>
    </form>
  </div>
</div>
@if($p_gotdata == true)
<div class="panel panel-default">
  <div class="panel-heading">Punch History</div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Clock-in</th>
           <th>Clock-out</th>
           <th>Status</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($p_list as $ap)
         <tr>
           <td>{{ $ap->punch_in_time }}</td>
           <td>{{ $ap->punch_out_time }}</td>
           <td>{{ $ap->status }}</td>
           <td>
            @if($ap->punch_out_time!=null)
              <form id="formdate" action="{{route('ot.formdate')}}" method="POST">
                @csrf
                <input type="date" id="inputdate" class="hidden" name="inputdate" value="{{ date('Y-m-d', strtotime($ap->punch_in_time)) }}" required>
                    
                  <button type="button" class="btn btn-sm btn-primary">Apply Claim</button>
                </p>
              </form>
              <button type="button" data-id="{{$ap['id']}}" data-start="{{$ap['punch_in_time']}}" data-end="{{$ap['punch_out_time']}}" class="del btn btn-sm btn-danger" style="color: white"><i class="fas fa-times-circle"></i></button>
            @endif
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
  </div>
</div>
<form action="{{route('punch.delete')}}" method="POST" class="" id="form">
  @csrf
  <input type="text" class="hidden" id="inputid" name="inputid" required>
</form>
@endif
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tPunchHIstory').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]],
      fixedHeader : true
    });
} );


$(".del").on("click", function(){
  var id = $(this).data('id');
  var start = $(this).data('start');
  var end = $(this).data('end');
  $('#inputid').val(id);
  Swal.fire({
      title: 'Are you sure to delete Clock In?',
      text: "Delete clock in time "+start+" - "+end,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Delete'
      }).then((result) => {
      if (result.value) {
          $("#form").submit();
      }
  })
})
</script>

@stop
