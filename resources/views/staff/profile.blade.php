@extends('adminlte::page')

@section('title', 'User Profile')

@section('content')
<h3 class="box-title">Profile</h3>
<div class="row-eq-height">

<div class="col-md-6">
    <div class="box box-warning boxfullheight">
        <div class="box-header with-border">
              <i class="glyphicon glyphicon-user"></i>
              <h3 class="box-title">My Profile</h3>
        </div>
            <!-- /.box-header -->
        <div class="box-body">

        <div class="col-sm-6 col-md-4">
                        <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" alt="" class="img-rounded img-responsive" />
                    </div>
                    <div class="col-sm-6 col-md-8">
                        <h4>{{ $staff_basic->name }}</h4>
                        <small>
                        <cite title="{{ $staff_detail->empsgroup }}">{{ $staff_detail->empsgroup }} ({{ $staff_detail->empgroup }})</cite></small>
                        <p>
                            <i class="glyphicon glyphicon-user"></i> <b>Staff ID:</b> {{ $staff_basic->staff_no }} 
                            <br />
                            <i class="glyphicon glyphicon-briefcase"></i> <b>Company:</b> {{ $staff_basic->companyid->company_descr }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Cost Center:</b> {{ $staff_detail->costcentr }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Ot Salary Exception:</b> 
                            @if ($staff_basic->ot_salary_exception == '1') 
                            YES
                            @elseif ($staff_basic->ot_salary_exception == '0') 
                            NO
                            @else 
                            N/A
                            @endif
                            <br />
                            <i class="glyphicon glyphicon-time"></i> <b>Ot Hour Exception:</b>                            
                            @if ($staff_basic->ot_hour_exception ?? '') 
                            N/A
                            @else 
                            {{ $staff_basic->ot_hour_exception }}
                            @endif
                            <br />
                            <i class="glyphicon glyphicon-envelope"></i> <b>Email:</b> {{ $staff_basic->email }} 
                            <br />
                        </p>                     
                    </div>
            
        </div>
            <!-- /.box-body -->
    </div>
</div>
<div class="col-md-6">
    <div class="box box-warning boxfullheight">
        <div class="box-header with-border">
              <i class="glyphicon glyphicon-queen"></i>
              <h3 class="box-title">Direct Report</h3>
        </div>
            <!-- /.box-header -->
        <div class="box-body">
        <div class="col-sm-6 col-md-4">
                        <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" alt="" class="img-rounded img-responsive" />
        </div>
                    <div class="col-sm-6 col-md-8">
                        <h4>{{ $direct_report->name }}</h4>
                        <small>
                        <cite title="{{ $staff_detail->empsgroup }}">{{ $direct_report_detail->empsgroup }} ({{ $direct_report_detail->empgroup }})</cite></small>
                        <p>
                            <i class="glyphicon glyphicon-user"></i> <b>Staff ID:</b> {{ $direct_report->staff_no }} 
                            <br />
                            <i class="glyphicon glyphicon-briefcase"></i> <b>Company:</b> {{ $direct_report->companyid->company_descr }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Cost Center:</b> {{ $direct_report_detail->costcentr }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Ot Salary Exception:</b> 
                            @if ($direct_report->ot_salary_exception == '1') 
                            YES
                            @elseif ($direct_report->ot_salary_exception == '0') 
                            NO
                            @else 
                            N/A
                            @endif
                            <br />
                            <i class="glyphicon glyphicon-time"></i> <b>Ot Hour Exception:</b>                            
                            @if ($direct_report->ot_hour_exception ?? '') 
                            N/A
                            @else 
                            {{ $direct_report->ot_hour_exception }}
                            @endif
                            <br />
                            <i class="glyphicon glyphicon-envelope"></i> <b>Email:</b> {{ $direct_report->email }} 
                            <br />
                        </p>                     
            </div>
            
        </div><!-- /.box-body -->            
    </div><!-- /.box box-solid -->  
</div><!-- /.col-md-6-->  

</div><!-- /.row -->


<div class="row-eq-height">

<div class="col-md-6">

<div class="box box-warning boxfullheight">
        <div class="box-header with-border">
        <i class="glyphicon glyphicon-briefcase"></i>
              <h3 class="box-title">Company</h3>
        </div>
            <!-- /.box-header -->
        <div class="box-body">

        <div class="col-sm-6 col-md-4">
                        <img src="https://www.tm.com.my/style%20library/tmap/images/tm-logo-200%20x%20137.png"  alt="" class="img-rounded img-responsive" />
                    </div>
                    <div class="col-sm-6 col-md-8">
                        <h4>{{ $staff_basic->companyid->company_descr}}</h4>
                            <p>
                            <i class="glyphicon glyphicon-user"></i> <b>Personal Area:</b> {{ $staff_psubarea->perssubareades }} 
                            <br />
                            <i class="glyphicon glyphicon-briefcase"></i> <b>Personal Subarea:</b> {{ $staff_psubarea->persareadesc }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Cost Center:</b> {{ $staff_detail->costcentr }}
                            <br />   
                            <br />
                            <br />
                            <br />
                        </p>                     
                    </div>
            
        </div>
            <!-- /.box-body -->
    </div>

</div>

<div class="col-md-6">

<div class="box box-warning boxfullheight">
        <div class="box-header with-border">
              <i class="glyphicon glyphicon-user"></i>
              <h3 class="box-title">Verifier</h3>
        </div>
            <!-- /.box-header -->
        <div class="box-body">
        
        <div class="col-sm-6 col-md-4">
                        <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png"  alt="" class="img-rounded img-responsive" />
        </div>
                    <div class="col-sm-6 col-md-8">
                        <h4>{{ $direct_report->name}}</h4>
                            <p>
                            <i class="glyphicon glyphicon-user"></i> <b>Staff ID:</b> {{ $direct_report->staff_no }} 
                            <br />
                            <i class="glyphicon glyphicon-briefcase"></i> <b>Company:</b> {{ $direct_report->companyid->company_descr }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Cost Center:</b> {{ $direct_report_detail->costcentr }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Ot Salary Exception:</b> 
                            @if ($direct_report->ot_salary_exception == '1') 
                            YES
                            @elseif ($direct_report->ot_salary_exception == '0') 
                            NO
                            @else 
                            N/A
                            @endif
                            <br />
                            <i class="glyphicon glyphicon-time"></i> <b>Ot Hour Exception:</b>                            
                            @if ($direct_report->ot_hour_exception ?? '') 
                            N/A
                            @else 
                            {{ $direct_report->ot_hour_exception }}
                            @endif
                            <br />
                            <i class="glyphicon glyphicon-envelope"></i> <b>Email:</b> {{ $direct_report->email }} 
                            <br />
                        </p>                     
            </div>            

            </div><!-- /.box-body -->            
    </div><!-- /.box box-solid -->  
</div><!-- /.col-md-6-->  

</div><!-- /.row -->

<div class="row">
<div class="col-md-12">
<div class="col-md-12">



<div class="box box-warning">
        <div class="box-header with-border">
              <i class="glyphicon glyphicon-queen"></i>
              <h3 class="box-title">Subordinates</h3>
        </div>
            <!-- /.box-header -->
        <div class="box-body">

@if($list_subord ?? '')
<p>No Subordinates</p>
@else
@foreach ($list_subord as $row_subord)
    <div class="col-md-3">
          <div class="panel panel-warning">
            <div class="panel-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="/vendor/images/useravatar.png" alt="User profile picture">

              <h3 class="profile-username text-center">{{ $row_subord->name }} </h3>

              <p class="text-muted text-center">Software Engineer</p>
              <div class="col-md-12" style="word-wrap: break-word">
              <i class="glyphicon glyphicon-user"></i> <b>Staff ID:</b> {{ $row_subord->staff_no }} 
                            <br />
                            <i class="glyphicon glyphicon-briefcase"></i> <b>Company:</b> {{ $row_subord->companyid->company_descr }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Cost Center:</b> {{ $row_subord->costcentr }}
                            <br />
                            <i class="glyphicon glyphicon-usd"></i> <b>Ot Salary Exception:</b> 
                            @if ($row_subord->ot_salary_exception == '1') 
                            YES
                            @elseif ($row_subord->ot_salary_exception == '0') 
                            NO
                            @else 
                            N/A
                            @endif
                            <br />
                            <i class="glyphicon glyphicon-time"></i> <b>Ot Hour Exception:</b>                            
                            @if ($row_subord->ot_hour_exception ?? '') 
                            N/A
                            @else 
                            {{ $row_subord->ot_hour_exception }}
                            @endif
                            <br />
                            <i class="glyphicon glyphicon-envelope"></i> <b>Email:</b> {{ $row_subord->email }} 
                            <br />
            </div>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
@endforeach
@endif
        </div><!-- /.box-body -->            
</div><!-- /.box box-warning -->  

</div><!-- /.col-md-12-->  
</div><!-- /.col-md-12-->  
</div><!-- /.row -->



@stop
