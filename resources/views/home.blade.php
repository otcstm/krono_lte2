@extends('adminlte::page')
@section('plugins.Chartjs', true)

@section('title', 'Dashboard')
@section('content')
<!-- <h3><p>Welcome {{ $uname }}!</p></h3> -->
@include('dashboard/section_header')
@include('dashboard/section_main')
@include('dashboard/section_approver')

@stop
