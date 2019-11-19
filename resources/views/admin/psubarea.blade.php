@extends('adminlte::page')

@section('title', 'Psubarea List')

@section('content')

<div class="panel panel-default">
	<div class="panel-heading"><strong>Personnel Subarea</strong></div>
	<div class="panel-body">
		@if (session()->has('a_text'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('a_text') }}</strong>
    </div>
    @endif
		<div class="table-responsive" >
			<table id="tPsubareaList" class="table table-hover table-bordered" >
				<thead>
					<tr>
							<th>Company</th>
							<!-- <th>Comp Desc</th> -->
							<th>Persarea</th>
							<!-- <th>Persarea Desc</th> -->
							<th>Perssubarea</th>
							<!-- <th>Perssubarea Desc</th> -->
							<th>State</th>
							<!-- <th>State Desc</th> -->
							<th>Region</th>
							<th>Created by</th>
							<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($psubareas as $psubarea)
					<tr>
							<td>@if($psubarea->companyid ?? '') {{ $psubarea->companyid->id }} - {{ $psubarea->companyid->company_descr}} @endif</td>
							<!-- <td>{{ $psubarea->companyid->company_descr}}</td> -->
							<td>{{ $psubarea->persarea }} - {{ $psubarea->persareadesc }}</td>
							<!-- <td>{{ $psubarea->persareadesc }}</td> -->
							<td>{{ $psubarea->perssubarea }} - {{ $psubarea->perssubareades }}</td>
							<!-- <td>{{ $psubarea->perssubareades }}</td> -->
							<td>@if($psubarea->stateid ?? '') {{ $psubarea->stateid->id }} - {{ $psubarea->stateid->state_descr}}@endif </td>
							<!-- <td>{{ $psubarea->stateid->state_descr}}</td> -->
							<td>{{ $psubarea->region }}</td>
							<td>{{ $psubarea->createdby->id }}</td>
							<td>
								<form method="post" action="{{ route('psubarea.delete', [], false) }}" onsubmit="return confirm('Are you sure you want to delete?')">
									@csrf
									<button type="button" class="btn btn-xs btn-warning" title="Edit"
											data-toggle="modal"
											data-target="#editfPsubarea"
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
									<button type="submit" class="btn btn-xs btn-danger" title="Delete">
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
	</div>
</div>

<!-- add Psubarea -->
<div class="panel panel-default">
  <div class="panel-heading"><strong>Add new personnel subarea</strong></div>
  <div class="panel-body">
    <form action="{{ route('psubarea.store', [], false) }}" method="post">
      @csrf
			<div class="form-group">
					<label for="inputcomp">Company</label><br>
					<select name="inputcomp" id="inputcomp" required style="width: 250px">
							<option value="" disabled selected>Select</option>
							@foreach($companies as $singlecompany)
							<option value="{{$singlecompany->id}}">{{$singlecompany->id}} - {{$singlecompany->company_descr}}</option>
							@endforeach
					</select>
			</div>
			<div class="form-group">
					<label for="inputparea">Personnel Area</label>
					<input type="text" class="form-control" id="inputparea" name="inputparea" placeholder="personnel area" value="{{ old('inputparea') }}" required autofocus>
			</div>
			<div class="form-group">
					<label for="inputparead">Personnel Area Desc</label>
					<input type="text" class="form-control" id="inputparead" name="inputparead" placeholder="personnel area description" value="{{ old('inputparead') }}" required autofocus>
			</div>
			<div class="form-group">
					<label for="inputpsubarea">Personnel Subarea</label>
					<input type="text" class="form-control" id="inputpsubarea" name="inputpsubarea" placeholder="personnel subarea" value="{{ old('inputpsubarea') }}" required autofocus>
			</div>
			<div class="form-group">
					<label for="inputpsubaread">Personnel Subarea Desc</label>
					<input type="text" class="form-control" id="inputpsubaread" name="inputpsubaread" placeholder="personnel subarea description" value="{{ old('inputpsubaread') }}" required autofocus>
			</div>
			<div class="form-group">
					<label for="inputstate">State</label><br>
					<select name="inputstate" id="inputstate" required style="width: 250px">
							<option value="" disabled selected>Select</option>
							@foreach($states as $singlestate)
							<option value="{{$singlestate->id}}">{{$singlestate->id}} - {{$singlestate->state_descr}}</option>
							@endforeach
					</select>
			</div>
			<div class="form-group">
					<label for="inputregion">Region</label>
					<input type="text" class="form-control" id="inputregion" name="inputregion" placeholder="region" value="{{ old('inputregion') }}" required autofocus>
			</div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>

