@extends('adminlte::page')
@section('title', 'Report')
@section('content')

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

  <div class="col-lg-12">
    <div class="form-check" >
    <label for="cball">Select Column :</label>
    <input type="checkbox" id="cball" onclick="for(c in document.getElementsByName('cbcol[]')) document.getElementsByName('cbcol[]').item(c).checked = this.checked">
    <label class="form-check-label" for="cball" >  All
    </label>
  </div>

  <div class="col-sm-3">
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
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="salexp" id="salexcp" name="cbcol[]" >
    <label class="form-check-label" for="salexcp"> Salary Exception  </label>
  </div>
  </div>
  <div class="col-sm-3">
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="capsal" id="capsalry" name="cbcol[]" >
    <label class="form-check-label" for="capsalry"> Capping Salary (RM)  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="empst" id="empstt" name="cbcol[]" >
    <label class="form-check-label" for="empstt"> Employment Status  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="mflag" id="mflg" name="cbcol[]" >
    <label class="form-check-label" for="mflg"> Manual Flag </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="dytype" id="dyty" name="cbcol[]" >
    <label class="form-check-label" for="dyty"> Day Type  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="loc" id="loctn" name="cbcol[]" >
    <label class="form-check-label" for="loctn"> Location</label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="trnscd" id="trncd" name="cbcol[]" >
    <label class="form-check-label" for="trncd"> Transaction Code  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="estamnt" id="estamt" name="cbcol[]" >
    <label class="form-check-label" for="estamt"> Estimated Amount  </label>
  </div>
  </div>
  <div class="col-sm-3">
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="clmstatus" id="clmst" name="cbcol[]" >
    <label class="form-check-label" for="clmst"> Claim Status  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="chrtype" id="chtype" name="cbcol[]" >
    <label class="form-check-label" for="chtype"> Charge Type </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="noh" id="numoh" name="cbcol[]" >
    <label class="form-check-label" for="numoh"> Number of Hours </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="nom" id="numom" name="cbcol[]" >
    <label class="form-check-label" for="numom"> Number of Minutes </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="jst" id="just" name="cbcol[]" >
    <label class="form-check-label" for="just"> Justification </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="appdate" id="appdt" name="cbcol[]" >
    <label class="form-check-label" for="appdt"> Application Date </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="verdate" id="verdt" name="cbcol[]" >
    <label class="form-check-label" for="verdt"> Verification Date </label>
  </div>
  </div>
  <div class="col-sm-3">
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="verid" id="ver" name="cbcol[]" >
    <label class="form-check-label" for="ver"> Verifier </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="appdate" id="appdt" name="cbcol[]" >
    <label class="form-check-label" for="appdt"> Approval Date </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="apprvrid" id="apprvr" name="cbcol[]" >
    <label class="form-check-label" for="apprvr"> Approver </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="qrdate" id="qrdt" name="cbcol[]" >
    <label class="form-check-label" for="qrdt"> Queried Date </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="qrdby" id="qrby" name="cbcol[]" >
    <label class="form-check-label" for="qrby"> Queried By </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="pydate" id="pydt" name="cbcol[]" >
    <label class="form-check-label" for="pydt"> Payment Date </label>
  </div>
  </div>

  <div class="col-lg-12">
  <div class="form-group text-center"><br>
    <button type="submit" name="searching" value="exceld" class="btn btn-primary">Download</button>
    <button type="submit" name="searching" value="detail" class="btn btn-primary">View</button>
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
