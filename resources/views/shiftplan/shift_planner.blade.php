@extends('adminlte::page')

@section('title', 'Shift Groups')

@section('content')
<h1>Shift Planner/Members Assignment</h1>
<div class="panel panel-default">
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <div class="table-responsive">
      <table id="grplist" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Code</th>
           <th>Name</th>
           <th>Owner</th>
           <th>Staff Count</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($p_list as $ap)
         <tr>
           <td>{{ $ap->group_code }}</td>
           <td>{{ $ap->group_name }}</td>
           <td>{{ $ap->Manager->name }}</td>
           <td>{{ $ap->Members->count() }}</td>
           <td>
             <form method="post" action="{{ route('shift.planner.del', [], false) }}" onsubmit='return confirm("Confirm delete?")'  class="text-center">
               @csrf
               <a href="{{ route('shift.planner.view', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-np" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
               <button type="submit" class="btn btn-np" title="Delete"><i class="fas fa-trash-alt"></i></button>
               <input type="hidden" name="id" value="{{ $ap->id }}" />
             </form>
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>

    <h4>Create Shift Planner</h4>
    <form action="{{ route('shift.planner.add', [], false) }}" method="post">
      @csrf
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('group_code') ? 'has-error' : '' }}">
            <label for="group_code">Group Code</label>
            <input id="group_code" type="text" name="group_code" class="form-control" value="{{ old('group_code') }}"
                   placeholder="Short name for this group" required maxlength="10">
            @if ($errors->has('group_code'))
                <span class="help-block">
                    <strong>{{ $errors->first('group_code') }}</strong>
                </span>
            @endif
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('group_name') ? 'has-error' : '' }}">
            <label for="group_name">Group Name</label>
            <input id="group_name" type="text" name="group_name" class="form-control" value="{{ old('group_name') }}"
                   placeholder="Some info about this group" required maxlength="200">
            @if ($errors->has('group_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('group_name') }}</strong>
                </span>
            @endif
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('owner_name') ? 'has-error' : '' }}">
            <label for="owner_name">Group Owner Name</label>
            <div class="row">
              <div class="col-xs-10">
                <input type="text" id="owner_name" name="owner_name" class="form-control" placeholder="Staff finder">
              </div>
              <div class="col-xs-2">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#sfresult"><i class="fas fa-search"></i></button>
              </div>
            </div>

          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('group_name') ? 'has-error' : '' }}">
            <label for="group_owner_id">Group Owner ID</label>
            <input id="group_owner_id" type="text" name="group_owner_id" class="form-control" value="{{ old('group_owner_id') }}"
                   placeholder="Search group owner name to populate" required readonly>
            @if ($errors->has('group_owner_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('group_owner_id') }}</strong>
                </span>
            @endif
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group text-center pull-right">
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </div>
      </div>
    </form>
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

  $('#grplist').DataTable({
    "responsive": "true"
  });

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
