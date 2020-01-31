@extends('adminlte::page')

@section('title', 'Verifier Management')

@section('content')
     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Staff</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

<table class="table">
<tr>
<th>Name:</th>
<td>{{ $userdata->name }} ({{ $userdata->id }})</td>
</tr>
<tr>
<th>Employee Sub Group:</th>
<td>{{ $userdata->userrecordLatest->empsgroup }}</td>
</tr>
<tr>
<th>Company:</th>
<td>{{ $userdata->companyid->company_descr }}</td>
</tr>
</table>

            </div>
          </div>

<div class="row">
<div class="col-md-12">

  <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Verifier</h3>

<div class="box-tools pull-right">
<form action="{{ route('verifier.create',[],false) }}" style="display:inline; float:right">
@csrf
<input type="hidden" name="uid" id="uid" value="{{ $userdata->id }}" />
<button type="submit" name="submit" class="btn btn-xs btn-default">
  <i class="fa fa-plus"></i> Add Verifier</button>
</form> 
</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

<!-- <div class="table-responsive"> -->
<table id="verifierList" class="table">
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Company</th>
      <th>Empsgroup</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody>

@php($rowcount = 0)    
@foreach ($verifiers as $row_verifier)
    <tr>
      <td>{{ $rowcount }}</td>
      <td>{{ $row_verifier->name }}</td>
      <td>{{ $row_verifier->companyid->company_descr }}</td>
      <td>{{ $row_verifier->tblUserRecord_userid->empsgroup }}</td>
    </tr>
@php($rowcount++)     
@endforeach   

  </tbody>
</table>
<!-- </div> -->


            </div><!-- /.box-body -->
          </div><!-- /.box box-info -->

</div><!-- /.col-md-12 -->
</div><!-- /.row -->

<div class="row">
<div class="col-md-12">

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Subordinates</h3>
            </div>

            <div class="box-body">

<div class="table-responsive">
<table id="subsList" class="table">
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Company</th>
      <th>Empsgroup</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody>
@php($rowcount = 0)    
@foreach ($subordinates as $row_subordinate)
    <tr>
      <td>{{ $rowcount }}</td>
      <td>{{ $row_subordinate->name }}</td>
      <td>{{ $row_subordinate->companyid->company_descr }}</td>
      <td>{{ $row_subordinate->tblUserRecord_userid->empsgroup }}</td>
    </tr>
@php($rowcount++)     
@endforeach   
  </tbody>
</table>
</div>
            
            </div>
          </div>  

</div> 
</div>                
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#userList').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]]
    });
    
    $('#verifierList').DataTable({
    });
    
    $('#subsList').DataTable({
    });
} );

</script>
@stop