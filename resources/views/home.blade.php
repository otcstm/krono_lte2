@extends('adminlte::page')
@section('plugins.Chartjs', true)

@section('title', 'Dashboard')
@section('content')
<!-- <h3><p>Welcome {{ $uname }}!</p></h3> -->
@include('dashboard/section_header')
@include('dashboard/section_main')
@if($isVerifier==1)
    @include('dashboard/section_verifier')
@endif
@include('dashboard/section_otChart')
@if($isApprover==1)
    @include('dashboard/section_approver')
@endif
{{-- @include('dashboard/section_admin') --}}

@stop
