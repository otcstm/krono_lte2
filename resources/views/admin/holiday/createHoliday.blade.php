@extends('adminlte::page')
@section('content')
@if($s_year!= 'all')

@endif
<h1>Create New Holiday</h1>
<div class="panel panel-default">
  	<div class="panel-body" style="padding-bottom: 20vh">
		<form method="POST" action="{{ route('holiday.insert',[],false) }}">
		@csrf
		<input type="hidden" name="s_year"  value="{{$s_year}}"/>
		<div class="row">
			<div class="col-md-11">
				<div class="row" style="margin-bottom: 15px">
					<div class="col-md-3">Date</div>
					<div class="col-md-9">: <input type="date" name="dt" required value="{{$dtVal}}" /></div>
				</div>
				<div class="row">		
					<div class="col-md-3">Holiday Description</div>
					<div class="col-md-9">:
						
					<input type="text" name="descr" required  style="margin-bottom: 15px"/>
					<br>
						<div class="flex" style="display: inline;">
							<button type="button" class="check btn-up" value="Check All">CHECK ALL</button>
							<button type="button" class="uncheck btn-up" value="UnCheck All">UNCHECK ALL</button>
						</div>
						<br>
						<input type="checkbox" name="guarantee_flag" value='1' style="margin-left: 5px;"> Guarentee Flag
						<div class="flex">
							@foreach ($states as $state)
							<div style="margin-left: 5px; width: 48%;  margin-top: 5px">
								<div class="row">
									<div class="col-md-2" style="white-space: nowrap;">
										<input type="checkbox" name="state_selections[]" value="{{ $state->id }} " class="questionCheckBox" > {{ $state->id }}
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
