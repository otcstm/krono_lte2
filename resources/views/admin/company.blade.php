@extends('adminlte::page')

@section('title', 'Company List')

@section('content')
<h1>Company Management</h1>

<div class="panel panel-default panel-main">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Company Management</strong></div>
		<div class="panel-body">
			<div class="table-responsive" >
				<table id="tCompanyList" class="table table-hover table-bordered" >
					<thead>
						<tr>
								<th>Company ID</th>
								<th>Company Description</th>
								<th>Created by</th>
								<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($companies as $no => $company)
						<tr>
								<td>{{ $company->id }}</td>
								<td>{{ $company->company_descr }}</td>
								<td>@if($company->createdby ?? ''){{ $company->createdby->name }}@endif</td>
								<td>
									<form method="post" action="{{ route('company.delete', [], false) }}" id="formdelete">
										@csrf
										<button type="button" class="btn btn-np" title="Edit"
											id="edit-{{$no}}"
											data-id="{{$company->id}}"
											data-compdescr="{{$company->company_descr}}"
											>
											<i class="fas fa-edit"></i>
										</button>
										<button type="button" class="btn btn-np" title="Delete" data-compdescr="{{$company->company_descr}}" onclick="return deleteid()" id="buttond">
												<i class="fas fa-trash-alt"></i>
										</button>
										<input type="hidden" name="inputid" value="{{$company->id}}">
									</form>
								</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="line"></div>
			<h4><b>Add New Company</b></h4>
			<form action="{{ route('company.store', [], false) }}" method="post">
				@csrf
				<div class="row">
					<div class="col-md-8">
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputdescr">Company</label>
							</div>
							<div class="col-md-3">
								<input type="text" id="inputdescr" name="inputdescr"  value="{{ old('inputdescr') }}" required autofocus>
							</div>
						</div>
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputcomp">ID</label>
							</div>
							<div class="col-md-3">
								<input type="text" id="inputcomp" name="inputcomp"  value="{{ old('inputcomp') }}" required autofocus>
							</div>
						</div>
						
					</div>
				</div>
		</div>
		<div class="panel-footer">
		
		<div class="text-right">
							<button type="submit" class="btn btn-p btn-primary">CREATE NEW COMPANY</button>
							
		</div>
			</form>
		</div>
	</div>
</div>

<!-- edit Psubarea -->
<!-- <div id="editCompany" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="{{ route('company.update') }}" method="POST">
            @csrf
						<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
            </div>
						<div class="modal-body">
							<input type="text" class="form-control hidden" id="eid" name="eid" value="">
							<div class="form-group">
										<label for="compid">Company Code</label>
										<input type="text" class="form-control" id="eid" name="eid" value="" disabled>
								</div>
								<div class="form-group">
										<label for="editdescr">Company Descr</label>
										<input type="text" class="form-control" id="editdescr" name="editdescr" value="" required autofocus>
								</div>
						</div>
            <div class="modal-footer">
			<div class="text-center">
                <button type="submit" class="btn btn-primary btn-p ">SAVE</button>
								<button type="button" class="btn btn-p btn-outline" data-dismiss="modal">CANCEL</button>
								</div>
            </div>
        </form>
        </div>
    </div>
</div> -->


<form action="{{ route('company.update') }}" method="POST" id="edit" class="hidden">
	@csrf
	<input type="text" class="form-control" id="eid" name="eid" value="">
	<input type="text" class="form-control" id="editdescr" name="editdescr" value="">
</form>
		
@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {
    $('#tCompanyList').DataTable({
        "responsive": "true",
        "order" : [[0, "asc"]]
    });
});

function populate(e){
		var ps_id = $(e.relatedTarget).data('id');
    var ps_comp = $(e.relatedTarget).data('compdescr');
    $('input[name=eid]').val(ps_id);
    $('input[name=editdescr]').val(ps_comp);
    }

$('#editCompany').on('show.bs.modal', function(e) {
    populate(e);
});

for(i = 0; i<{{count($companies)}}; i++){
	$("#edit-"+i).on("click", edit(i));
	
}

function edit(i){
	return function(){
		var ps_id = $("#edit-"+i).data('id');
		var ps_comp = $("#edit-"+i).data('compdescr');
		Swal.fire({
			title: 'Edit Company',
			html: 
				"<div class='row'>"+
					"<div class='col-md-4'>"+
						"<p>Company ID</p>"+
					"</div>"+
					"<div class='col-md-8'>"+
						"<input type='text' id='cid' value='"+ps_id+"' style='width: 100%' disabled>"+
					"</div>"+
				"</div>"+
				"<div class='row'>"+
					"<div class='col-md-4'>"+
						"<p>Company Description</p>"+
					"</div>"+
					"<div class='col-md-8'>"+
						"<input type='text' id='cdes' value='"+ps_comp+"' style='width: 100%'>"+
					"</div>"+
				"</div>",
			showCancelButton: true,
			customClass:'test4',
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'SELECT',
			cancelButtonText: 'CANCEL'
			}).then((result) => {
			if (result.value) {
				if(($('#cid').val()!="")&&($('#cdes').val()!="")){
					$('#eid').val($('#cid').val());
					$('#editdescr').val($('#cdes').val());
					$("#edit").submit();
					// alert($('#cid').val()+" "+$('#eid').val());
				}else{
					Swal.fire({
							icon: 'error',
							title: 'Edit Error',
					text: "One of the input fields cannot be empty!",
					confirmButtonText:'OK'
					})
				}
			}
		})
	}
}

function deleteid(){
	
    var ps_comp = $("#buttond").data('compdescr');
	Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete company "+ps_comp+"?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'DELETE',
                    cancelButtonText: 'CANCEL'
                    }).then((result) => {
                    if (result.value) {
                        $("#formdelete").submit();
                    }
                })
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
