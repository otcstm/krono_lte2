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
      Planner: {{ $sp->Group->Planner->name }}
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
              <a href="{{ route('shift.staff', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-xs btn-success" title="Edit"><i class="far fa-eye"></i></button></a>
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
         @if($sp->status != 'Finalized')
         <button type="submit" class="btn btn-success" name="action" value="approve">{{ __('shift.f_btn_approve') }}</button>
         @if($sp->status == 'Submit')
         <button type="submit" class="btn btn-danger" name="action" value="reject">{{ __('shift.f_btn_reject') }}</button>
         @endif
         @endif
         @if($sp->status == 'Finalized')
         <button type="submit" class="btn btn-warning" name="action" value="revert">{{ __('shift.f_btn_revert') }}</button>
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

@stop

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $cal->script() !!}
@stop
