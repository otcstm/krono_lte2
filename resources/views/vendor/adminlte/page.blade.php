@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ secure_asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    <link rel="stylesheet" href="{{ secure_asset('vendor/bootstrap-timepicker/css/timepicker.less')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ route('misc.home', [], false) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            @else
            <!-- Logo -->
            <a href="{{ route('misc.home', [], false) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle fa5" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ __('adminlte::adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

        <!-- Tasks: style can be found in dropdown.less -->
        <li class="dropdown notifications-menu">

            @if(session()->has('notifycount') && session('notifycount') != 0)
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Require Your Attention">
              <i class="glyphicon glyphicon-exclamation-sign text-green"></i>
              <span class="label label-danger">{{ session('notifycount') }}</span>
            </a>
            <ul class="dropdown-menu">
              @foreach(session('notifylist') as $nitem)
              <li>
                <a  style="word-break: break-all;" href="{{ route('notify.read', ['nid' => $nitem->id]) }}">
                  <i class="{{ $nitem->data['icon'] }}"></i> {{ $nitem->data['text'] }}
                </a>
              </li>
              @endforeach
            </ul>
            @else
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="No Notification">
              <i class="glyphicon glyphicon-ok-sign text-gray"></i>
            </a>
            @endif
         </li>

         <!-- Notifications: style can be found in dropdown.less -->
         <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="glyphicon glyphicon-bell"></i>
              <!-- <span class="label label-warning">10</span> -->
            </a>
            <ul class="dropdown-menu">
              <li class="header">You dont have new notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> Update on covid-19 sfsdf ldf ie skdmfl eks fsdf
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
         </li>



          <!-- User Account: style can be found in dropdown.less -->
          <li class="user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/user/image/{{Auth::user()->staff_no}}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
              
            </a>
          </li>
          <li>
          <a href="#"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-fw fa-power-off"></i> <span class="hidden-xs">{{ __('adminlte::adminlte.log_out') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout', [], false) }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>

      </div>

                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

    <div class="user-panel">
        <div class="pull-left image">
          <img src="/user/image/{{ Auth::user()->staff_no }}" class="img-circle" alt="User Image">
        </div>
        <div class=" info">
          <p class="wraptext">{{ Auth::user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          <p class="profile"><a class="btn-user" href="{{ route('staff.profile',[],false) }}">Profile</a></p>
        </div>
      </div>

      <!-- <div class="user-panel">
        <a href="{{ route('staff.profile',[],false) }}" class="image btn btn-xs btn-p btn-primary btn-outline">
        Profile</a>
      </div>      -->
                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->

        <!-- <div class="content-wrapper" style="background: transparent"> -->
        <div class="content-wrapper">

            <!-- <div class="bckg">
                <img src="/vendor/ot-assets/main-bg.png" class="bckg-img">
            </div> -->
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif








        @if(Session::get('announcementx'))
            @if(Session::get('announcement')!=null)
                <div class="announcement text-center">
                    <p>{{Session::get('announcement')->title}}</p>
                    <button id="announcement" class="btn btn-announcement" data-title="{{Session::get('announcement')->title}}" data-announcement="{{nl2br(Session::get('announcement')->announcement)}}" onclick="return openannouncement();">Click Here</button>
                    <button id="x" class="btn btn-announcement-x" onclick="return closeannouncement();"><i class="fas fa-times"></i></button>
                </div>
            @endif
        @endif











            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')

            </section>

            <!-- Main content -->
            <section class="content">
                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

        @hasSection('footer')
        <footer class="main-footer">
            @yield('footer')
        </footer>
        @endif

        @if(config('adminlte.right_sidebar') and (config('adminlte.layout') != 'top-nav'))
            <aside class="control-sidebar control-sidebar-{{config('adminlte.right_sidebar_theme')}}">
                <!-- yield('right-sidebar') -->
                <ul class="control-sidebar-menu">
                  @if(session()->has('notifycount') && session()->get('notifycount') > 0)
                  @foreach(session()->get('notifylist') as $onutifi)
                  <li>
                    <a href="{{ $onutifi['href'] }}">
                      <i class="{{ $onutifi['icon'] }}"></i>
                      <span>{{ $onutifi['text'] }}</span>
                    </a>
                  </li>
                  @endforeach
                  @else
                  <li style="text-align:center">Nothing to show here</li>
                  @endif
                </ul>
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        @endif

    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')

    <script src="{{ secure_asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ secure_asset('vendor/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
    @stack('js')
    @yield('js')


    <script type="text/javascript">
        function openannouncement(){
            var title = $("#announcement").data('title');
            var announcement = $("#announcement").data('announcement');
            Swal.fire({
                title: 'Announcement',
                html: '<div style="max-height: 60vh; overflow-y: scroll;  overflow-x: hidden;">'+announcement+'</div>',
                customClass: 'initial',
                showCancelButton: false,
                confirmButtonColor: '#d33',
                confirmButtonText: 'CLOSE',
            })
        }

        function closeannouncement(){
            $.ajax({
            type: "GET",
            url: '{{ route("announce.close", [], false)}}',
                success: function(resp) {
                    $(".announcement").addClass("announcement-close");
                    $("#x").css("display","none");

                }
            });
        }
    </script>
@stop
