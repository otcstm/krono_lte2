@extends('adminlte::page')

@section('title', 'Verifier Management')

@section('content')
<div class="row">
<div class="col-md-12">

  <div class="panel panel-default">
            <div class="panel-heading">            
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


<div class="row">
<div class="col-md-12">

  <div class="panel panel-default">
            <div class="panel-heading">            
         <h3 class="panel-title">Create Group Verifier</h3>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
<form action="{{ route('verifier.createGroup',[],false) }}">
@csrf
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupname">Group Name:</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="groupname" placeholder="Enter group name">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupcode">Group Code:</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="groupcode" placeholder="Enter group code">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd">Verifier Name:</label>
    <div class="col-sm-4">
      <select class="verifierListId form-control" name="verifierId"></select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary btn-outline">Create</button>
    </div>
  </div>
</form> 

            </div><!-- /.panel-body -->
          </div><!-- /.panel panel-info -->

</div><!-- /.col-md-12 -->
</div><!-- /.row -->            
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    
    $('#verifierList').DataTable({
    });

    $('.verifierListId').select2({
        placeholder: 'Type a name',
        minimumInputLength: 3,
        ajax: {
          url: '/admin/verifier/subordSearch',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.name+' ('+item.id+')',
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
      });

} );


</script>
@stop