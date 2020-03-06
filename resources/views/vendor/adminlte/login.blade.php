@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
<div class="row master">
    <div class="col-md-7 login">

        <div class="login-title">
            <img src="/vendor/images/logintext.png">
        </div>
    </div>
    <div class="col-md-5 login-d">
        <div class="login-logo">
            <img src="/vendor/images/tmlogo.png">
        </div>
        <div class="login-text">
            <form action="{{ route('login', [], false) }}" method="post">
                {{ csrf_field() }}
                <h1>Login to Get Started</h1>
                <br>
                <p>Your Staff ID</p>
                <div class="form-group has-feedback {{ $errors->has('username') ? 'has-error' : '' }}">
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                           placeholder="Eg: TM52025">
                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <p>Your Password</p>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ __('adminlte::adminlte.password') }}">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            <br class="d-none">
                <div class="login-flex">
                    <a href="https://idss.tm.com.my/" target="_blank">Forgot Password</a>
                    <button type="submit" class="btn btn-primary">
                            {{ __('adminlte::adminlte.sign_in') }}
                    </button>
                </div>
            </form>
            <br class="d-none"><br class="d-none">
            <small>Dear User, <br />
            If you have any queries, problems or have not received any ID and password for OT System, please log into 
            <a href="https://iris2.tm.com.my/" target="_blank">IRIS Self Service System</a></small>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
    @yield('js')
@stop

