@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop


@section('title', 'Payment Schedule')

@section('content')
<h1>Payment Schedule</h1>
<div class="panel panel-default panel-main">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Payment Schedule Management</strong></div>
		 <div class="panel-body">
			<div>
				<input type="hidden" name="slctyr" id="slctyr_hidden" value="{{$slctyr}}" />
				<form  method="post" class="form-inline" id="fselectyear" >
					@csrf
					<label for="email">Select Year :</label>
					<select name="slctyr" id="slctyr_id" class="form-control">
									@foreach ($list_year as $yr)
									<option>{{$yr}}</option>
									@endforeach
							</select>
				</form>
			</div>
		</div> 
		<div class="panel-body">
			<div class="table-responsive" >
				<table id="tpayment_sche" class="table table-hover table-bordered" >
					<thead>
						<tr>
							<!-- <th>Year</th> -->
							<th>Month</th>
							<th>Group</th>
							<th>Last Submission Date</th>
							<th>Last Approval Date</th>
							<th>Interface Date</th>
							<th>Payment Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($ps_list as $no => $ps)
						<tr>
							<td>
								<span style="display:none">{{ $ps->interface_date->format('Ymd') }}</span>
								{{ date("F", strtotime($ps->payment_date)) }}</td>
							<td>{{ $ps->payrollgroupid->pygroup }}</td>
							<td>{{ $ps->last_sub_date->format('d/m/Y') }}</td>
							<td>{{ $ps->last_approval_date->format('d/m/Y') }}</td>
							<td>{{ $ps->interface_date->format('d/m/Y') }}</td>
							<td>{{ $ps->payment_date->format('d/m/Y') }}</td>
							<td>
								<form method="post" action="{{ route('paymentsc.delete', [], false) }}" id="deln-{{$no}}">
									@csrf
									<button type="button" class="btn btn-np" id="edit-{{$no}}"
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
									<button type="button" data-ls="{{$ps->last_sub_date->format('Y-m-d')}}"
										data-ad="{{$ps->last_approval_date->format('Y-m-d')}}"
										data-intd="{{$ps->interface_date->format('Y-m-d')}}"
										data-pd="{{$ps->payment_date->format('Y-m-d')}}" id="btn-dell-{{$no}}" class="btn btn-np" title="Delete">
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
			<div class="line"></div>
			<h4><b>Add Payment Schedule</b></h4>
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
			</div>
			<div class="panel-footer">
				<div class="text-right">
					<button type="submit" class="btn btn-p btn-primary">SUBMIT</button>
				</div>
			</div>
		</form>
	</div>
</div>

<form action="{{ route('paymentsc.edit') }}" method="POST" class="hidden" id="editn">
	@csrf
	<input type="text" id="editid" name="inputid" value="">
	<input name="inpyg" id="editpyg" required>
	<input type="date" id="editsub" name="inputsub"  value="" required autofocus>
	<input type="date" id="editapp" name="inputapp" value="" required autofocus>
	<input type="date" id="editint" name="inputint" value="" required autofocus>
	<input type="date" id="editpay" name="inputpay" value="" required autofocus>
</form>

@stop

@section('js')

<script type="text/javascript">
$("#slctyr_id").val('{{$slctyr}}');

$(document).ready(function() {
    $('#tpayment_sche').DataTable({
		"lengthMenu": [[12, 24, 36, -1], [12, 24, 36, "All"]],
        "responsive": "true",
				"order" : [[0, "asc"]],
				"columnDefs": [
					 { "width": "4%", "targets": 6 }
				 ]
	});
});



