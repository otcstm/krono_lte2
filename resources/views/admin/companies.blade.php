@extends('adminlte::page')
@section('content')
<div class="content panel">
	<div class="row">

		@if(session('alert'))

				<div class="alert alert-{{$ac}}" role="alert">{{session('alert')}}</div>

		@endif

		<!-- left column -->
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Add New Companies</h3>
				</div>

				<form role="form" method="POST" action="{{ route('company.store') }}">
					@csrf
					<div class="box-body">
						<div class="form-group">
							<label for="company_code_id">Company Code</label><input type="text"
								class="form-control" id="company_code_id" name="company_code">
						</div>
						<div class="form-group">
							<label for="company_descr_id">Company Descr</label> <input
								type="text" class="form-control" id="company_descr_id"
								placeholder="Insert Company Description" name="company_descr"
								size="5">
						</div>
						<button type="submit" class="btn btn-primary">Add Company</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>


<div class="col-md-12">
	<div class="box">@include('admin.listCompany')</div>
</div>
<div class="modal fade" id="editCompanyForm" tabindex="-1" role="dialog"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="box-body">
				<h3 class="box-title">Add New Companies</h3>
				<form method="POST" action="{{route('company.update') }}">
					@csrf
					<div class="modal-body">
						<input type="hidden" value="0" name="id" id="edit-id-hidden" />
						<div class="form-group row">
							<label for="edit-key"
								class="col-sm-4 col-form-label text-sm-right">Company ID:</label>
							<input type="text" class="form-control col-sm-6" id="edit-id"
								name="id" disabled="disabled">
						</div>
						<div class="form-group row">
							<label for="edit-value"
								class="col-sm-4 col-form-label text-sm-right">Company Description:</label>
							<input type="text" class="form-control col-sm-6" id="edit-value"
								name="company_descr" autofocus>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary"
							data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<form method="POST" action="{{route('company.destroy') }}"
	id="formDeleteID">
	@csrf <input name="company_id" id="delete_company_id" type="hidden" />
</form>

@endsection @section('js')

<script type="text/javascript">
$('#editCompanyForm').on('show.bs.modal', function(e) {
    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');
    var company_descr = $(e.relatedTarget).data('company_descr');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="company_descr"]').val(company_descr);

});

function delCompany(stid){

     $.post("{{route('company.destroy') }}", { company_id: coid , _token: "{{ csrf_token() }}" },
       function(data) {
         alert(data);
       });
     }

function submitDeleteForm(coid){
	var txt;
	var r = confirm("Are you sure ? Company code "+coid+" would be deleted");
	if (r == true) {
		$('#delete_company_id').val(coid);
		$('#formDeleteID').submit();
	} else {
	  txt = coid+ " Not deleted!";
	}
  };

</script>
@endsection
