@extends('adminlte::page')
@section('title', 'Psubarea List')

@section('content')

<div class="panel panel-default">
	<div class="panel-heading panel-primary"><strong>Personnel Subarea</strong></div>
	<div class="panel-body">
		@if (session()->has('a_text'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('a_text') }}</strong>
    </div>
    @endif
		<div class="table-responsive">
			<table id="tPsubareaList" class="table table-bordered" >
				<thead>
					<tr>
							<th>Company Code</th>
							<th>Personnel Area</th>
							<th>Personnel Subarea</th>
							<th>State</th>
							<th>Created by</th>
							<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($psubareas as $psubarea)
					<tr>
							<td>@if($psubarea->company_id ?? ''){{ $psubarea->companyid->company_descr }}@endif</td>
							<td>{{ $psubarea->persarea }}</td>
							<td>{{ $psubarea->perssubarea }}</td>
							<td>@if($psubarea->state_id ?? ''){{ $psubarea->stateid->state_descr }}@endif</td>
							<td>{{ $psubarea->createdby->name }}</td>
							<td>
								<form action="{{ route('psubarea.delete', [], false) }}" method="post" onsubmit="return confirm('seriyes nak delete?')">
									@csrf
									<input type="hidden" name="inputid" value="{{$psubarea->id}}">
									<button type="button" class="btn btn-primary"
											data-toggle="modal"
											data-target="#editPsubarea"
											data-id="{{$psubarea->id}}"
											data-comp="{{$psubarea->company_id}}"
											data-area="{{$psubarea->persarea}}"
											data-sub="{{$psubarea->perssubarea}}"
											data-state_id="{{$psubarea->state_id}}"
											>
											<i class="fas fa-cog"></i>
									</button>
									<button type="submit" class="btn btn-danger">
											<i class="fas fa-trash-alt"></i>
									</button>
								</form>

							</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		<div class="text-center">
			<button type="button" class="btn btn-primary"
				data-toggle="modal"
				data-target="#newPsubarea">
				ADD PERSONNEL SUBAREA
			</button>
		</div>
	</div>
</div>

<!-- add Psubarea -->
<div id="newPsubarea" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Personnel Subarea</h4>
            </div>
						<div class="modal-body">
								<form action="{{route('psubarea.store')}}" method="POST">
										@csrf
										<div class="box-body">
											<div class="form-group">
													<label for="inputcomp">Company</label>
													<input type="text" class="form-control" id="inputcomp" name="inputcomp" placeholder="company code" value="{{ old('inputcomp') }}" required autofocus>
											</div>
											<div class="form-group">
													<label for="inputparea">Personnel Area</label>
													<input type="text" class="form-control" id="inputparea" name="inputparea" placeholder="personnel area" value="{{ old('inputparea') }}" required autofocus>
											</div>
											<div class="form-group">
													<label for="inputpsubarea">Personnel Subarea</label>
													<input type="text" class="form-control" id="inputpsubarea" name="inputpsubarea" placeholder="personnel subarea" value="{{ old('inputpsubarea') }}" required autofocus>
											</div>
											<div class="form-group">
													<label for="inputstate">State</label>
													<input type="text" class="form-control" id="inputstate" name="inputstate" placeholder="state" value="{{ old('inputstate') }}" required autofocus>
											</div>
									<div class="text-center">
													<button type="submit" class="btn btn-primary">CREATE</button>
											</div>
										</div>
								</form>
						</div>
						<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- edit Psubarea -->
<div id="editPsubarea" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
                <form action="{{ route('psubarea.edit') }}" method="POST">
                    @csrf
										<div class="modal-header">
				                <button type="button" class="close" data-dismiss="modal">&times;</button>
				                <h4 class="modal-title">Edit</h4>
				            </div>
										<div class="modal-body">
		                    <input type="hidden" id="editid" name="inputid" value="">
												<div class="form-group">
														<label for="inputcomp">Company Code</label>
														<input type="text" class="form-control" id="editcomp" name="inputcomp" required autofocus disabled>
												</div>
												<div class="form-group">
														<label for="inputparea">Personnel Area</label>
														<input type="text" class="form-control" id="editparea" name="inputparea" required autofocus>
												</div>
												<div class="form-group">
														<label for="inputpsubarea">Personnel Subarea</label>
														<input type="text" class="form-control" id="editpsubarea" name="inputpsubarea" required autofocus>
												</div>
												<div class="form-group">
														<p>State</p>
														<select name="inputstate" id="editstate" required>
				                    @if($states ?? '')
				                        @foreach($states as $singlestate)
				                        <option value="{{$singlestate->id}}">{{$singlestate->state_descr}}</option>
				                        @endforeach
				                    @endif
				                    </select>
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

<div id="deletePsubarea" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete</h4>
            </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-warning-sign" style="color: #F0AD4E; font-size: 32px;"></div>
                <p>Are you sure you want to delete personnel subarea <span id="showname"></span>?<p>
                <form action="{{ route('psubarea.delete') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <button type="submit" class="btn btn-primary">DELETE</button>
                </form>
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
    $('#tPsubareaList').DataTable({
        "responsive": "true",
        "order" : [[1, "asc"]],
        "columns": [
            null,
            null,
            null,
            null,
            { "width": "15%" }
        ]
    });
});

function populate(e){
		var ps_id = $(e.relatedTarget).data('id');
    var ps_comp = $(e.relatedTarget).data('comp');
    var ps_area = $(e.relatedTarget).data('area');
		var ps_sub = $(e.relatedTarget).data('sub');
    var ps_state = 'test'; //$(e.relatedTarget).data('state');
		var ps_state_id = $(e.relatedTarget).data('state_id');
    $('input[name=inputid]').val(ps_id);
    $('input[name=inputcomp]').val(ps_comp);
		$('input[name=inputparea]').val(ps_area);
		$('input[name=inputpsubarea]').val(ps_sub);
		// $('input[name=inputstate]').val(ps_state_id);
		// $("#inputstate").val(ps_state);
		$("#editstate").val(ps_state_id);
    $('#showname').text(ps_state);
    }


$('#editPsubarea').on('show.bs.modal', function(e) {
    populate(e);
});

$('#deletePsubarea').on('show.bs.modal', function(e) {
    populate(e);
});

// @if(session()->has('feedback'))
//     $('#feedback').modal('show');
// @endif

</script>
@stop
