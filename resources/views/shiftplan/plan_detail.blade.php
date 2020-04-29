@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@stop

@section('title', 'Shift Plan Details')

@section('content')
{{ Breadcrumbs::render('shift.view', $sp) }}
<div class="panel panel-default">
  <div class="panel-heading">Team member for {{ $sp->name }}, month {{$sp->plan_month->format('M-Y')}}</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <p>
      Approver: {{ $sp->Group->Manager->name }} <br />
      @if($sp->Group->Planner && $sp->Group->planner_id != 0)
      Planner: {{ $sp->Group->Planner->name }}
      @endif
    </p>
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Staff No</th>
           <th>Name</th>
           <th>Total Days</th>
           <th>Total Work Hours</th>
           <th>From</th>
           <th>Until</th>
           <th>Status</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($stafflist as $ap)
         <tr>
           <td style="color: {{ $ap->col['f'] }};background-color:{{ $ap->col['bg'] }}">{{ $ap->User->staff_no }}</td>
           <td>{{ $ap->User->name }}</td>
           <td>{{ $ap->total_days }}</td>
           <td>{{ $ap->total_minutes / 60 }}</td>
           <td>{{ $ap->start_date }}</td>
           <td>{{ $ap->end_date }}</td>
           <td>{{ $ap->status }}</td>
           <td>
             @if($ap->status == 'Planning')
             <a href="{{ route('shift.staff', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-xs btn-warning" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
             @else
              <a href="{{ route('shift.staff', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-xs btn-success" title="Edit"><i class="glyphicon glyphicon-info-sign"></i></button></a>
             @endif
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
     @if($role != 'noone')
     <div class="form-group text-center">
       <form action="{{ route('shift.takeaction', [], false) }}" method="post">
         @csrf
         <input type="hidden" name="plan_id" value="{{ $sp->id }}" />
         @if($role == 'approver')
         @if($sp->status != 'Approved')
         <button type="submit" class="btn btn-success" name="action" value="approve">{{ __('shift.f_btn_approve') }}</button>
         @if($sp->status == 'Submitted')
         <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectThisPlan">{{ __('shift.f_btn_reject') }}</button>
         @endif
         @endif
         @if($sp->status == 'Approved')
         <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#revertThisPlan">{{ __('shift.f_btn_revert') }}</button>
         @endif
         @else
         <!-- is planner -->
         @if($sp->status == 'Planning')
         <button type="submit" class="btn btn-success" name="action" value="submit">{{ __('shift.f_btn_submit') }}</button>
         @endif
         @endif
       </form>
     </div>
     @endif
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">{{$sp->plan_month->format('M-Y')}}'s calendar for {{ $sp->name }}</div>
  <div class="panel-body">
    {!! $cal->calendar() !!}
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">History</div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="planhist" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Time</th>
           <th>Action</th>
           <th>By</th>
           <th>Remark</th>
         </tr>
       </thead>
       <tbody>
         @foreach($sp->History as $ap)
         <tr>
           <td>{{ $ap->created_at }}</td>
           <td>{{ $ap->action }}</td>
           <td>{{ $ap->ActionBy->name }}</td>
           <td>{{ $ap->remark }}</td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
  </div>
</div>

<div id="rejectThisPlan" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('shift.takeaction', [], false) }}" method="POST">
          @csrf
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reject this plan</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="plan_id" value="{{ $sp->id }}" />
          <input type="hidden" name="action" value="reject" />
          <div class="form-group">
              <label for="content">Reason:</label>
              <textarea rows="3" class="form-control" id="content" placeholder="Why?" name="remark" required ></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
    </div>
</div>

<div id="revertThisPlan" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('shift.takeaction', [], false) }}" method="POST">
          @csrf
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Revert this plan</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="plan_id" value="{{ $sp->id }}" />
          <input type="hidden" name="action" value="revert" />
          <div class="form-group">
              <label for="content">Reason:</label>
              <textarea rows="3" class="form-control" id="content" placeholder="Why?" name="remark" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
    </div>
</div>

@stop

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $cal->script() !!}

<script type="text/javascript">

$(document).ready(function() {

  $('#tPunchHIstory').DataTable({
    "responsive": "true"
  });

  $('#planhist').DataTable({
    "responsive": "true"
  });

} );

</script>

@stop
