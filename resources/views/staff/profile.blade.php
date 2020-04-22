@extends('adminlte::page')
@section('title', 'User Profile')

@section('content')
<h3 class="box-title">Profile</h3>
<div class="row-eq-height">

<div class="col-md-6 col-xs-12">
<div class="panel panel-default boxfullheight">
        <div class="panel-heading">              
            </i> My Profile
        </div>
            <!-- /.box-header -->
        <div class="box-body">
      
        <div class="col-sm-6 col-md-4">
        <img src="{{route('user.image', ['staffno' => str_replace(' ','',$staff_basic->staff_no) ])}}" 
        alt="" class="profile-user-img img-rounded img-responsive img-zoom" />
{{--
        <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" alt="" class="img-rounded img-responsive" />
                    
                    --}}
                    </div>
                    <div class="col-sm-6 col-md-8">
                        <div id="msgtoread">
                        <h4>{{ $staff_basic->name }}</h4>
                        <small>
                        <cite title="{{ $staff_detail->empsgroup }}">{{ $staff_detail->empsgroup }} ({{ $staff_detail->empgroup }})</cite></small>
                        <p>
                            <b>Staff ID:</b> {{ $staff_basic->staff_no }} 
                            <br />
                            <b>Company:</b> {{ $staff_basic->companyid->company_descr }}
                            <br />
                            <b>Cost Center:</b> {{ $staff_detail->costcentr }}
                            <br />
                            <b>Ot Salary Exception:</b> 
                            @if ($staff_basic->ot_salary_exception == '1') 
                            YES
                            @elseif ($staff_basic->ot_salary_exception == '0') 
                            NO
                            @else 
                            N/A
                            @endif
                            <br />
                            <b>Ot Hour Exception:</b>                            
                            @if ($staff_basic->ot_hour_exception ?? '') 
                            N/A
                            @else 
                            {{ $staff_basic->ot_hour_exception }}
                            @endif
                            <br />
                            <b>Email:</b> {{ $staff_basic->email }} 
                            <br />
                        </p>                      
                        </div>              
                    </div>
            
        </div>
            <!-- /.box-body -->
    </div>
</div>
<div class="col-md-6 col-xs-12">
<div class="panel panel-default boxfullheight">
        <div class="panel-heading">              
              </i> Direct Report
        </div>
            <!-- /.box-header -->
        <div class="box-body">
        <div class="col-sm-6 col-md-4">
        <img src="{{route('user.image', ['staffno' => str_replace(' ','',$direct_report->staff_no)   ])}}" 
        alt="" class="profile-user-img img-rounded img-responsive img-zoom" />

        </div>
                    <div class="col-sm-6 col-md-8">
                        <h4>{{ $direct_report->name }}</h4>
                        <small>
                        <cite title="{{ $staff_detail->empsgroup }}">{{ $direct_report_detail->empsgroup }} ({{ $direct_report_detail->empgroup }})</cite></small>
                        <p>
                            <b>Staff ID:</b> {{ $direct_report->staff_no }} 
                            <br />
                            <b>Company:</b> {{ $direct_report->companyid->company_descr }}
                            <br />
                            <b>Cost Center:</b> {{ $direct_report_detail->costcentr }}
                            <br />
                            <!-- <b>Ot Salary Exception:</b> 
                            @if ($direct_report->ot_salary_exception == '1') 
                            YES
                            @elseif ($direct_report->ot_salary_exception == '0') 
                            NO
                            @else 
                            N/A
                            @endif
                            <br />
                            <b>Ot Hour Exception:</b>                            
                            @if ($direct_report->ot_hour_exception ?? '') 
                            N/A
                            @else 
                            {{ $direct_report->ot_hour_exception }}
                            @endif
                            <br /> -->
                            <b>Email:</b> {{ $direct_report->email }} 
                            <br />
                        </p>                     
            </div>
            
        </div><!-- /.box-body -->            
    </div><!-- /.box box-solid -->  
</div><!-- /.col-md-6-->  

</div><!-- /.row -->


<div class="row-eq-height">

<div class="col-md-6 col-xs-12">

<div class="panel panel-default boxfullheight">
        <div class="panel-heading"> 
        </i> Company
        </div>
            <!-- /.box-header -->
        <div class="box-body">

        <div class="col-sm-6 col-md-4">
                        <img src="https://www.tm.com.my/style%20library/tmap/images/tm-logo-200%20x%20137.png"  alt="" class="img-rounded img-responsive" />
                    </div>
                    <div class="col-sm-6 col-md-8">
                        <h4>{{ $staff_basic->companyid->company_descr}}</h4>
                            <p>
                            <b>Personal Area:</b> {{ $staff_psubarea->perssubareades }} 
                            <br />
                            <b>Personal Subarea:</b> {{ $staff_psubarea->persareadesc }}
                            <br />
                            <b>Cost Center:</b> {{ $staff_detail->costcentr }}
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

