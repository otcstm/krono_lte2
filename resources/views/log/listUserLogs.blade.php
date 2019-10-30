 @extends('adminlte::page')

@section('title', 'List Activity Logs')

@section('content')
     <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">List Activity Logs</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="userLogs" class="table table-hover">
                  <thead>
        <tr>
            <th>No</th>
            <!-- <th>ID</th> -->
            <th>User Name</th>
            <th>Module Name</th>
            <th>Activity Type</th>
            <th>IP Address</th>
            <th>User Agent</th>
            <th>Session ID</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listUserLogs as $userLog)
        <tr>
            <td>{{ ++$i }}</td>
            <!-- <td>{{ $userLog->id }}</td> -->
            <td>{{ $userLog->getUserTbl->name }}</td>
            <td>{{ $userLog->module_name }}</td>
            <td>{{ $userLog->activity_type }}</td>
            <td>{{ $userLog->ip_address }}</td>
            <td>{{ $userLog->user_agent }}</td>
            <td>{{ $userLog->session_id }}</td>
           
        </tr>
        @endforeach
      </tbody>
    </table>
  

              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
           <!--  <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div> -->
            <!-- /.box-footer -->
          </div>
          @stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#userLogs').DataTable({
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