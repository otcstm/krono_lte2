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

<div class="panel panel-primary">
  <div class="panel-heading">My Current Work Schedule Rule</div>
  <div class="panel-body p-3">
    <div class="row">
      <div class="col-lg-8">
        <form method="post" action="{{ route('staff.worksched.edit')}}">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">Work Schedule Rule</div>
                <div class="col-md-7">
                  <select name="spid" id="sspid" onchange="loadTimeTable()" disabled>
                    @foreach($planlist as $aplan)
                    <option value="{{ $aplan->id }}" @if($aplan->id == $cspid) selected @endif>{{ $aplan->code }} : {{ $aplan->description }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">Start Date</div>
                <div class="col-md-7">
                  <input type="date" name="start_date" value="{{ $sdate->toDateString() }}" id="ssdate" readonly />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">End Date</div>
                <div class="col-md-7">
                  <input type="date" name="end_date" value="{{ $edate->toDateString() }}" id="esdate" readonly />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              &nbsp;
            </div>
            <div class="col-md-12">
              <div  id="baten_form">
                <div class="row">
                  <div class="col-md-8 text-center">
                    <button type="button" class="btn btn-sm btn-primary btn-outline" onclick="cancelEdit()">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                  </div>
                </div>
              </div>
              <div id="baten_edit">
                <div class="row" id="baten_edit">
                  <div class="col-md-8 text-center">
                    <button type="button" class="btn btn-sm btn-primary btn-outline" onclick="showEditForm()">Edit</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              &nbsp;
            </div>
          </div>
        </form>
      </div>
      <div class="col-lg-4">
        <p>Working Time</p>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed cell-border">
                <thead>
                    <tr class="info">
                        <th>Day</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody id="daylistt">
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {
  document.getElementById("baten_form").className = "hidden";
  loadTimeTable();
} );

function showEditForm(){
  document.getElementById("baten_form").className = "";
  document.getElementById("baten_edit").className = "hidden";
  document.getElementById("sspid").disabled = false;
  document.getElementById("ssdate").readOnly = false;
  document.getElementById("esdate").readOnly = false;
}

function cancelEdit(){
  location.reload(true);
}

function loadTimeTable(){

  var e = document.getElementById("sspid");
  var sp_id = e.options[e.selectedIndex].value;
  var search_url = "{{ route('staff.worksched.api.days', ['id' => '']) }}" + sp_id;

  $.ajax({
    url: search_url,
    success: function(result) {
      var tebel = document.getElementById("daylistt");
      tebel.innerHTML = "";
      result.forEach(function(item, index){
        tebel.innerHTML += "<tr><td>" + item.day + "</td><td>" + item.time + "</td></tr>"
      });
    },
    error: function(xhr){
      alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
  });

}

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif

</script>
@stop
