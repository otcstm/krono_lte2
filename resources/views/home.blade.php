@extends('adminlte::page')
@section('plugins.Chartjs', true)

@section('title', 'Dashboard')
@section('content')
{{-- <h3><p>Welcome {{ $uname }}!</p></h3> --}}
@include('dashboard/section_header')
@include('dashboard/section_main')
@if($isVerifier==1)
    @include('dashboard/section_verifier')
@endif
@if($isApprover==1)
    @include('dashboard/section_approver')
@endif 
@if($isUserAdmin==1)
    @include('dashboard/section_admin')
@endif 
@if($isSysAdmin==1)
    @include('dashboard/section_sysadmin')
@endif 

@include('dashboard/section_otChart')

@stop
