@extends('adminlte::page') @section('content')
<div class="content panel">
	<div class="row">

		@if(isset($alert))
		<div class="alert alert-{{$ac}}" role="alert">{{$alert}}</div>
		@endif
		<!-- left column -->
		<div class="col-md-12">
			<!-- general form elements -->
			<div class="box box-primary">

				<div class="box-header with-border">
					<h3 class="box-title">Add New States</h3>
				</div>

				<!-- /.box-header -->
				<!-- form start -->
				<form role="form" method="POST" action="{{ route('state.store') }}">
					@csrf
					<div class="box-body">
						<div class="form-group">

							<label for="state_code_id">State Code</label> <input type="text"
								class="form-control" id="state_code_id" name="state_code">


						</div>


						<div class="form-group">
							<label for="state_descr_id">State Descr</label> <input
								type="text" class="form-control" id="state_descr_id"
								placeholder="Insert State Description" name="state_descr"
								size="5">
						</div>
						<button type="submit" class="btn btn-primary">Add State</button>
					</div>
					<!-- /.box-body -->
				</form>
			</div>
			<!-- /.box -->
		</div>
		<!--/.col (left) -->
	</div>
	<!-- /.row -->
</section>
<!-- /.section -->


<div class="col-md-12">
	<div class="box">@include('admin.listState')</div>
</div>
<div class="modal fade" id="editStateForm" tabindex="-1" role="dialog"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="box-body">
				<h3 class="box-title">Add New States</h3>
				<form method="POST" action="{{route('state.update') }}">
					@csrf
					<div class="modal-body">
						<input type="hidden" value="0" name="id" id="edit-id-hidden" />
						<div class="form-group row">
							<label for="edit-key"
								class="col-sm-4 col-form-label text-sm-right">State ID:</label>
							<input type="text" class="form-control col-sm-6" id="edit-id"
								name="id" disabled="disabled">
						</div>
						<div class="form-group row">
							<label for="edit-value"
								class="col-sm-4 col-form-label text-sm-right">State Descr:</label>
							<input type="text" class="form-control col-sm-6" id="edit-value"
								name="state_descr" autofocus>
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



<form method="POST" action="{{route('state.destroy') }}"
	id="formDeleteID">
	@csrf <input name="state_id" id="delete_state_id" type="hidden" />




</form>


@endsection @section('js')






<script type="text/javascript">
$('#editStateForm').on('show.bs.modal', function(e) {
    //get data-id attribute of the clicked element
    var id = $(e.relatedTarget).data('id');
    var state_descr = $(e.relatedTarget).data('state_descr');

    //populate the textbox
    $(e.currentTarget).find('input[name="id"]').val(id);
    $(e.currentTarget).find('input[name="state_descr"]').val(state_descr);

});

function delState(stid){
	
     $.post("{{route('state.destroy') }}", { state_id: stid , _token: "{{ csrf_token() }}" },
       function(data) {
         alert(data);
       
         
       }); 
     }

function submitDeleteForm(stid){
	var txt;
	var r = confirm("Are you sure ? "+stid+" would be deleted");
	if (r == true) {
		$('#delete_state_id').val(stid);
		$('#formDeleteID').submit();
	} else {
	  txt = stid+ " Not deleted!";
	}
  }; 





</script>




@endsection


