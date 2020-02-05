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
<div class="panel-heading"><strong>Report : Overtime Details</strong></div>
<div class="panel-body">
  <form action="{{ route('otr.viewOTd', [], false) }}" method="post">
  @csrf
  <div class="col-lg-6">
  <div class="form-group">
    	<label for="fdate">From</label>
  	<input type="date" class="form-control" id="fdate" name="fdate" required autofocus>
  </div>
  </div>
  <div class="col-lg-6">
  <div class="form-group">
  	<label for="tdate">To</label>
  	<input type="date" class="form-control" id="tdate" name="tdate"  required autofocus>
  </div>
  </div>

  <div class="col-lg-6">
  <div class="form-group">
  	<label for="frefno">Refno</label>
  	<input type="text" class="form-control" id="frefno" name="frefno">
  </div>
  </div>
  <div class="col-lg-6">
  <div class="form-group">
  	<label for="fapprover_id">Approver ID</label>
  	<input type="text" class="form-control" id="fapprover_id" name="fapprover_id">
  </div>
  </div>
  <div class="col-lg-6">
  <div class="form-group">
  	<label for="fverifier_id">Verifier ID</label>
  	<input type="text" class="form-control" id="fverifier_id" name="fverifier_id">
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
  <div class="form-group">
    <label for="fcompany">Company Code</label>
    <br>
      <input type="text" class="form-control hidden" id="fcompany" name="fcompany">
      <span id="fcompanydummy" style="display: inline-block;height: 34px;overflow: hidden;padding: 3px 0 3px 5px;border: 1px solid #A9A9A9; min-width: 80% !important;max-width: 80% !important;"></span>
      <button style="position:relative; top: -15px !important" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addcompany">Add</button>
  </div>
  </div>
  <div class="col-lg-12">
  <div class="form-group">
    <label for="fstate">State</label>
    <br>
      <input type="text" class="form-control hidden" id="fstate" name="fstate">
      <span id="fstatedummy" style="display: inline-block;height: 34px;overflow: hidden;padding: 3px 0 3px 5px;border: 1px solid #A9A9A9; min-width: 80% !important;max-width: 80% !important;"></span>
      <button style="position:relative; top: -15px !important" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addstate">Add</button>
  </div>
  </div>
  <div class="col-lg-12">
  <div class="form-group">
    <label for="fregion">Region</label>
    <br>
      <input type="text" class="form-control hidden" id="fregion" name="fregion">
      <span id="fregiondummy" style="display: inline-block;height: 34px;overflow: hidden;padding: 3px 0 3px 5px;border: 1px solid #A9A9A9; min-width: 80% !important;max-width: 80% !important;"></span>
      <button style="position:relative; top: -15px !important" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addregion">Add</button>
  </div>
  </div>

  <div class="col-lg-12">
  <div class="form-group text-center">
    <input type="hidden" name="searching" value="detail">
    <button type="submit" class="btn btn-primary">Search</button>
  </div>
  </div>
  </form>
</div>
</div>

{{--modal company--}}
<div id="addcompany" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Company</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <p><b>Select Companies:</b></p>
                    <div style="max-height: 210px; overflow-y: scroll">
                      @if($companies ?? '')
                          @foreach($companies as $no=>$company)
                          <div class="checkbox">
                              <label><input type="checkbox" id="checkbox_{{$no}}" name="company[]" value="{{$company->id}}" data-description="{{$company->company_descr}}">({{$company->id}}) {{$company->company_descr}}</label>
                          </div>

                          @endforeach
                      @endif
                    </div>
                    <div class="text-center">
                      <button type="button" id="btndoneCo" data-dismiss="modal" class="btn btn-default">Done</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--modal state--}}
