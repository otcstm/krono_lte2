@extends('adminlte::page')
@section('content')
<div class="container">
	<p><input type="button" class="check" value="Check All" />
   <input type="button" class="uncheck" value="UnCheck All" />
</p>
<form method="POST" action="{{ route('holiday.update',[],false) }}">
 @csrf
 <input name="id" value="{{$holiday->id}}" type="hidden"/>
 <table>
   <tr>
   <td>Date</td>
     <td><input type="date" name="dt" value="{{$holiday->dt}}" required /></td>
   </tr>
     <td>Holiday Description</td>
     <td><input type="text" name="descr" value="{{$holiday->descr}}"  required /></td>
   </tr>
   <tr>
     <td>Guarantee Flag </td>
     <td><input type="number" name="guarantee_flag" value="{{$holiday->guarantee_flag}}" /></td>
   </tr>
 </table>

 @foreach ($states as $state)
 <input type="checkbox" name="state_selections[]" value="{{ $state->id }} "  id="{{$state->id}}"class="questionCheckBox" />
 {{ $state->id }} :{{$state->state_descr}} <br/>
 @endforeach
 <input type="submit">
</form>
@endsection
@section('js')
<script type="text/javascript">
function checkState(id){
  $("#" + id).prop('checked',true);
}

@foreach ($holiday->StatesThatCelebrateThis as $var)
checkState('{{$var->stateid->id}}');
  @endforeach
</script>


@endsection