</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#slctyr_id').change(function(){
      	$('#fselectyear').submit();
      });
   });
   for(i = 0; i<{{count($ps_list)}}+1; i++){
		$("#edit-"+i).on("click", edit(i));
		$("#btn-dell-"+i).on("click", deleteid(i));
	}
	function edit(i){
		return function(){
			var id = $("#edit-"+i).data('id');
			var payrollgroup_id = $("#edit-"+i).data('payrollgroup_id');
			var ls = $("#edit-"+i).data('ls');
			var ad = $("#edit-"+i).data('ad')
			var intd = $("#edit-"+i).data('intd')
			var pd = $("#edit-"+i).data('pd')
			var html = "<div class='row'>"+
							"<div class='col-md-4'>"+
								"<p>Payroll Group: </p>"+
							"</div>"+
							"<div class='col-md-8'>"+
								"<select id='payrollgroup_ids' class='check-0' value='"+payrollgroup_id+"' style='width: 100%' disabled>";
									@foreach($pygroups as $pygroup)
										if(payrollgroup_id=={{$pygroup->id}}){
											html = html + "<option value='{{$pygroup->id}}' selected>{{$pygroup->pygroup}}</option>";
										}else{
											html = html + "<option value='{{$pygroup->id}}'>{{$pygroup->pygroup}}</option>";
										}
									@endforeach
							html = html + "</select>"+
							"</div>"+
							"<div class='col-md-4'>"+
								"<p>Last Submission Date: </p>"+
							"</div>"+
							"<div class='col-md-8'>"+
								"<input type='date' id='lss' class='check-1' value='"+ls+"' style='width: 100%' >"+
							"</div>"+
							"<div class='col-md-4'>"+
								"<p>Approval Date: </p>"+
							"</div>"+
							"<div class='col-md-8'>"+
								"<input type='date' id='ads' class='check-2' value='"+ad+"' style='width: 100%' >"+
							"</div>"+
							"<div class='col-md-4'>"+
								"<p>Interface Date: </p>"+
							"</div>"+
							"<div class='col-md-8'>"+
								"<input type='date' id='intd' class='check-3' value='"+intd+"' style='width: 100%' >"+
							"</div>"+
							"<div class='col-md-4'>"+
								"<p>Payment Date: </p>"+
							"</div>"+
							"<div class='col-md-8'>"+
								"<input type='date' id='pds' class='check-4' value='"+pd+"' style='width: 100%' >"+
							"</div>"+
						"</div>";
			var submit = true;
			Swal.fire({
				title: 'Edit Payment Schedule',
				html: "<div class='text-left'>"+html+
					"</div>",
				showCancelButton: true,
				customClass:'test4',
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'SAVE',
				cancelButtonText: 'CANCEL'
				}).then((result) => {
				if (result.value) {
					for(i = 0; i<5; i++){
						if($('.check-'+i).get(0).checkValidity()==false){
							submit = false;
						}
					}
					if(submit){
						$('#editid').val(id);
						$('#editpyg').val($("#payrollgroup_ids").val());
						$('#editsub').val($("#lss").val());
						$('#editapp').val($("#ads").val());
						$('#editint').val($("#intd").val());
						$('#editpay').val($("#pds").val());
						$('#editn').submit();
					}else{
						Swal.fire({
							title: "Incomplete Form",
							html: "Please fill in all input fields before saving",
							confirmButtonText: 'OK'
						}).then((result) => {
							edit(i);
						});
					}
				}
			})
		}
	};
function deleteid(i){
	return function(){
		var ls = $("#btn-dell-"+i).data('ls');
		var ad = $("#btn-dell-"+i).data('ad');
		var intd = $("#btn-dell-"+i).data('intd');
		var pd = $("#btn-dell-"+i).data('pd');
		Swal.fire({
			title: 'Are you sure?',
			html: "<div class='text-left'>Delete Payroll Group with details below?<br><br>"+
					"Last submittion date: "+ls+"<br>"+
					"Last approval date: "+ad+"<br>"+
					"Last interface date: "+intd+"<br>"+
					"Last payment date: "+pd+"</div>",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'DELETE',
			cancelButtonText: 'CANCEL'
			}).then((result) => {
			if (result.value) {
				$("#deln-"+i).submit();
			}
		})
	}
}
   @if(session()->has('feedback'))
    Swal.fire({
			icon: "{{session()->get('a_icon')}}",
			html: "{{session()->get('a_text')}}",
			confirmButtonText: 'OK'
    })
@endif
</script>

@stop
