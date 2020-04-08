@extends('adminlte::page')
@section('css')

@section('title', 'Report')
@section('content')


<h1>Summary of Overtime Report</h1>
<div class="panel panel-default panel-main">
  <div class="panel panel-default" id="psearch">
    <div class="panel-heading"><strong>Report : Summary of Overtime</strong></div>
    <div class="panel-body">
      <form action="{{ route('rep.viewOT', [], false) }}" method="post">
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
      <div class="col-lg-6">
        <div class="form-group">
          <label for="fpersno">Persno </label>
          <input type="text" class="form-control" id="fpersno" name="fpersno" placeholder="Use commas to search multiple persno, e.g. 30013,45450,38884">

        </div>
      </div>
      <div class="col-lg-12">
        <div class="form-group">
          <label for="fcompany">Company Code</label>
          <br>
          <select class="selectReport form-control" name="fcompany[]" multiple="multiple">
            @if($companies ?? '')
                @foreach($companies as $no=>$company)
          <option value="{{$company->id}}">{{$company->id}}-{{$company->company_descr}}</option>
                @endforeach
            @endif
          </select>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="form-group">
          <label for="fstate">State</label>
          <br>
          <select class="selectReport form-control" name="fstate[]" multiple="multiple">
            @if($states ?? '')
                @foreach($states as $no=>$state)
          <option value="{{$state->id}}">{{$state->id}}-{{$state->state_descr}}</option>
                @endforeach
            @endif
          </select>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="form-group">
          <label for="fregion">Region</label>
          <br>
          <select class="selectReport form-control" name="fregion[]" multiple="multiple">
            @if($regions ?? '')
                @foreach($regions as $no=>$region)
          <option value="{{$region->item2}}">{{$region->item3}}</option>
                @endforeach
            @endif
          </select>
        </div>
      </div>
    <div class="hidden">
      <div class="col-lg-12">
      <!-- <div class="form-group"> -->
        <div class="form-check" >
          <label for="cball">Select Column :</label>
          <input type="checkbox" id="cball" onclick="for(c in document.getElementsByName('cbcol[]')) document.getElementsByName('cbcol[]').item(c).checked = this.checked">
          <label class="form-check-label" for="cball" >  All
          </label>

        </div>
      </div>
      <!-- </div> -->

      <!-- <div class="col-sm-3">
        <div class="form-check" >
        <input type="checkbox" id="cball" onclick="for(c in document.getElementsByName('cbcol[]')) document.getElementsByName('cbcol[]').item(c).checked = this.checked">
        <label class="form-check-label" for="cball" > Select All
        </label>
      </div> -->
      <div class="col-sm-3">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="psarea" name="cbcol[]"  id="choose-1">
          <label class="form-check-label" for="persarea"> Personnel Area  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="psbarea" name="cbcol[]"  id="choose-2">
          <label class="form-check-label" for="persbarea"> Personnel Subarea  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="state" name="cbcol[]"  id="choose-3">
          <label class="form-check-label" for="st"> State  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="region" name="cbcol[]"  id="choose-4">
          <label class="form-check-label" for="reg"> Region  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="empgrp" name="cbcol[]"  id="choose-5">
          <label class="form-check-label" for="emgrp"> Employee Group  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="empsubgrp"  name="cbcol[]"  id="choose-6">
          <label class="form-check-label" for="emsubgrp"> Employee Subgroup  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="salexp" name="cbcol[]"  id="choose-7">
          <label class="form-check-label" for="salexcp"> Salary Exception  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="capsal" name="cbcol[]"  id="choose-8">
          <label class="form-check-label" for="capsalry"> Capping Salary (RM)  </label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="empst" name="cbcol[]"  id="choose-9">
          <label class="form-check-label" for="empstt"> Employment Status  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="dytype"  name="cbcol[]"  id="choose-10">
          <label class="form-check-label" for="dyty"> Day Type  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="trnscd"  name="cbcol[]"  id="choose-11">
          <label class="form-check-label" for="trncd"> Transaction Code  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="estamnt" name="cbcol[]"  id="choose-12">
          <label class="form-check-label" for="estamt"> Estimated Amount  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="clmstatus" name="cbcol[]"  id="choose-13">
          <label class="form-check-label" for="clmst"> Claim Status  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="chrtype" name="cbcol[]"  id="choose-14">
          <label class="form-check-label" for="chtype"> Charge Type </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="bodycc" name="cbcol[]"  id="choose-15">
          <label class="form-check-label" for="bdcc"> Body Cost Center </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="othrcc" name="cbcol[]"  id="choose-16">
          <label class="form-check-label" for="occ"> Other Cost Center </label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="prtype" name="cbcol[]"  id="choose-17">
          <label class="form-check-label" for="ptype"> Project Type </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="pnumbr" name="cbcol[]"  id="choose-18">
          <label class="form-check-label" for="pnum"> Project Number </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="ntheadr" name="cbcol[]" id="choose-19" >
          <label class="form-check-label" for="nthd"> Network Header </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="ntact" name="cbcol[]"  id="choose-20">
          <label class="form-check-label" for="ntactv"> Network Activity </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="ordnum" name="cbcol[]" id="choose-21" >
          <label class="form-check-label" for="ornum"> Order Number </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="tthour" name="cbcol[]"  id="choose-22">
          <label class="form-check-label" for="tthr"> Total Hours </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="ttlmin" name="cbcol[]" id="choose-23" >
          <label class="form-check-label" for="ttmin"> Total Minutes </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="appdate" name="cbcol[]"  id="choose-24">
          <label class="form-check-label" for="appdt"> Application Date </label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="verdate" name="cbcol[]"  id="choose-25">
          <label class="form-check-label" for="verdt"> Verification Date </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="verid" name="cbcol[]"  id="choose-26">
          <label class="form-check-label" for="ver"> Verifier </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="appdate" name="cbcol[]" id="choose-27" >
          <label class="form-check-label" for="appdt"> Approval Date </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="apprvrid" name="cbcol[]"  id="choose-28">
          <label class="form-check-label" for="apprvr"> Approver </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="qrdate" name="cbcol[]"  id="choose-29">
          <label class="form-check-label" for="qrdt"> Queried Date </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="qrdby" name="cbcol[]"  id="choose-30">
          <label class="form-check-label" for="qrby"> Queried By </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="pydate" name="cbcol[]"  id="choose-31">
          <label class="form-check-label" for="pydt"> Payment Date </label>
        </div>
      </div>
    </div>
      <!-- <div class="col-lg-12">  <BR>
      <div class="form-group text-center">
        <button type="submit" name="searching" value="excelm" class="btn btn-primary">Download</button>
        <button type="submit" name="searching" value="main" class="btn btn-primary">View</button>
      </div>
      </div> -->
    </div>
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
          <p id="reportn-10">Day Type</p>
          <p id="reportn-11">Transaction Code</p>
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
          <p id="reportn-22">Total Hours</p>
          <p id="reportn-23">Total Minutes</p>
          <p id="reportn-24">Application Date</p>
          <p id="reportn-25">Verification Date</p>
          <p id="reportn-26">Verifier</p>
          <p id="reportn-27">Approval Date</p>
          <p id="reportn-28">Approver</p>
          <p id="reportn-29">Queried Date</p>
          <p id="reportn-30">Queried By</p>
          <p id="reportn-31">Payment Date </p>
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
          <p id="reporty-10" class="hidden">Day Type</p>
          <p id="reporty-11" class="hidden">Transaction Code</p>
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
          <p id="reporty-22" class="hidden">Total Hours</p>
          <p id="reporty-23" class="hidden">Total Minutes</p>
          <p id="reporty-24" class="hidden">Application Date</p>
          <p id="reporty-25" class="hidden">Verification Date</p>
          <p id="reporty-26" class="hidden">Verifier</p>
          <p id="reporty-27" class="hidden">Approval Date</p>
          <p id="reporty-28" class="hidden">Approver</p>
          <p id="reporty-29" class="hidden">Queried Date</p>
          <p id="reporty-30" class="hidden">Queried By</p>
          <p id="reporty-31" class="hidden">Payment Date </p>
        </div>
      </div>
    </div>
  </div>
    <div class="panel-footer text-right">

      <button type="submit" name="searching" value="excelm" class="btn btn-primary btn-outline">DOWNLOAD REPORT</button>
      <button type="submit" name="searching" value="main" class="btn btn-primary">DISPLAY REPORT</button>
      </form>
    </div>
</div>

@stop
@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('.selectReport').select2({
      closeOnSelect: false
    });
});


var checkno = 0;
for(var i=0; i<32; i++){
  $("#reportn-"+i).on("click", clicked(i, "n"));
  $("#reporty-"+i).on("click", clicked(i, "y"));
}

function clicked(i, t){
  return function(){
    checkno = i;
    for(var n=0; n<32; n++){
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
  for(var n=0; n<32; n++){
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
  for(var n=0; n<32; n++){
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
