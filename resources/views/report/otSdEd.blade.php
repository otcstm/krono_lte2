@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop
@section('title', 'Report')
@section('content')
<style>
    table.table-borderless{
        border:0;
        width:80% !important;
    }
</style>

<div class="panel panel-default">
<div class="panel-heading"><strong>Report : List of Start/End OT Time</strong></div>
<div class="panel-body">
  <form action="{{ route('otr.viewStEd', [], false) }}" method="post">
  @csrf
  <div class="col-lg-6">
  <div class="form-group">
    	<label for="fdate">From</label>
  	<input type="date" class="form-control" id="fdate" name="fdate"  autofocus>
  </div>
  </div>
  <div class="col-lg-6">
  <div class="form-group">
  	<label for="tdate">To</label>
  	<input type="date" class="form-control" id="tdate" name="tdate"   autofocus>
  </div>
  </div>
  <div class="col-lg-12">
  <div class="form-group">
    <label for="fpersno">Persno</label>
    <div class="table-responsive">
    <table class="table-borderless" id="dynamic_persno">
      <tr>
        <td><input type="text" class="form-control" style=" background-color: white;" readonly id="fpersno" name="fpersno"></td>
        <td>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addpersno">Add</button></td>
      </tr>
    </table>
  </div>
  </div>
  </div>

  <div class="col-lg-12">
  <div class="form-group text-center">
    <input type="hidden" name="searching" value="SdEd">
    <button type="submit" class="btn btn-primary">Search</button>
  </div>
  </div>
  </form>
</div>
</div>


{{--modal persno--}}
<div id="addpersno" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Persno</h4>
      </div>
      <form action="" method="post">
      <div class="modal-body">
        @csrf
        <div class="form-group">
          <div class="table-responsive">
            <table class="table table-bordered" id="append_persno">
              <tr>
                <td style="border:0;"><input type="text" class="form-control" id="fpersno0" ></td>
                <td style="border:0;"><button type="button" name="add" id="btaddprsno" class="btn btn-success">+</button>
                </td>
              </tr>
            </table>
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <div class="text-center">
            <button type="button" id="btndone" data-dismiss="modal" class="btn btn-default">Add</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


@if($vlist == true)
<div class="panel panel-default">
<div class="panel-heading panel-primary">List of Start/End OT Time</div>
<div class="panel-body">
  <div class="table-responsive">
  <table id="tOtlist" class="table table-bordered">
    <thead>
      <tr>
      <th>Pers Number</th>
      <th>Employee Name</th>
      <th>IC Number</th>
      <th>Staff ID</th>
      <th>Comp Code</th>
      <th>Pers Area</th>
      <th>Pers Subarea</th>
      <th>State</th>
      <th>Region</th>
      <th>Emp Group</th>
      <th>Emp Subgroup</th>
      <th>Date</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Day Type</th>
      <th>Location</th>
      <th>Apply OT Claim?</th>
      </tr>
    </thead>
    <tbody>
      @foreach($otrep as $otr)
      <tr>
        <td>{{ $otr->user_id }}</td>
        <td>{{ $otr->URpio()->name }}</td>
        <td>{{ $otr->URpio()->new_ic }}</td>
        <td>{{ $otr->URpio()->staffno }}</td>
        <td>{{ $otr->URpio()->company_id }}</td>
        <td>{{ $otr->URpio()->persarea }}</td>
        <td>{{ $otr->URpio()->perssubarea }}</td>
        <td>{{ $otr->URpio()->state_id }}</td>
        <td>{{ $otr->URpio()->region }}</td>
        <td>{{ $otr->URpio()->empgroup }}</td>
        <td>{{ $otr->URpio()->empsgroup }}</td>
      <td>{{ date('d-m-Y', strtotime($otr->punch_in_time)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->punch_in_time)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->punch_out_time)) }}</td>
      <td>{{ $otr->day_type}}</td>
      <td>({{ $otr->in_latitude }}, {{ $otr->in_longitude }})</td>
      <td>
        @if( $otr->apply_ot == 'X')

          Yes

          {{--
          if(staff_punches.st == staff_punches.et && overtime_details.st == overtime_details.et && is_manual == null && checked == Y)
          Yes
          else
          No
          --}}
        @else
          No
        @endif

      </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
</div>
</div>
@endif


@if(session()->has('feedback'))
<div id="feedback" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dilgiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <div class="glyphicon glyphicon-{{session()->get('feedback_icon')}}" style="color: {{session()->get('feedback_color')}}; font-size: 32px;"></div>
        <p>{{session()->get('feedback_text')}}<p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dilgiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>
@endif

@stop
@section('js')

<script type="text/javascript">

/*------------------------------js persno-------------------------------*/
var i=1;

$(document).ready(function(){

	$('#btaddprsno').click(function(){
				$('#append_persno').append('<tr id="row'+i+'"><td style="border:0;"><input type="text" class="form-control" id="fpersno'+i+'" ></td><td style="border:0;"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">x</button></td></tr>');
    i++;
	});

  $(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr("id");
		$('#row'+button_id+'').remove();
    i--;
	});

	$(document).on('click', '#btndone', function(){
    var str="";
    for(x=0 ; x<i ; x++){
      if($("#fpersno"+x).val()!=""){
        str += $("#fpersno"+x).val()+",";
      }
    }
    $("#fpersno").val(str);
	});

});
</script>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
  $('#tOtlist').DataTable({
    "responsive": "true",
    "order" : [[0, "asc"]],
    dom: 'Bfrtip',
		buttons: [
    {extend: 'excelHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Details Report', sheetName: 'OT Details', title: 'OT Details Report'},
    {extend: 'pdfHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Details Report', sheetName: 'OT Details', title: 'OT Details Report'},
		{extend: 'colvis', collectionLayout: 'fixed three-column'}]
  });
});


@if(session()->has('feedback'))
    $('#feedback').modal('show');
@endif
</script>
@stop
