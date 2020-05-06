@extends('adminlte::page')

@section('title', 'Shift Template Management')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">Shift Template / Work Pattern Detail</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <form action="{{ route('sp.edit', [], false) }}" method="post">
      @csrf
      <input type="hidden" name="id" value="{{ $tsp->id }}"  />
      <div class="form-group has-feedback {{ $errors->has('code') ? 'has-error' : '' }}">
        <label for="code">Code</label>
        <input id="code" type="text" name="code" class="form-control" value="{{ $tsp->code }}" disabled >
      </div>
      <div class="form-group has-feedback {{ $errors->has('description') ? 'has-error' : '' }}">
        <label for="description">Description</label>
        <input id="description" type="text" name="description" class="form-control" value="{{ old('description', $tsp->description) }}"
               placeholder="Shift pattern description" required maxlength="200">
        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group }}">
        <label for="code">Total Days</label>
        <input type="text" class="form-control" value="{{ $tsp->days_count }}" disabled >
      </div>
      <div class="form-group }}">
        <label for="code">Total Hours</label>
        <input type="text" class="form-control" value="{{ $tsp->total_hours }}" disabled >
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Add day to this shift template</div>
  <div class="panel-body">
    <form action="{{ route('sp.day.add', [], false) }}" method="post">
      @csrf
      <input type="hidden" name="sp_id" value="{{ $tsp->id }}" />
      <input type="hidden" name="sp_code" value="{{ $tsp->code }}" />
      <div class="form-group">
        <label for="description">Description</label>
        <select class="form-control" id="daytype" name="daytype" required>
          @foreach($daytype as $day)
          <option value="{{ $day->id }}">{{ $day->code }} : {{ $day->description }}</option>
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
  <div class="panel-heading">Days under this Shift Template</div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Seq</th>
           <th>Code</th>
           <th>Description</th>
           <th>Is Working Day?</th>
           <th>Start Time</th>
           <th>End Time</th>
           <th>Duration</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @if($daycount > 0)
         @foreach($daylist as $ap)
         <tr>
           <td>{{ $ap->day_seq }}</td>
           <td>{{ $ap->Day->code }}</td>
           <td>{{ $ap->Day->description }}</td>
           <td>
             @if($ap->Day->is_work_day)
             &#10004;
             @else
             &#10008;
             @endif
           </td>
           <td>{{ $ap->Day->start_time }}</td>
           <td>{{ $ap->Day->showEndTime() }}</td>
           <td>{{ $ap->Day->dur_hour }} h, {{ $ap->Day->dur_minute }} m</td>
           <td>
             @if($ap->day_seq == $daycount)
             <form method="post" action="{{ route('sp.day.del', [], false) }}" onsubmit='return confirm("Confirm delete?")'>
               @csrf
               <button type="submit" class="btn btn-xs btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
               <input type="hidden" name="id" value="{{ $ap->id }}" />
               <input type="hidden" name="sp_id" value="{{ $tsp->id }}" />
             </form>
             @endif
           </td>
         </tr>
         @endforeach
         @endif
       </tbody>
     </table>
    </div>
  </div>
  <div class="panel-footer">
  <div class="text-center">
    <form action="{{ route('sp.index', [], false) }}" method="post">
      @csrf
            <button type="submit" name="return" value="rtn" class="btn btn-p btn-primary">Return</button>
  </form>
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
