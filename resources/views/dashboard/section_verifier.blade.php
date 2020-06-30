<h1>Verifier</h1>
<div class="row row-eq-height"> 
  {{-- <div class="col-md-3 col-sm-6 col-xs-12 ">
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
  </div>  --}}
  
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <a href="{{ route('ot.verify', [], false) }}">
    <div class="box box-solid">
<div class="box-body">    
<div class="media">
  <div class="media-left">
    <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
  </div>
  <div class="media-body">
    <h4 class="media-heading">
      @if(isset($pending_verification_count))
      {{ $pending_verification_count }}                
      @else
      0        
      @endif
      Pending</h4>
    <p>Verification</p>
  </div>
</div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Pending OT for Verification</h3>
    </div><!-- /.box-header -->
    </div>
    </a>
  </div>  

  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <a href="{{ route('ot.verifyrept', [], false) }}">
    <div class="box box-solid">
        <div class="box-body">
            
            <div class="media">
              <div class="media-left">
                <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
              </div>
              <div class="media-body">
                <h4 class="media-heading">Claim Verification</h4>
                <p>Report</p>
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




