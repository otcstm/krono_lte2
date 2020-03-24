<h1>Dashboard</h1>

<div class="row-eq-height"> 

  <div class="col-md-3 col-sm-6 col-xs-12">
  <a href="{{route('ot.formnew')}}"> 
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/ot-clock-icon.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading">Apply Claim</h4>
        <p>Apply New Overtime</p>
      </div>
    </div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">Overtime Application</h3>
    </div><!-- /.box-header -->
    </div>
  </a>  
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="box box-solid">
                        <div class="box-body">
    
<div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">My Work</h4>
    <p>My Work Schedule</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">Schedule</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @if(isset($act_payment_curr_month))
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="box box-solid">
<div class="box-body">
  <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/bill.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">Actual Payment</h4>
    <p>{{ date('F Y', strtotime(now())) }}</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">RM {{ $act_payment_curr_month }}</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @endif

  @if(isset($act_payment_curr_month))
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/bill-3.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">Pending Payment</h4>
    <p>{{ date('F Y', strtotime($first_last_month)) }}</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">RM {{ $pending_payment_last_month }} (Estimated)</h3>
    </div><!-- /.box-header -->
    </div>
  </div>  
  @endif


@if(isset($total_hour_ot_curr_month))
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="box box-solid">
  <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading">{{ $total_hour_ot_curr_month }}/104 Hour</h4>
        <p>{{ date('F Y', strtotime(now())) }} </p>
      </div>
    </div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">Total OT Hour Monthly</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @endif  
  @if(isset($next_payment_sch))
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="box box-solid">
<div class="box-body">
    
<div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">Next Payment Date</h4>
    <p>{{ date('F Y', strtotime($first_next_month)) }}</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">Next Payment Date</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @endif  

  
  <div class="col-md-3 col-sm-6 col-xs-12">
    
  <a href="{{ route('punch.list', [], false) }}">
    <div class="box box-solid">
                        <div class="box-body">
  <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/stopwatch.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">List OT</h4>
    <p>Start/End Date</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">Display all clocking time</h3>
    </div><!-- /.box-header -->
    </div>
</a>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
  <a href="{{ route('ot.list', [], false) }}">  
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/claim.jpg" class="media-object" style="width:50px; height:50px;">
  </div>
  <div class="media-body">
    <h4 class="media-heading">List Claim</h4>
    <p>All Claim</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">Display all claims</h3>
    </div><!-- /.box-header -->
    </div>
</a>
  </div>

  
  <div class="col-md-3 col-sm-6 col-xs-12">
    <!-- <div class="box box-solid">
  <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/ot-clock-icon.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading">4 Planned OT</h4>
        <p>Request</p>
      </div>
    </div>
    </div>
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title">Planned OT Request</h3>
    </div>
    </div> -->
  </div>

</div><!-- /.row-eq-height -->

<!-- 
<div class="row">
<div class="col-md-3 col-sm-6 col-xs-12">
chart code put here
</div>
</div> -->


