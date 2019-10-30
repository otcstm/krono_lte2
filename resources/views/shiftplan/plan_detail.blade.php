@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@stop

@section('title', 'Shift Plan Details')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">Team member for {{ $sp->name }}, month {{$sp->plan_month->format('M-Y')}}</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Staff No</th>
           <th>Name</th>
           <th>Total Day</th>
           <th>From</th>
           <th>Until</th>
           <th>Status</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($sp->StaffList as $ap)
         <tr>
           <td>{{ $ap->User->staff_no }}</td>
           <td>{{ $ap->User->name }}</td>
           <td>{{ $ap->total_days }}</td>
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
