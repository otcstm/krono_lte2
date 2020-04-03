
<div class="row-eq-height"> 
  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/bill-3.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">4 PLANNED OT</h4>
    <p>REQUEST</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Planned OT Request</h3>
    </div><!-- /.box-header -->
    </div>
  </div> 

  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
    <a href="{{ route('ot.verify', [], false) }}">
    <div class="box box-solid">
<div class="box-body">    
<div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">{{ $pending_verification_count }} PENDING</h4>
    <p>VERIFICATION</p>
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
    <a href="{{ route('ot.verifyrept', [], false) }}">
    <div class="box box-solid">
        <div class="box-body">
            
            <div class="media">
              <div class="media-left">
                <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
              </div>
              <div class="media-body">
                <h4 class="media-heading">CLAIM VERIFICATION</h4>
                <p>REPORT</p>
              </div>
            </div>
        </div><!-- /.box-body -->
        <div class="box-header text-center bg-yellow-active color-palette">
          <h3 class="box-title text-left">Claim Verification Report</h3>
        </div><!-- /.box-header -->
    </div>
    </a>
  </div>
 
</div><!-- /.row-eq-height-->




