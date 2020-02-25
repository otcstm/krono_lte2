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

<div class="panel panel-default">
  <div class="panel-body">
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Date</th>
           <th>Start OT</th>
           <th>End OT</th>
           <th></th>
           <th>Day Type</th>
           <th>Hours/Minutes</th>
           <th>Start Location</th>
           <th>End Location</th>
           <th>Action</th>
         </tr>
       </thead>
      <tbody>
       @if(count($p_list)>0)
          @php($date1 = null)
          @php($date2 = null)
          @foreach($p_list as $ap)
            @php($date1 = date('Y-m-d', strtotime($ap->punch_in_time)))
              @if($date1!=$date2)
              <tr>
                <td>{{date('d.m.Y', strtotime($ap->punch_in_time))}}</td>
                <td>
                  @foreach($p_list as $app)
                    @if(date('Y-m-d', strtotime($app->punch_in_time))==$date1)
                      @foreach($app->detail as $no=>$aps)
                        <p style="margin: 2px 0;">{{date('Hi', strtotime($aps->start_time))}}</p>
                      @endforeach
                    @endif
                  @endforeach
                </td>
                <td>
                  @foreach($p_list as $app)
                    @if(date('Y-m-d', strtotime($app->punch_in_time))==$date1)
                      @foreach($app->detail as $no=>$aps)
                        <p style="margin: 2px 0;">{{date('Hi', strtotime($aps->end_time))}}</p>
                      @endforeach
                    @endif
                  @endforeach
                </td>
                <td>
                @foreach($p_list as $app)
                  @if(date('Y-m-d', strtotime($app->punch_in_time))==$date1)
                    @foreach($app->detail as $no=>$aps)
                    @if($ap->apply_ot!="X") <p style="margin: 2px 0;"><button type="button" data-id="{{ $aps->id }}" data-start="{{$aps->start_time}}" data-end="{{$aps->end_time}}" class="del btn btn-sm btn-x btn-x-sm" style="display: block;" ><i class="fas fa-times"></i></button></p> @endif
                    @endforeach
                  @endif
                @endforeach
                </td>
                <td>{{$ap->day_type}}</td>
                <td>
                  @foreach($p_list as $app)
                    @if(date('Y-m-d', strtotime($app->punch_in_time))==$date1)
                      @foreach($app->detail as $no=>$aps)
                        {{$aps->hour}}h/{{$aps->minute}}m<br>
                      @endforeach
                    @endif
                  @endforeach
                </td>
                <td>{{$ap->in_latitude}} {{$ap->in_longitude}}</td>
                <td>{{$ap->out_latitude}} {{$ap->out_longitude}}</td>
                <td>
                  @if($ap->punch_out_time!=null)
                    <form id="formdate" action="{{route('ot.formdate')}}" method="POST">
                      @csrf
                      <input type="date" id="inputdate" class="hidden" name="inputdate" value="{{ date('Y-m-d', strtotime($ap->punch_in_time)) }}" required>
                          
                      @if($ap->apply_ot!="X")<button type="submit" class="btn btn-sm btn-primary">APPLY OT</button> @endif

                    </form>
                  @endif
                </td>
              </tr>
              @endif
            @php($date2 = date('Y-m-d', strtotime($ap->punch_in_time)))
          @endforeach
        @else
          <td colspan="9"><div class="text-center"><i>Not available</i></div></td>
        @endif

        </tbody>
     </table>
    </div>
  </div>
</div>
<form action="{{route('punch.delete')}}" method="POST" class="hidden" id="form">
  @csrf
  <input type="text" class="hidden" id="inputid" name="inputid" required>
</form>
@stop

@section('js')
<script type="text/javascript">

@if(count($p_list)>0)
$(document).ready(function() {
    $('#tPunchHIstory').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]],
      fixedHeader : true,
      "columnDefs": [
        { "orderable": false,  "targets": 3 }
      ],
    });
} );
@endif
// var now = new Date(); 
// x = Date.parse(now).toString("yyyy-MM-dd HH:mm:ss")
// $("#timesss").val(x);

$(".del").on("click", function(){
  var id = $(this).data('id');
  var start = $(this).data('start');
  var end = $(this).data('end');
  var s="N/A";
  var e="N/A";
  if(start!=""){
    s = Date.parse(start).toString("dd.MM.yyyy HH:mm:ss");  
  }
  if(end!=""){
    e = Date.parse(end).toString("dd.MM.yyyy HH:mm:ss");  
  }
  $('#inputid').val(id);
  Swal.fire({
      title: 'Are you sure to permanently delete Clock In?',
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
