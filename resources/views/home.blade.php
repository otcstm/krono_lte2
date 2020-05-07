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
@if($chartSumYear>0)
    @include('dashboard/section_otChart')
@endif
@if($isApprover==1)
    @include('dashboard/section_approver')
@endif 
@if(isset($is_shift_gowner) or isset($is_shift_gplanner) or isset($is_shift_gapprover))
    @include('dashboard/section_shift')
@endif 
@if($isUserAdmin==1)
    @include('dashboard/section_admin')
@endif 
@if($isSysAdmin==1)
    @include('dashboard/section_sysadmin')
@endif 

@stop

