@extends('adminlte::page')

@section('title', 'Group Details')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">Shift Group Details</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <form action="{{ route('shift.group.edit', [], false) }}" method="post">
      @csrf
      <input type="hidden" name="id" value="{{ $groupd->id }}" />
      <div class="form-group has-feedback {{ $errors->has('group_code') ? 'has-error' : '' }}">
        <label for="group_code">Group Code</label>
        <input id="group_code" type="text" name="group_code" class="form-control" value="{{ $groupd->group_code }}" disabled>
      </div>
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

      <div class="form-group has-feedback {{ $errors->has('planner_id') ? 'has-error' : '' }}">
        <label for="planner_id">Shift Planner</label>
        <select class="form-control" id="planner_id" name="planner_id" required>
          @foreach($stafflist as $day)
          <option value="{{ $day['id'] }}" {{ $day['selected'] }}>{{ $day['staff_no'] }} : {{ $day['name'] }}</option>
          @endforeach
        </select>
        @if ($errors->has('planner_id'))
            <span class="help-block">
                <strong>{{ $errors->first('planner_id') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Group Members </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="staffingrp" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Staff</th>
           <th>Remove</th>
         </tr>
       </thead>
       <tbody>
         @foreach($groupd->Members as $ap)
         <tr>
           <td>{{ $ap->User->name }}</td>
           <td><form action="{{ route('shift.staff.del', [], false) }}" method="post">
             @csrf
             <input type="hidden" name="group_id" value="{{ $ap->shift_group_id }}" />
             <input type="hidden" name="user_id" value="{{ $ap->user_id }}" />
             <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-minus"></i></button>
           </form></td>
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
           <th>Position</th>
           <th>Add to group?</th>
         </tr>
       </thead>
       <tbody>
         @foreach($free_member as $ap)
         <tr>
           <td>{{ $ap['staff_no'] }}</td>
           <td>{{ $ap['name'] }}</td>
           <td>{{ $ap['position'] }}</td>
           <td>
             <form action="{{ route('shift.staff.add', [], false) }}" method="post">
               @csrf
               <input type="hidden" name="user_id" value="{{ $ap['id'] }}" />
               <input type="hidden" name="group_id" value="{{ $groupd->id }}" />
               <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-user-plus"></i></button>
             </form>
           </td>
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
