@extends('adminlte::page')
@section('title', 'Location Demo')
@section('content')


<form method="POST" action="{{ route('demo.location', [], false) }}">
  @csrf
  <div class="form-group row">
    <label for="lat" class="col-md-4 col-form-label text-md-right">Latitude</label>
    <div class="col-md-6">
      <input type="text" class="form-control" name="lat" id="lat" placeholder="Location is" maxlength="300" readonly />
    </div>
  </div>
  <div class="form-group row">
    <label for="lon" class="col-md-4 col-form-label text-md-right">Longitude</label>
    <div class="col-md-6">
      <input type="text" class="form-control" name="lon" id="lon" placeholder="not enabled" maxlength="300" readonly />
    </div>
  </div>
  <div id="batens" class="form-group row mb-0">
    <div class="col text-center">
      <input type="hidden" class="form-control" name="submitForm" id="submitForm" value="1" />
      <button type="submit" class="btn btn-primary" name="action" value="checklocation" title="Masuk">Check-in Location</button>

    </div>
  </div>

</form>
<hr />
Loc Address:

@if($loc)

  <div class="row-eq-height">
    <div class="col-md-6 col-sm-6 col-xs-12 ">
      <div class="box box-solid">
        <div class="box-body">
          <h3>Address:</h3>
          
        {{$loc['formatted_address']}}
        </div><!-- /.box-body -->
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 ">
      <div class="box box-solid">
        <div class="box-body">
        {{var_dump($loc)}}
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>

  @else
No Address
  @endif 










  @stop

  @section('js')

  <script type="text/javascript">
    function getLoc() {

      if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(showPos, showError);
      } else {

        alert('Location not supported');
        document.getElementById('lat').value = "";
        document.getElementById('lon').value = "";
        document.getElementById('batens').classList.add('d-none');
      }
    }

    function showError(error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
          document.getElementById('lon').placeholder = "denied";
          break;
        case error.POSITION_UNAVAILABLE:
          document.getElementById('lon').placeholder = "unavailable. Try using chrome?";
          break;
        case error.TIMEOUT:
          document.getElementById('lon').placeholder = "not available - timed out";
          break;
        case error.UNKNOWN_ERROR:
          document.getElementById('lon').placeholder = ".. error?";
          break;
      }
      document.getElementById('lat').value = "";
      document.getElementById('lon').value = "";
      document.getElementById('batens').classList.add('d-none');
    }

    function showPos(position) {
      document.getElementById('lat').value = position.coords.latitude;
      document.getElementById('lon').value = position.coords.longitude;
    }

    $(document).ready(function() {

      getLoc();
    });
  </script>

  @stop