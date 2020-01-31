@extends('adminlte::page')

@section('title', 'Punch Dashboard')

@section('content')
<h1>List of Start/End Overtime</h1>

{{--<!-- <div class="panel panel-default">
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
</div> -->--}}

@if($p_gotdata == true)
<div class="panel panel-default">
  <div class="panel-body">
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Date</th>
           <th>Start OT</th>
           <th>End OT</th>
           <th>Day Type</th>
           <th>Hours/Minutes</th>
           <th>Start Location</th>
           <th>End Location</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @php($date1 = null)
         @php($date2 = null)
         @foreach($p_list as $ap)
          @php($date1 = date('Y-m-d', strtotime($ap->punch_in_time)))
            @if($date1!=$date2)
            <tr>
              <td>{{date('d.m.Y', strtotime($ap->punch_in_time))}}</td>
              <td>
              @if(count($ap->detail)!=0)
                @foreach($ap->detail as $no=>$aps)
                  {{date('Hi', strtotime($aps->start_time))}}<br>
                  @if($no==0)
                    @php($first=$aps->start_time)
                  @endif
                @endforeach
              @else
                N/A
              @endif
              </td>
              <td>
              @if(count($ap->detail)!=0)
                @foreach($ap->detail as $no=>$aps)
                  {{date('Hi', strtotime($aps->end_time))}}<br>
                  @if($no==count($ap->detail)-1)
                    @php($last=$aps->end_time)
                  @endif
                @endforeach
              @else
                N/A
              @endif
              </td>
              <td>{{$ap->day_type}}</td>
              <td>
              @if(count($ap->detail)!=0)
                @foreach($ap->detail as $aps)
                  {{$aps->hour}}h/{{$aps->minute}}m<br>
                @endforeach
              @else
                N/A
              @endif
              </td>
              <td>{{$ap->in_latitude}} {{$ap->in_longitude}}</td>
              <td>{{$ap->out_latitude}} {{$ap->out_longitude}}</td>
              <td>
                @if($ap->punch_out_time!=null)
                  <div style="display: flex">
                  <form id="formdate" action="{{route('ot.formdate')}}" method="POST">
                    @csrf
                    <input type="date" id="inputdate" class="hidden" name="inputdate" value="{{ date('Y-m-d', strtotime($ap->punch_in_time)) }}" required>
                        
                      <button type="submit" class="btn btn-sm btn-primary" @if($ap->apply_ot=="X") disabled @endif>APPLY OT</button>

                  </form>
                  <button type="button" data-date="{{ date('Y-m-d', strtotime($ap->punch_in_time)) }}" data-start="{{$first}}" data-end="{{$last}}" class="del btn btn-sm btn-x" style="margin-left: 3px" @if($ap->apply_ot=="X") disabled @endif><i class="fas fa-times"></i></button>
                  </div>
                @endif
              </td>
            </tr>
            @endif
          @php($date2 = date('Y-m-d', strtotime($ap->punch_in_time)))
         @endforeach
       </tbody>
     </table>
    </div>
  </div>
</div>
<form action="{{route('punch.delete')}}" method="POST" class="hidden" id="form">
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

// var now = new Date(); 
// x = Date.parse(now).toString("yyyy-MM-dd HH:mm:ss")
// $("#timesss").val(x);

$(".del").on("click", function(){
  var date = $(this).data('date');
  var start = $(this).data('start');
  var end = $(this).data('end');
  var s = Date.parse(start).toString("dd.MM.yyyy HH:mm:ss");  
  var e = Date.parse(end).toString("dd.MM.yyyy HH:mm:ss");  
  $('#inputid').val(date);
  Swal.fire({
      title: 'Are you sure to delete Clock In?',
      text: "Delete clock in time "+s+" - "+e,
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
