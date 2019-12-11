@extends('adminlte::page')
@section('title', 'Edit Payroll Group')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading panel-primary">Payroll Group</div>
	<div class="panel-body">
		<form method="POST" action="{{ route('pygroup.update',[],false) }}">
		 @csrf
		 <div class="form-group">
			 <input name="id" value="{{$pygroup->id}}" type="hidden"/>
			 <label for="descr">Group Name</label>
			 <input  class="form-control" type="text" name="descr" value="{{$pygroup->pygroup}}" disabled  required />
		 </div>
		 <div class="form-group">
			 <label for="dt">Effective Date</label>
			 <input class="form-control"  type="date" id="sdt" name="sdt" value="{{ $dtVal}}" min="{{$mindate}}" disabled required>
			 <input name="dt" value="{{ $dtVal}}" type="hidden"/>
			 <!-- <input  class="form-control"  type="date" name="dt" required value="{{ $dtVal ?? '' }}" /> -->
		 </div>
		 <div class="form-group">
			 <input  type="button" id="check" value="Check All" />
		    <input type="button" id="uncheck" value="UnCheck All" />
		 	 <input type="button" id="reset" value="Reset" />
		 </div>
		 <div>
		 <table>
		   <tr>
		   	<td>
					<div class="form-group">
						<label for="comp_selections[]">Companies</label><br/>
						@foreach ($companies as $company)
			 		 <input type="checkbox" name="company_selections[]" value="{{ $company->id }} "  id="{{$company->id}}" class="questionCheckBox" />
			 		 {{ $company->id }} :{{$company->company_descr}} <br/>
			 		 @endforeach
				 </div>
				</td>
		   </tr>
		 </table>
	 	</div>

		 <div class="form-group text-center">
		 <a   class="btn btn-primary" href="{{route('pygroup.index',[],false)}}" >
		 Return
		 </a>
		 <input type="submit" class="btn btn-primary" >
	 	</div>
		</form>
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
		 @foreach ($pygroup->companyingroup as $var)
		 checkCompany('{{$var->companyid->id}}');
			 @endforeach
	 });
});

</script>



@endsection
