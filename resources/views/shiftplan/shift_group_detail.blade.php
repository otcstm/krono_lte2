@extends('adminlte::page')

@section('title', 'Group Details')

@section('content')

<div class="row">
  <div class="col-md-12">
<h1>Assigned Work Schedule Rule</h1>
  </div>
<div class="col-md-12">
<div class="panel panel-default">
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif

    <div class="table-responsive">

      {{-- <h4>List assigned Work Schedule Rule</h4> --}}
      <table id="staffingrp" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Code</th>
           <th>Description</th>
           <th>Total Days</th>
           <th>Total Hours</th>
           <th>Remove?</th>
         </tr>
       </thead>
       <tbody>
         @foreach($groupd->shiftpatterns as $ap)
         <tr>
           <td class="text-left">{{ $ap->code }}</td>
           <td class="text-left">{{ $ap->description }}</td>
           <td class="text-left">{{ $ap->days_count }}</td>
           <td class="text-left">{{ $ap->total_hours }}</td>
           <td>
             <form action="{{ route('shift.group.del.sp', [], false) }}" method="post"  class="text-center">
               @csrf
               <input type="hidden" name="sp_id" value="{{ $ap->id }}" />
               <input type="hidden" name="group_id" value="{{ $groupd->id }}" />
               <button type="submit" class="btn btn-np"><i class="glyphicon glyphicon-minus-sign"></i></button>
             </form>
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>


    <div class="table-responsive">
      <h4>Available Work Schedule Rule </h4>
      <table id="staffnogrp" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Code</th>
           <th>Description</th>
           <th>Total Days</th>
           <th>Total Hours</th>
           <th>Add?</th>
         </tr>
       </thead>
       <tbody>
         @foreach($spattern as $ap)
         <tr>
           <td class="text-left">{{ $ap->code }}</td>
           <td class="text-left">{{ $ap->description }}</td>
           <td class="text-left">{{ $ap->days_count }}</td>
           <td class="text-left">{{ $ap->total_hours }}</td>
           <td>
             <form action="{{ route('shift.group.add.sp', [], false) }}" method="post" class="text-center">
               @csrf
               <input type="hidden" name="sp_id" value="{{ $ap->id }}" />
               <input type="hidden" name="group_id" value="{{ $groupd->id }}" />
               <button type="submit" class="btn btn-np "><i class="glyphicon glyphicon-plus-sign"></i></button>
             </form>
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
    <hr />
    <div class="pull-right">
    <a href="{{ route('shift.group', [], false) }}" class="btn btn-p btn-primary">Create</a>
    </div>
  </div>
</div>
</div>
</div>

<div class="row">
  <div class="col-md-12">
<h1>Shift Group Information</h1>
  </div>

  <div class="col-md-12">
<div class="panel panel-default">
  <div class="panel-heading">Shift Group Details</div>
  <div class="panel-body">
    <form action="{{ route('shift.group.edit', [], false) }}" method="post">
      @csrf
      <input type="hidden" name="id" value="{{ $groupd->id }}" />

      <row>
        <div class="col-sm-6">
          <div class="form-group has-feedback {{ $errors->has('group_code') ? 'has-error' : '' }}">
            <label for="group_code">Group Code</label>
            <input id="group_code" type="text" name="group_code" class="form-control" value="{{ $groupd->group_code }}" disabled>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group has-feedback {{ $errors->has('group_name') ? 'has-error' : '' }}">
            <label for="group_name">Group Name</label>
            <input id="group_name" type="text" name="group_name" class="form-control" value="{{ old('group_name', $groupd->group_name) }}"
                   placeholder="Some info about this group" required maxlength="200">
            @if ($errors->has('group_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('group_name') }}</strong>
                </span>
            @endif
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group has-feedback {{ $errors->has('owner_name') ? 'has-error' : '' }}">
            <label for="owner_name">Group Owner Name</label>
            <div class="row">
              <div class="col-xs-10">
                <input type="text" id="owner_name" name="owner_name" class="form-control" placeholder="Staff finder" value="{{ $groupd->Manager->name }}" />
              </div>
              <div class="col-xs-2">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#sfresult"><i class="fas fa-search"></i></button>
              </div>
            </div>

          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group has-feedback {{ $errors->has('group_name') ? 'has-error' : '' }}">
            <label for="group_owner_id">Group Owner ID</label>
            <input id="group_owner_id" type="text" name="group_owner_id" class="form-control" value="{{ old('group_owner_id', $groupd->manager_id) }}"
                   placeholder="Search group owner name to populate" required readonly>
            @if ($errors->has('group_owner_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('group_owner_id') }}</strong>
                </span>
            @endif
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </div>
      </row>
    </form>
  </div>
</div>
</div>
</div>



<div id="sfresult" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Search Result</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="stes" class="table table-hover table-bordered">
           <thead>
             <tr>
               <th>Staff No</th>
               <th>Name</th>
               <th>Choose</th>
             </tr>
           </thead>
           <tbody id="srbody">
             <tr>
               <td>s53877</td>
               <td>amer bin ahmad</td>
               <td><button type="button" class="btn btn-xs btn-success" title="Select"><i class="fas fa-plus"></i></button></td>
             </tr>
           </tbody>
         </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {

  $('#staffnogrp').DataTable();

  $('#staffingrp').DataTable();

  sresdt = $('#stes').DataTable({
    columns : [
      {data: 'staff_no'},
      {data: 'name'},
      {
        data: 'id',
        render: function(data, type, row){
          return '<button type="button" class="btn btn-xs btn-success" title="Select" onclick="selectOneStaff('+data+')"><i class="fas fa-plus"></i></button>';
        }
      }
    ]
  });

} );

function selectOneStaff(persno){
  document.getElementById('group_owner_id').value = persno;

  var search_url = "{{ route('shift.group.api.getname', ['uid' => '']) }}" + persno;

  $.ajax({
    url: search_url,
    success: function(result) {
      document.getElementById('owner_name').value = result;
    },
    error: function(xhr){
      alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });


  $('#sfresult').modal('hide');
}

$('#sfresult').on('show.bs.modal', function(e) {
  sresdt.clear();
  var search_url = "{{ route('shift.group.api.searchstaff', ['input' => '']) }}" + document.getElementById('owner_name').value;

  $.ajax({
    url: search_url,
    success: function(result) {
      sresdt.rows.add(result).draw();
    },
    error: function(xhr){
      alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });
});
</script>
@stop
