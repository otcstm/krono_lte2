 @extends('adminlte::page')

@section('title', 'List Activity Logs')

@section('content')

<h1>List Activity Logs</h1>

    <div class="panel panel-default">
        <div class="panel-body">
              <div class="table-responsive">
                <table id="userLogs" class="table table-hover">
                  <thead>
        <tr>
            <th>No</th>
            <th>Date Time</th>
            <!-- <th>ID</th> -->
            <th>User</th>
            <th>Module</th>
            <th>Activity Type</th>
            <th>IP Address</th>
            <th>User Agent</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listUserLogs as $userLog)
        <tr>
            <td></td>
            <td>{{ $userLog->created_at }}</td>
            <!-- <td>{{ $userLog->id }}</td> -->
            <td>{{ $userLog->getUserTbl->name }}</td>
            <td>{{ $userLog->module_name }}</td>
            <td>{{ $userLog->activity_type }}</td>
            <td>{{ $userLog->ip_address }}</td>
            <td>{{ $userLog->user_agent }}</td>
           
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
  var t = $('#userLogs').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]],
      
      dom: '<"flext"lB>rtip',
      buttons: [
            'csv', 'excel', 'pdf'
        ]
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
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