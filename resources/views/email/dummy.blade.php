@extends('adminlte::page')
@section('content')



<div class="content panel">
	<div class="row">

		@if(session('alert'))

				<div class="alert alert-{{$ac}}" role="alert">{{session('alert')}}</div>

		@endif
		<!-- left column -->
		<div class="col-md-12">
			<!-- general form elements -->
			<div class="box box-primary">

				<div class="box-header with-border">
					<h3 class="box-title">Send Basic Email</h3>
				</div>

				<!-- /.box-header -->
				<!-- form start -->
				<form role="form" method="POST" action="{{ route('email.senddummy', [], false) }}">
					@csrf
					<div class="box-body">
						<div class="form-group">

							<label for="email_to_id">To</label> <input type="text"
								class="form-control" id="email_to_id" name="email_to">


						</div>

            <div class="form-group">

              <label for="email_subject_id">Subject</label> <input type="text"
                class="form-control" id="email_subject_id" name="email_subject">
            </div>


						<div class="form-group">
							<label for="email_body_id">Content</label>
              <textarea
								class="form-control" id="email_body_id"
								placeholder="Write something" name="email_body"
								size="5"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Send Email</button>
					</div>
					<!-- /.box-body -->
				</form>
			</div>
			<!-- /.box -->
		</div>
		<!--/.col (left) -->
	</div>
	<!-- /.row -->
</div>
<!-- /.section -->








@endsection
