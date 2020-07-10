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
                <!-- <i style="position: relative; z-index: 9; margin-left: -25px" class="fas fa-search"></i> -->
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
                        <th>Name</th>
                        <th>Persno</th>
                        <th>Staff ID</th>
                        <th>NIC</th>
                        <th>Email</th>
                        <th>Active Status</th>
                        <th>Perssubarea</th>
                        <th>Company</th>
                        @if($auth ?? '')
                        <th>Roles</th>
                        <th>Action</th>
                        @else
                        <th>Emp Group</th>
                        <th>Region</th>
                        <th>Cost Center</th>
                        <th>Ot Salary Exception</th>
                        <th>Ot Hour Exception</th>
                        <th>Salary (RM)</th>
                        <th>Allowance (RM)</th>
                        <!-- <th>Work Schedule</th> -->
                        <th>Direct Report(DR)</th>
                        <th>Persno DR</th>
                        <th>Staff ID DR</th>
                        <th>Email DR</th>
                        <th>Company DR</th>
                        <th>Cost Center DR</th>
                        <!-- <th>State</th> -->
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $no=> $one)
                    <tr>
                      @if($auth ?? '')
                        <td>{{ $one->name }}</td>
                        @else
                        <td><a href="{{route('staff.profile',['getProfile'=>$one['id'],'user'=>'admin'],false)}}" >{{ $one->name }}</a></td>
                        @endif
                        <td>{{ $one->persno }}</td>
                        <td>{{ $one->rs->staffno }}</td>
                        <td>{{ $one->rs->new_ic }}</td>
                        <td>{{ $one->rs->email }}</td>
                        <td>{{ $one->rs->emptstat }}</td>
                        <td>{{ $one->rs->perssubarea}} {{$one->rs->getreg()->perssubareades}}</td>
                        <td>@if($one->rs->company_id ?? ''){{$one->rs->company_id}} {{ $one->rs->companyid->company_descr }}@endif</td>
                        @if($auth ?? '')
                        <td>@foreach ($one->roles as $indexKey => $user)<p>{{$indexKey+1}}. {{ $user->title }}</p>@endforeach</td>
                        <td>
                            <button type="button" class="btn btn-np" id="edit-{{$no}}" data-role_id="{{$one['id']}}" data-role_no="{{$one['staff_no']}}" data-role_name="{{$one['name']}}" data-role_user="@foreach ($one->roles as $user){{ $user->id }} @endforeach">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        @else

                        <td>{{ $one->rs->empgroup }}</td>
                        <td>{{ $one->rs->getreg()->region ?? 'N/A'}}</td>
                        <td>{{ $one->rs->costcentr ?? 'N/A'}}</td>
                        <td>
                          @if(!empty($one->otindistaff->ot_salary_exception))

                        @if ($one->otindistaff->ot_salary_exception == 'Y')
                        YES
                        @elseif ($one->otindistaff->ot_salary_exception == 'N')
                        NO
                        @else
                        N/A
                        @endif
                        @else
                        N/A

                        @endif
                        </td>
                        <td>
                          @if(!empty($one->otindistaff->ot_salary_exception))

                          @if ($one->otindistaff->ot_hour_exception == 'Y')
                          YES
                          @elseif ($one->otindistaff->ot_hour_exception == 'N')
                          NO
                          @else
                          N/A
                          @endif
                          @else
                          N/A

                          @endif
                        </td>
                        <td>{{ $one->gajistaff->salary ??'N/A'}}</td>
                        <td>{{ $one->otindistaff->allowance ?? 'N/A'}}</td>
                        <!-- <td> Work Schedule</td> -->
                        <td>{{ $one->report2->name ?? 'N/A'}} </td>
                        <td>{{ $one->report2->user_id ?? 'N/A'}} </td>
                        <td>{{ $one->report2->staffno ?? 'N/A'}} </td>
                        <td>{{ $one->report2->email ?? 'N/A'}} </td>
                        <td>{{ $one->report2->company_id ?? 'N/A'}} {{ $one->report2->companyid->company_descr ?? ''}} </td>
                        <td>{{ $one->report2->costcentr ?? 'N/A'}}</td>
                        <!-- <td>@if($one->state_id ?? ''){{ $one->stateid->state_descr }}@endif</td> -->
                            @if($mgmt ?? '')
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editMgmt"
                                data-role_iddata-role_id="{{$one['id']}}"
                                data-role_no="{{$one['staff_no']}}"
                                data-role_name="{{$one['name']}}"
                                data-role_company="{{$one['company_id']}}"
                                data-role_state="{{$one['state_id']}}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </td>
                            @else
                            <!-- <td>
                                {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info"
                                data-role_id="{{$one['id']}}"
                                data-role_no="{{$one['staff_no']}}"
                                data-role_name="{{$one['name']}}"
                                data-role_email="{{$one['email']}}"
                                data-role_company="@if($one->company_id ?? ''){{ $one->companyid->company_descr }}@endif"
                                data-role_state="@if($one->state_id ?? ''){{ $one->stateid->state_descr }}@endif">
                                    <i class="fas fa-info"></i>
                                </button> --}}

        <form action="{{ route('staff.profile') }}" target="_blank" method="POST">
            @csrf
            <input type="hidden" name="getProfile" value="{{$one['id']}}" />
                                <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#info">
                                    <i class="fas fa-info"></i>
                                </button>
        </form>
                            </td> -->
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
        dom: '<"flext"lB>rtip',
      buttons: ['excel'],
        "columns": [
            { "width": "20%" },
            null,
            null,
            null,
            null,
            { "width": "10%" },
            null,
            null
            @if($auth ?? '')
            ,null
            ,{ "width": "5%" }
            @else
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            ,null
            @endif
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
