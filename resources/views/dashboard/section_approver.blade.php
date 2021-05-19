
<h1>Approver</h1>
<div class="row row-eq-height"> 
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/ot-clock-icon.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading text-left">Last Approval Date</h4>
        <p>{{ date('j F Y', strtotime($last_approval_date)) }}</p>
      </div>
    </div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Last Approval Date</h3>
    </div><!-- /.box-header -->
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <a href="{{ route('ot.approval', [], false) }}">
    <div class="box box-solid">
<div class="box-body">    
<div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">            
      @if(isset($pending_approval_count))
      {{ $pending_approval_count }}                
      @else
      0        
      @endif Pending</h4>
    <p>Approval</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Pending OT for Approval</h3>
    </div><!-- /.box-header -->
    </div>
    </a>
  </div>  
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    
  <a href="{{ route('verifier.listGroup', [], false) }}">

    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
    <div class="media-left">
      <img src="vendor/ot-assets/bill.jpg" class="media-object" style="width:50px">
    </div>
    <div class="media-body">
      <h4 class="media-heading">Set Default</h4>
      <p>Verifier</p>
    </div>
  </div>
      </div><!-- /.box-body -->
      <div class="box-header text-center bg-yellow-active color-palette">
      <h3 class="box-title text-left">Set Default Verifier</h3>
      </div><!-- /.box-header -->
      </div>
</a>

  </div>
  {{-- <div class="col-md-3 col-sm-6 col-xs-12 ">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/bill-3.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">OVERTIME</h4>
    <p>PLAN</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">List & Create OT Plan</h3>
    </div><!-- /.box-header -->
    </div>
  </div> --}}

  
  {{-- <div class="col-md-3 col-sm-6 col-xs-12 ">
    <div class="box box-solid">
                        <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading">3 MANPOWER</h4>
        <p>REQUEST</p>
      </div>
    </div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Manpower Request for Planned OT</h3>
    </div><!-- /.box-header -->
    </div>
  </div> --}}
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <a href="{{ route('ot.approvalrept', [], false) }}">
    <div class="box box-solid">
        <div class="box-body">
            
            <div class="media">
              <div class="media-left">
                <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
              </div>
              <div class="media-body">
                <h4 class="media-heading">Claim Approval</h4>
                <p>Report</p>
              </div>
            </div>
        </div><!-- /.box-body -->
        <div class="box-header text-center bg-yellow-active color-palette">
          <h3 class="box-title text-left">Claim Approval Report</h3>
        </div><!-- /.box-header -->
    </div>
    </a>
  </div>
 
</div><!-- /.row-eq-height-->




