@extends('adminlte::page')

@section('title', 'My Work Schedule')

@section('content')
<h1>My Work Schedule</h1>

<div class="row-eq-height">
  <div class="col-md-3 col-sm-6 col-xs-12 noPaddingLeft">
  <a href="{{route('ot.formnew')}}">
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
  <a href="{{route('ot.formnew')}}">
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
  <a href="{{route('ot.formnew')}}">
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
        <form>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">Work Schedule Rule</div>
                <div class="col-md-7">OFF1</div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">Start Date</div>
                <div class="col-md-7">OFF1</div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">End Date</div>
                <div class="col-md-7">OFF1</div>
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
                    <tr>
                        <td>Senin</td>
                        <td>8-5</td>
                    </tr>
                    <tr>
                        <td>Slasa</td>
                        <td>8-5</td>
                    </tr>
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
  loadTimeTable(2);
} );

function showEditForm(){
  document.getElementById("baten_form").className = "";
  document.getElementById("baten_edit").className = "hidden";
}

function cancelEdit(){
  location.reload(true);
}

function loadTimeTable(sp_id){
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

</script>
@stop
