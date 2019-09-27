


<div class="box-body table-responsive no-padding">
	<table class="table table-hover">
		<tbody>
			<tr>

				<th>ID</th>
				<th>State Description</th>
				<th class="hidden-xs">Created At</th>
				<th>Actions</th>
			</tr>
			@foreach ($states as $state)
			<tr>
				<td>{{ $state->id }}</td>
				<td>{{ $state->state_descr }}</td>
				<td class="hidden-xs">{{ $state->created_at }}</td>
				<td>
					<button id="btnedit" type="button" class="btn btn-warning btn-sm"
						title="Edit" data-toggle="modal" data-target="#editStateForm"
						data-id="{{$state['id']}}"
						data-state_descr="{{$state['state_descr']}}">Edit</button> 
						<a href="#" class="btn btn-danger btn-sm"
						onClick="submitDeleteForm('{{$state['id']}}')"
						stid="{{$state['id']}}">Delete </a>
				</a>
				</td>
			</tr>
			@endforeach
	
	</table>
</div>







