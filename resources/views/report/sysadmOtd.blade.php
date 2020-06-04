@extends('adminlte::page')
@section('title', 'Report')
@section('content')

<h1>Overtime Details Report</h1>
<div class="panel panel-default panel-main">
<div class="panel panel-default">
<div class="panel-heading"><strong>Select Report Parameter</strong></div>
<div class="panel-body">
  <form action="{{ route('rep.sa.OTd', [], false) }}" method="GET">

    <div class="row">
      <div class="col-md-6">
        <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="fpersno">Refno</label>
        </div>
        <div class="col-md-9">
          <input type="text" class="form-control" id="frefno" name="frefno">
        </div>
      </div>
        <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="fcompany">Company Code</label>
        </div>
        <div class="col-md-9">
          <select class="selectReport form-control" name="fcompany[]" multiple="multiple">
            @if($companies ?? '')
                @foreach($companies as $no=>$company)
          <option value="{{$company->id}}">{{$company->id}}-{{$company->company_descr}}</option>
                @endforeach
            @endif
          </select>
        </div>
      </div>
        <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="fstate">State</label>
        </div>
        <div class="col-md-9">
          <select class="selectReport form-control" name="fstate[]" multiple="multiple">
            @if($states ?? '')
                @foreach($states as $no=>$state)
          <option value="{{$state->id}}">{{$state->id}}-{{$state->state_descr}}</option>
                @endforeach
            @endif
          </select>
        </div>
      </div>
        <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
           <label for="fdate">Overtime Date</label>
        </div>
        <div class="col-md-4">
          <input type="date" class="form-control" id="fdate" name="fdate" required autofocus>
        </div>
        <div class="col-md-1">
           <label for="fdate">To</label>
        </div>
        <div class="col-md-4">
          <input type="date" class="form-control"  id="tdate" name="tdate"  required autofocus>
        </div>
      </div>

      </div>
      <div class="col-md-6">
        <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="fpersno">Persno</label>
        </div>
        <div class="col-md-9">
          <input type="text" class="form-control" id="fpersno" name="fpersno" placeholder="Use commas to search multiple persno, e.g. 30013,45450,38884">
        </div>
      </div>
        <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="fapprover_id">Approver ID</label>
        </div>
        <div class="col-md-9">
          <input type="text" class="form-control" id="fapprover_id" name="fapprover_id">
        </div>
      </div>
        <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="fverifier_id">Verifier ID</label>
        </div>
        <div class="col-md-9">
          <input type="text" class="form-control" id="fverifier_id" name="fverifier_id">
        </div>
      </div>
        <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="fregion">Region</label>
        </div>
        <div class="col-md-9">
          <select class="selectReport form-control" name="fregion[]" multiple="multiple">
            @if($regions ?? '')
                @foreach($regions as $no=>$region)
          <option value="{{$region->item2}}">{{$region->item3}}</option>
                @endforeach
            @endif
          </select>
        </div>
      </div>

      </div>
    </div>
    <div class="hidden">
      <div class="col-sm-3">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="psarea" id="choose-1" name="cbcol[]" >
          <label class="form-check-label" for="persarea"> Personnel Area  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="psbarea" id="choose-2" name="cbcol[]" >
          <label class="form-check-label" for="persbarea"> Personnel Subarea  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="state" id="choose-3" name="cbcol[]" >
          <label class="form-check-label" for="st"> State  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="region" id="choose-4" name="cbcol[]" >
          <label class="form-check-label" for="reg"> Region  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="empgrp" id="choose-5" name="cbcol[]" >
          <label class="form-check-label" for="emgrp"> Employee Group  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="empsubgrp" id="choose-6" name="cbcol[]" >
          <label class="form-check-label" for="emsubgrp"> Employee Subgroup  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="salexp" id="choose-7" name="cbcol[]" >
          <label class="form-check-label" for="salexcp"> Salary Exception  </label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="capsal" id="choose-8" name="cbcol[]" >
          <label class="form-check-label" for="capsalry"> Capping Salary (RM)  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="empst" id="choose-9" name="cbcol[]" >
          <label class="form-check-label" for="empstt"> Employment Status  </label>
        </div>

        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="mflag" id="choose-10" name="cbcol[]" >
          <label class="form-check-label" for="mflg"> Manual Flag </label>
        </div>

        <div class="form-check">
           <input class="form-check-input-inline" type="checkbox" value="loc" id="choose-11" name="cbcol[]" >
           <label class="form-check-label" for="loctn"> Location</label>
         </div>

        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="estamnt" id="choose-12" name="cbcol[]" >
          <label class="form-check-label" for="estamt"> Estimated Amount  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="clmstatus" id="choose-13" name="cbcol[]" >
          <label class="form-check-label" for="clmst"> Claim Status  </label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="chrtype" id="choose-14" name="cbcol[]" >
          <label class="form-check-label" for="chtype"> Charge Type </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="bodycc" id="choose-15" name="cbcol[]" >
          <label class="form-check-label" for="dyty"> Body Cost Center </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="othrcc" id="choose-16" name="cbcol[]" >
          <label class="form-check-label" for="trncd"> Other Cost Center </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="prtype" id="choose-17" name="cbcol[]" >
          <label class="form-check-label" for="dyty"> Project Type </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="pnumbr" id="choose-18" name="cbcol[]" >
          <label class="form-check-label" for="dyty"> Project Number </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="ntheadr" id="choose-19" name="cbcol[]" >
          <label class="form-check-label" for="trncd"> Network Header </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="ntact" id="choose-20" name="cbcol[]" >
          <label class="form-check-label" for="trncd"> Network Activity</label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="ordnum" id="choose-21" name="cbcol[]" >
          <label class="form-check-label" for="trncd"> Order Number</label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="noh" id="choose-22" name="cbcol[]" >
          <label class="form-check-label" for="numoh"> Number of Hours </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="nom" id="choose-23" name="cbcol[]" >
          <label class="form-check-label" for="numom"> Number of Minutes </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="jst" id="choose-24" name="cbcol[]" >
          <label class="form-check-label" for="just"> Justification </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="appdate" id="choose-25" name="cbcol[]" >
          <label class="form-check-label" for="appdt"> Application Date </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="verdate" id="choose-26" name="cbcol[]" >
          <label class="form-check-label" for="verdt"> Verification Date </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="verid" id="choose-27" name="cbcol[]" >
          <label class="form-check-label" for="ver"> Verifier ID</label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="vername" id="choose-28" name="cbcol[]" >
          <label class="form-check-label" for="vernm"> Verifier Name</label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="vercocd" id="choose-29" name="cbcol[]" >
          <label class="form-check-label" for="vercd"> Verifier Cocd</label>
        </div>

        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="aprvdate" id="choose-30" name="cbcol[]" >
          <label class="form-check-label" for="appdt"> Approval Date </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="apprvrid" id="choose-31" name="cbcol[]" >
          <label class="form-check-label" for="apprvr"> Approver ID</label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="apprvrname" id="choose-32" name="cbcol[]" >
          <label class="form-check-label" for="apprvrnm"> Approver Name</label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="apprvrcocd" id="choose-33" name="cbcol[]" >
          <label class="form-check-label" for="apprvrcd"> Approver Cocd</label>
        </div>

        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="qrdate" id="choose-34" name="cbcol[]" >
          <label class="form-check-label" for="qrdt"> Queried Date </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="qrdby" id="choose-35" name="cbcol[]" >
          <label class="form-check-label" for="qrby"> Queried By </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="pydate" id="choose-36" name="cbcol[]" >
          <label class="form-check-label" for="pydt"> Payment Date </label>
        </div>

        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="trnscd" id="choose-37" name="cbcol[]" >
          <label class="form-check-label" for="trncd"> Transaction Code  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="dytype" id="choose-38" name="cbcol[]" >
          <label class="form-check-label" for="dyty"> Day Type  </label>
        </div>
      </div>

    </div>
  </div>
