@extends('adminlte::page')

@section('title', 'Payroll Group List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Payroll Groups</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="tRoleList" class="table table-bordered">
                <thead>
                    <tr>
                      <th>ID</th>
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
                      <td>{{ $pygroup->id }}</td>
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
                          <a href="{{ route('pygroup.editnew',['id'=>$pygroup['id']],false) }}" class="btn btn-xs btn-warning"><i class="fas fa-pencil-alt"></i></a>
                          <button type="submit" class="btn btn-xs btn-danger" title="Delete">
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
        <form action="{{ route('pygroup.create',[],false) }}" style="display:inline; float:right">
          @csrf
          <input type="submit" name="submit" value="Create New PYG" class="btn btn-primary"/>
        </form>

    </div>
</div>

<div id="deletePyg" class="modal fade" role="dialog">
</div>

@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tPygList').DataTable({
        "responsive": "true",
        "order" : [[2, "asc"]],
        "columns": [
            null,
            null,
            null,
            null,
            { "width": "10%" }
        ]
    });
});

function populate(e){
  var pyg_id = $(e.relatedTarget).data('pyg_id');
  var pyg_name = $(e.relatedTarget).data('pyg_name')
  var pyg_company = $(e.relatedTarget).data(('pyg_company'));
  var pyg_companies = pyg_company.split(" ");
  $('input[name=inputid]').val(pyg_id);
  $('input[name=inputname]').val(pyg_name);
  $('#showname').text(pyg_name);
  for(i=0; i<pyg_companies.length; i++){
      $(".checkbox_"+pyg_companies[i]).prop('checked', true);
  }
}

$('#deletePyg').on('show.bs.modal', function(e) {
    populate(e);
});

@if(session()->has('feedback'))
    $('#feedback').modal('show');
@endif

</script>
@stop
