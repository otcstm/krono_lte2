@extends('adminlte::page')

@section('title', 'Verifier Management')

@section('content')
<div class="row">
<div class="col-md-12">

  <div class="panel panel-default">
            <div class="panel-heading">            
         <h3 class="panel-title pull-left">Set Default Verifier
            </h3>
        <div class="clearfix"></div>

            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
@if (session()->has('sysmsg_type'))
        <div class="alert alert-{{ session()->get('sysmsg_class') }} alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><i class="{{ session()->get('sysmsg_icon') }} "></i> {{ session()->get('sysmsg_text') }}</strong>
        </div>
@endif
<!-- <div class="table-responsive"> -->
<table id="verifierList" class="table">
  <thead>
    <tr>
      <th>Group Code</th>
      <th>Verifier Name</th>
      <th>Verifier ID</th>
      <th>Staff Count</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody>

@foreach($verifierGroups as $row_verifierGroups)
    <tr>
      <td>{{ $row_verifierGroups->group_code }}</td>
      <td>{{ $row_verifierGroups->Verifier->name }}</td>
      <td>{{ $row_verifierGroups->Verifier->staff_no }}</td>
      <td>{{ $row_verifierGroups->Members()->count() }}</td>
      <td>
      <form method="post" action="{{ route('verifier.delGroup', [], false) }}" id="fd{{ $row_verifierGroups->id }}">
               @csrf
               <a href="{{ route('verifier.viewGroup', ['gid' => $row_verifierGroups->id], false) }}">
               <button type="button" class="btn btn-np" title="Edit">
               <i class="fas fa-pencil-alt"></i></button></a>
               <button type="button" class="btn btn-np" title="Delete" 
               onclick="return deletefile('{{ $row_verifierGroups->id }}','{{ $row_verifierGroups->group_name }}')"><i class="fas fa-trash-alt"></i></button>
               <input type="hidden" name="id" value="{{ $row_verifierGroups->id }}" />
             </form>
             
      </td>
    </tr>  
@endforeach   

  </tbody>
</table>
<!-- </div> -->
            </div><!-- /.panel-body -->
          </div><!-- /.panel panel-info -->
</div><!-- /.col-md-12 -->
</div><!-- /.row -->


<div class="row">
<div class="col-md-12">

  <div class="panel panel-default">
            <div class="panel-heading">            
         <h3 class="panel-title">Create Group Verifier</h3>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
<form class="form-horizontal" action="{{ route('verifier.createGroup',[],false) }}" method="post">
@csrf
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupname">Group Name:</label>
    <div class="col-sm-4">
      <input name="groupname" type="text" class="form-control" id="groupname" placeholder="Enter group name" required
      maxlength="150">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupcode">Group Code:</label>
    <div class="col-sm-4">
      <input name="groupcode" type="text" class="form-control" id="groupcode" placeholder="Enter group code" required
      maxlength="16">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="verifierid">Verifier Name:</label>
    <div class="col-sm-4">
      <select class="verifierListId form-control" name="verifierId" required></select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary btn-outline">Create</button>
    </div>
  </div>
</form> 

            </div><!-- /.panel-body -->
          </div><!-- /.panel panel-info -->

</div><!-- /.col-md-12 -->
</div><!-- /.row -->            
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    
    $('#verifierList').DataTable({
    });

    $('.verifierListId').select2({
        placeholder: 'Type a name',
        minimumInputLength: 3,
        ajax: {
          url: '/admin/verifier/subordSearch',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.name+' ('+item.id+')',
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
      });

} );

//when click delete group
function deletefile(id,gname){
  var fid = id;
  var gname = gname;

      Swal.fire({
      title: 'Group Deletion',
      html: "Are you sure want ot delete group "+gname+"?<br/>You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'YES',
      cancelButtonText: 'NO',
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
    }).then((result) => {
      if (result.value) {
        $("#fd"+fid).submit();
        Swal.fire(
          'Deleted!',
          'Your file has been deleted.',
          'success'
        )
      }
    })
  }

</script>
@stop