<br>
  <div class="flexd">
  <div class="col-mx-5">
    <div>
        <p id="reportn-1">Personnel Area</p>
        <p id="reportn-2">Personnel Subarea</p>
        <p id="reportn-3">State</p>
        <p id="reportn-4">Region</p>
        <p id="reportn-5">Employee Group</p>
        <p id="reportn-6">Employee Subgroup</p>
        <p id="reportn-7">Salary Exception</p>
        <p id="reportn-8">Capping Salary (RM)</p>
        <p id="reportn-9">Employment Status</p>
        <p id="reportn-10">Manual Flag</p>
        <p id="reportn-11">Location</p>
        <p id="reportn-12">Estimated Amount</p>
        <p id="reportn-13">Claim Status</p>
        <p id="reportn-14">Charge Type</p>
        <p id="reportn-15">Body Cost Center</p>
        <p id="reportn-16">Other Cost Center</p>
        <p id="reportn-17">Project Type</p>
        <p id="reportn-18">Project Number</p>
        <p id="reportn-19">Network Header</p>
        <p id="reportn-20">Network Activity</p>
        <p id="reportn-21">Order Number</p>
        <p id="reportn-22">Number of Hours</p>
        <p id="reportn-23">Number of Minutes</p>
        <p id="reportn-24">Justification</p>
        <p id="reportn-25">Application Date</p>
        <p id="reportn-26">Verification Date</p>
        <p id="reportn-27">Verifier ID</p>
        <p id="reportn-28">Verifier Name</p>
        <p id="reportn-29">Verifier Cocd</p>
        <p id="reportn-30">Approval Date</p>
        <p id="reportn-31">Approver ID</p>
        <p id="reportn-32">Approver Name</p>
        <p id="reportn-33">Approver Cocd</p>
        <p id="reportn-34">Queried Date</p>
        <p id="reportn-35">Queried By</p>
        <p id="reportn-36">Payment Date</p>
        <p id="reportn-37">Transaction Code</p>
        <p id="reportn-38">Day Type</p>

    </div>
  </div>
  <div class="col-mx-2">
    <div>
      <button class="btn-gray" type="button" onclick="return add()">ADD<i class="fas fa-caret-right"></i></button>
      <button class="btn-gray" type="button" onclick="return addall()">ADD ALL<i class="fas fa-caret-right"></i></button>
      <p style="height: 20px"></p>
      <button class="btn-gray" type="button" onclick="return remove()">REMOVE<i class="fas fa-caret-left"></i></button>
      <button class="btn-gray" type="button" onclick="return removeall()">REMOVE ALL<i class="fas fa-caret-left"></i></button>
    </div>
  </div>
  <div class="col-mx-5">
    <div>
      <p id="reporty-1" class="hidden">Personnel Area</p>
      <p id="reporty-2" class="hidden">Personnel Subarea</p>
      <p id="reporty-3" class="hidden">State</p>
      <p id="reporty-4" class="hidden">Region</p>
      <p id="reporty-5" class="hidden">Employee Group</p>
      <p id="reporty-6" class="hidden">Employee Subgroup</p>
      <p id="reporty-7" class="hidden">Salary Exception</p>
      <p id="reporty-8" class="hidden">Capping Salary (RM)</p>
      <p id="reporty-9" class="hidden">Employment Status</p>
      <p id="reporty-10" class="hidden">Manual Flag</p>
      <p id="reporty-11" class="hidden">Location</p>
      <p id="reporty-12" class="hidden">Estimated Amount</p>
      <p id="reporty-13" class="hidden">Claim Status</p>
      <p id="reporty-14" class="hidden">Charge Type</p>
      <p id="reporty-15" class="hidden">Body Cost Center</p>
      <p id="reporty-16" class="hidden">Other Cost Center</p>
      <p id="reporty-17" class="hidden">Project Type</p>
      <p id="reporty-18" class="hidden">Project Number</p>
      <p id="reporty-19" class="hidden">Network Header</p>
      <p id="reporty-20" class="hidden">Network Activity</p>
      <p id="reporty-21" class="hidden">Order Number</p>
      <p id="reporty-22" class="hidden">Number of Hours</p>
      <p id="reporty-23" class="hidden">Number of Minutes</p>
      <p id="reporty-24" class="hidden">Justification</p>
      <p id="reporty-25" class="hidden">Application Date</p>
      <p id="reporty-26" class="hidden">Verification Date</p>
      <p id="reporty-27" class="hidden">Verifier ID</p>
      <p id="reporty-28" class="hidden">Verifier Name</p>
      <p id="reporty-29" class="hidden">Verifier Cocd</p>
      <p id="reporty-30" class="hidden">Approval Date</p>
      <p id="reporty-31" class="hidden">Approver ID</p>
      <p id="reporty-32" class="hidden">Approver Name</p>
      <p id="reporty-33" class="hidden">Approver Cocd</p>
      <p id="reporty-34" class="hidden">Queried Date</p>
      <p id="reporty-35" class="hidden">Queried By</p>
      <p id="reporty-36" class="hidden">Payment Date</p>
      <p id="reporty-37" class="hidden">Transaction Code</p>
      <p id="reporty-38" class="hidden">Day Type</p>
    </div>
  </div>