@if(!empty($verifier_detail))
<div class="col-md-6  col-xs-12">

<div class="panel panel-default boxfullheight">
        <div class="panel-heading">   
        <i class="glyphicon glyphicon-user"></i> Verifier
        </div>
            <!-- /.box-header -->
        <div class="box-body">
        
        <div class="col-sm-6 col-md-4">
            <img src="{{route('user.image', ['staffno' => str_replace(' ','',$verifier_detail->staff_no) ])}}" 
            alt="" class="profile-user-img img-rounded img-responsive img-zoom" />
                        {{-- <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png"  alt="" class="img-rounded img-responsive" /> --}}
        </div>
                    <div class="col-sm-6 col-md-8">
                        <h4>{{ $verifier_detail->name}}</h4>
                            <p>
                            <b>Staff ID:</b> {{ $verifier_detail->staff_no }} 
                            <br />
                            <b>Company:</b> {{ $verifier_detail->companyid->company_descr }}
                            <br />
                            <b>Cost Center:</b> {{ $verifier_detail->costcentr }}
                            <br />
                            <!--<b>Ot Salary Exception:</b> 
                            @if ($verifier_detail->ot_salary_exception == '1') 
                            YES
                            @elseif ($verifier_detail->ot_salary_exception == '0') 
                            NO
                            @else 
                            N/A
                            @endif
                            <br />
                            <b>Ot Hour Exception:</b>                            
                            @if ($verifier_detail->ot_hour_exception ?? '') 
                            N/A
                            @else 
                            {{ $verifier_detail->ot_hour_exception }}
                            @endif
                            <br /> -->
                            <b>Email:</b> {{ $verifier_detail->email }} 
                            <br />
                        </p>                     
            </div>            

            </div><!-- /.box-body -->            
    </div><!-- /.box box-solid -->  
</div><!-- /.col-md-6-->  
@endif

</div><!-- /.row -->

<h3 class="box-title">Subordinates @if($list_subord->count() > 0) ({{ $list_subord->count() }}) @endif</h3>
@if($list_subord->count() == 0)
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
            <!-- /.box-header -->
        <div class="box-body">
<p>No Subordinates</p>
            <!-- /.box-body -->
          </div>
</div>
</div><!-- /.col-md-12-->  
</div><!-- /.row -->

@else

@php $countrow=0 @endphp
@foreach ($list_subord as $row_subord)

@if($countrow % 4 === 0)
<div class="row-eq-height">
@endif

@php $countrow++ @endphp
    <div class="col-md-3">
          <div class="panel panel-default boxfullheight">
            <div class="panel-heading">
            Subordinate {{ $countrow }}
            </div>
            <div class="panel-body box-profile">
{{-- <img src="{{route('user.image', ['staffno' => str_replace(' ','',$verifier_detail->staff_no) ])}}" alt="" class="img-rounded img-responsive" /> --}}
<img class="profile-user-img img-responsive img-circle img-zoom" 
src="{{route('user.image', ['staffno' => str_replace(' ','',$row_subord->staff_no) ])}}" 
alt="User profile picture">

              <h3 class="profile-username text-center">{{ $row_subord->name }} </h3>

              <p class="text-muted text-center">{{ $row_subord->userrecordLatest->empsgroup }} </p>
              <div class="col-md-12" style="word-wrap: break-all">
              <b>Staff ID:</b> {{ $row_subord->staff_no }} 
                            <br />
                            <b>Company:</b> {{ $row_subord->companyid->company_descr }}
                            <br />
                            <b>Cost Center:</b> {{ $row_subord->costcentr }}
                            <br />
                            <b>Ot Salary Exception:</b> 
                            @if ($row_subord->ot_salary_exception == '1') 
                            YES
                            @elseif ($row_subord->ot_salary_exception == '0') 
                            NO
                            @else 
                            N/A
                            @endif
                            <br />
                            <b>Ot Hour Exception:</b>                            
                            @if ($row_subord->ot_hour_exception ?? '') 
                            N/A
                            @else 
                            {{ $row_subord->ot_hour_exception }}
                            @endif
                            <br />
                            <b>Email:</b>
                            <span style="word-break: break-all">{{ $row_subord->email }}</span> 
                            <br />
            </div>  
            </div>
        </div>
    </div>
@if($countrow % 4 === 0)
</div><!-- /.row-eq-height -->
@endif
@endforeach

@endif

@stop


