@extends('adminlte::page')
@section('content')
<div class="container">
	 <form method="POST" action="{{ route('insertHoliday') }}">
	 @csrf
		<table>
			<tr>
			<td>Date</td>
				<td><input type="date" name="dt" /></td>

			</tr>
				<td>Holiday Description</td>

				<td><input type="text" name="descr" /></td>
			</tr>
			<tr>
				<td>Guarantee Flag </td>
				<td><input type="number" name="guarantee_flag" /></td>
			</tr>
  			<tr>
  			<td colspan=2>
			@foreach ($states as $state)
			<input type="checkbox" name="state_selections[]" value="{{ $state->id }} " />
			{{ $state->id }} :{{$state->state_descr}} <br/>
			 @endforeach
			 </td>

			 </tr>
			 <tr>
			 <td>
			 <input type="submit">
			 </td>

			 </tr>



		</table>
	</form>
</div>
@endsection
