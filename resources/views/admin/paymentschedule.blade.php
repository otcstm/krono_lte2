@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop


@section('title', 'Payment Schedule')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading"><strong>Payment Schedule</strong></div>

	<!-- <div class="panel-body">
		<div>
			<input type="hidden" name="slctyr" id="slctyr_hidden" value="{{$slctyr}}" />
			<form  method="post" class="form-horizontal" id="fselectyear" >
				@csrf
				<table width="200px"><tr><td width="100px"><strong>Select Year</strong></td>
					<td width="100px">
						<select name="slctyr" id="slctyr_id" class="form-control">
								@foreach ($list_year as $yr)
								<option>{{$yr}}</option>
								@endforeach
						</select>
					</td></tr></table>
			</form>
		</div>
	</div> -->
		<div class="panel-body">
		<div class="table-responsive" >
			<table id="tpayment_sche" class="table table-hover table-bordered" >
				<thead>
					<tr>
						<!-- <th>Year</th> -->
						<th>Group</th>
            <th>Last Submission Date</th>
            <th>Last Approval Date</th>
            <th>Interface Date</th>
            <th>Payment Date</th>
            <th>Created By</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
         @foreach($ps_list as $ps)
         	<tr>
					 <!-- <td>{{ $ps->year}}</td> -->
					 <td>{{ $ps->payrollgroupid->pygroup }}</td>
           <td>{{ $ps->last_sub_date->format('d/m/Y') }}</td>
 					 <td>{{ $ps->last_approval_date->format('d/m/Y') }}</td>
           <td>{{ $ps->interface_date->format('d/m/Y') }}</td>
           <td>{{ $ps->payment_date->format('d/m/Y') }}</td>
           <td>{{ $ps->created_by }}</td>
           <td>
								<form method="post" action="{{ route('paymentsc.delete', [], false) }}" onsubmit="return confirm('Are you sure you want to delete?')">
									@csrf
									<button type="button" class="btn btn-xs btn-warning" title="Edit"
											data-toggle="modal"
											data-target="#editfPsc"
											data-id="{{$ps->id}}"
											data-payrollgroup_id="{{$ps->payrollgroup_id}}"
											data-yr="{{$ps->year}}"
											data-ls="{{$ps->last_sub_date->format('Y-m-d')}}"
											data-ad="{{$ps->last_approval_date->format('Y-m-d')}}"
											data-intd="{{$ps->interface_date->format('Y-m-d')}}"
											data-pd="{{$ps->payment_date->format('Y-m-d')}}"
											>
											<i class="fas fa-pencil-alt"></i>
									</button>
									<button type="submit" class="btn btn-xs btn-danger" title="Delete">
											<i class="fas fa-trash-alt"></i>
									</button>
									<input type="hidden" name="inputid" value="{{$ps->id}}">
								</form>
							</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="panel panel-default">
  <div class="panel-heading"><strong>Add Payment Schedule</strong></div>
  <div class="panel-body">
    <form action="{{ route('paymentsc.store', [], false) }}" method="post" class="form-horizontal">
      @csrf
			<div class="form-group">
				<label for="pyg" class="control-label col-sm-2">Payroll Group</label>
				<div class="col-sm-10">
					<select class="form-control" name="pyg" id="pyg" required >
							<option value="" disabled selected>Select</option>
							@foreach($pygroups as $pygroup)
							<option value="{{$pygroup->id}}">{{$pygroup->pygroup}}</option>
							@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
        <label for="last_sub" class="control-label col-sm-2">Last Submission Date</label>
        <div class="col-sm-10">
          <input id="last_sub" type="date" name="last_sub" value="{{ old('last_sub') }}" required autofocus>
        </div>
			</div>
			<div class="form-group">
        <label for="last_approval" class="control-label col-sm-2">Last Approval Date</label>
        <div class="col-sm-10">
          <input id="last_approval" type="date" name="last_approval" value="{{ old('last_approval') }}" required autofocus>
        </div>
      </div>
			<div class="form-group">
        <label for="int_date" class="control-label col-sm-2">Interface Date</label>
        <div class="col-sm-10">
          <input id="int_date" type="date" name="int_date" value="{{ old('int_date') }}" required autofocus>
        </div>
      </div>
      <div class="form-group">
        <label for="pay_date" class="control-label col-sm-2">Payment Date</label>
        <div class="col-sm-10">
          <input id="pay_date" type="date" name="pay_date" value="{{ old('pay_date') }}" required autofocus>
        </div>
      </div>
      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>

