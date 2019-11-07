@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<p><a href="{{route('misc.home')}}" style="display: inline">Home</a> > <a href="{{route('ot.list')}}" style="display: inline">OT List</a> > Apply OT</p>
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT Application List</div>
    <div class="panel-body">
        <form action="{{route('ot.formdate')}}" method="POST">
            @csrf
            <div class="form-group">
                <label>Date:</label>
                <input type="date" id="inputdate" name="inputdate" value="" required>asda
            </div>
        </form>
    </div>
</div>  

@stop

@section('js')
<script type="text/javascript">


</script>
@stop