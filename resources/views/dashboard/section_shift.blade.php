<h1>Shift Management</h1>
<div class="row row-eq-height">
  {{-- <div class="col-md-3 col-sm-6 col-xs-12 ">
  <a href="{{ route('staff.worksched', ['page'=>'teamc'], false) }}">
  <div class="box box-solid">
  <div class="box-body">
  <div class="media">
    <div class="media-left">
      <img src="vendor/ot-assets/usradm_work_team_schedule.png" class="media-object" style="width:50px">
    </div>
    <div class="media-body">
      <h4 class="media-heading text-left">WORK TEAM SCHEDULE</h4>
     <!-- <p>DESC FUNCTION</p>-->
    </div>
  </div>
  </div><!-- /.box-body -->
  <div class="box-header text-center bg-yellow-active color-palette">
  <h3 class="box-title text-left">Work Team Schedule</h3>
  </div><!-- /.box-header -->
  </div>
</div> --}}
  @if(isset($is_shift_gowner) and $is_shift_gowner > 0)
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <a href="{{ route('shift.mygroup', [], false) }}">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/usradm_assign_shift_planner-members.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading text-left">Assign Shift</h4>
        <p>Planner/Members</p>
      </div>
    </div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Assign Shift Planner/Members</h3>
    </div><!-- /.box-header -->
    </div>
    </a>
  </div>
  @endif
  @if(isset($is_shift_gplanner) and $is_shift_gplanner > 0)
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <a href="{{ route('shift.index', [], false) }}">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/usradm_shift_planning.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading text-left">Shift Planning</h4>
        <!--<p>DESC FUNCTION</p>-->
      </div>
    </div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Shift Planning</h3>
    </div><!-- /.box-header -->
    </div>
    </a>
  </div>
  @endif
  @if(isset($is_shift_gapprover) and $is_shift_gapprover > 0)
  <div class="col-md-3 col-sm-6 col-xs-12 ">
    <a href="{{ route('shift.index', [], false) }}">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/usradm_shift_approval.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <h4 class="media-heading text-left">Shift Approval</h4>
        <!--<p>DESC FUNCTION</p>-->
      </div>
    </div>
    </div><!-- /.box-body -->
    <div class="box-header text-center bg-yellow-active color-palette">
    <h3 class="box-title text-left">Shift Planning Approval</h3>
    </div><!-- /.box-header -->
    </div>
    </a>
  </div>
  @endif

</div>
