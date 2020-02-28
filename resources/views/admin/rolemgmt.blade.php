@extends('adminlte::page')

@section('title', 'Roles List')

@section('content')

<h1>Role Management</h1>

<div class="panel panel-default panel-main">
    <div class="panel panel-default">
		<div class="panel-heading"><strong>Role Management</strong></div>
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
                        @foreach($roles as $no => $singleuser)
                        <tr>
                            <td>{{++$no}}</td>
                            <td>{{ $singleuser->title }}</td>
                            <td>{{ $singleuser->createdby->name }}</td>
                            <td class="td-left">@foreach ($singleuser->permissions as $indexKey => $user)<p>{{$indexKey+1}}. {{ $user->title }}</p>@endforeach</td>
                            <td>
                                <form method="post" action="{{ route('role.delete', [], false) }}" id="formdelete">
                                    @csrf
                                    <button type="button" class="btn btn-np" title="Edit" id="edit-{{$no}}" data-role_id="{{$singleuser['id']}}" data-role_name="{{$singleuser['title']}}" data-role_permission="@foreach ($singleuser->permissions as $user){{ $user->id }} @endforeach"
                                        >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-np" title="Delete" data-compdescr="{{$singleuser['title']}}" onclick="return deleteid()" id="buttond">
                                            <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <input type="hidden" name="inputid" value="{{$singleuser->id}}">
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="line"></div>
			<h4><b>Add New Role</b></h4>
			<form action="{{ route('role.store', [], false) }}" method="post">
				@csrf
				<div class="row">
					<div class="col-md-8">
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-3">
								<label for="inputname">Role Name:</label>
							</div>
							<div class="col-md-3">
                            <input type="text" id="inputname" name="inputname" placeholder="{{ __('adminlte::adminlte.input_role_name') }}" value="{{ old('inputname') }}" required autofocus>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 col-md-offset-3">
                            @if($permissions ?? '')
                                @foreach($permissions as $singlerole)
                                <div class="checkbox">
                                    <label><input type="checkbox" id="checkbox_{{$singlerole->id}}" name="permission[]" value="{{$singlerole->id}}">{{$singlerole->title}}</label>
                                </div>
                                @endforeach
                            @endif
							</div>
						</div>
						
					</div>
				</div>
		</div>
		<div class="panel-footer">
		
		<div class="text-right">
                <button type="submit" class="btn btn-p btn-primary">CREATE NEW ROLE</button>
							
		</div>
			</form>
        </div>
    </div>
</div>

<form action="{{ route('role.edit') }}" method="POST" id="edit" class="hidden">
	@csrf
	<input type="text" class="form-control" id="eid" name="inputid" value="">
	<input type="text" class="form-control" id="ename" name="inputname" value="">
        @if($permissions ?? '')
            @foreach($permissions as $no => $singlerole)
            <input type='checkbox' id='check-{{++$no}}' name='permission[]' value='{{$singlerole->id}}'>
	
            @endforeach
        @endif
</form>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tRoleList').DataTable({
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
    var role_id = $(e.relatedTarget).data('role_id');
    var role_name = $(e.relatedTarget).data('role_name')
    var role_permission = $(e.relatedTarget).data(('role_permission'));
    var role_permissions = role_permission.split(" ");
    $('input[name=inputid]').val(role_id);
    $('input[name=inputname]').val(role_name);
    $('#showname').text(role_name);
    for(i=0; i<role_permissions.length; i++){
        $(".checkbox_"+role_permissions[i]).prop('checked', true);
    }
}

$('#editRole').on('show.bs.modal', function(e) {
    populate(e);
});

$('#deleteRole').on('show.bs.modal', function(e) {
    populate(e);
});

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif



function deleteid(){
	
    var ps_comp = $("#buttond").data('compdescr');
	Swal.fire({
        title: 'Are you sure?',
        text: "Delete role "+ps_comp+"?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'DELETE',
        cancelButtonText: 'CANCEL'
        }).then((result) => {
        if (result.value) {
            $("#formdelete").submit();
        }
    })
}

for(i = 0; i<{{count($roles)}}+1; i++){
	$("#edit-"+i).on("click", edit(i));
	
}

function edit(i){
	return function(){
        var role_id = $("#edit-"+i).data('role_id');
        var role_name = $("#edit-"+i).data('role_name')
        var role_permission = $("#edit-"+i).data(('role_permission'));
        var role_permissions = role_permission.split(" ");
        var html = "<div class='row'>"+
					"<div class='col-md-4'>"+
						"<p>Role Name</p>"+
					"</div>"+
					"<div class='col-md-8'>"+
						"<input type='text' id='cname' value='"+role_name+"' style='width: 100%' required>"+
					"</div>"+
				"</div>"+
				"<div class='row'>"+
					"<div class='col-md-4'>"+
						"<p>Set Permission</p>"+
                    "</div>";
        var checked = "";
        @if($permissions ?? '')
            @foreach($permissions as $no => $singlerole)
                @php(++$no)
                checked = "";
                if({{$no}}==1){
                    html = html +"<div class='col-md-8'>";
                }else{
                    html = html +"<div class='col-md-8 col-md-offset-4'>";
                }
                
                for(i=0; i<role_permissions.length; i++){
                    if(role_permissions[i]=={{$singlerole->id}}){
                        checked = "checked";
                    }
                }
                html = html + "<input type='checkbox' id='checkbox-{{$no}}' name='permission[]' value='{{$singlerole->id}}' "+checked+"> {{$singlerole->title}}"+
                "</div>";
        
            @endforeach
        @endif
		Swal.fire({
			title: 'Edit Company',
			html: "<div class='text-left'>"+html+
				"</div>"+
				"</div>",
			showCancelButton: true,
			customClass:'test4',
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'SELECT',
			cancelButtonText: 'CANCEL'
			}).then((result) => {
			if (result.value) {
				if(($('#cname').val()!="")){
                    $('#eid').val(role_id);
                    $('#ename').val($('#cname').val());
                    for(x=1; x<5; x++){
                        if ($('#checkbox-'+x).is(':checked')){
                            $('#check-'+x).prop('checked', true);
                        }
                    }
					$("#edit").submit();
					// alert($('#cid').val()+" "+$('#eid').val());
				}else{
					// Swal.fire({
					// 		icon: 'error',
					// 		title: 'Edit Error',
					// text: "Name field cannot be empty!",
					// confirmButtonText:'OK'
					// }).then((result) => {
                    //     if (result.value) {
                    //       return edit(i);  
                    //     }
                    // })
                    if($('#cname').get(0).checkValidity()==false){
                        $('#cname').get(0).reportValidity();
                    }
				}
			}
		})
	}
}

</script>
@stop