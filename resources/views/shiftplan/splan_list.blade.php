@extends('adminlte::page')

@section('title', 'Shift Planning')

@section('content')
{{ Breadcrumbs::render() }}
<div class="panel panel-default">
  <div class="panel-heading">Plan the Shift Schedule Here</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <form action="{{ route('shift.add', [], false) }}" method="post" class="form-horizontal">
      @csrf
      <div class="form-group has-feedback {{ $errors->has('plan_month') ? 'has-error' : '' }}">
        <label for="plan_month" class="control-label col-sm-2">Plan Month</label>
        <div class="col-sm-10">
          <input id="plan_month" type="date" name="plan_month" value="{{ old('plan_month', $curdate) }}" required autofocus>
        </div>
        @if ($errors->has('plan_month'))
            <span class="help-block">
                <strong>{{ $errors->first('plan_month') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('shift_group_id') ? 'has-error' : '' }}">
        <label for="shift_group_id" class="control-label col-sm-2">Shift Group</label>
        <div class="col-sm-10">
          <select class="form-control" id="shift_group_id" name="shift_group_id" required>
            @foreach($grouplist as $day)
            <option value="{{ $day->id }}">{{ $day->group_code }} : {{ $day->group_name }}</option>
            @endforeach
          </select>
        </div>
        @if ($errors->has('shift_group_id'))
          <span class="help-block">
              <strong>{{ $errors->first('shift_group_id') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Create Plan</button>
      </div>
    </form>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Created Plans</div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Month</th>
           <th>Name</th>
           <th>Staff Count</th>
           <!-- <th>Created By</th> -->
           <th>Status</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($p_list as $ap)
         <tr>
           <td>{{ $ap->plan_month->format('M Y') }}</td>
           <td>{{ $ap->name }}</td>
           <td>{{ $ap->StaffList->count() }}</td>
           <!-- <td>{{ $ap->Creator->name }}</td> -->
           <td>{{ $ap->status }}</td>
           <td>
             <form method="post" action="{{ route('shift.delete', [], false) }}" onsubmit='return confirm("Confirm delete?")'>
               @csrf
               <a href="{{ route('shift.view', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-xs btn-warning" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
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

@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {

  $('#tPunchHIstory').DataTable({
    "responsive": "true"
  });
} );

</script>
@stop
