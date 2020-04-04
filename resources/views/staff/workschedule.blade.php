@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@stop

@section('title', 'Calendar')

@section('content')
<h1>My Work Schedule</h1>

<div class="panel">
  <div class="panel-heading">dd</div>
  <div class="panel-body">
    {!! $cal->calendar() !!}
  </div>
</div>

@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
{!! $cal->script() !!}
<script type="text/javascript">

</script>
@stop
