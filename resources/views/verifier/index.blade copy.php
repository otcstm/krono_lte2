@extends('adminlte::page')

@section('title', 'Verifier Management')

@section('content')
     <!-- <div class="panel panel-default">
            <div class="panel-heading with-border">
              <h3 class="panel-title">Staff</h3>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

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
          </div> -->

<div class="row">
<div class="col-md-12">

  <div class="panel panel-default">
            <div class="panel-heading with-border">            
         <h3 class="panel-title pull-left">Verifier
            </h3>
<form action="{{ route('verifier.create',[],false) }}">
@csrf
<input type="hidden" name="uid" id="uid" value="{{ $userdata->id }}" />
<button type="submit" name="submit" class="btn btn-xs btn-primary pull-right">
  <i class="fa fa-plus"></i> Add Verifier</button>
</form> 
        <div class="clearfix"></div>

            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

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
@foreach($verifierGroups as $row_verifierGroups)
    <tr>
      <td>{{ $rowcount }}</td>
      <td>{{ $row_verifierGroups->name }}</td>
      <td>{{ $row_verifierGroups->companyid->company_descr }}</td>
      <td>{{ $row_verifierGroups->tblUserRecord_userid->empsgroup }}</td>
    </tr>
@php($rowcount++)     
@endforeach   

  </tbody>
</table>
<!-- </div> -->


            </div><!-- /.panel-body -->
          </div><!-- /.panel panel-info -->

</div><!-- /.col-md-12 -->
</div><!-- /.row -->

            
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