<div id="addstate" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">State</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <p><b>Select States:</b></p>
                    <div style="max-height: 210px; overflow-y: scroll">
                      @if($states ?? '')
                          @foreach($states as $no=>$state)
                          <div class="checkbox">
                              <label><input type="checkbox" id="cbState_{{$no}}" name="state[]" value="{{$state->id}}" data-description="{{$state->state_descr}}">{{$state->id}}-{{$state->state_descr}}</label>
                          </div>
                          @endforeach
                      @endif
                    </div>
                    <div class="text-center">
                      <button type="button" id="btdState" data-dismiss="modal" class="btn btn-default">Done</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{--modal region--}}
<div id="addregion" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Region</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <p><b>Select Regions:</b></p>
                    <div style="max-height: 210px; overflow-y: scroll">
                      @if($regions ?? '')
                          @foreach($regions as $no=>$region)
                          <div class="checkbox">
                              <label><input type="checkbox" id="cbRegion_{{$no}}" name="region[]" value="{{$region->item2}}" data-description="{{$region->item3}}">{{$region->item3}}</label>
                          </div>
                          @endforeach
                      @endif
                    </div>
                    <div class="text-center">
                      <button type="button" id="btdRegion" data-dismiss="modal" class="btn btn-default">Done</button>
                    </div>
                </form>
            </div>
        </div>
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
<div class="panel-heading panel-primary">List of OT</div>
<div class="panel-body">
  <div class="table-responsive">
  <table id="tOtlist" class="table table-bordered">
    <thead>
      <tr>
      <th>Personnel Number</th>
      <th>Employee Name</th>
      <th>IC Number</th>
      <th>Staff ID</th>
      <th>Company Code</th>
      <th>Personnel Area</th>
      <th>Personnel Subarea</th>
      <th>State</th>
      <th>Region</th>
      <th>Employee Group</th>
      <th>Employee Subgroup</th>
      <th>Salary Exception</th>
      <th>Capping Salary (RM)</th>
      <th>Employment Status</th>
      <th>Reference Number</th>
      <th>OT Date</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Manual Flag</th>
      <th>Day Type</th>
      <th>Location</th>
      <th>Transaction Code</th>
      <th>Estimated Amount (RM)</th>
      <th>Claim Status</th>
      <th>Charge Type</th>
      <th>Number of Hours</th>
      <th>Number of Minutes</th>
      <th>Justification</th>
      <th>Application Date</th>
      <th>Verification Date</th>
      <th>Verified By</th>
      <th>Approval Date</th>
      <th>Approved By</th>
      <th>Queried Date</th>
      <th>Queried By</th>
      <th>Payment Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($otrep as $otr)
      <tr>
        <td>{{ $otr->mainOT->user_id }}</td>
        <td>{{ $otr->mainOT->URecord->name }}</td>
        <td>{{ $otr->mainOT->URecord->new_ic }}</td>
        <td>{{ $otr->mainOT->URecord->staffno }}</td>
        <td>{{ $otr->mainOT->company_id }}</td>
        <td>{{ $otr->mainOT->URecord->persarea }}</td>
        <td>{{ $otr->mainOT->URecord->perssubarea }}</td>
        <td>{{ $otr->mainOT->state_id }}</td>
        <td>{{ $otr->mainOT->region }}</td>
        <td>{{ $otr->mainOT->URecord->empgroup }}</td>
        <td>{{ $otr->mainOT->URecord->empsgroup }}</td>
      <td>
        @if( $otr->mainOT->URecord->ot_hour_exception == 'X')
        Yes
        @else
        No
        @endif
      </td>
      <td>
        @if( $otr->mainOT->URecord->ot_hour_exception == 'X')
        @else
        {{ $otr->mainOT->SalCap()->salary_cap }}
        @endif
      </td>
      <td>{{ $otr->mainOT->URecord->empstats }}</td>
      <td>{{ $otr->mainOT->refno }}</td>
      <td>{{ date('d-m-Y', strtotime($otr->mainOT->date)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->start_time)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->end_time)) }}</td>
      <td>{{ $otr->is_manual }}</td>
      <td>{{ $otr->mainOT->daytype_id }}</td>
      <td>({{ $otr->in_latitude }}, {{ $otr->in_longitude }})</td>
      <td>{{ $otr->mainOT->wage_type }}</td>
      <td>{{ $otr->amount }}</td>
      <td>{{ $otr->mainOT->OTStatus()->item3 }}</td>
      <td>{{ $otr->mainOT->charge_type }}</td>
      <td>{{ $otr->hour }}</td>
      <td>{{ $otr->minute }}</td>
      <td>{{ $otr->justification }}</td>
      <td>
        @if( $otr->mainOT->created_at == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->created_at)) }}
        @endif
      </td>
      <td>
        @if( $otr->mainOT->verification_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->verification_date)) }}
        @endif
      </td>
      <td>{{ $otr->mainOT->verifier_id }}</td>
      <td>
        @if( $otr->mainOT->approval_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->approval_date)) }}
        @endif
      </td>
      <td>{{ $otr->mainOT->approver_id }}</td>
      <td>
        @if( $otr->mainOT->queried_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->queried_date)) }}
        @endif
      </td>
      <td>{{ $otr->mainOT->querier_id }}</td>
      <td>
        @if( $otr->mainOT->payment_date == '')
        @else
        {{ date('d-m-Y', strtotime($otr->mainOT->payment_date)) }}
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

