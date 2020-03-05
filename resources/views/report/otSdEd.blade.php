@extends('adminlte::page')
@section('title', 'Report')
@section('content')

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
    <input class="form-check-input-inline" type="checkbox" value="psarea" id="persarea" name="cbcol[]" >
    <label class="form-check-label" for="persarea"> Personnel Area  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="psbarea" id="persbarea" name="cbcol[]" >
    <label class="form-check-label" for="persbarea"> Personnel Subarea  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="state" id="st" name="cbcol[]" >
    <label class="form-check-label" for="st"> State  </label>
  </div>
  </div>
  <div class="col-sm-4">
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="region" id="reg" name="cbcol[]" >
    <label class="form-check-label" for="reg"> Region  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="empgrp" id="emgrp" name="cbcol[]" >
    <label class="form-check-label" for="emgrp"> Employee Group  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="empsubgrp" id="emsubgrp" name="cbcol[]" >
    <label class="form-check-label" for="emsubgrp"> Employee Subgroup  </label>
  </div>
  </div>
  <div class="col-sm-4">
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="dytype" id="dyty" name="cbcol[]" >
    <label class="form-check-label" for="dyty"> Day Type  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="loc" id="loctn" name="cbcol[]" >
    <label class="form-check-label" for="loctn"> Location</label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="claim" id="clm" name="cbcol[]" >
    <label class="form-check-label" for="clm"> Apply OT Claim</label>
  </div>
  </div>

  <div class="col-lg-12">  <BR>
  <div class="form-group text-center">
    <button type="submit" name="searching" value="excelSE" class="btn btn-primary">Download</button>
    <button type="submit" name="searching" value="StEt" class="btn btn-primary">View</button>
  </div>
  </div>
</form>
</div>
</div>

@stop
@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('.selectReport').select2();
});
</script>
@stop
