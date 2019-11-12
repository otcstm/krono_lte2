@extends('adminlte::page')

@section('title', 'Work-Day Management')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">Type of work day</div>
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
         @foreach($p_list as $ap)
         <tr>
           <td style="color: {{ $ap->font_color }};background-color:{{ $ap->bg_color }}">{{ $ap->code }}</td>
           <td>{{ $ap->description }}</td>
           <td>
             @if($ap->is_work_day)
             &#10004;
             @else
             &#10008;
             @endif
           </td>
           <td>{{ $ap->start_time }}</td>
           <td>{{ $ap->showEndTime() }}</td>
           <td>{{ $ap->dur_hour }} h, {{ $ap->dur_minute }} m</td>
           <td>

             <form method="post" action="{{ route('wd.delete', [], false) }}">
               @csrf
               <button type="button" class="btn btn-xs btn-warning" title="Edit"
                  data-toggle="modal"
                  data-target="#editwd"
                  data-id="{{ $ap->id }}"
                  data-code="{{ $ap->code }}"
                  data-desc="{{ $ap->description }}"
                  data-fontc="{{ $ap->font_color }}"
                  data-bgc="{{ $ap->bg_color }}"
               ><i class="fas fa-pencil-alt"></i></button>
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
  <div class="panel-heading">Add new work-day type</div>
  <div class="panel-body">
    <form action="{{ route('wd.add', [], false) }}" method="post">
      @csrf
      <div class="form-group has-feedback {{ $errors->has('code') ? 'has-error' : '' }}">
        <label for="code">Code</label>
        <input id="code" type="text" name="code" class="form-control" value="{{ old('code') }}"
               placeholder="work-day code" required autofocus maxlength="5">
        @if ($errors->has('code'))
            <span class="help-block">
                <strong>{{ $errors->first('code') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('description') ? 'has-error' : '' }}">
        <label for="description">Description</label>
        <input id="description" type="text" name="description" class="form-control" value="{{ old('description') }}"
               placeholder="work-day description" required maxlength="200">
        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('is_work_day') ? 'has-error' : '' }}">
        <input id="is_work_day" type="checkbox" name="is_work_day" value="{{ old('is_work_day') }}" onchange="checkIsFullDay()">
        <label for="is_work_day">Is a working day</label>
        @if ($errors->has('is_work_day'))
            <span class="help-block">
                <strong>{{ $errors->first('is_work_day') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('start_time') ? 'has-error' : '' }}">
        <label for="start_time">Start Time</label>
        <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}"
               placeholder="08:30" required>
        @if ($errors->has('start_time'))
            <span class="help-block">
                <strong>{{ $errors->first('start_time') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('duration') ? 'has-error' : '' }}">
        <label for="dur_hour">Duration</label>
        <input id="dur_hour" type="number" name="dur_hour" value="{{ old('dur_hour') }}"
               placeholder="Hour" required min="0" max="23" step="1">
        <input id="dur_minute" type="number" name="dur_minute" value="{{ old('dur_minute') }}"
               placeholder="Minute" required min="0" max="59" step="1">
        @if ($errors->has('duration'))
            <span class="help-block">
                <strong>{{ $errors->first('duration') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('bgcolor') ? 'has-error' : '' }}">
        <label for="bgcolor">Background color in calendar</label>
        <input id="bgcolor" type="color" name="bgcolor" value="{{ old('bgcolor', '#ffffff') }}">
        @if ($errors->has('bgcolor'))
            <span class="help-block">
                <strong>{{ $errors->first('bgcolor') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('fontcolor') ? 'has-error' : '' }}">
        <label for="fontcolor">Font color in calendar</label>
        <input id="fontcolor" type="color" name="fontcolor" value="{{ old('fontcolor', '#000000') }}">
        @if ($errors->has('fontcolor'))
            <span class="help-block">
                <strong>{{ $errors->first('fontcolor') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>

<div id="editwd" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('wd.edit') }}" method="POST">
          @csrf
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit work-day</h4>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control hidden" id="inputid" name="id" value="">
          <div class="form-group">
              <label for="inputname">Code:</label>
              <input type="text" class="form-control" id="inputname" name="code" value="" disabled>
          </div>
          <div class="form-group">
              <label for="inputdesc">Description:</label>
              <input type="text" class="form-control" id="inputdesc" name="description" value="" required>
          </div>
          <div class="form-group">
              <label for="inputbgc">Background Color:</label>
              <input type="color" id="inputbgc" name="bgcolor" value="" required>
          </div>
          <div class="form-group">
              <label for="inputfc">Font Color:</label>
              <input type="color" id="inputfc" name="fontcolor" value="" required>
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
<script type="text/javascript">

function checkIsFullDay(){
  var iscek = document.getElementById("is_work_day").checked;
  document.getElementById("start_time").required = iscek;
  document.getElementById("dur_hour").required = iscek;
  document.getElementById("dur_minute").required = iscek;
}

$(document).ready(function() {

  checkIsFullDay();

  $('#tPunchHIstory').DataTable({
    "responsive": "true"
  });
} );

function populate(e){
    var wd_id = $(e.relatedTarget).data('id');
    var wd_code = $(e.relatedTarget).data('code')
    var wd_desc = $(e.relatedTarget).data('desc')
    var wd_bgc = $(e.relatedTarget).data('bgc')
    var wd_fontc = $(e.relatedTarget).data('fontc')
    $('input[id=inputid]').val(wd_id);
    $('input[id=inputname]').val(wd_code);
    $('input[id=inputdesc]').val(wd_desc);
    $('input[id=inputbgc]').val(wd_bgc);
    $('input[id=inputfc]').val(wd_fontc);
}

$('#editwd').on('show.bs.modal', function(e) {
    populate(e);
});


</script>
@stop
