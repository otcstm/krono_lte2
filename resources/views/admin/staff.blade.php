@extends('adminlte::page')

@section('title', 'Search Staff')

@section('content')


@if($auth ?? '')
<h1>User Authorization Setting</h1>
@endif


<div class="panel panel-default panel-main">

    <div class="panel-body">
        <form action="{{ route('staff.search') }}" method="POST">
            @csrf
            <h4><b>Search Staff</b></h4>
            <div class="form-group">
                <input type="text" style="position: relative; z-index: 8; width: 100%" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}" autofocus>
                <i style="position: relative; z-index: 9; margin-left: -25px" class="fas fa-search"></i>
            </div>
            @if($auth ?? '')
            <input type="text" class="form-control hidden" id="auth" name="auth" value="auth">
            @elseif($mgmt ?? '')
            <input type="text" class="form-control hidden" id="mgmt" name="mgmt" value="mgmt">
            @endif
            <div class="text-right">
                <button  type="submit" class="btn-up">SEARCH</button>
            </div>
            <br>
        </form>
        <div class="line2"></div>
        <h4><b>Search Result</b></h4>
        <br>
        {{--@if(session()->has('staffs'))--}}
        <div class="table-responsive">
            <table id="tStaffList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        @if($auth ?? '')
                        <th>Roles</th>
                        @else
                        <th>Company</th>
                        <th>State</th>
                        @endif
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $no=> $singleuser)
                    <tr>
                        <td>{{ $singleuser->staff_no }}</td>
                        <td>{{ $singleuser->name }}</td>
                        <td>{{ $singleuser->email }}</td>
                        @if($auth ?? '')
                        <td>@foreach ($singleuser->roles as $indexKey => $user)<p>{{$indexKey+1}}. {{ $user->title }}</p>@endforeach</td>
                        <td>
                            <button type="button" class="btn btn-np" id="edit-{{$no}}" data-role_id="{{$singleuser['id']}}" data-role_no="{{$singleuser['staff_no']}}" data-role_name="{{$singleuser['name']}}" data-role_user="@foreach ($singleuser->roles as $user){{ $user->id }} @endforeach">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        @else
                        <td>@if($singleuser->company_id ?? ''){{ $singleuser->companyid->company_descr }}@endif</td>
                        <td>@if($singleuser->state_id ?? ''){{ $singleuser->stateid->state_descr }}@endif</td>
                            @if($mgmt ?? '')
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editMgmt" data-role_id="{{$singleuser['id']}}" data-role_no="{{$singleuser['staff_no']}}" data-role_name="{{$singleuser['name']}}"data-role_company="{{$singleuser['company_id']}}" data-role_state="{{$singleuser['state_id']}}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </td>
                            @else
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info" data-role_id="{{$singleuser['id']}}" data-role_no="{{$singleuser['staff_no']}}" data-role_name="{{$singleuser['name']}}" data-role_email="{{$singleuser['email']}}" data-role_company="@if($singleuser->company_id ?? ''){{ $singleuser->companyid->company_descr }}@endif" data-role_state="@if($singleuser->state_id ?? ''){{ $singleuser->stateid->state_descr }}@endif">
                                    <i class="fas fa-info"></i>
                                </button>
                            </td>
                            @endif
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{--@endif--}}
    </div>
</div>

<form action="{{ route('staff.edit.auth') }}" method="POST" id="edit" class="hidden">
	@csrf
    <input type="text" class="form-control" id="inputid" name="inputid" value="" required>
        @if($roles ?? '')
            @foreach($roles as $no  => $singlerole)
            <div class="checkbox">
                <label><input type="checkbox" id="checkbox_{{$no}}" name="role[]" value="{{$singlerole->id}}">{{$singlerole->title}}</label>
            </div>
            @endforeach
        @endif
</form>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tStaffList').DataTable({
        "responsive": "true",
        "order" : [[0, "desc"]],
        "columns": [
            null,
            null,
            null,
            null
            @if($auth ?? '')
            @else
            ,null
            @endif
            ,{ "width": "5%" }
        ]
    });
});