<!-- edit Psubarea -->
<div id="editfPsubarea" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="{{ route('psubarea.edit') }}" method="POST">
            @csrf
						<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
            </div>
						<div class="modal-body">
                <input type="text" class="form-control hidden" id="editid" name="inputid" value="">
								<div class="form-group">
										<label for="inputcomp">Company Code</label>
										<input type="text" class="form-control" id="editcomp" name="inputcomp" value="" disabled>
								</div>
								<div class="form-group">
										<label for="inputparea">Personnel Area</label>
										<input type="text" class="form-control" id="editparea" name="inputparea" value="" required autofocus>
								</div>
								<div class="form-group">
										<label for="inputparead">Personnel Area Desc</label>
										<input type="text" class="form-control" id="editparead" name="inputparead" value="" required autofocus>
								</div>
								<div class="form-group">
										<label for="inputpsubarea">Personnel Subarea</label>
										<input type="text" class="form-control" id="editpsubarea" name="inputpsubarea" value="" required autofocus>
								</div>
								<div class="form-group">
										<label for="inputpsubaread">Personnel Subarea Desc</label>
										<input type="text" class="form-control" id="editpsubaread" name="inputpsubaread" value="" required autofocus>
								</div>
								<div class="form-group">
										<label for="inputpsubarea">State</label><br>
										<select name="inputstate" id="editstate" required style="width: 250px">
                        @foreach($states as $singlestate)
                        <option value="{{$singlestate->id}}">{{$singlestate->id}} - {{$singlestate->state_descr}}</option>
                        @endforeach
                    </select>
								</div>
								<div class="form-group">
										<label for="inputpsubarea">Region</label>
										<input type="text" class="form-control" id="editregion" name="inputregion" value="" required autofocus>
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
<script type="text/javascript">

$(document).ready(function() {
    $('#tPsubareaList').DataTable({
        "responsive": "true",
        "order" : [[0, "asc"]]
    });
});

function populate(e){
		var ps_id = $(e.relatedTarget).data('id');
    var ps_comp = $(e.relatedTarget).data('comp');
    var ps_area = $(e.relatedTarget).data('area');
		var ps_aread = $(e.relatedTarget).data('aread');
		var ps_sub = $(e.relatedTarget).data('sub');
		var ps_subd = $(e.relatedTarget).data('subd');
    //var ps_state = $(e.relatedTarget).data('state');
		var ps_state_id = $(e.relatedTarget).data('state_id');
		var ps_reg = $(e.relatedTarget).data('reg');
    $('input[name=inputid]').val(ps_id);
    $('input[name=inputcomp]').val(ps_comp);
		$('input[name=inputparea]').val(ps_area);
		$('input[name=inputparead]').val(ps_aread);
		$('input[name=inputpsubarea]').val(ps_sub);
		$('input[name=inputpsubaread]').val(ps_subd);
		// $('input[name=inputstate]').val(ps_state_id);
		// $("#inputstate").val(ps_state);
		$("#editstate").val(ps_state_id);
		$('input[name=inputregion]').val(ps_reg);
    //$('#showname').text(ps_state);
    }


$('#editfPsubarea').on('show.bs.modal', function(e) {
    populate(e);
});

// $('#deletePsubarea').on('show.bs.modal', function(e) {
//     populate(e);
// });

// @if(session()->has('feedback'))
//     $('#feedback').modal('show');
// @endif

</script>
@stop
