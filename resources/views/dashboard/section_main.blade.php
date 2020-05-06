<h1>Dashboard</h1>

<div class="row row-eq-height"> 

  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <a href="{{ route('ot.formnew', [], false) }}">
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
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">Overtime Application</h3>
    </div><!-- /.box-header -->
    </div>
  </a>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12 ">      
    <a href="{{ route('staff.worksched', [], false) }}">
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
        <div class="box-header bg-yellow-active color-palette">
          <h3 class="box-title text-left">Schedule</h3>
        </div><!-- /.box-header -->
      </div>
    </a>
  </div>
  @if(isset($act_payment_curr_month))
  @if(date('D', strtotime(now())) <= 14)
  @if($act_payment_curr_month > 0)
  <div class="col-md-3 col-sm-6 col-xs-12 ">
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
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">RM {{ number_format((float)$act_payment_curr_month, 2, '.', '') }}</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @elseif(isset($act_payment_prev_month))
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <div class="box box-solid">
<div class="box-body">
  <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/bill.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">Actual Payment</h4>
    <p>{{ date('F Y', strtotime($first_last_month)) }}</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">RM {{ number_format((float)$act_payment_prev_month, 2, '.', '') }}</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @endif
  @else
  <div class="col-md-3 col-sm-6 col-xs-12 ">
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
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">RM {{ number_format((float)$act_payment_curr_month, 2, '.', '') }}</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @endif
  @endif

  @if(isset($pending_payment_last_month))
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/bill-3.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">Pending Payment</h4>
    <p>{{ date('F Y', strtotime(now())) }}</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">RM {{ $pending_payment_last_month }} (Estimated)</h3>
    </div><!-- /.box-header -->
    </div>
  </div>  
  @endif

</div><!-- /.row -->

  <div class="row row-eq-height"> 

@if(isset($total_hour_ot_curr_month))
  <div class="col-md-3 col-sm-6 col-xs-12 ">
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
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">Total OT Hour Monthly</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @endif  
  @if(isset($next_payment_sch))
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <div class="box box-solid">
<div class="box-body">
    
<div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">Next Payment Date</h4>
    <p>{{ date('d F Y', strtotime($next_payment_sch)) }}</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">Next Payment Date</h3>
    </div><!-- /.box-header -->
    </div>
  </div>
  @endif  

  
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    
  <a href="{{ route('punch.list', [], false) }}">
    <div class="box box-solid">
                        <div class="box-body">
  <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/stopwatch.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">Overtime List</h4>
    <p>Display all clocking time</p>
  </div>
</div>

</a>
    </div><!-- /.box-body -->
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">Display all clocking time</h3>
    </div><!-- /.box-header -->
    </div>
</a>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12 ">
  <a href="{{ route('ot.list', [], false) }}">  
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/claim.jpg" class="media-object" style="width:50px; height:50px;">
  </div>
  <div class="media-body">
    <h4 class="media-heading">Claim List</h4>
    <p>All Claim</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">Display all claims</h3>
    </div><!-- /.box-header -->
    </div>
</a>
  </div>

</div><!-- /.row -->

{{-- <div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12 ">
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
    <div class="box-header bg-yellow-active color-palette">
    <h3 class="box-title text-left">Planned OT Request</h3>
    </div>
    </div> -->
  </div>
</div> --}}