function populate(e){
    var role_id = $(e.relatedTarget).data('role_id');
    var role_name = $(e.relatedTarget).data('role_name');
    var role_no = $(e.relatedTarget).data('role_no');
    var role_email = $(e.relatedTarget).data('role_email');
    var role_company = $(e.relatedTarget).data('role_company');
    var role_state = $(e.relatedTarget).data('role_state');
    @if($auth ?? '')
    var role_user = $(e.relatedTarget).data(('role_user'));
    var role_users = role_user.split(" ");
    @endif
    $('.showno').text(role_no);
    $('.showname').text(role_name);
    $('.showid').text(role_id);
    $('.showemail').text(role_email);
    $('.showncompany').text(role_company);
    $('.showstate').text(role_state);
    $('input[name=inputid]').val(role_id);
    $('input[name=inputname]').val(role_name);
    $('input[name=inputno]').val(role_no);
    $("#company").val(role_company);
    $("#state").val(role_state);
    @if($auth ?? '')
    for(i=0; i<role_users.length; i++){
        $("#checkbox_"+role_users[i]).prop('checked', true);
    }

    @endif
}

$('#editRole').on('show.bs.modal', function(e) {
    populate(e);
});

$('#editMgmt').on('show.bs.modal', function(e) {
    populate(e);
});

$('#info').on('show.bs.modal', function(e) {
    populate(e);
});

for(i = 0; i<{{count($roles)}}; i++){
	$("#edit-"+i).on("click", edit(i));
	
}

function edit(i){
	return function(){
        var role_id = $("#edit-"+i).data('role_id');
        var role_name = $("#edit-"+i).data('role_name')
        var role_no = $("#edit-"+i).data('role_no');
        var role_user = $("#edit-"+i).data(('role_user'));
        var role_users = role_user.split(" ");
        var html = "<div class='row'>"+
					"<div class='col-md-3 col-md-offset-1'>"+
						"<p>Staff ID</p>"+
					"</div>"+
					"<div class='col-md-8'>"+
                        "<p>"+role_no+"</p>"+
					"</div>"+
				"</div>"+
                "<div class='row'>"+
					"<div class='col-md-3 col-md-offset-1'>"+
						"<p>Role Name</p>"+
					"</div>"+
					"<div class='col-md-8'>"+
                        "<p>"+role_name+"</p>"+
					"</div>"+
				"</div>"+
				"<div class='row'>"+
					"<div class='col-md-3 col-md-offset-1'>"+
						"<p>Set Roles</p>"+
                    "</div>";
        var checked = "";
        var num = 1;
        @if($roles ?? '')
            @foreach($roles as $no => $singlerole)
                checked = "";
                if({{$no}}==0){
                    html = html +"<div class='col-md-8'>";
                }else{
                    html = html +"<div class='col-md-8 col-md-offset-4'>";
                }
                
                for(i=0; i<role_users.length; i++){
                    if(role_users[i]=={{$singlerole->id}}){
                        checked = "checked";
                    }
                }
                html = html + "<input type='checkbox' id='checkbox-{{$no}}' name='permission[]' value='{{$singlerole->id}}' "+checked+"> {{$singlerole->title}}"+
                "</div>";
                num++;
            @endforeach
        @endif
		Swal.fire({
			title: 'Edit Role',
			html: "<div class='text-left'>"+html+
				"</div>"+
				"</div>",
			showCancelButton: true,
			customClass:'test4',
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'SAVE',
			cancelButtonText: 'CANCEL'
			}).then((result) => {
			if (result.value) {
                $('#inputid').val(role_id);
                for(x=0; x<num; x++){
                    if ($('#checkbox-'+x).is(':checked')){
                        $('#checkbox_'+x).prop('checked', true);
                    }
                }
                $("#edit").submit();
			}
		})
	}
}

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif

</script>
@stop