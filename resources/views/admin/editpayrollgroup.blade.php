@extends('adminlte::page')
@section('title', 'Edit Payroll Group')

@section('content')
<h1>Edit Payroll Group</h1>
<div class="panel panel-default panel-main">
	<div class="panel panel-default">
		<div class="panel-heading panel-primary">Payroll Group</div>
		<div class="panel-body">
			<form method="POST" action="{{ route('pygroup.update',[],false) }}">
			@csrf
			<input name="id" value="{{$pygroup->id}}" type="hidden"/>
			<div class="row">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-4">
							<label for="descr">Group Name</label>
						</div>
						<div class="col-md-8">
							<input  class="form-control" type="text" name="descr" value="{{$pygroup->pygroup}}" disabled  required />
						</div>
						<div class="col-md-4">
							<label for="dt">Effective Date</label>
						</div>
						<div class="col-md-8">
							<input class="form-control"  type="date" id="sdt" name="sdt" value="{{ $dtVal}}" min="{{$mindate}}" disabled required>
							<input name="dt" value="{{ $dtVal}}" type="hidden"/>
							<!-- <input  class="form-control"  type="date" name="dt" required value="{{ $dtVal ?? '' }}" /> -->
						</div>
						<br>
					</div>
					<div style="margin-top: 25px">
						<input  type="button" class="btn-up" id="check" value="Check All" />
						<input type="button" class="btn-up" id="uncheck" value="Uncheck All" />
						<input type="button" class="btn-up" id="reset" value="Reset" />
						<div class="form-group">
							<label for="comp_selections[]">Companies</label><br/>
							@foreach ($companies as $company)
							<input type="checkbox" name="company_selections[]" value="{{ $company->id }} "  id="{{$company->id}}" class="questionCheckBox" />
							{{ $company->id }} :{{$company->company_descr}} <br/>
							@endforeach	
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


@endsection
@section('js')
<script type="text/javascript">
function checkCompany(id){
  $("#" + id).prop('checked',true);
}

@foreach ($actives as $var)
checkCompany('{{$var->company_id}}');
  @endforeach
</script>

<script type="text/javascript">
$(function () {
	 $('#check').on('click', function () {
			 $('.questionCheckBox').prop('checked',true);
	 });
});

$(function () {
	 $('#uncheck').on('click', function () {
			 $('.questionCheckBox').prop('checked',false);
	 });
});

$(function () {
	 $('#reset').on('click', function () {
		 $('.questionCheckBox').prop('checked',false);
		 @foreach ($actives as $var)
		 checkCompany('{{$var->company_id}}');
			 @endforeach
	 });
});

</script>



@endsection
