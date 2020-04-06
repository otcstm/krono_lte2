@extends('adminlte::page')

@section('title', 'My Work Schedule')

@section('content')
<h1>My Work Schedule</h1>

<div class="row-eq-height">
  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
  <a href="{{route('staff.worksched', ['page' => 'myc'])}}">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/calendar.jpg" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <p>View My Monthly Work Schedule</p>
      </div>
    </div>
    </div><!-- /.box-body -->
    </div>
  </a>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
  <a href="{{route('staff.worksched', ['page' => 'teamc'])}}">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/ot-clock-icon.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <p>View Team Work Schedule</p>
      </div>
    </div>
    </div><!-- /.box-body -->
    </div>
  </a>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
  <a href="{{route('staff.worksched', ['page' => 'reqs'])}}">
    <div class="box box-solid">
    <div class="box-body">
    <div class="media">
      <div class="media-left">
        <img src="vendor/ot-assets/ot-clock-icon.png" class="media-object" style="width:50px">
      </div>
      <div class="media-body">
        <p>View Status of Change Request</p>
      </div>
    </div>
    </div><!-- /.box-body -->
    </div>
  </a>
  </div>
</div>

@if($requests->count() > 0)
<div class="panel panel-primary">
  <div class="panel-heading">Work Schedule Change Request That Requires My Approval</div>
  <div class="panel-body p-3">
    <div class="table-responsive">
        <table id="pendingapp" class="table table-bordered table-condensed cell-border">
            <thead>
                <tr>
                  <th>Submission Date</th>
                  <th>Work Schedule Rule</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Staff Name</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
              @foreach($requests as $areq)
              <tr>
                <td>{{ $areq->created_at->format('d.m.Y') }}</td>
                <td>{{ $areq->shiftpattern->code }} : {{ $areq->shiftpattern->description }}</td>
                <td>{{ $areq->created_at->format('d.m.Y') }}</td>
                <td>{{ $areq->created_at->format('d.m.Y') }}</td>
                <td>{{ $areq->requestor->name }}</td>
                <td>
                  <button type="button" class="btn btn-np" title="Approve"
                     data-toggle="modal"
                     data-target="#sreqhandler"
                     data-id="{{ $areq->id }}"
                     data-code="Approve"
                  ><i class="fas fa-tick"></i></button>
                  <button type="button" class="btn btn-np" title="Reject"
                     data-toggle="modal"
                     data-target="#sreqhandler"
                     data-id="{{ $areq->id }}"
                     data-code="Reject"
                  ><i class="fas fa-tick"></i></button>
                </td>
              </tr>
              @endforeach
            </tbody>
        </table>
    </div>
  </div>
</div>
@endif

<div class="panel panel-primary">
  <div class="panel-heading">My List of Work Schedule Change Request</div>
  <div class="panel-body p-3">
    <div class="table-responsive">
        <table id="myreqs" class="table table-bordered table-condensed cell-border">
            <thead>
                <tr>
                  <th>Submission Date</th>
                  <th>Work Schedule Rule</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Approver Name</th>
                  <th>Status</th>
                  <th>Action Date</th>
                  <th>Remark</th>
                </tr>
            </thead>
            <tbody>
              @foreach($mine as $areq)
              <tr>
                <td>{{ $areq->created_at->format('d.m.Y') }}</td>
                <td>{{ $areq->shiftpattern->code }} : {{ $areq->shiftpattern->description }}</td>
                <td>{{ $areq->created_at->format('d.m.Y') }}</td>
                <td>{{ $areq->created_at->format('d.m.Y') }}</td>
                <td>{{ $areq->approver->name }}</td>
                <td>{{ $areq->status }}</td>
                <td>{{ $areq->action_date->format('d.m.Y') }}</td>
                <td>{{ $areq->remark }}</td>
              </tr>
              @endforeach
            </tbody>
        </table>
    </div>
  </div>
</div>

<div id="sreqhandler" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ route('staff.worksched.approve') }}" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Request Action</h4>
        </div>
        <div class="modal-body">
            <input type="text" class="form-control hidden" id="inputid" name="id" value="">
            <div class="form-group">
                <label for="inputname">Action</label>
                <input type="text" class="form-control" id="aaction" name="action" value="" readonly>
            </div>
            <div class="form-group">
                <label for="inputdesc">Remark</label>
                <input type="text" class="form-control" name="remark" value="" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>

  </div>
</div>

@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {
  $('#myreqs').DataTable();
  $('#pendingapp').DataTable();
} );

function populate(e){
    var wd_id = $(e.relatedTarget).data('id');
    var wd_code = $(e.relatedTarget).data('code')
    $('input[id=inputid]').val(wd_id);
    $('input[id=aaction]').val(wd_code);
}

$('#sreqhandler').on('show.bs.modal', function(e) {
    populate(e);
});

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif

</script>
@stop