<div id="editfPsc" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
					<form action="{{ route('paymentsc.edit') }}" method="POST">
	            @csrf
							<div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <h4 class="modal-title">Edit</h4>
	            </div>
							<div class="modal-body">
								<input type="text" class="form-control hidden" id="editid" name="inputid" value="">
								<div class="form-group">
										<label for="inpyg">Payroll Group</label>
										<select class="form-control" name="inpyg" id="editpyg" required>
												@foreach($pygroups as $spygroup)
												<option value="{{$spygroup->id}}">{{$spygroup->pygroup}}</option>
												@endforeach
										</select>
								</div>
								<div class="form-group">
										<label for="inputsub">Last Submission Date</label>
										<input type="date" class="form-control" id="editsub" name="inputsub"  value="" required autofocus>
								</div>
								<div class="form-group">
										<label for="inputapp">Last Approval Date</label>
										<input type="date" class="form-control" id="editapp" name="inputapp" value="" required autofocus>
								</div>
								<div class="form-group">
										<label for="inputint">Interface Date</label>
										<input type="date" class="form-control" id="editint" name="inputint" value="" required autofocus>
								</div>
								<div class="form-group">
										<label for="inputpay">Payment Date</label>
										<input type="date" class="form-control" id="editpay" name="inputpay" value="" required autofocus>
								</div>
						</div>
	            <div class="modal-footer">
	                <button type="submit" class="btn btn-primary">SAVE</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
	      </form>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
$("#slctyr_id").val('{{$slctyr}}');

$(document).ready(function() {
    $('#tpayment_sche').DataTable({
        "responsive": "true",
        "order" : [[0, "asc"]]
			// 	dom: 'Bfrtip',
			// 	buttons: [         {
            //     extend: 'copyHtml5',
            //     exportOptions: {
            //         columns:  ':visible'
            //     }
            // },
            // {
            //     extend: 'excelHtml5',
            //     exportOptions: {
            //         columns: ':visible'
            //     }
            // },
            // {
            //     extend: 'pdfHtml5',
            //     exportOptions: {
            //         columns: ':visible'
            //     }
            // },
			// 			{
			// 			                extend: 'colvis',
			// 			                collectionLayout: 'fixed three-column'
			// 			            }

			// 		]
});
});

function populate(e){
		var ps_id = $(e.relatedTarget).data('id');
		var ps_payrollgroup_id = $(e.relatedTarget).data('payrollgroup_id');
    var ps_lastsub = $(e.relatedTarget).data('ls');
    var ps_app = $(e.relatedTarget).data('ad');
		var ps_int = $(e.relatedTarget).data('intd');
		var ps_pay = $(e.relatedTarget).data('pd');
		$('input[name=inputid]').val(ps_id);
		$("#editpyg").val(ps_payrollgroup_id);
		// $('.showyear').text(ps_year);
		$('input[name=inputsub]').val(ps_lastsub);
		$('input[name=inputapp]').val(ps_app);
		$('input[name=inputint]').val(ps_int);
		$('input[name=inputpay]').val(ps_pay);
    }

$('#editfPsc').on('show.bs.modal', function(e) {
    populate(e);
});

</script>
<script type="text/javascript">

  $(document).ready(function(){
    $('#slctyr_id').change(function(){
      	$('#fselectyear').submit();
      });
   });
</script>

@stop
