@extends('adminlte::page')
@section('content')



@if(session('alert'))
    <div class="alert alert-{{$ac}}" role="alert">{{session('alert')}}</div>
@endif
<table width='100%'>
  <tr>
    <td style="width:7em">
      <form method="post" style="display:inline; " id="form_select_year_id" >
        	@csrf
        <select name="s_year" id="s_year_id" class="form-control">
          @foreach ($years as $y)
        <option>{{$y}}</option>
        @endforeach
        </select>



      </form>
    </td>
    <td>
      <form action="{{ route('holiday.create',[],false) }}" style="display:inline; float:right">
        @csrf
        <input type="hidden" name="s_year" id="s_year_create" value="{{$s_year}}" />

        <input type="submit" name="submit" value="Create New Holiday" class="btn btn-primary"/>


      </form>

    </td>




  </tr>
</table>



<table border="1" style="width:100%"  >
<tr>
  @php($col = 0)
  @foreach ($header as $h)
    @if($col==0)
    @else
      <th>{{ $h }}</th>
    @endif
    @php($col = $col+1)
  @endforeach
  <th>Edit</th>
</tr>

@foreach ($content as $cmain)
<tr>

  @php($col = 0)
  @foreach ($cmain as $c)
    @if($col==0)

    @else
      <td>{{ $c }}</td>
    @endif
    @php($col = $col+1)
  @endforeach
  <th>
<a href="{{ route('holiday.edit',['id'=>$cmain[0]],false) }}" class="btn btn-info btn-sm">Edit</a>

<a href="#" class="btn btn-danger btn-sm"
onClick="submitDeleteForm('{{$cmain[0]}}')"
holid="{{$cmain[0]}}">Delete </a>
</a>
<span style="color:transparent">
    {{$cmain[0]}}
</span>
  </th>
</tr>

@endforeach
</table>
Legends <br/>
@foreach ($states as $state)

{{ $state->id }} :{{$state->state_descr}} <br/>
 @endforeach



<form method="POST" action="{{route('holiday.destroy',[],false) }}"
	id="formDeleteID">
	@csrf <input name="holiday_id" id="delete_holiday_id" type="hidden" />
</form>



@endsection
@section('js')
<script type="text/javascript">
  $("#s_year_id").val('{{$s_year}}');


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
<script type="text/javascript">
  $(document).ready(function(){

    $('#s_year_id').change(function(){
      	$('#form_select_year_id').submit();
      });
   });
</script>


@endsection
