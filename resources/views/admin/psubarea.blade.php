@extends('adminlte::page')

@section('title', 'Psubarea List')

@section('content')

<h1>Subarea Management</h1>
<div class="panel panel-default panel-main">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Subarea Management</strong></div>
		<div class="panel-body">
			<div class="table-responsive" >
				<table id="tPsubareaList" class="table table-hover table-bordered" >
					<thead>
						<tr>
								<th>Company</th>
								<th>Persarea</th>
								<th>Perssubarea</th>
								<th>State</th>
								<th>Region</th>
								<th>Action</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($psubareas as $no => $psubarea)
						<tr>
								<td>@if($psubarea->companyid ?? '') {{ $psubarea->companyid->id }} - {{ $psubarea->companyid->company_descr}} @else N/A @endif</td>
								<td>{{ $psubarea->persarea }} - {{ $psubarea->persareadesc }}</td>
								<td>{{ $psubarea->perssubarea }} - {{ $psubarea->perssubareades }}</td>
								<td>@if($psubarea->stateid ?? '') {{ $psubarea->stateid->id }} - {{ $psubarea->stateid->state_descr}}@endif </td>
								<td>{{ $psubarea->region }}</td>
								<td>
									<form method="post" action="{{ route('psubarea.delete', [], false) }}" data-compdescr="{{ $psubarea->perssubarea }} - {{$psubarea['perssubareades']}}" id="formdelete-{{$no}}">
										@csrf
										<button type="button" class="btn btn-np" title="Edit"
												id="edit-{{$no}}"
												data-id="{{$psubarea->id}}"
												data-comp="{{$psubarea->company_id}}"
												data-area="{{$psubarea->persarea}}"
												data-aread="{{$psubarea->persareadesc}}"
												data-sub="{{$psubarea->perssubarea}}"
												data-subd="{{$psubarea->perssubareades}}"
												data-state_id="{{$psubarea->state_id}}"
												data-reg="{{$psubarea->region}}"
												>
												<i class="fas fa-pencil-alt"></i>
										</button>
										<button type="button" class="btn btn-np" title="Delete" id="del-{{$no}}">
												<i class="fas fa-trash-alt"></i>
										</button>
										<input type="hidden" name="inputid" value="{{$psubarea->id}}">
									</form>
								</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="line"></div>
			<h4><b>Add New Personnel Subarea</b></h4>
		<!-- add Psubarea -->
			<form action="{{ route('psubarea.store', [], false) }}" method="post">
			@csrf
				<div class="row">
					<div class="col-md-6">
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputcomp">Company</label><br>
							</div>
							<div class="col-md-9">
								<select name="inputcomp" id="inputcomp" required style="width: 100%">
										<option value="" hidden disabled selected>Please Select</option>
										@foreach($companies as $singlecompany)
										<option value="{{$singlecompany->id}}">{{$singlecompany->id}} - {{$singlecompany->company_descr}}</option>
										@endforeach
								</select>
							</div>
						</div>
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputparea">Personnel Area</label>
							</div>
							<div class="col-md-9">
								<input type="text" id="inputparea" name="inputparea"  value="{{ old('inputparea') }}"  style="width: 100%" required autofocus>
							</div>
						</div>
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputparea">Personnel Area Desc</label>
							</div>
							<div class="col-md-9">
								<input type="text" id="inputparead" name="inputparead" value="{{ old('inputparead') }}" style="width: 100%" required autofocus>
							</div>
						</div>
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputparea">Personnel Subarea</label>
							</div>
							<div class="col-md-9">
							<input type="text" id="inputpsubarea" name="inputpsubarea"  value="{{ old('inputpsubarea') }}" style="width: 100%" required autofocus>
							</div>
						</div>
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputparea">Personnel  Subarea Desc</label>
							</div>
							<div class="col-md-9">
							<input type="text" id="inputpsubaread" name="inputpsubaread"  value="{{ old('inputpsubaread') }}" style="width: 100%" required autofocus>
							</div>
						</div>
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputcomp">State</label><br>
							</div>
							<div class="col-md-9">
								<select name="inputstate" id="inputstate" required style="width: 100%">
										<option value="" hidden disabled selected>Please Select</option>
										@foreach($states as $singlestate)
										<option value="{{$singlestate->id}}">{{$singlestate->id}} - {{$singlestate->state_descr}}</option>
										@endforeach
								</select>
							</div>
						</div>
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputparea">Region</label>
							</div>
							<div class="col-md-9">
								<select type="text" id="inputregion" name="inputregion" style="width: 100%" required autofocus>

										<option value="" hidden disabled selected>Please Select</option>
										<option value="SEM">SEM</option>
										<option value="SWK">SWK</option>
										<option value="SBH">SBH</option>

								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
				<div class="panel-footer">
					<div class="text-right">
							<button type="submit" class="btn btn-p btn-primary">SUBMIT</button>
					</div>
				</div>
			</form>

        <form action="{{ route('psubarea.edit') }}" method="POST" id="edit" class="hidden">
            @csrf
						<input type="text" class="form-control hidden" id="editid" name="inputid" value="">
						<input type="text" class="form-control" id="editcomp" name="inputcomp" value="" disabled>
						<input type="text" class="form-control" id="editparea" name="inputparea" value="" required autofocus>
						<input type="text" class="form-control" id="editparead" name="inputparead" value="" required autofocus>
						<input type="text" class="form-control" id="editpsubarea" name="inputpsubarea" value="" required autofocus>
						<input type="text" class="form-control" id="editpsubaread" name="inputpsubaread" value="" required autofocus>
						<input type="text" name="inputstate" id="editstate" required style="width: 250px">
            <input type="text" class="form-control" id="editregion" name="inputregion" value="" required autofocus>
        </form>
        </div>
    </div>
