@extends('adminlte::page')

@section('title', 'Shift Groups')

@section('content')
<h1>Shift Grouping</h1>
@if (session()->has('alert'))
<div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>{{ session()->get('alert') }}</strong>
</div>
@endif
<div class="panel panel-default">
  <div class="panel-heading">Shift Planner {{ $grp->group_name }}</div>
  <div class="panel-body">
    
    <form action="{{ route('shift.mygroup.delplanner', [], false) }}" method="post">
      @csrf
      <input type="hidden" name="sgid" value="{{ $grp->id }}" />
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group has-feedback {{ $errors->has('group_code') ? 'has-error' : '' }}">
            <label for="group_code">Group Code</label>
            <input id="group_code" type="text" class="form-control" value="{{ $grp->group_code }}" readonly>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group has-feedback {{ $errors->has('group_name') ? 'has-error' : '' }}">
            <label for="group_name">Group Name</label>
            <input id="group_name" type="text" class="form-control" value="{{ $grp->group_name }}" readonly>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group has-feedback {{ $errors->has('owner_name') ? 'has-error' : '' }}">
            <label for="planner_name">Shift Planner</label>
            <div class="row">
              <div class="col-xs-10">
                <input type="text" id="planner_name" name="planner_name" class="form-control" value="{{ $planner }}">
              </div>
              <div class="col-xs-2">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#sfresult" title="Assign planner"><i class="fas fa-search"></i></button>
                @if($planner != '')
                <button class="btn btn-warning" type="submit" title="Remove planner" ><i class="fas fa-trash"></i></button>
                @endif
              </div>
              <div class="col-sm-12">
                <div class="form-group has-feedback {{ $errors->has('planner_name') ? 'has-error' : '' }}">
                  <label for="fPlannerId">Shift Planner ID</label>
                  <input id="fPlannerId2" type="text" name="planner_id" class="form-control" value="{{ $grp->planner_id }}"
                         placeholder="Search planner name to populate" required readonly>
                  @if ($errors->has('planner_id'))
                      <span class="help-block">
                          <strong>{{ $errors->first('planner_id') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </form>   
    
  </div>
</div> 

<div class="panel panel-default"> 
  <div class="panel-heading">Shift Group {{ $grp->group_name }}</div>
  <div class="panel-body">
    

<h4>Group Members</h4>
    <div class="table-responsive">
      <table id="memlist" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Staff No</th>
           <th>Name</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($ingrp as $ap)
         <tr>
           <td>{{ $ap->User->staff_no }}</td>
           <td>{{ $ap->User->name }}</td>
           <td>
             <form method="post" action="{{ route('shift.staff.del', [], false) }}" onsubmit='return confirm("Confirm remove?")'>
               @csrf
               <button type="submit" class="btn btn-np" title="Delete"><i class="fas fa-trash-alt"></i></button>
               <input type="hidden" name="id" value="{{ $ap->id }}" />
             </form>
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
<Br />
<h4>Subordinate Without Group</h4>

    <div class="table-responsive">
      <table id="nonmemlist" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Staff No</th>
           <th>Name</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($outgrp as $ap)
         <tr>
           <td>{{ $ap->staff_no }}</td>
           <td>{{ $ap->name }}</td>
           <td>
             {{-- <form method="post" action="{{ route('shift.staff.add', [], false) }}" onsubmit='return confirm("Confirm add?")'>
               @csrf
               <button type="submit" class="btn btn-np" title="Add"><i class="fas fa-plus"></i></button>
               <input type="hidden" name="user_id" value="{{ $ap->id }}" />
             </form> --}}
             <form action="{{ route('shift.staff.add', [], false) }}" method="post" onsubmit='return confirm("Confirm add?")'>
              @csrf 
              <button type="submit" class="btn btn-np" title="Add"><i class="fas fa-plus"></i></button>
              <input type="hidden" name="group_id" value="{{ $grp->id }}" />
              <input type="hidden" id="fUserId" name="user_id" value="{{ $ap->id }}" />
            </form>
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
    <Br />
    <h4>Search member to group {{ $grp->group_name }}</h4>
    <div class="table-responsive">
    <div class="panel panel-default">
      <div class="panel-body">
            <div class="form-group has-feedback {{ $errors->has('owner_name') ? 'has-error' : '' }}">
              <label for="gmember_name">Find staff to add</label>
              <div class="row">
                <div class="col-xs-10">
                  <input type="text" id="gmember_name" name="gmember_name" class="form-control" placeholder="Find staff here to add to group">
                </div>
                <div class="col-xs-2">
                  <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#sgresult"><i class="fas fa-search"></i></button>
                </div>
              </div>
        </div>
      </div>
    </div>    
  </div>    

  <Br />
  {{-- @if(!$planner)
  <div class="alert alert-warning">
    Theres no assigned <b>Planner</b> for thsi group yet.
  </div>
  @endif --}}
<div class="pull-right">
<a id="btnCancelGrpAssign"  href="{{ route('shift.mygroup', [], false) }}" class="btn btn-p btn-primary btn-outline">Cancel</a>
<a id="btnCreateGrpAssign" href="{{ route('shift.mygroup', [], false) }}" class="btn btn-p btn-primary">Create</a>
</div>
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
               <td>S53877</td>
               <td>Amer bin ahmad</td>
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

<!-- modal untuk add group member -->
<div id="sgresult" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add as Group Member</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="sgmbr" class="table table-hover table-bordered">
           <thead>
             <tr>
               <th>Staff No</th>
               <th>Name</th>
               <th>Choose</th>
             </tr>
           </thead>
           <tbody>
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
<form id="fAssignPlanner" action="{{ route('shift.mygroup.setplanner', [], false) }}" method="post">
  @csrf
  <input type="hidden" name="sgid" value="{{ $grp->id }}" />
  <input type="hidden" id="fPlannerId" name="planner_id" value="" />
</form>

<form id="fAddMember" action="{{ route('shift.staff.add', [], false) }}" method="post">
  @csrf
  <input type="hidden" name="group_id" value="{{ $grp->id }}" />
  <input type="hidden" id="fUserId2" name="user_id" value="" />
</form>

@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {

  $('#memlist').DataTable({
    "responsive": "true"
  }); 
  $('#nonmemlist').DataTable({
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

  gmbrdt = $('#sgmbr').DataTable({
    oLanguage: {
       "sSearch": "Filter"
     },
    columns : [
      {data: 'staff_no'},
      {data: 'name'},
      {
        data: 'id',
        render: function(data, type, row){
          return '<button type="button" class="btn btn-xs btn-np" title="Select" onclick="addMember('+data+')"><i class="fas fa-plus"></i></button>';
        }
      }
    ]
  });


} );

function assignPlanner(persno){
  document.getElementById('fPlannerId').value = persno;
  document.getElementById('fAssignPlanner').submit();
}

function addMember(persno){
  document.getElementById('fUserId2').value = persno;
  document.getElementById('fAddMember').submit();
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

// $('#btnCancelGrpAssign').click(function() {
//       checked_field = $("input[name=planner_name]").val();
//       //alert(checked_field);
//       var r = confirm("Are you sure want to leave this page without assign a Planner?");
//       if (r == true) {
//         return true;
//       } else {  
//         $("#planner_name").focus();
//         return false;
//       } 
// });

// $('#btnCreateGrpAssign').click(function() {
//       checked_field = $("input[name=planner_name]").val();
//       //alert(checked_field);
//       var r = confirm("Are you sure want to proceed without assign a Planner?");
//       if (r == true) {
//         return true;
//       } else {  
//         $("#planner_name").focus();
//         return false;
//       } 
// });

</script>
@stop
