@extends('adminlte::page')
@section('content')

@if(session('alert'))
    <div class="alert alert-{{$ac}}" role="alert">{{session('alert')}}</div>
@endif

<h1>Holiday Management</h1>
<div class="panel panel-default">
  <div class="panel-body">
    <div class="flex-sb" style="margin-bottom: 15px">
      <div style="margin-top: 5px">
        <span>Year </span>
        <form method="post" id="form_select_year_id" style="display: inline">
          @csrf
          <select name="s_year" id="s_year_id">
            @foreach ($years as $y)
              <option>{{$y}}</option>
            @endforeach
          </select>
        </form> 
      </div>
      <form action="{{ route('holiday.create',[],false) }}" style="display:inline; float:right">
        @csrf
        <input type="hidden" name="s_year" id="s_year_create" value="{{$s_year}}" />
        <button type="submit" name="submit" class="btn-up" style="margin-top: 15px">CREATE NEW HOLIDAY</button>
      </form>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-white">
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
                      @if($c=="*")
                        
                        <td class="fill"> 
                        </td>
                      @else
                        <td>{{ $c }}</td>
                      @endif
                  @endif
                  @php($col = $col+1)
                @endforeach
                <td>
                  <a href="{{ route('holiday.edit',['id'=>$cmain[0]],false) }}" class="btn  btn-np">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="#" class="btn  btn-np" onClick="submitDeleteForm('{{$cmain[0]}}')" holid="{{$cmain[0]}}">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                  <span style="color:transparent">
                      {{$cmain[0]}}
                  </span>
                </td>
              </tr>
              @endforeach
          </tbody>
      </table>
    </div>
    <div class="line"></div>
    <h4 style="margin-left: 5%"><b>Legend</b></h4>
    <div class="flex-state">
      @foreach ($states as $state)
        <div class="state-item">
          <div class="row">
            <div class="col-md-1">{{ $state->id }}</div>
            <div class="col-md-11">: {{$state->state_descr}}</div>
          </div>
        </div>
      @endforeach
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

   });
</script>


@endsection
