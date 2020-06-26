@extends('adminlte::page')

@section('title', 'System Eligibility')

@section('content')

<h1>System Eligibility</h1>

<div class="panel panel-default panel-main">
    <div class="panel panel-default">
		<div class="panel-heading"><strong>Adding Eligibility of Employee</strong></div>
        <div class="panel-body">
            <form action="{{route('oe.eligibility.add')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">Start Date</div>
                            <div class="col-md-6"><input type="date" name="sdate" id="sdate" style="width: 100%; position:relative; z-index: 11; background: transparent;" required><i style="position:relative; z-index: 10; margin-left: -20px;" class="far fa-calendar-alt"></i></div>
                        </div>
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Company Code</div>
                            <div class="col-md-6">
                                <select type="text" name="companycode" id="companycode" style="width: 100%;" required>
                                    <option selected hidden disabled></option>
                                    @foreach($comp as $companies)
                                        <option value="{{$companies->id}}">{{$companies->id}} - {{$companies->company_descr}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Region</div>
                            <div class="col-md-6">
                                <select type="text" name="region" id="region" style="width: 100%;" required>
                                    <option selected hidden disabled></option>
                                    <option value="SEM">SEM</option>
                                    <option value="SWK">SWK</option>
                                    <option value="SBH">SBH</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Employee Group</div>
                            <div class="col-md-6">
                                <select type="text" name="empgroup" id="empgroup" style="width: 100%;" required>
                                    <option selected hidden disabled></option>
                                    <option value="Permanent">Permanent</option>
                                    <option value="Contract - Internal">Contract - Internal</option>
                                    <option value="Contract - External">Contract - External</option>
                                    <option value="Contract - Retiree">Contract - Retiree</option>
                                    <option value="Contract - Sales">Contract - Sales</option>
                                    <option value="Contract - Project">Contract - Project</option>
                                    <option value="Contract for Service">Contract for Service</option>
                                    <option value="Retiree">Retiree</option>
                                    <option value="Trainee">Trainee</option>
                                    <option value="Leasing">Leasing</option>
                                    <option value="Part-Timer">Part-Timer</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Employee Subgroup</div>
                            <div class="col-md-6">
                                <select type="text" name="empsgroup" id="empsgroup" style="width: 100%;" required>
                                    <option selected hidden disabled></option>
                                    <option value="Non Executive">Non Executive</option>
                                    <option value="Executives">Executives</option>
                                    <option value="Academic">Academic</option>
                                    <option value="Management">Management</option>
                                    <option value="Senior Management">Senior Management</option>
                                    <option value="Top Management">Top Management</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Pay Scale Group</div>
                            <div class="col-md-6"><input type="text" name="psgroup" id="psgroup" style="width: 100%;" required></div>
                            <!-- <div class="col-md-6">
                                <select type="text" name="psgroup" id="psgroup" style="width: 100%;" required>
                                    <option selected hidden disabled></option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="D">D</option>
                                    <option value="E1">E1</option>
                                    <option value="E2">E2</option>
                                    <option value="M1">M1</option>
                                    <option value="M2">M2</option>
                                    <option value="P1">P1</option>
                                    <option value="P3">P3</option>
                                </select>
                            </div> -->
                        </div>
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Capping</div>
                            <div class="col-md-6"><input type="number" name="capping" id="capping" min="0" style="width: 100%;" required></div>
                        </div>
                        <!-- <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Minimum Basic Salary</div>
                            <div class="col-md-6"><input type="number" name="minsalary" id="minsalary" min="0" style="width: 100%;" required></div>
                        </div>
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Maximum Basic Salary</div>
                            <div class="col-md-6"><input type="number" name="maxsalary" id="maxsalary" min="0" style="width: 100%;" required></div>
                        </div> -->
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Total Hours</div>
                            <div class="col-md-6"><input type="number" name="hours" id="hours" min="0" max="744" style="width: 100%;" required></div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-right">
                    <button type="submit" class="btn btn-up">ADD NEW</button>
                </div>
                <br>
            </form>
            <div class="line2"></div>

            <br>
            <br>

            <h4><strong>Eligibility of Employee to Apply OT Claim</strong></h4>
            <div class="table-responsive">
                <table id="tRoleList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Region</th>
                            <th>Company Code</th>
                            <th>Employee Group</th>
                            <th>Employee Subgroup</th>
                            <th>Pay Scale Group</th>
                            <th>Capping</th>
                            <!-- <th>Minimum Basic Salary</th>
                            <th>Maximum Basic Salary<</th> -->
                            <th>Total Hours</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($oe as $no => $singleuser)
                        
                        <tr>
                            <td>{{$singleuser->region}}</td>
                            <td>{{$singleuser->company_id}}</td>
                            <td>{{$singleuser->empgroup}}</td>
                            <td>{{$singleuser->empsgroup}}</td>
                            <td>{{$singleuser->psgroup}}</td>
                            <td>RM{{$singleuser->salary_cap}}</td>
                            <!-- <td>RM{{$singleuser->min_salary}}</td>
                            <td>RM{{$singleuser->max_salary}}</td> -->
                            <td>{{$singleuser->hourpermonth}}</td>
                            <td>{{$singleuser->start_date}}</td>
                            <td>{{$singleuser->end_date}}</td>
                            <td>
                            @if(!($singleuser->start_date<=(date('Y-m-d'))))
                            <form method="post" action="{{route('oe.eligibility.remove')}}" id="formdelete-{{$no}}">
                                    @csrf
                                    <button type="button" class="btn btn-np" title="Edit" id="edit-{{$no}}" data-id="{{$singleuser['id']}}" data-company_id="{{$singleuser['company_id']}}" data-region="{{$singleuser['region']}}" data-empgroup="{{$singleuser['empgroup']}}" data-empsgroup="{{$singleuser['empsgroup']}}" data-psgroup="{{$singleuser['psgroup']}}" data-salary_cap="{{$singleuser['salary_cap']}}" data-min_salary="{{$singleuser['min_salary']}}" data-max_salary="{{$singleuser['max_salary']}}" data-hourpermonth="{{$singleuser['hourpermonth']}}" data-end_date="{{$singleuser['end_date']}}" data-start_date="{{$singleuser['start_date']}}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-np" title="Delete" data-empgroup="{{$singleuser['empgroup']}}" data-empsgroup="{{$singleuser['empsgroup']}}"  data-region="{{$singleuser['region']}}"  data-company_id="{{$singleuser['company_id']}}" data-start_date="{{$singleuser['start_date']}}" id="del-{{$no}}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <input type="hidden" name="inputid" value="{{$singleuser->id}}">
                                </form>
                                
                            @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form action="{{route('oe.eligibility.update')}}" method="post" class="hidden" id="edit">
    @csrf
    <input type="text" name="eid" id="eid">
    <input type="date" name="esdate" id="esdate">
    <input type="text" name="ecompanycode" id="ecompanycode">
    <input type="text" name="eregion" id="eregion">
    <input type="text" name="eempgroup" id="eempgroup">
    <input type="text" name="eempsgroup" id="eempsgroup">
    <input type="text" name="epsgroup" id="epsgroup">
    <input type="number" name="ecapping" id="ecapping">
    <!-- <input type="number" name="eminsalary" id="eminsalary">
    <input type="number" name="emaxsalary" id="emaxsalary"> -->
    <input type="number" name="ehours" id="ehours">
</form>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    var t = $('#tRoleList').DataTable({
        "responsive": "true",
        "order" : [[9, "desc"]],
        "searching": false,
    });

    // t.on( 'order.dt search.dt', function () {
    //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         cell.innerHTML = i+1;
    //     } );
    // } ).draw();
});

