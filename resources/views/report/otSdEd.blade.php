@extends('adminlte::page')
@section('title', 'Report')
@section('content')

<h1>List of Start/End OT Time Report</h1>
<div class="panel panel-default panel-main">
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
        <div class="col-sm-4">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="psarea" name="cbcol[]" id="choose-1" >
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
        </div>
        <div class="col-sm-4">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="region" name="cbcol[]"  id="choose-4">
          <label class="form-check-label" for="reg"> Region  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="empgrp" name="cbcol[]"  id="choose-5">
          <label class="form-check-label" for="emgrp"> Employee Group  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="empsubgrp" name="cbcol[]"  id="choose-6">
          <label class="form-check-label" for="emsubgrp"> Employee Subgroup  </label>
        </div>
        </div>
        <div class="col-sm-4">
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="dytype" name="cbcol[]" id="choose-7" >
          <label class="form-check-label" for="dyty"> Day Type  </label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="loc" name="cbcol[]"  id="choose-8">
          <label class="form-check-label" for="loctn"> Location</label>
        </div>
        <div class="form-check">
          <input class="form-check-input-inline" type="checkbox" value="claim"  name="cbcol[]"  id="choose-9" >
          <label class="form-check-label" for="clm"> Apply OT Claim</label>
        </div>
        </div>
      </div>
    </div>
  </div><div class="flexd">
      <div class="col-mx-5">
        <div>
          <p id="reportn-1">Personnel Area</p>
          <p id="reportn-2">Personnel Subarea</p>
          <p id="reportn-3">State</p>
          <p id="reportn-4">Region</p>
          <p id="reportn-5">Employee Group</p>
          <p id="reportn-6">Employee Subgroup</p>
          <p id="reportn-7">Day Type</p>
          <p id="reportn-8">Location</p>
          <p id="reportn-9">Apply OT Claim</p>
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
          <p id="reporty-7" class="hidden">Day Type</p>
          <p id="reporty-8" class="hidden">Location</p>
          <p id="reporty-9" class="hidden">Apply OT Claim</p>
        </div>
      </div>
    </div>
  </div>
  <div class="panel-footer text-right">

    <button type="submit" name="searching" value="excelSE" class="btn btn-primary btn-outline">DOWNLOAD REPORT</button>
    <button type="submit" name="searching" value="StEt" class="btn btn-primary">DISPLAY REPORT</button>
  </form>
  </div>
</div>

@stop
@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('.selectReport').select2();
});
var checkno = 0;
for(var i=0; i<28; i++){
  $("#reportn-"+i).on("click", clicked(i, "n"));
  $("#reporty-"+i).on("click", clicked(i, "y"));
}

function clicked(i, t){
  return function(){
    checkno = i;
    for(var n=0; n<28; n++){
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
  for(var n=0; n<28; n++){
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
  for(var n=0; n<28; n++){
    $("#reportn-"+n).removeClass("hidden");
    $("#reporty-"+n).addClass("hidden");
    $("#choose-"+n).prop('checked', false);
  }
  checkno = 0;
}
</script>
@stop
