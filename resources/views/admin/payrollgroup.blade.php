@extends('adminlte::page')

@section('title', 'Payroll Group List')

@section('content')
<h1>Payroll Group</h1>
<div class="panel panel-default panel-main">
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Payroll Group Management</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="tPygList" class="table table-hover table-bordered">
                <thead>
                    <tr>
                      <th>Group Name</th>
                      <th>Companies</th>
                      <th>Effective Date</th>
                      <th>Created by</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pygroups as $pygroup)
                    <tr>
                      <td>{{ $pygroup->pygroup }}</td>
                      <td>@foreach ($pygroup->companyingroup as $var)
                 		     {{$var->companyid->company_descr}}<br>
                 			 @endforeach</td>
                      <td>@foreach ($pygroup->companyingroup as $var)
                 		     {{$var->start_date->format('d/m/Y')}} to {{$var->end_date->format('d/m/Y')}}<br>
                 			 @endforeach</td>
                      <td>{{ $pygroup->createdby->id }}</td>
                      <td>
                      <form method="post" action="{{ route('pygroup.delete', [], false) }}" onsubmit="return confirm('Are you sure you want to delete?')">
                        @csrf
                        <a href="{{ route('pygroup.editnew',['id'=>$pygroup['id']],false) }}" class="btn btn-np"><i class="fas fa-pencil-alt"></i></a>
                        <button type="submit" class="btn btn-np" title="Delete">
      											<i class="fas fa-trash-alt"></i>
      									</button>
      									<input type="hidden" name="inputid" value="{{$pygroup->id}}">
      								</form>
                      </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- <div class="form-group text-center"> -->
			</div>
				<div class="panel-footer">
					<div class="text-right">
          <form action="{{ route('pygroup.create',[],false) }}" style="display:inline; float:center">
            @csrf
            <button type="submit" name="submit" value="Create New PYG" class="btn btn-primary">CREATE NEW PAYROLL</button>
          </form>
        </div>

				</div>
    </div>
</div>
<div id="deletePyg" class="modal fade" role="dialog">
</div>
</div>

@stop
@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tPygList').DataTable({
        "responsive": "true",
        "order" : [[0, "asc"]],
    

    });
});

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif
</script>
@stop
