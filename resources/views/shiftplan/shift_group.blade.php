@extends('adminlte::page')

@section('title', 'Shift Groups')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">Create Shift Grouping</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <form action="{{ route('shift.group.add', [], false) }}" method="post">
      @csrf
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

      <div class="form-group has-feedback {{ $errors->has('planner_id') ? 'has-error' : '' }}">
        <label for="planner_id">Shift Planner</label>
        <select class="form-control" id="planner_id" name="planner_id" required>
          @foreach($stafflist as $day)
          <option value="{{ $day['id'] }}">{{ $day['staff_no'] }} : {{ $day['name'] }}</option>
          @endforeach
        </select>
        @if ($errors->has('planner_id'))
            <span class="help-block">
                <strong>{{ $errors->first('planner_id') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Created Shift Groups</div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="grplist" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Code</th>
           <th>Name</th>
           <th>Staff Count</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($p_list as $ap)
         <tr>
           <td>{{ $ap->group_code }}</td>
           <td>{{ $ap->group_name }}</td>
           <td>{{ $ap->Members->count() }}</td>
           <td>
             <form method="post" action="{{ route('shift.group.del', [], false) }}" onsubmit='return confirm("Confirm delete?")'>
               @csrf
               <a href="{{ route('shift.group.view', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-xs btn-warning" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
               <button type="submit" class="btn btn-xs btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
               <input type="hidden" name="id" value="{{ $ap->id }}" />
             </form>

           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Shift subordinates without group </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="staffnogrp" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Staff No</th>
           <th>Name</th>
           <th>Unit</th>
         </tr>
       </thead>
       <tbody>
         @foreach($s_list as $ap)
         <tr>
           <td>{{ $ap->staff_no }}</td>
           <td>{{ $ap->name }}</td>
           <td>{{ $ap->orgunit }}</td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Shift subordinates in group </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="staffingrp" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Staff</th>
           <th>group</th>
         </tr>
       </thead>
       <tbody>
         @foreach($in_grp as $ap)
         <tr>
           <td>{{ $ap->user_id }}</td>
           <td>{{ $ap->shift_group_id }}</td>
         </tr>
         @endforeach
       </tbody>
     </table>
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

  $('#staffnogrp').DataTable({
    "responsive": "true"
  });

  $('#staffingrp').DataTable({
    "responsive": "true"
  });

  $('#planner_id').select2();
} );


</script>
@stop
