@extends('adminlte::page')
@section('content')
@if($s_year!= 'all')

@endif
<div class="container">
	<p><input type="button" class="check" value="Check All" />
   <input type="button" class="uncheck" value="UnCheck All" />
</p>
	 <form method="POST" action="{{ route('holiday.insert',[],false) }}">

	 @csrf
	 <input type="hidden" name="s_year"  value="{{$s_year}}" />
		<table>
			<tr>
			<td>Date</td>
				<td><input type="date" name="dt" required value="{{$dtVal}}" /></td>

			</tr>
				<td>Holiday Description</td>

				<td><input type="text" name="descr" required /></td>
			</tr>
			<tr>
				<td>Guarantee Flag </td>
				<td>
<input type="checkbox" name="guarantee_flag" value='1'>
					<!---<input type="number" name="guarantee_flag3" value="0" />-->

				</td>
			</tr>
  			<tr>
  			<td colspan=2>
			@foreach ($states as $state)
			<input type="checkbox" name="state_selections[]" value="{{ $state->id }} "
			class="questionCheckBox" />
			{{ $state->id }} :{{$state->state_descr}} <br/>
			 @endforeach
			 </td>

			 </tr>
			 <tr>
			 <td>
			 <input type="submit" class="btn btn-alert btn-sm">
			 <a class="btn btn-info btn-sm" href="{{route('holiday.show',[],false)}}" >
			 Return
			 </a>
			 </td>

			 </tr>



		</table>

	</form>



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
