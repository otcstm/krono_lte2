@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
<div class="loginbgfull">
<nav class="navbar navbar-inverse navtop">
<div class="container"> 
    <img class="foot-img navbar-right" src="/vendor/images/tmlogo.png">
</div>
</nav>

<div class="container">
<div class="row">
<div class="cold-md-8">
</div>
<div class="cold-md-4 col-md-offset-6">
<div class="login-box" style="margin-top: 10px;">
        <div class="login-logo">
            <a href="{{ route('misc.home', [], false) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ __('adminlte::adminlte.login_message') }}</p>
            <form action="{{ route('login', [], false) }}" method="post">
                {{ csrf_field() }}

                <div class="form-group has-feedback {{ $errors->has('staff_no') ? 'has-error' : '' }}">
                    <input type="text" name="staff_no" class="form-control" value="{{ old('staff_no') }}"
                           placeholder="Staff No">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('staff_no'))
                        <span class="help-block">
                            <strong>{{ $errors->first('staff_no') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ __('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">{{ __('adminlte::adminlte.remember_me') }}</label>
                            <br /><br />
                            <a href="https://idss.tm.com.my/" target="_blank"><i class="glyphicon glyphicon-key"></i> I forgot my password</a>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            {{ __('adminlte::adminlte.sign_in') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <br>
<small>Dear User, <br />
If you have any queries, problems or have not received any ID and password for OT System, please log into 
<a href="https://iris2.tm.com.my/" target="_blank">IRIS Self Service System</a></small>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
</div>
</div>
</div>
</div>
</div>
@stop

@section('adminlte_js')
    @yield('js')
@stop
