@extends('adminlte::page')
@section('content')
<h1>Create Payroll Group</h1>
<div class="panel panel-default panel-main">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Add new group</strong></div>
		<div class="panel-body">
				<form method="POST" action="{{ route('pygroup.store',[],false) }}">
				@csrf
				<div class="row">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								<label for="descr">Group Name</label>
							</div>
							<div class="col-md-8">
								<input  class="form-control" type="text" name="descr" required />
							</div>
							<div class="col-md-4">
								<label for="dt">Effective Date</label>
							</div>
							<div class="col-md-8">
								<input class="form-control"  type="date" id="dt" name="dt" min="{{$dtVal}}" required>
								<!-- <input  class="form-control"  type="date" name="dt" required value="{{ $dtVal ?? '' }}" /> -->
							</div>
							<br>	
							<br>	
							<br>	
							<br>	
							<br>
							<div style="margin-left: 15px !important">
								<input type="button" class="check btn-up" value="Check All" />
								<input type="button" class="uncheck btn-up" value="Uncheck All" />
								<div class="form-group">
									<label for="comp_selections[]">Companies</label><br/>
											@foreach ($companies as $company)
											<input  type="checkbox" name="comp_selections[]" value="{{ $company->id }} "
											class="questionCheckBox" />
											{{ $company->id }} :{{$company->company_descr}} <br/>
											@endforeach
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="text-right">
					<a class="btn btn-primary btn-outline" href="{{route('pygroup.index',[],false)}}" >RETURN</a>
					<button type="submit" class="btn btn-primary">SUBMIT</button>
				</div>
				
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
$(function () {
	 $('.check').on('click', function () {
			 $('.questionCheckBox').prop('checked',true);
	 });
});

$(function () {
	 $('.uncheck').on('click', function () {
			 $('.questionCheckBox').prop('checked',false);
	 });
});

</script>

@endsection
