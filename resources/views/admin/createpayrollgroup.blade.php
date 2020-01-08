@extends('adminlte::page')
@section('content')
<div class="panel panel-default">
  <div class="panel-heading"><strong>Add new group</strong></div>
  <div class="panel-body">
		<form method="POST" action="{{ route('pygroup.store',[],false) }}">
 	 	@csrf
    <div class="form-group">
				<label for="descr">Group Name</label>
				<input  class="form-control" type="text" name="descr" required />
		</div>
		<div class="form-group">
				<label for="dt">Effective Date</label>
        <input class="form-control"  type="date" id="dt" name="dt" min="{{$dtVal}}" required>
				<!-- <input  class="form-control"  type="date" name="dt" required value="{{ $dtVal ?? '' }}" /> -->
		</div>
		<div class="form-group">
			<input type="button" class="check" value="Check All" />
      <input type="button" class="uncheck" value="UnCheck All" />
		</div>

 		<table>
			<div class="form-group">
			<label for="comp_selections[]">Companies</label><br/>
					@foreach ($companies as $company)
 			<input  type="checkbox" name="comp_selections[]" value="{{ $company->id }} "
 			class="questionCheckBox" />
 			{{ $company->id }} :{{$company->company_descr}} <br/>
 			 @endforeach
		 </div>
		 <div class="form-group text-center">
 			 <input type="submit" class="btn btn-primary">
 			 <a class="btn btn-primary" href="{{route('pygroup.index',[],false)}}" >Return</a>
		 </div>
 		</table>
 	</form>
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