var today = new Date();
today.setDate(today.getDate() + 1);
var m = today.getMonth()+1;
var y = today.getFullYear();
var d = today.getDate().toString();

if(m < 10){
        m = "0"+m;
}
while(d.length<2){
    d = "0"+d;
}

$("#sdate").val(y+"-"+m+"-"+d);
$("#sdate").attr("min", y+"-"+m+"-"+d);

for(i = 0; i<{{count($oe)}}; i++){
	$("#edit-"+i).on("click", edit(i));
	$("#del-"+i).on("click", deleteid(i));	
}

function deleteid(i){
    return function(){
        var comp = $("#del-"+i).data('company_id');
        var region = $("#del-"+i).data('region');
        var empgroup = $("#del-"+i).data('empgroup');
        var empsgroup = $("#del-"+i).data('empsgroup');
        var start = $("#del-"+i).data('start_date');
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete system eligibility configuration for company code "+comp+" region "+region+" with employee group "+empgroup+" and employee subgroup "+empsgroup+" starting on "+start+"?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'DELETE',
            cancelButtonText: 'CANCEL'
            }).then((result) => {
            if (result.value) {
                $("#formdelete-"+i).submit();
            }
        })
    }
}

function edit(i){
    return function(){
        var id = $("#edit-"+i).data('id');
        var comp = $("#edit-"+i).data('company_id');
        var region = $("#edit-"+i).data('region');
        var empgroup = $("#edit-"+i).data('empgroup');
        var empsgroup = $("#edit-"+i).data('empsgroup');
        var psgroup = $("#edit-"+i).data('psgroup');
        var salary_cap = $("#edit-"+i).data('salary_cap'); 
        // var min_salary = $("#edit-"+i).data('min_salary'); 
        // var max_salary = $("#edit-"+i).data('max_salary'); 
        var hourpermonth = $("#edit-"+i).data('hourpermonth'); 
        var start_date = $("#edit-"+i).data('start_date'); 
        var end_date = $("#edit-"+i).data('end_date'); 
        var html = "<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Start Date</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='date' id='ssdate' class='check-0' value='"+start_date+"' style='width: 100%' min="+y+"-"+m+"-"+d+" required>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Company Code</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='scompanycode' class='check-1' value='"+comp+"' style='width: 100%' required disabled>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Region</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='sregion' class='check-2' value='"+region+"' style='width: 100%' disabled required>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Employee Group</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='empgroup' id='sempgroup' class='check-3' value='"+empgroup+"' style='width: 100%' required disabled>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Employee Subgroup</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='sempsgroup' class='check-4' value='"+empsgroup+"' style='width: 100%' required disabled>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Pay Scale Group</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='text' id='spsgroup' class='check-5' value='"+psgroup+"' style='width: 100%' required disabled>"+
						"</div>"+
					"</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Capping</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='number' id='scapping' class='check-6' value='"+salary_cap+"' style='width: 100%' required>"+
						"</div>"+
					"</div>"+
        			// "<div class='row'>"+
					// 	"<div class='col-md-4'>"+
					// 		"<p>Minimum Basic Salary</p>"+
					// 	"</div>"+
					// 	"<div class='col-md-8'>"+
					// 		"<input type='number' id='sminsalary' class='check-7' value='"+min_salary+"' style='width: 100%' required>"+
					// 	"</div>"+
					// "</div>"+
        			// "<div class='row'>"+
					// 	"<div class='col-md-4'>"+
					// 		"<p>Maximum Basic Salary</p>"+
					// 	"</div>"+
					// 	"<div class='col-md-8'>"+
					// 		"<input type='number' id='smaxsalary' class='check-8' value='"+max_salary+"' style='width: 100%' required>"+
					// 	"</div>"+
					// "</div>"+
        			"<div class='row'>"+
						"<div class='col-md-4'>"+
							"<p>Total Hours</p>"+
						"</div>"+
						"<div class='col-md-8'>"+
							"<input type='number' id='shours' class='check-9' value='"+hourpermonth+"' style='width: 100%' required>"+
						"</div>"+
					"</div>";
        var submit = true;
		Swal.fire({
			title: 'Edit System Eligibility',
			html: "<div class='text-left'>"+html+
				"</div>",
			showCancelButton: true,
			customClass:'test4',
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'SAVE',
			cancelButtonText: 'CANCEL'
			}).then((result) => {
			if (result.value) {
				for(i = 0; i<10; i++){
					if($('.check-'+i).get(0).checkValidity()==false){
						submit = false;
					}
				}
				if(submit){
					$('#eid').val(id);
					$('#esdate').val($("#ssdate").val());
					$('#ecompanycode').val($("#scompanycode").val());
					$('#eregion').val($("#sregion").val());
					$('#eempgroup').val($("#sempgroup").val());
					$('#eempsgroup').val($("#sempsgroup").val());
					$('#epsgroup').val($("#spsgroup").val());
					$('#ecapping').val($("#scapping").val());
					$('#eminsalary').val($("#sminsalary").val());
					$('#emaxsalary').val($("#smaxsalary").val());
					$('#ehours').val($("#shours").val());
					$('#edit').submit();
				}else{
					Swal.fire({
						title: "Incomplete Form",
						html: "Please fill in all input fields before saving",
						confirmButtonText: 'OK'
					}).then((result) => {
						edit(i);
					})
				}
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