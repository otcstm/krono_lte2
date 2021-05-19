<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'AdminLTE 2'))
@yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ secure_asset('vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ secure_asset('vendor/font-awesome/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ secure_asset('vendor/Ionicons/css/ionicons.min.css') }}">

    @include('adminlte::plugins', ['type' => 'css'])

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ secure_asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('vendor/jqueryui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('vendor/jqueryui/jquery-ui.theme.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('vendor/custom.css') }}">

    <link rel="stylesheet" href="{{ secure_asset('vendor/simditor/simditor.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('vendor/air-datepicker/css/datepicker.min.css') }}">

    

    @yield('adminlte_css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->

    
    <!-- <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css?family=Cousine&display=swap" rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css?family=B612+Mono&display=swap" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Source+Code+Pro&display=swap" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap" rel="stylesheet"> -->
</head>
<body class="hold-transition @yield('body_class')">
    

@yield('body')
<!-- <div class="punch-layer"> -->

<!-- <button id="punchb" class="btn btn-punch-layer" type="button" onclick="return puncho()"><i class="fas fa-clock"></i> START OT</button> -->
<!-- </div> -->
<footer class="foot">
    <div class="container" style="padding: 0 2.5vw; width: 100%">
        <div class="foot-text">Copyright © 2020 Telekom Malaysia Berhad. All rights reserved.
        <span style="color: rgba(0, 0, 0, 0);">
        {{ env('APP_HOSTNAME') }} 
        </span>
        </div>
        
        <img class="foot-img" src="/vendor/ot-assets/footer-logo.png">
    </div>
</footer>
<script src="{{ secure_asset('vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ secure_asset('vendor/jqueryui/jquery-ui.min.js') }}"></script>
<script src="{{ secure_asset('vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="{{ secure_asset('vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>


<script src="{{ secure_asset('vendor/simditor/module.js') }}"></script>
<script src="{{ secure_asset('vendor/simditor/hotkeys.js') }}"></script>
<script src="{{ secure_asset('vendor/simditor/simditor.js') }}"></script>
<script src="{{ secure_asset('vendor/air-datepicker/js/datepicker.js') }}"></script>
<script src="{{ secure_asset('vendor/air-datepicker/js/i18n/datepicker.en.js') }}"></script>


@include('adminlte::plugins', ['type' => 'js'])

@yield('adminlte_js')



</body>
</html>
