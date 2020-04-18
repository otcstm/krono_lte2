@extends('adminlte::page')

@section('title', 'Shift Planner/Member Assignment')

@section('content')
<h1>Shift Planner/Member Assignment</h1>
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
           <th>Planner</th>
           <th>Staff Count</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($p_list as $ap)
         <tr>
           <td>{{ $ap->group_code }}</td>
           <td>{{ $ap->group_name }}</td>
           <td>
           @if($ap->Planner)
           {{ $ap->Planner->name }}
           @endif
           </td>
           <td>{{ $ap->Members->count() }}</td>
           <td>
             <a href="{{ route('shift.mygroup', ['sgid' => $ap->id], false) }}"><button type="button" class="btn btn-np" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>

@if(request()->get('sgid'))
<br />
<h4>Create Shift Planner</h4> {{-- {{ $grp->group_name }} --}}
<form id="fAssignPlanner" action="{{ route('shift.mygroup.setplanner', [], false) }}" method="post">
  @csrf
      <input type="hidden" name="sgid" value="{{ $grp->id }}" />
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('group_code') ? 'has-error' : '' }}">
            <label for="group_code">Group Code</label>
            <input id="group_code" type="text" class="form-control" value="{{ $grp->group_code }}" readonly>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('group_name') ? 'has-error' : '' }}">
            <label for="group_name">Group Name</label>
            <input id="group_name" type="text" class="form-control" value="{{ $grp->group_name }}" readonly>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('owner_name') ? 'has-error' : '' }}">
            <label for="planner_name">Shift Planner Name</label>
            <div class="row">
              <div class="col-xs-10">
                <input type="text" id="planner_name" name="planner_name" class="form-control" placeholder="Find staff here to assign" value="{{ $planner_name }}">
              </div>
              <div class="col-xs-2">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#sfresult" title="Assign planner"><i class="fas fa-search"></i></button>
                @if($planner != '')
                <button class="btn btn-warning" type="submit" title="Remove planner" ><i class="fas fa-trash"></i></button>
                @endif
              </div>
            </div>

          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('planner_name') ? 'has-error' : '' }}">
            <label for="fPlannerId">Shift Planner ID</label>
            <input id="fPlannerId" type="text" name="planner_id" class="form-control" value="{{ $grp->planner_id }}"
                   placeholder="Search planner name to populate" required readonly>
            @if ($errors->has('planner_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('planner_id') }}</strong>
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


<!-- modal untuk shift planner -->
<div id="sfresult" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign as Shift Planner</h4>
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

<!-- placeholder forms for submissions -->
{{-- <form id="fAssignPlanner" action="{{ route('shift.mygroup.setplanner', [], false) }}" method="post">
  @csrf
  <input type="hidden" name="sgid" value="{{ $grp->id }}" />
  <input type="hidden" id="fPlannerId" name="planner_id" value="" />
</form> --}}

@endif


@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {

  $('#grplist').DataTable({
    "responsive": "true"
  });


  sresdt = $('#stes').DataTable({
    oLanguage: {
       "sSearch": "Filter"
     },
    columns : [
      {data: 'staff_no'},
      {data: 'name'},
      {
        data: 'id',
        render: function(data, type, row){
          return '<button type="button" class="btn btn-xs btn-np" title="Select" onclick="assignPlanner('+data+')"><i class="fas fa-check"></i></button>';
        }
      }
    ]
  });

} );


function assignPlanner(persno){
  document.getElementById('fPlannerId').value = persno;
  //document.getElementById('fAssignPlanner').submit();

  var search_url = "{{ route('shift.group.api.getname', ['uid' => '']) }}" + persno;

  $.ajax({
    url: search_url,
    success: function(result) {
      document.getElementById('planner_name').value = result;
    },
    error: function(xhr){
      alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });

  $('#sfresult').modal('hide');
}

$('#sfresult').on('show.bs.modal', function(e) {
  sresdt.clear();
  var search_url = "{{ route('shift.group.api.searchstaff', ['input' => '']) }}" + document.getElementById('planner_name').value;

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

$('#sgresult').on('show.bs.modal', function(e) {
  gmbrdt.clear();
  var search_url = "{{ route('shift.group.api.searchstaff', ['input' => '']) }}" + document.getElementById('gmember_name').value;

  $.ajax({
    url: search_url,
    success: function(result) {
      gmbrdt.rows.add(result).draw();
    },
    error: function(xhr){
      alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });
});


</script>
@stop
