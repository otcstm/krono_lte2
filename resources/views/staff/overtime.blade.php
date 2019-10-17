@extends('adminlte::page')

@section('title', 'Search Staff')

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
                        <th>Created by</th>
                        <th>Permissions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $singleuser)
                    <tr>
                        <td>{{ $singleuser->id }}</td>
                        <td>{{ $singleuser->title }}</td>
                        <td>{{ $singleuser->createdby->name }}</td>
                        <td>@foreach ($singleuser->permissions as $indexKey => $user)<p>{{$indexKey+1}}. {{ $user->title }}</p>@endforeach</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRole" data-role_id="{{$singleuser['id']}}" data-role_name="{{$singleuser['title']}}" data-role_permission="@foreach ($singleuser->permissions as $user){{ $user->id }} @endforeach">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteRole" data-role_id="{{$singleuser['id']}}" data-role_name="{{$singleuser['title']}}" data-role_permission="@foreach ($singleuser->permissions as $user){{ $user->id }} @endforeach">
                                <i class="fas fa-trash-alt"></i>
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


@stop

@section('js')
<script type="text/javascript">
</script>
@stop