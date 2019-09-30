@extends('adminlte::page')

@section('title', 'Search Staff')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading panel-primary">Search Staff</div>
    <div class="panel-body">
        <!-- <h3>Search User</h3> -->
        <form action="{{ route('staff.dosearch') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" id="inputstaffid" name="inputstaffid" placeholder="Enter {{ __('adminlte::adminlte.staff_no') }}" value="{{ old('inputstaffid') }}">
            </div>
            <button type="submit" class="btn btn-primary">SEARCH</button>
        </form>

        @if($search===1)
        <h3>Search Result</h3>
        @if(count($staffs)>0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Staff Name</th>
                    <th>Staff Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $singleuser)
                <tr>
                    <td>{{ $singleuser->staff_no }}</td>
                    <td>{{ $singleuser->name }}</td>
                    <td>{{ $singleuser->email }}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewUser" data-staff_id="{{$singleuser['staff_no']}}" data-staff_name="{{$singleuser['name']}}" data-staff_email="{{$singleuser['email']}}">
                            MANAGE
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No staff found. Try to search again.</p>
        @endif
        @endif
    </div>
  </div>
</div>

<!-- Modal-->
<div id="viewUser" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Staff Details</h4>
      </div>
      <div class="modal-body">
        <p><b>Staff ID</b></p>
        <p id="staff_id"></p>
        <p><b>Staff Name</b></p>
        <p id="staff_name"></p>
        <p><b>Staff Email</b></p>
        <p id="staff_email"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$('#viewUser').on('show.bs.modal', function(e) {
    //get data-id attribute of the clicked element
    var staff_id = $(e.relatedTarget).data('staff_id');
    var staff_name = $(e.relatedTarget).data('staff_name');
    var staff_email = $(e.relatedTarget).data('staff_email');

    //populate the textbox
    $('#staff_id').text(staff_id);
    $('#staff_name').text(staff_name);
    $('#staff_email').text(staff_email);

});
</script>
@stop