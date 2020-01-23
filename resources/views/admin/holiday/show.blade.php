@extends('adminlte::page')
@section('content')

@if(session('alert'))
    <div class="alert alert-{{$ac}}" role="alert">{{session('alert')}}</div>
@endif

<div class="box box-primary box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Holiday</h3>

              <div class="box-tools pull-right">

              <form action="{{ route('holiday.create',[],false) }}" style="display:inline; float:right">
        @csrf
        <input type="hidden" name="s_year" id="s_year_create" value="{{$s_year}}" />

        <button type="submit" name="submit" class="btn btn-block btn-default"><i class="fa fa-plus"></i> Create New Holiday</button>
     
      </form>
               
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
     
  <div class="row">   
  <div class="col-md-6">
  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-4 control-label">Select Year: </label>

                  <div class="col-sm-8">
                  <form method="post" id="form_select_year_id" >
        	@csrf
        <select name="s_year" id="s_year_id" class="form-control">
          @foreach ($years as $y)
        <option>{{$y}}</option>
        @endforeach
        </select>
      </form> </div>
                </div>
    </div>  
    </div>  <br />
<table id="holidaylist" class="table-bordered table-hover">
<thead>
<tr>
  @php($col = 0)
  @foreach ($header as $h)
    @if($col==0)
    @else
      <th>{{ $h }}</th>
    @endif
    @php($col = $col+1)
  @endforeach
  <th>Action</th>
</tr>
</thead>
<tbody>
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
<a href="{{ route('holiday.edit',['id'=>$cmain[0]],false) }}" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-edit"></i></a>

<a href="#" class="btn btn-danger btn-sm"
onClick="submitDeleteForm('{{$cmain[0]}}')"
holid="{{$cmain[0]}}"><i class="glyphicon glyphicon-trash"></i></a>
</a>
<span style="color:transparent">
    {{$cmain[0]}}
</span>
  </th>
</tr>

@endforeach
</tbody>
</table>

</div>
<!-- /.box-body -->
</div>

<div class="row">
<div class="col-md-6">
<div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Legends</h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
@foreach ($states as $state)

{{ $state->id }} :{{$state->state_descr}} <br >
 @endforeach

 </div>
<!-- /.box-body -->
</div>

</div>
</div>

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

   
    $('#holidaylist').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]]
    });
   });
</script>


@endsection