</div>

@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {
    $('#tPsubareaList').DataTable({
        "responsive": "true",
        "order" : [[0, "asc"]]
    });
});


for(i = 0; i<{{count($psubareas)}}+1; i++){
	$("#edit-"+i).on("click", edit(i));
	$("#del-"+i).on("click", deleteid(i));
}

function deleteid(i){

	return function(){
		var ps_comp = $("#formdelete-"+i).data('compdescr');
		Swal.fire({
			title: 'Are you sure?',
			text: "Delete Personnel Subarea "+ps_comp+"?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'DELETE',
			cancelButtonText: 'CANCEL'
			}).then((result) => {
			if (result.value) {
				$("#formdelete-"+i).submit();
			}
		})
	}
}

function edit(i){
	return function(){
		var ps_id = $("#edit-"+i).data('id');
		var ps_comp = $("#edit-"+i).data('comp');
		var ps_area = $("#edit-"+i).data('area');
		var ps_aread = $("#edit-"+i).data('aread');
		var ps_sub = $("#edit-"+i).data('sub');
		var ps_subd = $("#edit-"+i).data('subd');
    	//var ps_state = $("#edit-"+i).data('state');
		var ps_state_id = $("#edit-"+i).data('state_id');
		var ps_reg = $("#edit-"+i).data('reg');
        var html = "<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Company Code</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='sps_comp' class='check-0' value='"+ps_comp+"' style='width: 100%' disabled>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Personnel Area</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='ps_area' class='check-1' value='"+ps_area+"' style='width: 100%' required>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Personnel Area Desc</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='ps_aread' class='check-2' value='"+ps_aread+"' style='width: 100%' required>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Personnel Subarea</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='ps_sub' class='check-3' value='"+ps_sub+"' style='width: 100%' required>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Personnel Subarea Desc</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='ps_subd' class='check-4' value='"+ps_subd+"' style='width: 100%' required>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>State</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
						"<select id='ps_state_id' class='check-5' value='"+ps_state_id+"' style='width: 100%'>";
						@foreach($states as $singlestate)
							if(ps_state_id=='{{$singlestate->id}}'){
								html = html + "<option value='{{$singlestate->id}}' selected>{{$singlestate->id}}-{{$singlestate->state_descr}}</option>";
							}else{
								html = html + "<option value='{{$singlestate->id}}'>{{$singlestate->state_descr}}</option>";
							}
						@endforeach
						html = html + "</select>"+
					"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Region</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<select id='ps_reg' class='check-6' value='"+ps_reg+"' style='width: 100%' required>";
							@foreach($regs as $reg)
								if(ps_reg=='{{$reg->item2}}'){
									html = html + "<option value='{{$reg->item2}}' selected>{{$reg->item2}}</option>";
								}else{
									html = html + "<option value='{{$reg->item2}}'>{{$reg->item2}}</option>";
								}
							@endforeach
							html = html + "</select>"+
						"</div>"+
					"</div>";
        var submit = true;
		Swal.fire({
			title: 'Edit Subarea',
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
				for(i = 0; i<7; i++){
					if($('.check-'+i).get(0).checkValidity()==false){
						submit = false;
					}
				}
				if(submit){
					$('#editid').val(ps_id);
					$('#editcomp').val($("#ps_comp").val());
					$('#editparea').val($("#ps_area").val());
					$('#editparead').val($("#ps_aread").val());
					$('#editpsubarea').val($("#ps_sub").val());
					$('#editpsubaread').val($("#ps_subd").val());
					$('#editstate').val($("#ps_state_id").val());
					$('#editregion').val($("#ps_reg").val());
					$('#edit').submit();
				}else{
					Swal.fire({
						title: "Incomplete Form",
						html: "Please fill in all input fields before saving",
						confirmButtonText: 'OK'
					}).then((result) => {
						edit(i);
					})
				}
			}
		})
	}
}

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif
// $('#deletePsubarea').on('show.bs.modal', function(e) {
//     populate(e);
// });

// @if(session()->has('feedback'))
//     $('#feedback').modal('show');
// @endif

</script>
@stop
