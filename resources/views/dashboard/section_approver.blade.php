
<div class="row-eq-height"> 
  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/ot-clock-icon.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading text-left">LAST APPROVAL DATE</h4>
        <p>{{ date('j F Y', strtotime($last_approval_date)) }}</p>
      </div>
    </div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Last Approval Date</h3>
    </div><!-- /.box-header -->
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
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
      0    
      @else
      {{ $pending_approval_count }}          
      @endif PENDING</h4>
    <p>APPROVAL</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Pending OT for Verification</h3>
    </div><!-- /.box-header -->
    </div>
    </a>
  </div>  
  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
    
  <a href="{{ route('verifier.listGroup', [], false) }}">

    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
    <div class="media-left">
      <img src="vendor/ot-assets/bill.jpg" class="media-object" style="width:50px">
    </div>
    <div class="media-body">
      <h4 class="media-heading">SET DEFAULT</h4>
      <p>VERIFIER</p>
    </div>
  </div>
      </div><!-- /.box-body -->
      <div class="box-header text-center bg-yellow-active color-palette">
      <h3 class="box-title text-left">Set Default Verifier</h3>
      </div><!-- /.box-header -->
      </div>
</a>

  </div>
  {{-- <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
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

  
  {{-- <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
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
  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
    <a href="{{ route('ot.approvalrept', [], false) }}">
    <div class="box box-solid">
        <div class="box-body">
            
            <div class="media">
              <div class="media-left">
                <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
              </div>
              <div class="media-body">
                <h4 class="media-heading">CLAIM APPROVAL</h4>
                <p>REPORT</p>
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




