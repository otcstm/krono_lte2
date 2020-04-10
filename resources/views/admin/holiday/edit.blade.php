@extends('adminlte::page')
@section('content')
<h1>Edit Holiday</h1>
<div class="panel panel-default">
	<div class="panel-body" style="padding-bottom: 20vh">
		<form method="POST" action="{{ route('holiday.update',[],false) }}">
		@csrf
		<input name="id" value="{{$holiday->id}}" type="hidden"/>
		<div class="row">
			<div class="col-md-11">
				<div class="row" style="margin-bottom: 15px">
					<div class="col-md-3">Date</div>
					<div class="col-md-9">: <input type="date" name="dt" value="{{$holiday->dt}}" required /></div>
				</div>
				<div class="row">		
					<div class="col-md-3">Holiday Description</div>
					<div class="col-md-9">:
					<input type="text" name="descr" value="{{$holiday->descr}}"  required   style="margin-bottom: 15px"/>
					<br>
						<div class="flex" style="display: inline;">
							<button type="button" class="btn-up" id="check" value="Check All">CHECK ALL</button>
							<button type="button" class="btn-up" id="uncheck" value="UnCheck All">UNCHECK ALL</button>
							<button type="button" class="btn-up"  id="reset" value="Reset State Selection">RESET</button>
						</div>
						<br>
						<input type="checkbox" name="guarantee_flag"  value="1" @if($holiday->guarantee_flag == 1) checked @endif  style="margin-left: 5px;"> Guarentee Flag
						<div class="flex">
							@foreach ($states as $state)
							<div style="margin-left: 5px; width: 48%;  margin-top: 5px">
								<div class="row">
									<div class="col-md-2" style="white-space: nowrap;">
									<input type="checkbox" name="state_selections[]" value="{{ $state->id }} "  id="{{$state->id}}" class="questionCheckBox" /> {{ $state->id }}
									</div>
									<div class="col-md-10">: {{$state->state_descr}}</div>

								</div>

								
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
  	<div class="panel-footer">
		<div class="text-right">
			<a href="{{route('holiday.show',[],false)}}"><button type="button" class="btn btn-outline btn-primary btn-p">CANCEL</button></a>
			<button type="submit" class="btn btn-primary btn-p">SUBMIT</button>
		</form>
		</div>
	</div>
</div>

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
		 @foreach ($holiday->StatesThatCelebrateThis as $var)
		 checkState('{{$var->stateid->id}}');
			 @endforeach
	 });
});

</script>



@endsection
