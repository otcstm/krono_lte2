@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<p>
  Welcome {{ $uname }} !
</p>

<div class="row">
<div class="col-md-4">
@include('log/listUserLogsDash')
</div>
<div class="col-md-4">
@include('log/listUserLogsDash')
</div>
<div class="col-md-4">
@include('log/listUserLogsDash')
</div>
</div>

@stop
