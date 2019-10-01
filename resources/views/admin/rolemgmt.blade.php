@extends('adminlte::page')

@section('title', 'Roles List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Roles</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="tRoleList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Created at</th>
                        <th>Created by</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $singleuser)
                    <tr>
                        <td>{{ $singleuser->id }}</td>
                        <td>{{ $singleuser->title }}</td>
                        <td>{{ $singleuser->created_at }}</td>
                        <td>{{ $singleuser->created_by }}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRole" data-role_id="{{$singleuser['id']}}" data-role_name="{{$singleuser['title']}}">
                                MANAGE
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteRole" data-role_id="{{$singleuser['id']}}" data-role_name="{{$singleuser['title']}}">
                                DELETE
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newRole">
                CREATE NEW ROLE
            </button>
        </div>
    </div>
</div>

<!-- Modal-->
<div id="newRole" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Create New Role</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('role.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="inputname">Role Name:</label>
                    <input type="text" class="form-control" id="inputname" name="inputname" placeholder="{{ __('adminlte::adminlte.input_role_name') }}" value="{{ old('inputname') }}" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">CREATE</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
        </div>
    </div>
    
    </div>
</div>

<!-- Modal-->
<div id="editRole" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Role</h4>
        </div>
        <div class="modal-body">
            <form action="{{ route('role.edit') }}" method="POST">
                @csrf
                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                <div class="form-group">
                    <label for="inputname">Role Name:</label>
                    <input type="text" class="form-control" id="inputname" name="inputname" value="" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">SAVE</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>

    </div>
</div>

<!-- Modal-->
<div id="deleteRole" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Role</h4>
            </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-warning-sign" style="color: #F0AD4E; font-size: 32px;"></div>
                <p>Are you sure you want to delete role <span id="showname"></span>?<p>
                <form action="{{ route('role.delete') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <input type="text" class="form-control hidden" id="inputname" name="inputname" value="" required>
                    <button type="submit" class="btn btn-primary">DELETE</button>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

@if(session()->has('feedback'))
<div id="feedback" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-{{session()->get('feedback_icon')}}" style="color: {{session()->get('feedback_color')}}"></span>&nbsp;{{session()->get('feedback_title')}}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{{session()->get('feedback_text')}}<p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
@endif

@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tRoleList').DataTable({
      "responsive": "true",
      "order" : [[2, "asc"]]
    //   "columns": [
    //         null,
    //         { "width": "40%" },
    //         null,
    //         null,
    //         { "width": "20%" }
    //     ]
    });
} );

function populate(e){
    var role_id = $(e.relatedTarget).data('role_id');
    var role_name = $(e.relatedTarget).data('role_name')
    $('input[name=inputid]').val(role_id);
    $('input[name=inputname]').val(role_name);
    $('#showname').text(role_name);
}

$('#editRole').on('show.bs.modal', function(e) {
    populate(e);
});

$('#deleteRole').on('show.bs.modal', function(e) {
    populate(e);
});

@if(session()->has('feedback'))
    $('#feedback').modal('show');   
@endif

</script>
@stop