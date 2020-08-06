@extends('adminlte::page')

@section('title', 'Search Staff')

@section('content')
<style>
    .sizeX{
        width: 200px;
    }
    .sizeA{
        width: 100px;
    }
</style>


<h1>Display User Profile</h1>

<div class="panel panel-default panel-main">

    <div class="panel-body">
        <form action="{{route('staff.cari')}}" method="POST">
            @csrf
            <h4><b>Search Staff</b></h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">Personnel Number</div>
                        <!-- <input type="text" style="position: relative; z-index: 8; width: 100%" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}" autofocus> -->
                        <div class="col-md-8"><input type="text" placeholder="e.g. 30013,45450" id="inputpersno"  name="inputpersno" style="width: 100%; " value="{{ old('inputpersno') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Name</div>
                        <div class="col-md-8"><input type="text" id="inputName"  name="inputName" style="width: 100%; " value="{{ old('inputName') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Email</div>
                        <div class="col-md-8"><input type="text" id="inputEmail"  name="inputEmail" style="width: 100%; " value="{{ old('inputEmail') }}"></div>
                    </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                      <div class="col-md-4">Staff No</div>
                      <div class="col-md-8"><input type="text" placeholder="e.g. TM32222,S66464" id="inputStaffno"  name="inputStaffno" style="width: 100%; " value="{{ old('inputStaffno') }}"></div>
                  </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">PMIC</div>
                        <div class="col-md-8"><input type="text" placeholder="e.g. 901026102121,931226095156" id="inputPMIC"  name="inputPMIC" style="width: 100%; " value="{{ old('inputPMIC') }}"></div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button class="btn-up">SEARCH</button>
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
                      <th><div class="sizeX">Name</div></th>
                      <th>Persno</th>
                      <th><div class="sizeA">Staff ID</div></th>
                      <th>NIC</th>
                      <th>Email</th>
                      <th><div class="sizeA">Active Status</div></th>
                      <th>Perssubarea</th>
                      <th><div class="sizeX">Company</div></th>
                      <th><div class="sizeA">Emp Group</div></th>
                      <th>Region</th>
                      <th><div class="sizeA">Cost Center</div></th>
                      <th><div class="sizeX">Ot Salary Exception</div></th>
                      <th><div class="sizeX">Ot Hour Exception</div></th>
                      <th>Salary(RM)</th>
                      <th>Allowance(RM)</th>
                      <th><div class="sizeA">Work Schedule</div></th>
                      <th><div class="sizeX">Direct Report(DR)</div></th>
                      <th><div class="sizeA">DR Persno </div></th>
                      <th><div class="sizeA">DR StaffID </div></th>
                      <th><div class="sizeX">DR Email</div></th>
                      <th><div class="sizeA">DR Company</div></th>
                      <th><div class="sizeA">DR Cost Center</div></th>
                      <th><div class="sizeX">Ver Name</div></th>
                      <th><div class="sizeA">Ver ID</div></th>
                      <th><div class="sizeA">Ver StaffNo</div></th>
                      <!-- <th>State</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $no=> $singleuser)
                    <tr>
                      @if($auth ?? '')
                        <td>{{ $singleuser->name }}</td>
                        @else
                        <td>
                          <a href="{{route('staff.profile',['getProfile'=>$singleuser['id'],'user'=>'admin'],false)}}" target="_blank" >{{ $singleuser->name }}</a></td>
                        @endif
                        <td>{{ $singleuser->persno }}</td>
                        <td>{{ $singleuser->rs->staffno }}</td>
                        <td>{{ $singleuser->rs->new_ic }}</td>
                        <td>{{ $singleuser->rs->email }}</td>
                        <td>{{ $singleuser->rs->empstats }}</td>
                        <td>{{ $singleuser->rs->perssubarea ?? ''}} <br> {{$singleuser->rs->getreg()->perssubareades ?? ''}}</td>
                        <td>@if($singleuser->rs->company_id ?? ''){{$singleuser->rs->company_id}} <br>{{ $singleuser->rs->companyid->company_descr }}@endif</td>
                        <td>{{ $singleuser->rs->empgroup }}</td>
                        <td>{{ $singleuser->rs->getreg()->region ?? 'N/A'}}</td>
                        <td>{{ $singleuser->rs->costcentr ?? 'N/A'}}</td>
                        <td>
                          @if ($singleuser->ot_salary_exception == 'Y')
                          Actual
                          @elseif ($singleuser->ot_salary_exception == 'N')
                          Limit
                          @else
                          N/A
                          @endif
                        </td>
                        <td>
                          @if ($singleuser->ot_hour_exception == 'Y')
                          Yes
                          @elseif ($singleuser->ot_hour_exception == 'N')
                          No
                          @else
                          N/A
                          @endif
                        </td>
                        <td>{{ $singleuser->gaji ??'N/A'}}</td>
                        <td>{{ $singleuser->allowance ?? 'N/A'}}</td>
                        <td>{{ $singleuser->wccode ?? ''}}{{ $singleuser->wcdesc ?? ''}}</td>
                        <td>{{ $singleuser->report2->name ?? 'N/A'}} </td>
                        <td>{{ $singleuser->report2->user_id ?? 'N/A'}} </td>
                        <td>{{ $singleuser->report2->staffno ?? 'N/A'}} </td>
                        <td>{{ $singleuser->report2->email ?? 'N/A'}} </td>
                        <td>{{ $singleuser->report2->company_id ?? 'N/A'}} <br>{{ $singleuser->report2->companyid->company_descr ?? ''}} </td>
                        <td>{{ $singleuser->report2->costcentr ?? 'N/A'}}</td>
                        <td>{{ $singleuser->vername ?? 'N/A'}}</td>
                        <td>{{ $singleuser->verid ?? 'N/A'}}</td>
                        <td>{{ $singleuser->verstaffno ?? 'N/A'}}</td>
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
            "order" : [[0, "asc"]],
            dom: '<"flext"lB>rtip',
            buttons: [
            'excel'
            ]

            });
            });
            /*$(document).ready(function() {
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
            });*/

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
