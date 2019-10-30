@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@stop

@section('title', 'Shift Plan Details')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">Add shift to this staff</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <form action="{{ route('sp.day.add', [], false) }}" method="post">
      @csrf
      <input type="hidden" name="sps_id" value="{{ $sps->id }}" />
      <div class="form-group">
        <label for="description">Shift Pattern</label>
        <select class="form-control" id="daytype" name="daytype" required>
          @foreach($patterns as $pt)
          <option value="{{ $pt->id }}">{{ $pt->code }} : {{ $pt->description }} ({{ $pt->days_count }} days / {{ $pt->total_hours }} hours)</option>
          @endforeach
        </select>
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Assigned Schedule</div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Pattern Code</th>
           <th>Pattern Desc</th>
           <th>Total Days</th>
           <th>Total Hours</th>
           <th>From</th>
           <th>Until</th>
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
             <form method="post" action="{{ route('shift.delete', [], false) }}" onsubmit='return confirm("Confirm reset?")'>
               @csrf
               <a href="{{ route('shift.view', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-xs btn-warning" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
               <button type="submit" class="btn btn-xs btn-danger" title="Reset"><i class="far fa-calendar-times"></i></button>
               <input type="hidden" name="id" value="{{ $ap->id }}" />
             </form>
             @else
              <a href="{{ route('shift.view', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-xs btn-success" title="Edit"><i class="far fa-eye"></i></button></a>
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