</div>

</div>
<div class="panel-footer text-right">
  <button type="submit" name="searching" value="gexceld" class="btn btn-primary">GENERATE REPORT</button>
</form>
</div>
</div>


<div class="panel panel-default" id="lofhis">
<div class="panel-heading"><strong>Reports History</strong></div>
<div class="panel-body">
  <div class="table-responsive">
  <table id="repothist" class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th scope="col">Submit Date</th>
        <th scope="col">Group</th>
        <th scope="col">Status</th>
        <th scope="col">Data From</th>
        <th scope="col">Data To</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($history as $his)
      <tr>
        <td>{{ $his->created_at }}</td>
        <td>{{ $his->job_type }}</td>
        <td>{{ $his->status }}</td>
        <td>{{ $his->from_date }}</td>
        <td>{{ $his->to_date }}</td>
        @if($his->status == 'Completed')
        <td><form action="{{ route('rep.sa.dOT', [], false)}}"
          method="post">
          <input type="hidden" name="bjid" value="{{ $his->id }}" />
          @csrf
          <button type="submit" class="btn btn-sm btn-info" title="Download"><i class="fa fa-download"></i></button>
        </form></td>
        @else
        <td>...</td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
</div>

</div>


@stop
@section('js')
<script type="text/javascript">

$(document).ready(function() {
    $('#repothist').DataTable({
        "responsive": "true",
        "order" : [[0, "desc"]],

    });
});

