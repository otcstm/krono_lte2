@extends('adminlte::page')
@section('content')

@if(session('alert'))

    <div class="alert alert-{{$ac}}" role="alert">{{session('alert')}}</div>

@endif

<table border="1" style="width:100%">
<tr>
  @foreach ($header as $h)
  <th>{{ $h }}</th>
  @endforeach
  <th>Edit</th>
</tr>
@foreach ($content as $cmain)

<tr>
  @foreach ($cmain as $c)
  <td>{{ $c }}</td>
  @endforeach
  <th>
<a href="{{ route('holiday.edit',['id'=>$cmain[0]],false) }}">Edit</a>

<a href="#" class="btn btn-danger btn-sm"
onClick="submitDeleteForm('{{$cmain[0]}}')"
holid="{{$cmain[0]}}">Delete </a>
</a>

    {{$cmain[0]}}

  </th>
</tr>

@endforeach
</table>
Legends <br/>
@foreach ($states as $state)

{{ $state->id }} :{{$state->state_descr}} <br/>
 @endforeach

<a href="{{ route('holiday.create',[],false) }}">Create New Holiday</a>

<form method="POST" action="{{route('holiday.destroy',[],false) }}"
	id="formDeleteID">
	@csrf <input name="holiday_id" id="delete_holiday_id" type="hidden" />
</form>



@endsection
@section('js')
<script type="text/javascript">
function submitDeleteForm(holid){
	var txt;
	var r = confirm("Are you sure ? "+holid+" would be destroyed");
	if (r == true) {
	   $('#delete_holiday_id').val(holid);
     $('#formDeleteID').submit();
   } else {
     txt = holid+ " Not deleted!";
	}
};

  </script>



@endsection
