@extends('adminlte::page')

@section('title', 'Verifier Management')

@section('content')
<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-heading">  
    Group Verifier
    </div><!--- .panel-heading --->
    <div class="panel-body">  
@if (session()->has('sysmsg_type'))
        <div class="alert alert-{{ session()->get('sysmsg_class') }} alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><i class="{{ session()->get('sysmsg_icon') }} "></i> {{ session()->get('sysmsg_text') }}</strong>
        </div>
@endif
<form class="form-horizontal" action="{{ route('verifier.updateGroup',[],false) }}" method="post">
@csrf 
<input name="gid" type="hidden" value="{{ $groupData->id }}">
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupcode">Group Code:</label>
    <div class="col-sm-4">
      <input name="groupcode" type="text" readonly="readonly" class="form-control" id="groupcode" placeholder="Enter group code" required
      value="{{ $groupData->group_code }}" maxlength="16">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupname">Group Name:</label>
    <div class="col-sm-4">
      <input name="groupname" type="text" class="form-control" id="groupname" placeholder="Enter group name" required
       value="{{ $groupData->group_name }}" maxlength="150">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="verifierid">Verifier Name:</label>
    <div class="col-sm-4">
      <select class="verifierListId form-control" name="verifierId" required>      
      <option selected="selected" value="{{ $groupData->verifier_id }}">{{ $verifierData->name }} ({{ $groupData->verifier_id }})</option>      
      </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary btn-outline">Update</button>
      <a href="{{ route('verifier.listGroup', [], false) }}"  class="btn btn-primary btn-outline">Back</a>
    </div>
  </div>
</form> 
    </div><!--- .panel-body --->
</div><!--- .panel panel-default --->

</div><!--- .row --->
</div><!--- .col-md-12 --->

<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-heading">  
    Group Members
    </div><!--- .panel-heading --->
    <div class="panel-body">  

    <div class="table-responsive">
    <table id="subord_group" class="table">
    <thead>
      <tr>
      <th></th>
      <th>Staff No</th>
      <th>Name</th>
      <th>Empsgroup</th>
      <th>Action</th>
      </tr>
    </thead>
    <tbody>
@php($rowcount = 0)    
@foreach($groupMember as $groupMember_row)
    <tr>
      <td>{{ $rowcount }}</td>
      <td>{{ $groupMember_row->staff_no }}</td>
      <td>{{ $groupMember_row->name }}</td>
      <td>{{ $groupMember_row->userRecordLatest->empsgroup }}</td>
      <td>
      <form action="{{ route('verifier.removeUser', [], false) }}" method="post"> 
      @csrf             
      <input type="hidden" name="user_id" value="{{ $groupMember_row->id }}">
<input type="hidden" name="group_id" value="{{ $groupData->id }}">
<button type="submit" class="btn btn-np" alt="Remove">
<i class="fas fa-user-minus"></i>
</button>
</form>      
      </td>
    </tr>
@php($rowcount++)     
@endforeach  
    </tbody>
    </table>
    </div>

    </div><!--- .panel-body --->
</div><!--- .panel panel-default --->

</div><!--- .row --->
</div><!--- .col-md-12 --->

<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-heading">  
    Subordinates without group
    </div><!--- .panel-heading --->
    <div class="panel-body">  

<div class="table-responsive">  
<table id="subord_nogroup" class="table">
    <thead>
      <tr>
      <th></th>
      <th>Staff No</th>
      <th>Name</th>
      <th>Empsgroup</th>
      <th>Action</th>
      </tr>
    </thead>
    <tbody>
@php($rowcount = 0)    
@foreach($freeMember as $freeMember_row)
@php($rowcount++)   
    <tr>
      <td>{{ $rowcount }}</td>
      <td>{{ $freeMember_row->staff_no }}</td>
      <td>{{ $freeMember_row->name }}</td>
      <td>{{ $freeMember_row->userRecordLatest->empsgroup }}</td>
      <td>
      <form action="{{ route('verifier.addUser', [], false) }}" method="post"> 
      @csrf             
      <input type="hidden" name="user_id" value="{{ $freeMember_row->id }}">
    <input type="hidden" name="group_id" value="{{ $groupData->id }}">
<button type="submit" class="btn btn-np" alt="Add">
<i class="fas fa-user-plus"></i></button>
</form>
      
      </td>
    </tr>  
@endforeach  
    </tbody>
    </table>
    </div>
    </div><!--- .panel-body --->
</div><!--- .panel panel-default --->

</div><!--- .row --->
</div><!--- .col-md-12 --->


@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {

    var t = $('#subord_nogroup').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    } );
 
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

var t = $('#subord_group').DataTable( {
    "columnDefs": [ {
        "searchable": false,
        "orderable": false,
        "targets": 0
    } ],
    "order": [[ 1, 'asc' ]]
} );

t.on( 'order.dt search.dt', function () {
    t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
} ).draw();

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