$(document).ready(function() {
    $('.selectReport').select2({
      closeOnSelect: false
    });
});

var checkno = 0;
for(var i=0; i<39; i++){
  $("#reportn-"+i).on("click", clicked(i, "n"));
  $("#reporty-"+i).on("click", clicked(i, "y"));
}

function clicked(i, t){
  return function(){
    checkno = i;
    for(var n=0; n<39; n++){
      $("#reportn-"+n).removeClass("click");
      $("#reporty-"+n).removeClass("click");
    }

    if(t=="n"){
      $("#reportn-"+i).addClass("click");
    }else{
      $("#reporty-"+i).addClass("click");
    }
  }
}

function add(){
  $("#reportn-"+checkno).addClass("hidden");
  $("#reporty-"+checkno).removeClass("hidden");
  $("#choose-"+checkno).prop('checked', true);
  checkno = 0;
}

function addall(){
  for(var n=0; n<39; n++){
    $("#reportn-"+n).addClass("hidden");
    $("#reporty-"+n).removeClass("hidden");
    $("#choose-"+n).prop('checked', true);
  }
  checkno = 0;
}

function remove(){
  $("#reportn-"+checkno).removeClass("hidden");
  $("#reporty-"+checkno).addClass("hidden");
  $("#choose-"+checkno).prop('checked', false);
  checkno = 0;
}

function removeall(){
  for(var n=0; n<39; n++){
    $("#reportn-"+n).removeClass("hidden");
    $("#reporty-"+n).addClass("hidden");
    $("#choose-"+n).prop('checked', false);
  }
  checkno = 0;
}

</script>
<script>
$(function() {
$('#multiselect').multiselect();});
</script>
@stop
