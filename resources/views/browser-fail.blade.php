@extends('adminlte::blank')

@section('adminlte_css')
    
    @yield('css')
@stop

@section('body_class', '')

@section('body')
<style>
.nobr	{ white-space:nowrap; }

</style>
<div class="row master">
    <div class="col-md-7 login">

        <div class="login-title">
            <img src="/vendor/images/logintext.png">
        </div>
    </div>
    <div class="col-md-5 login-d h-100">
        <div class="login-logo">
            <img src="/vendor/images/tmlogo.png">
        </div>
        <div class="login-text w-100">

        <div class="panel panel-default text-justify w-100" style="font-family: Arial, Helvetica, sans-serif;">
  <div class="panel-heading w-100">Oh snap! We don't support this version of your browser, and neither should you!</div>
  <div class="panel-body w-100">You are prompt this because we detected an unsupported browser. Your browser does not support security features that we require. 
  We highly recommend that you update your browser to the latest version of <span class="nobr">Microsoft Edge </span>, Chrome, Firefox, Safari</div>
</div>
        
        
            <br class="d-none"><br class="d-none">

            
            
            <small>

            
            If you have any queries or problems regarding your ID & password for this system, please log into 
            <a href="https://iris2.tm.com.my/" target="_blank">IRIS Self Service System</a></small>
            {{-- <br>
            <span style="color: white">Mode: {{ $_ENV['APP_ENV'] }}</span> --}}
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
   
@stop

