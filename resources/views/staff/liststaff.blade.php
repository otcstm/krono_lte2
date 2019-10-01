@extends('adminlte::page')

@section('title', 'Staff List')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading panel-primary">List of Staff</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="tStaffList" class="table table-bordered">
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
                            <button type="button" class="btn btn-primary">
                                MANAGE
                            </button>
                            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewUser" data-staff_id="{{$singleuser['staff_no']}}" data-staff_name="{{$singleuser['name']}}" data-staff_email="{{$singleuser['email']}}">
                                MANAGE
                            </button> -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
        <p><b>Staff ID:</b></p>
        <p id="staff_id"></p>
        <p><b>Staff Name:</b></p>
        <p id="staff_name"></p>
        <p><b>Staff Email:</b></p>
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
$(document).ready(function() {
    $('#tStaffList').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]]
    });
} );

// $('#viewUser').on('show.bs.modal', function(e) {
//     //get data-id attribute of the clicked element
//     var staff_id = $(e.relatedTarget).data('staff_id');
//     var staff_name = $(e.relatedTarget).data('staff_name');
//     var staff_email = $(e.relatedTarget).data('staff_email');

//     //populate the textbox
//     $('#staff_id').text(staff_id);
//     $('#staff_name').text(staff_name);
//     $('#staff_email').text(staff_email);

// });
</script>
@stop