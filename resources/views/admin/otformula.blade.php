@extends('adminlte::page')

@section('title', 'OT Formula')

@section('content')

<h1>Overtime Formula</h1>

<div class="panel panel-default panel-main">
    <div class="panel panel-default">
		<div class="panel-heading"><strong>Adding Overtime Formula</strong></div>
        <div class="panel-body">
        <p>form here</p>
        {{--<!-- <form action="{{route('oe.eligibility.add')}}" method="post">
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
                            <div class="col-md-6">
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
                            </div>
                        </div>
                        <div class="row" style=" margin-top: 15px">
                            <div class="col-md-4">Capping</div>
                            <div class="col-md-6"><input type="number" name="capping" id="capping" min="0" style="width: 100%;" required></div>
                        </div>
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
            </form> -->--}}
                <br>
            <div class="line2"></div>

            <br>
            <br>

            <h4><strong>Overtime Formula</strong></h4>
            <div class="table-responsive">
                <table id="tRoleList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Region</th>
                            <th>Company Code</th>
                            <th>Legacy Code</th>
                            <th>Day Type</th>
                            <th>Description</th>
                            <th>Claim Type</th>
                            <th>Min Hours</th>
                            <th>Min Minutes</th>
                            <th>Max Hours</th>
                            <th>Max Hours</th>
                            <th>Rate</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($of as $no => $singleuser)
                        
                        <tr>
                            <td>{{$singleuser->region}}</td>
                            <td>{{$singleuser->company_id}}</td>
                            <td>{{$singleuser->legacy_codes}}</td>
                            <td>{{$singleuser->day_type}}</td>
                            <td>{{$singleuser->descr}}</td>
                            <td>{{$singleuser->claim_type}}</td>
                            <td>{{$singleuser->min_hour}}</td>
                            <td>{{$singleuser->min_minute}}</td>
                            <td>{{$singleuser->max_hour}}</td>
                            <td>{{$singleuser->max_minute}}</td>
                            <td>{{$singleuser->rate}}</td>
                            <td>{{$singleuser->start_date}}</td>
                            <td>{{$singleuser->end_date}}</td>
                            {{--<!-- <td>
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
                            </td> --> --}}
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
        // "order" : [[13, "desc"]],
        "searching": false,
    });

    // t.on( 'order.dt search.dt', function () {
    //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         cell.innerHTML = i+1;
    //     } );
    // } ).draw();
});
</script>
@stop