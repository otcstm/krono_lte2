@extends('adminlte::master')

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<style>
    .helloKitty {
        background-image:url('/vendor/ot-assets/helloKitty.jpg');
        background-size:100%; 
        background-blend-mode:color;
        background-color:rgba(255,255,250 ,0.81 )
        
    }
</style>

@yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body helloKitty">
        <p class="login-box-msg">{{ __('adminlte::adminlte.login_message') }}</p>
        <form action="{{route('login.offline') }}" method="post">
            {{ csrf_field() }}

            <div class="form-group has-feedback {{ $errors->has('staff_no') ? 'has-error' : '' }}">
                <input type="text" name="staff_no" class="form-control  " value="{{ old('staff_no') }}" placeholder="Staff No">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('staff_no'))
                <span class="help-block">
                    <strong>{{ $errors->first('staff_no') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                <input type="password" name="password" class="form-control" placeholder="{{ __('adminlte::adminlte.password') }}">
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
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

<input type="hidden" id="text" value="Hello Kitty Kitty! Welcome abord offline. Prepare for take-off! Have a nice day! Meow meow! " />
<input type="hidden" id="rate" value="0.7" />
<input type="hidden" id="pitch" value="1" />
<script type="text/javascript">
    setTimeout(greetUser, 1000);
    
    function greetUser() {
    
    var message = new SpeechSynthesisUtterance($("#text").val());
    var voices = speechSynthesis.getVoices();
    
    speechSynthesis.speak(message);
    
    
    // Hack around voices bug
    var interval = setInterval(function () {
        voices = speechSynthesis.getVoices();
        if (voices.length) clearInterval(interval); else return;
    
        for (var i = 0; i < voices.length; i++) {
            $("select").append("<option value=\"" + i + "\">" + voices[i].name + "</option>");
        }
    }, 10);
    
    }
    </script>

@stop

@section('adminlte_js')
@yield('js')
@stop