<script type="text/javascript">

$("#fcompany").on('click',function(){
  $('#addcompany').modal('show');
});

$("#fpersno").on('click',function(){
  $('#addpersno').modal('show');
});


/*------------------------------js company-------------------------------*/

for ( n=0; n<{{count($companies)}};n++){
$("#checkbox_"+n).change(check(n));
}

function check(n){
 return function(){
  if ($('#checkbox_'+n).is(':checked')) {
      $("#fcompany").val(function() {
          return this.value + $('#checkbox_'+n).val()+", ";
      });

      $("#fcompanydummy").text( $("#fcompanydummy").text()+ $('#checkbox_'+n).val()+", ");

  }else{
      var str = ($('#fcompany').val()).replace($('#checkbox_'+n).val()+", ",'');
      $('#fcompany').val(str);
      var str2 = ($('#fcompanydummy').text()).replace($('#checkbox_'+n).val()+", ",'');
      $('#fcompanydummy').text(str2);
  }
  }
}
</script>

<script type="text/javascript">
/*------------------------------js state-------------------------------*/
for ( s=0; s<{{count($states)}};s++){
$("#cbState_"+s).change(checkSt(s));
}

function checkSt(s){
 return function(){
  if ($('#cbState_'+s).is(':checked')) {
      $("#fstate").val(function() {
                    return this.value + $('#cbState_'+s).val()+", ";
                });

      $("#fstatedummy").text( $("#fstatedummy").text()+ $('#cbState_'+s).val()+", ");

  }else{
      var st = ($('#fstate').val()).replace($('#cbState_'+s).val()+", ",'');
      $('#fstate').val(st);
      var st2 = ($('#fstatedummy').text()).replace($('#cbState_'+s).val()+", ",'');
      $('#fstatedummy').text(st2);
  }
  }
}

/*------------------------------js region-------------------------------*/
for ( r=0; r<{{count($regions)}};r++){
$("#cbRegion_"+r).change(checkReg(r));
}

function checkReg(r){
 return function(){
  if ($('#cbRegion_'+r).is(':checked')) {
      $("#fregion").val(function() {
          return this.value + $('#cbRegion_'+r).val()+", ";
      });
      $("#fregiondummy").text( $("#fregiondummy").text()+ $('#cbRegion_'+r).val()+", ");
  }else{
      var reg = ($('#fregion').val()).replace($('#cbRegion_'+r).val()+", ",'');
      $('#fregion').val(reg);
      var reg2 = ($('#fregiondummy').text()).replace($('#cbRegion_'+r).val()+", ",'');
      $('#fregiondummy').text(reg2);
  }
  }
}

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
