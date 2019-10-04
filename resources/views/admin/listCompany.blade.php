
<div class="box-body table-responsive no-padding">
	<table class="table table-hover">
		<tbody>
			<tr>

				<th>ID</th>
				<th>Company Description</th>
				<th class="hidden-xs">Created By</th>
				<th class="hidden-xs">Created At</th>
				<th class="hidden-xs">Updated By</th>
				<th class="hidden-xs">Updated At</th>
			<th>Actions</th>
			</tr>
			@foreach ($companies as $company)
			<tr>
				<td>{{ $company->id }}</td>
				<td>{{ $company->company_descr }}</td>
				<td class="hidden-xs">{{ $company->creator->name }}</td>
				<td class="hidden-xs">{{ $company->created_at }}</td>
				<td class="hidden-xs">{{ $company->creator->name }}</td>
				<td class="hidden-xs">{{ $company->updated_at }}</td>
				<td>
					<button id="btnedit" type="button" class="btn btn-warning btn-sm"
						title="Edit" data-toggle="modal" data-target="#editCompanyForm"
						data-id="{{$company['id']}}"
						data-company_descr="{{$company['company_descr']}}">Edit</button>
						<a href="#" class="btn btn-danger btn-sm"
						onClick="submitDeleteForm('{{$company['id']}}')"
						coid="{{$company['id']}}">Delete </a>
				</a>
				</td>
			</tr>
			@endforeach

	</table>
</div>
