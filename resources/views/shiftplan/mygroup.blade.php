@extends('adminlte::page')

@section('title', 'Shift Groups')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">My Shift Groups</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <div class="table-responsive">
      <table id="grplist" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Code</th>
           <th>Name</th>
           <th>Planner</th>
           <th>Staff Count</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($p_list as $ap)
         <tr>
           <td>{{ $ap->group_code }}</td>
           <td>{{ $ap->group_name }}</td>
           <td>
           @if($ap->Planner)
           {{ $ap->Planner->name }}
           @endif
           </td>
           <td>{{ $ap->Members->count() }}</td>
           <td>
             <a href="{{ route('shift.mygroup.view', ['sgid' => $ap->id], false) }}"><button type="button" class="btn btn-np" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
           </td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
  </div>
</div>

@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {

  $('#grplist').DataTable({
    "responsive": "true"
  });

} );


</script>
@stop
