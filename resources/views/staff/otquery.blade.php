@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
@if($view=='verifier')
<h1>Pending Verification Claim</h1>
@elseif($view=='verifierrept')
<h1>Claim Verification Report</h1>
@elseif($view=='approver')
<h1>Pending Approval Claim</h1>
@elseif($view=='approverrept')
<h1>Claim Approval Report</h1>
@elseif($view=='admin')
<h1>Assign Verifier/Approver</h1>
<!-- <h1>Claim Manual Approval</h1> -->
@endif
<div class="panel panel-default">
    <div class="panel-body">
        
        @if($view=='admin')
        
        <form action="{{route('ot.adminsearch',[],false)}}" method="POST" onsubmit="return submitsearch()"> 
            @csrf    
            <h4><b>Search Parameter</b></h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">Company Code</div>
                        <div class="col-md-8"><input type="text" id="search-1" class="searchman searchman-1" name="searchcomp" style="width: 100%; " data-text="Company Code" readonly ><i style="position: relative; margin-left: -20px" class="far fa-share-square"></i></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Personnel Number</div>
                        <div class="col-md-8"><input type="text" id="search-2" class="searchman searchman-2" name="searchpersno" style="width: 100%; " data-text="Personnel Number" readonly ><i style="position: relative; margin-left: -20px" class="far fa-share-square"></i></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Personnel Area</div>
                        <div class="col-md-8"><input type="text" id="search-3" class="searchman searchman-3" name="searchpersarea" style="width: 100%; " data-text="Personnel Area" readonly ><i style="position: relative; margin-left: -20px" class="far fa-share-square"></i></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Personnel Sub Area</div>
                        <div class="col-md-8"><input type="text" id="search-4" class="searchman searchman-4" name="searchperssarea" style="width: 100%; " data-text="Personnel Sub Area" readonly><i style="position: relative; margin-left: -20px" class="far fa-share-square"></i></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">Submission Date</div>
                        <div class="col-md-4"><input type="date" id="search-date-1" name="searchdate1" style="width: 100%; position: relative; z-index: 11; border: 1px solid #A9A9A9; background: transparent" data-text="Company Code"><i style="position: relative; margin-left: -20px; z-index: 10" class="far fa-calendar-alt"></i></div>
                        <div class="col-md-4"><input type="date" id="search-date-2" name="searchdate2" style="width: 100%; position: relative; z-index: 11; border: 1px solid #A9A9A9; background: transparent" data-text="Company Code"><i style="position: relative; margin-left: -20px; z-index: 10" class="far fa-calendar-alt"></i></div>       
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Claim Status</div>
                        <div class="col-md-8"><input type="text" id="search-5" class="searchman searchman-5" name="searchstatus" style="width: 100%; " data-text="Claim Status"><i style="position: relative; margin-left: -20px" class="far fa-share-square" readonly ></i></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Overtime Date</div>
                        <div class="col-md-8"><input type="text" id="search-6" class="searchman searchman-6" name="searchotdate" style="width: 100%; " data-text="Overtime Date"><i style="position: relative; margin-left: -20px" class="far fa-share-square" readonly ></i></div>
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
        @endif

    @if(count($otlist)!=0)
        
        <form action="{{route('ot.query',[],false)}}" method="POST" style="display:inline" onsubmit="return submits()"> 
            @csrf    
            @if($view=='verifier')
            <input type="text" class="hidden" name="typef" value="verifier" required>
            @elseif($view=='approver')
            <input type="text" class="hidden" name="typef" value="approver" required>
            @elseif($view=='admin')
            <input type="text" class="hidden" name="typef" value="admin" required>
            @endif
            <div class="table-responsive">
                <table id="tOTList" class="table table-bordered">
                    <thead style="background: grey">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Day Type</th>
                            <th>Start OT</th>
                            <th>End OT</th>
                            <th>Total Day</th>
                            <th>Total Hours/Minutes</th>
                            <th>Transaction Code</th>
                            <th>Amount (Estimated)</th>
                            <th>Status</th>
                            @if(($view=='verifier')||($view=='approver')||($view=='admin'))
                                @if(($view=='approver')||($view=='admin'))
                                <th>Verifier</th>
                                @endif
                                @if(($view=='verifier')||($view=='admin'))
                                <th>Approver</th>
                                @endif
                            <th class="border-right" id="borderman">Action</th>
                            <th id="aremark" style="display: none">Action Remark</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($otlist as $no=>$singleuser)
                        <tr>
                            <input type="text" class="form-control hidden" name="inputid[]" value="{{$singleuser->id}}" required>
                            <input type="text" class="form-control hidden" name="inputact[]" style="border: 1px solid green"  value="">
                            <input type="text" class="form-control hidden" style="border: 1px solid blue" name="inputapp[]" value="">
                            <input type="text" class="form-control hidden" style="border: 1px solid red" name="inputver[]" value="">
                            <input type="text" class="form-control hidden" name="inputrem[]" value="">
                            <td>{{++$no}}</td>
                            <td>{{ $singleuser->name->name }}</td>
                            <td><a href="" id="a-{{$no}}" style="font-weight: bold; color: #143A8C" data-id="{{$singleuser->id}}">{{ date("d.m.Y", strtotime($singleuser->date)) }} ({{$singleuser->employee_type}})</a></td>
                            <td>@if($singleuser->daytype->day_type == "N")
                                    Normal Day
                                @elseif($singleuser->daytype->day_type == "PH")
                                    Public Holiday
                                @elseif($singleuser->daytype->day_type == "R")
                                    Rest Day
                                @else
                                    Off Day
                                @endif
                            </td>
                            <td>
                                @foreach($singleuser->detail as $details)
                                    {{date('Hi', strtotime($details->start_time)) }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($singleuser->detail as $details)
                                    {{ date('Hi', strtotime($details->end_time))}}<br>
                                @endforeach
                            </td>
                            <td>{{ $singleuser->eligible_day }}</td>
                            <td>@if($singleuser->eligible_day==0){{$singleuser->total_hour}}h {{$singleuser->total_minute}}m @else @php($total = $singleuser->eligible_total_hours_minutes*60) {{(int)($total/60)}}h {{$total%60}}m @endif</td>
                            <td>@if(($singleuser->eligible_day_code)&&($singleuser->eligible_total_hours_minutes_code)) {{$singleuser->eligible_day_code}}, {{$singleuser->eligible_total_hours_minutes_code}} @elseif($singleuser->eligible_total_hours_minutes_code) {{$singleuser->eligible_total_hours_minutes_code}} @else {{$singleuser->eligible_day_code}} @endif</td>
                            
                            <td>RM{{$singleuser->amount}}</td>
                            <td>@if($singleuser->status=="PA")
                                    <span>Pending Approval</span>
                                @elseif($singleuser->status=="PV")
                                    <span>Pending Verification</span>
                                @elseif($singleuser->status=="A")
                                    <span>Approved</span>
                                @endif</td>
                            
                            @if(($view=='verifier')||($view=='approver')||($view=='admin'))
                                @if(($view=='approver')||($view=='admin'))
                                    <td>
                                        <span class='hidden' id="show-verifier-cache-{{$no}}">{{$singleuser->verifier->name}}</span>
                                        
                                        <span id="show-verifier-na-{{$no}}" @if($singleuser->verifier->name!="N/A") class="hidden" @endif >{{$singleuser->verifier->name}}</span>
                                        
                                        <a id="show-verifier-a-{{$no}}" href="#" style="font-weight: bold; color: #143A8C" @if($singleuser->verifier->name=="N/A") class="hidden" @endif onclick="showverifier({{$no}})" data-id="{{$singleuser->verifier_id}}" data-otid="{{$singleuser->id}}"><span id="show-verifier-{{$no}}">{{$singleuser->verifier->name}}</span></a>
                                        
                                    </td>
                                @endif
                                
                                @if(($view=='verifier')||($view=='admin'))
                                <td>
                                    <span class='hidden' id="show-approver-cache-{{$no}}">{{$singleuser->approver->name}}</span>
                                    <!-- <span id="show-approver-na-{{$no}}" @if($singleuser->approver->name!="N/A") class="hidden" @endif >{{$singleuser->approver->name}}</span> -->
                                     
                                     @if($singleuser->approver->name=="N/A")<a id="show-approver-a-{{$no}}" href="#" style="font-weight: bold; color: #143A8C" onclick="otid ={{$singleuser->id}}; return normal({{$no}}, 'none', 'Approver')" data-id="{{$singleuser->approver_id}}" data-otid="{{$singleuser->id}}"><span id="show-approver-{{$no}}">{{$singleuser->approver->name}}</span></a>
                                    @else<a id="show-approver-a-{{$no}}" href="#" style="font-weight: bold; color: #143A8C" @if($singleuser->approver->name=="N/A") class="hidden" @endif onclick="showapprover({{$no}})" data-id="{{$singleuser->approver_id}}" data-otid="{{$singleuser->id}}"><span id="show-approver-{{$no}}">{{$singleuser->approver->name}}</span></a>@endif
                                </td>
                                @endif
                            <td class="border-right" id="borderman-{{$no}}">
                                <input type="text" class="hidden"  id="verifier-cache-{{$no}}" value="{{$singleuser->verifier_id}}">
                                <input type="text" class="hidden"  id="approver-cache-{{$no}}" value="{{$singleuser->approver_id}}">
                                <input type="text" class="hidden"  id="verifier-{{$no}}" name="verifier[]" value="{{$singleuser->verifier_id}}">
                                <input type="text" class="hidden"  id="approver-{{$no}}" name="approver[]" value="{{$singleuser->approver_id}}">
                                <select name="inputaction[]" id="action-{{$no}}" data-vid="{{$singleuser->verifier_id}}" data-otid="{{$singleuser->id}}">
                                    <option selected value="">Select Action</option>
                                    <option hidden value="Remove">Remove Verifier</option>
                                    <!-- <option hidden disabled selected value="">Select Action</option> -->
                                    @if($view=="verifier") 
                                        <option value="PA">Verify</option>
                                    @endif
                                    @if($view=='approver')
                                        <option value="A">Approve</option>
                                        <option 
                                            @if($singleuser->verifier_id!=null) 
                                                hidden 
                                            @endif  
                                            value="Assign" id="assign-{{$no}}" data-type="Verifier" data-otid="{{$singleuser->id}}">Assign Verifier</option>
                                    @endif
                                    @if($view=='admin') 
                                        <option hidden value="Change">Change Approver</option>
                                    @endif
                                    <option value="Q2">Query</option>
                                </select>
                            </td>
                            <td id="aremark-{{$no}}" style="display: none">
                                <textarea rows = "4" cols="40" type="text" maxlength="300" id="inputremark-{{$no}}" name="inputremark[]" value="" placeholder="" onkeydown="this.onchange();" onchange='return checkstringx({{$no}});' style="max-height: 180px; resize: vertical; overflow-y: scroll; display: inline" readonly ></textarea>
                                <p style="float: right" class="small">Text remaining: <span id="textremain-{{$no}}">300</span></p>
                            </td>
                            @endif
                        </tr>
                        {{--<!-- <tr style="text-align:center; display: none" id="remark-{{$no}}">
                            <td colspan="11">
                                <span style="position: relative; top: -30px;"><b>Query Remark: </b></span>
                                <textarea rows = "2" cols = "100" type="text"  id="inputremark-{{$no}}" name="inputremark[]" value="" placeholder="Write justification" style="resize: none; display: inline"></textarea>
                            </td>
                        </tr> -->--}}
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(($view=='verifier')||($view=='approver')||($view=='admin'))
                @if($otlist ?? '')
                <div id="submitbtn" class="panel-footer">
                    <div class="text-right">  
                    <input type="hidden" name="pagenumber" id="pagenumber" value="0" />  
                        <button type="submit" class="btn btn-primary btn-p">SUBMIT</button>
                    </div>
                </div>
                @endif
            @endif
        </form>
        @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Day Type</th>
                        <th>Start OT</th>
                        <th>End OT</th>
                        <th>Total Hours/Minutes</th>
                        <th>Amount (Estimated)</th>
                        <!-- <th>Status</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8"><div class="text-center"><i>Not available</i></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>
    
</div>

        
<form action="{{route('ot.detail',[],false)}}" method="POST" class="hidden" id="form">
    @csrf
    <input type="text" class="hidden" name="detailid" id="detailid" value="" required>
    @if($view=='verifier')
    <input type="text" class="hidden" name="type" value="verifier" required>
    @elseif($view=='verifierrept')
    <input type="text" class="hidden" name="type" value="verifierrept" required>
    @elseif($view=='approver')
    <input type="text" class="hidden" name="type" value="approver" required>
    @elseif($view=='admin')
    <input type="text" class="hidden" name="type" value="admin" required>
    @elseif($view=='approverrept')
    <input type="text" class="hidden" name="type" value="approverrept" required>
    @endif
</form>
@stop

@section('js')
<script type="text/javascript">

    var otid;
    var today = new Date();
    var htmlstring = '<div style="border: 1px solid #DDDDDD; max-height: 60vh; overflow-y: scroll;  overflow-x: hidden;">';
    var no = 0
    var m = today.getMonth()+1;
    var y = today.getFullYear();
    var d = today.getDate().toString();
    if(m < 10){
        m = "0"+m;
    }
    while(d.length<2){
        d = "0"+d;
    }
    $("#search-date-1").attr("max", y+"-"+m+"-"+d);
    $("#search-date-2").attr("max", y+"-"+m+"-"+d);
    
    $(document).ready(function() {

        // var inputact = [];
        {{--@if($otlist ?? '')
            @foreach($otlist as $singleuser)
                inputact.push("");
            @endforeach
        @endif--}}
        // $('#inputact').val(inputact);

        var tot = $('#tOTList').DataTable({
            "responsive": "true",
            // "order" : [[1, "asc"]],
            "searching": false,
            "bSort": false,
            dom: '<"flext"lB>rtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ]
        });
        $('#pagenumber').val(tot.page.info().page + 1);

            $('#tOTList').on( 'page.dt', function () {
            var info = tot.page.info();
            $('#pagenumber').val(info.page + 1);
        } );

        // var ts = tot.rows().data();
        // alert(ts.length);
        // alert($('#inputact').val());
        // $('#inputact').val(inputact);
        // inputact
       // var info = tot.page.info();

        // var t = $('#time').DataTable({
        //     "responsive": "true",
        //     // "order" : [[1, "asc"]],
        //     "searching": false,
        //     "bSort": false
        // });

        // t.on( 'order.dt search.dt', function () {
        //     t.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //         cell.innerHTML = i+1;
        //     });
        // }).draw();
    });

    
    function yes(i){
        return function(){
            var id = $("#a-"+i).data('id');
            $("#detailid").val(id);
            // alert($("#inputid").val());
            $("#form").submit();
            return false;
        }
    }

    function checkstringx(i){
        $("#textremain-"+i).text(300-$("#inputremark-"+i).val().length);
        $('input[name="inputrem[]"').eq(i-1).val($('#inputremark-'+i).val());
    }

    function reset(i){
        $("#action-"+i).val("");
        $("#inputremark-"+i).prop('readonly',true);
        $("#inputremark-"+i).val("");
        $('input[name="inputact[]"').eq(i-1).val("");
        $('input[name="inputapp[]"').eq(i-1).val("");
        $('input[name="inputver[]"').eq(i-1).val("");
        $('input[name="inputrem[]"').eq(i-1).val("");
        $("#inputremark-"+i).prop('required',false);
        $("#inputremark-"+i).attr("placeholder", "");
        $("textremain-"+i).text("300");
        // alert($("#action-"+i).data("vid"));
        if($("#action-"+i).data("vid")==""){
            $('#show-verifier-na-'+i).removeClass("hidden");
            $('#show-verifier-a-'+i).addClass("hidden");
        }
        
        $("#approver-"+i).val($("#approver-cache-"+i).val());
        $("#show-approver-"+i).text($("#show-approver-cache-"+i).text());
        @if(($view=='approver') || ($view=='admin')) 
            $("#verifier-"+i).val($("#verifier-cache-"+i).val());
            $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
        @endif
        @if($otlist ?? '')
            table();
        @endif
    }

    // function remove(i){
    //         $("#verifier-"+i).val("");
    //         $("#show-verifier-na-"+i).text("N/A");
    //         $('#show-verifier-na-'+i).removeClass("hidden");
    //         $('#show-verifier-a-'+i).addClass("hidden");
    //         $('#action-'+i).val("Remove");
    //         $("#assign-"+i).prop('hidden',false);
    // }


    for(i=1; i<{{count($otlist)+1}}; i++){
        $("#a-"+i).on("click", yes(i));
    }

    function table(){
        
        @if($otlist ?? '')
        var aremark = false;
        for(x=1; x<{{count($otlist)}}+1; x++){
            if(($("#action-"+x).val()=="Q2")||($("#action-"+x).val()=="Assign")||($("#action-"+x).val()=="Change")){
                aremark = true;
            }
        }
        
        for(x=1; x<{{count($otlist)}}+1; x++){
            if(aremark){
                $("#aremark").css("display","table-cell");
                $("#aremark-"+x).css("display","table-cell");
                $("#borderman").removeClass("border-right");
                for(g=1; g<{{count($otlist)}}+1; g++){
                    $("#borderman-"+g).removeClass("border-right");
                }
            }else{
                $("#aremark").css("display","none");
                $("#aremark-"+x).css("display","none");
                $("#borderman").addClass("border-right");
                for(g=1; g<{{count($otlist)}}+1; g++){
                    $("#borderman-"+g).addClass("border-right");
                }
            }   
        }   
        @endif
    }

    var sendtype;
    function remark(i){
        return function(){
            
            $('input[name="inputact[]"').eq(i-1).val($('#action-'+i).val());
            @if($otlist ?? '')
                table();
            @endif
            otid = $("#action-"+i).data("otid");
            if($("#action-"+i).val()=="Q2"){
                // reset(i);
                Swal.fire({
                    title: 'Remarks',
                    html: "<p>Are you sure to query this claim application?</p>",
                    confirmButtonText:
                        'YES',
                        cancelButtonText: 'NO',
                    showCancelButton: true
                }).then((result) => {
                        if (result.value) {
                            $("#inputremark-"+i).attr("placeholder", "This is mandatory field. Please key in remarks here!");
                            $("#inputremark-"+i).prop('readonly',false);
                            $("#inputremark-"+i).prop('required',true);
                            $("#inputremark-"+i).val($('#remark').val());
                            $('input[name="inputrem[]"').eq(i-1).val($('#remark').val());
                            @if(($view=='approver')||($view=='admin'))
                                $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                                $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                            @endif
                            table();
                            
                        }else{
                            
                            
                            reset(i);
                        }
                })
            }else if($("#action-"+i).val()==""){
                
                // reset(i);
            }else if($("#action-"+i).val()=="Assign"){
                // alert($(this).find(':selected').data('type'));
                // reset(i);
                normal(i, 'none', 'Verifier');
            }else{
                if($("#action-"+i).val()=="A"){
                    sendtype = "approve"
                }else{
                    sendtype = "verify"
                }
                
                // reset(i);
                Swal.fire({
                    title: 'Terms and Conditions',
                    input: 'checkbox',
                    inputValue: 0,
                    inputPlaceholder:
                        "<p>By clicking on <span style='color: #143A8C'>\"Yes\"</span> button below, you are agreeing to the above related terms and conditions</p>",
                        html: "<p>I hereby "+sendtype+" that this claim is compliance with company's term and condition on <span style='font-weight: bold'>PERJANJIAN BERSAMA, HUMAN RESOURCE MANUAL, and BUSINESS PROCESS MANUAL</span> If deemed falsed, disciplinary can be imposed on me.</p>",
                        confirmButtonText:
                        'YES',
                        cancelButtonText: 'NO',
                    showCancelButton: true,
                    confirmButtonColor: '#EF7202',
                    cancelButtonColor: 'transparent',
                    inputValidator: (result) => {
                        return !result && 'You need to agree with T&C'
                    }
                }).then((result) => {
                    if (result.value) {
                        // whensubmit = false;
                        @if($singleuser ?? '')
                            @if($view=='verifier')$("#action-"+i).val("PA");
                            @elseif($view=='approver')$("#action-"+i).val("A");
                            @endif
                        @endif
                        $("#inputremark-"+i).prop('readonly',true);
                        $("#inputremark-"+i).val("");
                        $("#inputremark-"+i).prop('required',false);
                        @if($view=='approver')
                            $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                            $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                        @endif
                        // table();
                                // $('#remark-'+i).css("display", "table-row");
                    }else{
                        reset(i);
                    }
                })
            }
        };
    };

    function cleart(){
        $('#namet').val('');
        $('#namex').css('visibility','hidden');
    }

    function checkstring(){
        if(($('#namet').val().length)>3){
            $('#namex').css('visibility', 'visible');
            $('#3more').css('display', 'none');
            $('#margin').css('margin-left', '-20px');
        }else{
            $('#namex').css('visibility','hidden');
            $('#3more').css('display','block');
            $('#margin').css('margin-left','0');
        }
    }
    var number =  0;

    function normal(i, block, titles){
        Swal.fire({
            title: titles+"'s Name",
            html: "<div class='text-left'>"+
                    "<input id='namet' placeholder=\"Enter Employee's Name\" style='width: 100%; box-sizing: border-box;' onkeyup='this.onchange();' onchange='return checkstring();'>"+
                        "<button type='button' id='namex' onclick='return cleart()' style='visibility: hidden;display: inline; position: absolute; right: 30px; margin-top: 3px' class='btn-no'>"+
                            "<i class='far fa-times-circle'></i>"+
                        "</button>"+
                        "<button type='button' id='namex' onclick='return searcho("+i+","+titles+")' style='display: inline; position: absolute; right: 15px; margin-top: 5px' class='btn-no'>"+
                            "<i  class='fas fa-search'></i>"+
                        "</button>"+
                        "<p id='3more' style=' margin-top: 5px; color: #F00000; display: "+block+"'>Search input must be more than 3 alphabets!</p>"+
                        "<a href='#' onclick=\"advance("+i+",\'"+titles+"\'); \" style='color: #143A8C'><b><u>Advance Search</u></b></a>"+
                    "</div>",
            confirmButtonText:
                'NEXT',
            showCancelButton: false,
            inputValidator: (result) => {
                return !result && 'You need to agree with T&C'
            }
        }).then((result) => {
            if (result.value) {
                // {{--$("#inputremark-"+i).val($('#remark').val());
                // $("#inputremark-"+i).prop('readonly',true);
                // $("#inputremark-"+i).val("");
                // $("#inputremark-"+i).prop('required',false);
                // @if($view=='approver')
                //     $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                //     $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                // @endif
                // @if($view=='admin')
                //     if(titles=="Approver"){
                //         $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                //         $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                //     }
                // @endif--}}
                
                reset(i);
                return searcho(i, titles);
            }else{
                reset(i);
            }
        });
    }    

    function updateResp(item, index){
        htmlstring = htmlstring + 
            "<button style='border: 1px solid #DDDDDD; min-height: 10vh; width: 100%; padding: 5px; text-align: left; background: transparent' onclick='addverifier(\""+item.persnoo+"\","+index+",\""+item.name+"\");' id='addv-"+index+"'>"+
                "<div style='display: flex; align-items: center; flex-wrap: wrap; width: 95%; margin-left: 3%' padding: 15px>"+
                    "<div class='w-10 text-center'><img src='/user/image/"+item.staffno.replace(' ','')+"' class='approval-search-img'></div>"+
                    "<div class='w-30 m-15'>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Name<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.name+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Personnel No<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'><span class='dm'>: </span></span><b>"+item.persno+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Staff No<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.staffno+"</b></div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='w-30 m-15'>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Company Code<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.companycode+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Cost Center<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.costcenter+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Personnel Area<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.persarea+"</b></div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='w-30 m-15'>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Employee Subgroup<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.empsubgroup+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Email<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.email+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Mobile No<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.mobile+"</b></div>"+
                        "</div>"+
                    "</div>"+
                "</div>"+
            "</button>";
            
    }

    function updateRespA(item, index){
        htmlstring = htmlstring + 
            "<button style='border: 1px solid #DDDDDD; min-height: 10vh; width: 100%; padding: 5px; text-align: left; background: transparent' onclick='addapprover(\""+item.persnoo+"\","+index+",\""+item.name+"\");' id='addv-"+index+"'>"+
                "<div style='display: flex; align-items: center; flex-wrap: wrap; width: 95%; margin-left: 3%' padding: 15px>"+
                    "<div class='w-10 text-center'><img src='/user/image/"+item.staffno.replace(' ','')+"' class='approval-search-img'></div>"+
                    "<div class='w-30 m-15'>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Name<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.name+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Personnel No<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'><span class='dm'>: </span></span><b>"+item.persno+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Staff No<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.staffno+"</b></div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='w-30 m-15'>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Company Code<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.companycode+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Cost Center<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.costcenter+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Personnel Area<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.persarea+"</b></div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='w-30 m-15'>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Employee Subgroup<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.empsubgroup+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Email<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.email+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Mobile No<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.mobile+"</b></div>"+
                        "</div>"+
                    "</div>"+
                "</div>"+
            "</button>";
            
    }

    function addverifier(id, num, name){
        $('#verifier-'+no).val(id);
        $('input[name="inputver[]"').eq(no-1).val($('#verifier-'+no).val());
        $('input[name="inputact[]"').eq(no-1).val("Assign");
        $('#show-verifier-'+no).text(name);
        $('#show-verifier-na-'+no).addClass("hidden");
        $('#show-verifier-a-'+no).removeClass("hidden");
        $('#show-verifier-a-'+no).data("id", id);
        for(i = 0; i<number; i++){
            if(i!=num){
                $('#addv-'+i).css('outline','none');
                $('#addv-'+i).css('border','1px solid #DDDDDD');
            }else{
                $('#addv-'+i).css('outline','1px solid #143A8C');
                $('#addv-'+i).css('border','2px solid #143A8C');
            }
        }
    }

    function addapprover(id, num, name){
        console.log(no +" "+ num);
        $('#approver-'+no).val(id);
        $('input[name="inputapp[]"').eq(no-1).val($('#approver-'+no).val());
        $('input[name="inputact[]"').eq(no-1).val("Change");
        $('#show-approver-'+no).text(name);
        $('#show-approver-a-'+no).data("id", id);
        for(i = 0; i<number; i++){
            if(i!=num){
                $('#addv-'+i).css('outline','none');
                $('#addv-'+i).css('border','1px solid #DDDDDD');
            }else{
                $('#addv-'+i).css('outline','1px solid #143A8C');
                $('#addv-'+i).css('border','2px solid #143A8C');
            }
        }
    }
    
    function search(searchn, searchpn, searchsn, searchp, searchcc, searchct, searchpa, searchpsa, searchesg, searche, searchmn, searchon, type, block, i, titles){
        // alert(searchpn);
        const url='{{ route("ot.search", [], false)}}';
        no = i;
        htmlstring = '<div style="border: 1px solid #DDDDDD; max-height: 60vh; overflow-y: scroll;  overflow-x: hidden;">';
        $.ajax({
            type: "GET",
            url: url+"?name="+searchn+"&persno="+searchpn+"&staffno="+searchsn+"&position="+searchp+"&company="+searchcc+"&cost="+searchct+"&persarea="+searchpa+"&perssarea="+searchpsa+"&empsgroup"+searchesg+"&email="+searche+"&mobile="+searchmn+"&office="+searchon+"&type="+type+"&otid="+otid,
            success: function(resp) {
                if(resp.length>0){
                    number = resp.length;
                    if(titles == "Verifier"){
                        resp.forEach(updateResp);
                    }else{
                        resp.forEach(updateRespA);
                    }
                    cfm = 'SELECT';
                    yes = true;
                }
                else{
                    htmlstring = "<div style=' width: 100%; padding: 5px; text-align: center; vertical-align: middle'>"+
                                    "<p>No matching records found. Try to search again.</p>"+
                                    "</div>";
                                    
                    cfm = 'NEXT';
                    yes = false;
                }
                titlex =  titles.toLowerCase();
                Swal.fire({
                    title: titles+"'s Name",
                    customClass: 'test2',
                    // width: '75%',
                    html: "<div class='text-left swollo'>"+
                            "<input id='namet' placeholder=\"Enter Employee's Name\" style='width: 100%; box-sizing: border-box;' onkeyup='this.onchange();' onchange='return checkstring();'>"+
                            "<button type='button' id='namex' onclick='return cleart()' class='approval-search-x btn-no'>"+
                                "<i class='far fa-times-circle'></i>"+
                            "</button>"+
                            "<button type='button' id='namex' onclick='return searcho("+i+","+titles+")' class='approval-search-icon btn-no'>"+
                                "<i class='fas fa-search'></i>"+
                            "</button>"+
                            "<p id='3more' style=' margin-top: 5px; color: #F00000; display: none'>Search input must be more than 3 alphabets!</p>"+
                            "<a id='margin' href='#' onclick=\"advance("+i+",\'"+titles+"\'); \" style='margin-left: -20px; color: #143A8C'>"+
                                "<b><u>Advance Search</u></b>"+
                            "</a>"+
                        "</div>"+
                        "<p style=' margin-top: 5px; color: #F00000; display: "+block+"'>Please select "+titlex+"!</p>"+
                        "<div class='text-left'>"+htmlstring+"</div>",
                    confirmButtonText:
                        cfm,
                    showCancelButton: yes,
                    cancelButtonText: 'CANCEL',
                }).then((result) => {
                    if(yes){
                        if (result.value) {   
                            succeed = false;
                            if(titles=="Verifier"){  
                                // alert($('#verifier-'+i).val()+" "+$('#verifier-cache-'+i).val())
                                if($('#verifier-'+i).val()!=$('#verifier-cache-'+i).val()){
                                    succeed = true;
                                }
                            } else{  
                                if($('#approver-'+i).val()!=$('#approver-cache-'+i).val()){
                                    succeed = true;
                                }
                                // console.log(($('#approver-'+i).val()+" "+$('#approver-cache-'+i).val()));
                            }   
                            // alert(succeed);
                            if(succeed){                               
                                if(titles=="Verifier"){         
                                    $("#action-"+i).val("Assign");
                                    $('input[name="inputver[]"').eq(i-1).val($('#verifier-'+i).val());

                                }else{
                                    $("#action-"+i).val("Change");
                                    $('input[name="inputapp[]"').eq(i-1).val($('#approver-'+i).val());

                                }
                                table();
                                $('#remark-'+i).css("display", "table-row");
                                // Swal.fire({
                                //         title: 'Remarks',
                                //         html: "<textarea id='remark' rows='4' style='width: 90%' placeholder='This is mandatory field. Please key in remarks here!' style='resize: none;'></textarea><p>Are you sure to assign new verifier to this claim application?</p>",
                                //         confirmButtonText:
                                //             'YES',
                                //             cancelButtonText: 'NO',
                                //         showCancelButton: true,
                                //         inputValidator: (result) => {
                                //             return !result && 'You need to agree with T&C'
                                //         }
                                //     }).then((result) => {
                                //             if (result.value) {
                                                                        
                                                $("#inputremark-"+i).attr("placeholder", "This is mandatory field. Please key in remarks here!");
                                                $("#inputremark-"+i).prop('readonly',false);
                                                $("#inputremark-"+i).prop('required',true);
                                                // $("#inputremark-"+i).val($('#remark').val());  
                                                // $("#inputremark-"+i).val($('#remark').val());
                                                    // if(yes){
                                                    //     if($('#verifier').val()!=''){
                                                    //         $('#formverifier').submit();
                                                    //     }else{
                                                    //         search(searchn, 'block', i);
                                                    //     }
                                                    // }else{
                                                    //     return searcho(i);
                                                    // }
                                    //         }else{
                                                
                                                
                                    //             reset(i);
                                    //         }
                                    // })
                            }else{
                                search(searchn, searchpn, searchsn, searchp, searchcc, searchct, searchpa, searchpsa, searchesg, searche, searchmn, searchon, type, "block", i, titles);
                            }
                        }else{
                            reset(i);
                        }
                    }else{
                        normal(i, 'none', titles);
                    }
                });
                
            }
        });   
        
    }

    // advance(i);

    function advance(i, titles){
        var checksend = true;
        Swal.fire({
            title: "Advance Search",
            customClass: "test3",
            html: 
            "<div class='text-left'>"+
                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Name of Employee</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                                "<input type='text' id='sname' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+
                        
                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Staff Number</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                                "<input type='text' id='sstaffno' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+

                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Personnel Number</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                                "<input type='text' id='spersno' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+

                        // "<div class='row'>"+
                        //     "<div class='col-md-3'>"+
                        //         "<p><b>Email</b></p>"+
                        //     "</div>"+
                        //     "<div class='col-md-9'>"+
                        //     "<input type='text' id='semail' style='width: 100%; box-sizing: border-box;'>"+
                        //     "</div>"+
                        // "</div>"+
                        
                    "</div>",
            confirmButtonText:
                'SEARCH',
            showCancelButton: true,
            cancelButtonText:
                'CANCEL',
        }).then((result) => {
            if (result.value) {
                if(($("#sname").val()=="")&&($("#spersno").val()=="")&&($("#sstaffno").val()=="")&&($("#position").val()=="")&&($("#scompc").val()=="")&&($("#scostc").val()=="")&&($("#spersarea").val()=="")&&($("#sperssarea").val()=="")&&($("#sempsg").val()=="")&&($("#semail").val()=="")&&($("#smobile").val()=="")&&($("#soffice").val()=="")){
                    checksend = false;
                }
                if(checksend){
                    search($('#sname').val(), $('#spersno').val(), $('#sstaffno').val(), $('#position').val(), $('#scompc').val(), $('#scostc').val(), $('#spersarea').val(), $('#sperssarea').val(), $('#sempsg').val(), $('#semail').val(), $('#smobile').val(), $('#soffice').val(), 'advance', 'none', i, titles);
                }else{
                    advance(i, titles);
                }
            }else{
                
                reset(i); 
            }
        });
        
        return false;
    }

    function searcho(i, titles){
        htmlstring = '<div style="border: 1px solid #DDDDDD; max-height: 60vh; overflow-y: scroll;  overflow-x: hidden;">';
        if(($('#namet').val().length)<3){
                    normal(i, 'block', titles);
        }else{
            search($('#namet').val(), '', '', '', '', '', '', '', '', '', '', '', 'normal', 'none', i, titles);
        }
    }

    function showverifier(id){
        
        otid = $("#show-verifier-a-"+id).data("otid");
        const url='{{ route("ot.getverifier", [], false)}}';
        userid = $("#show-verifier-a-"+id).data("id");
        $.ajax({
            type: "GET",
            url: url+"?id="+userid,
            success: function(resp) {
                Swal.fire({
                    title: "Current Assigned Verifier",
                    customClass: 'test2',
                    // width: '75%',
                    html: "<div style='border: 1px solid #DDDDDD; min-height: 10vh; width: 100%; padding: 5px; text-align: left;'>"+
                            "<div style='display: flex; align-items: center; flex-wrap: wrap; width: 95%; margin-left: 3%' padding: 15px>"+
                                "<div class='w-10 text-center'><img src='/user/image/"+resp.staffno.replace(' ','')+"' class='approval-search-img'></div>"+
                                "<div class='w-30 m-15'>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Name<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.name+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Personnel No<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'><span class='dm'>: </span></span><b>"+resp.persno+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Staff No<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.staffno+"</b></div>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='w-30 m-15'>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Company Code<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.companycode+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Cost Center<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.costcenter+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Personnel Area<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.persarea+"</b></div>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='w-30 m-15'>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Employee Subgroup<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.empsubgroup+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Email<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.email+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Mobile No<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.mobile+"</b></div>"+
                                    "</div>"+
                                "</div>"+
                            "</div>"+
                        "</div>",
                    confirmButtonText: 'CHANGE VERIFIER',
                    // showCancelButton: yes,
                    // cancelButtonText: 'CHANGE VERIFIER',
                }).then((result) => {
                    // if (result.value) {
                    //     remove(id);
                    // }else if (result.dismiss === Swal.DismissReason.cancel){
                    //     normal(id, 'none');
                    // }
                    if (result.value) {
                        normal(id, 'none', 'Verifier');
                    }
                });
            }
        });   
    }

    function showapprover(id){
        
        otid = $("#show-approver-a-"+id).data("otid");
        // alert(otid);
        const url='{{ route("ot.getverifier", [], false)}}';
        userid = $("#show-approver-a-"+id).data("id");
        $.ajax({
            type: "GET",
            url: url+"?id="+userid,
            success: function(resp) {
                Swal.fire({
                    title: "Current Approver",
                    customClass: 'test2',
                    // width: '75%',
                    html: "<div style='border: 1px solid #DDDDDD; min-height: 10vh; width: 100%; padding: 5px; text-align: left;'>"+
                            "<div style='display: flex; align-items: center; flex-wrap: wrap; width: 95%; margin-left: 3%' padding: 15px>"+
                                "<div class='w-10 text-center'><img src='/user/image/"+resp.staffno.replace(' ','')+"' class='approval-search-img'></div>"+
                                "<div class='w-30 m-15'>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Name<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.name+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Personnel No<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'><span class='dm'>: </span></span><b>"+resp.persno+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Staff No<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.staffno+"</b></div>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='w-30 m-15'>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Company Code<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.companycode+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Cost Center<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.costcenter+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Personnel Area<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.persarea+"</b></div>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='w-30 m-15'>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Employee Subgroup<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.empsubgroup+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Email<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.email+"</b></div>"+
                                    "</div>"+
                                    "<div class='approval-search-item'>"+
                                        "<div class='w-30'>Mobile No<span class='dmx'>:</span></div>"+
                                        "<div class='w-70'><span class='dm'>: </span><b>"+resp.mobile+"</b></div>"+
                                    "</div>"+
                                "</div>"+
                            "</div>"+
                        "</div>",
                    confirmButtonText: 'CHANGE APPROVER',
                    // showCancelButton: yes,
                    // cancelButtonText: 'CHANGE VERIFIER',
                }).then((result) => {
                    // if (result.value) {
                    //     remove(id);
                    // }else if (result.dismiss === Swal.DismissReason.cancel){
                    //     normal(id, 'none');
                    // }
                    if (result.value) {
                        normal(id, 'none', 'Approver');
                    }
                });
            }
        });   
    }

    function remark2(i){
        return function(){
            // alert("s");
            if(($("#action-"+i).val()=="Q2")||($("#action-"+i).val()=="Assign")){
                if($("#action-"+i).val()=="Q2"){
                    tx = "Are you sure to query this claim application?";
                }else{
                    
                    tx = "Are you sure to assign new verifier to this claim application?";
                }
                var str = $("#inputremark-"+i).val();
                Swal.fire({
                    title: 'Remarks',
                    html: "<textarea id='remark' rows='4' style='width: 90%' placeholder='This is mandatory field. Please key in remarks here!' style='resize: none;'>"+str+"</textarea><p>"+tx+"</p>",
                    confirmButtonText:
                        'YES',
                        cancelButtonText: 'NO',
                    showCancelButton: true,
                    inputValidator: (result) => {
                        return !result && 'You need to agree with T&C'
                    }
                }).then((result) => {
                        if (result.value) {
                            
                            $("#inputremark-"+i).prop('readonly',false);
                            $("#inputremark-"+i).prop('required',true);
                            $("#inputremark-"+i).val($('#remark').val());
                            if($("#action-"+i).val()=="Q2"){
                                @if(($view=='approver')||($view=='admin'))
                                    $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                                    $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                                @endif
                            }
                        }else{
                            
                            // if($("#action-"+i).val()=="Q2"){
                            //     reset(i);   
                            // }
                            $("#inputremark-"+i).blur();
                        }
                })
            }
        }
    }

    for (i=1; i<{{count($otlist)+1}}; i++) {
        $("#action-"+i).change(remark(i));
        // $("#inputremark-"+i).on("click",remark2(i));
    }

    for (i=1; i<7; i++) {
        if(i==5){
            
            $("#search-"+i).on('click', roleparam(i));
        }else if(i==6){
            $("#search-"+i).on('click', searchparam(i, "date"));
        }else{
            $("#search-"+i).on('click', searchparam(i, "text"));
        }
    }

    // $("#searchcomp").on('click', function(){
    //     searchparam('Company Code');
    // });
    var html;
    var text; 
    var type;
    function searchparam(i, types){
        return function(){
            type = types;
            $("#search-"+i).blur();
            makeview(i, "");    
           
        }
    }

    function makeview(i, operation){
    
        var value = "";
        if($("#search-"+i).val()!=""){
            value = $("#search-"+i).val().split(', ');
        }
        text = $("#search-"+i).data("text");
        html = "<div class='row text-left'><div class='col-md-3'>"+text+"</div>";
        if(operation!="add"){
            if(value.length>0){
                if(operation!="remove"){
                    for(v=0; v<(value.length)+1; v++){
                        if(v==0){
                            html = html + "<div class='col-md-9'>"+
                                            "<input id='value-"+v+"' class='countsearch' type='"+type+"' style='width: 90%' value='"+value[v]+"'>"+
                                            "<span style='width: 10%'>"+
                                                "<button onclick='removesearch("+i+","+v+")' type='button' class='btn btn-times' style='display: inline'><i class='fas fa-times-circle'></i></button>"+
                                            "</span>"+
                                        "</div>";
                        }else if(v<value.length){
                            html = html + "<div class='col-md-9 col-md-offset-3'>"+
                                            "<input id='value-"+v+"' class='countsearch' type='"+type+"' style='width: 90%' value='"+value[v]+"'>"+
                                            "<span style='width: 10%'>"+
                                                "<button onclick='removesearch("+i+","+v+")' type='button' class='btn btn-times' style='display: inline'><i class='fas fa-times-circle'></i></button>"+
                                            "</span>"+
                                        "</div>";
                        }else{
                            html = html + "<div class='col-md-9 col-md-offset-3'>"+
                                            "<input id='value-"+v+"' class='countsearch' type='"+type+"' style='width: 90%'>"+
                                            "<span style='width: 10%'>"+
                                                "<button onclick='return addsearch("+i+")' type='button' class='btn btn-plus' style='display: inline'><i class='fas fa-plus-circle'></i></button>"+
                                            "</span>"+
                                        "</div>"; 
                        }
                    }
                }else{
                    for(v=0; v<value.length; v++){
                        if(v==0){
                            html = html + "<div class='col-md-9'>"+
                                            "<input id='value-"+v+"' class='countsearch' type='"+type+"' style='width: 90%' value='"+value[v]+"'>"+
                                            "<span style='width: 10%'>"+
                                                "<button onclick='removesearch("+i+","+v+")' type='button' class='btn btn-times' style='display: inline'><i class='fas fa-times-circle'></i></button>"+
                                            "</span>"+
                                        "</div>";
                        }else if(v==value.length-1){
                            html = html + "<div class='col-md-9 col-md-offset-3'>"+
                                            "<input id='value-"+v+"' class='countsearch' type='"+type+"' style='width: 90%' value='"+value[v]+"'>"+
                                            "<span style='width: 10%'>"+
                                                "<button onclick='return addsearch("+i+")' type='button' class='btn btn-plus' style='display: inline'><i class='fas fa-plus-circle'></i></button>"+
                                            "</span>"+
                                        "</div>"; 
                        }else if(v<value.length){
                            html = html + "<div class='col-md-9 col-md-offset-3'>"+
                                            "<input id='value-"+v+"' class='countsearch' type='"+type+"' style='width: 90%' value='"+value[v]+"'>"+
                                            "<span style='width: 10%'>"+
                                                "<button onclick='removesearch("+i+","+v+")' type='button' class='btn btn-times' style='display: inline'><i class='fas fa-times-circle'></i></button>"+
                                            "</span>"+
                                        "</div>";
                        }
                    }
                }
            }else{
                html = html + "<div class='col-md-9'>"+
                                    "<input id='value-0' class='countsearch' type='"+type+"' style='width: 90%'>"+
                                    "<span style='width: 10%'>"+
                                        "<button onclick='return addsearch("+i+")' type='button' class='btn btn-plus' style='display: inline'><i class='fas fa-plus-circle'></i></button>"+
                                    "</span>"+
                                "</div>";
            }
        }else{
            html = html + "<div class='col-md-9'>"+
                                "<input id='value-0' class='countsearch' type='"+type+"' style='width: 90%'>"+
                                "<span style='width: 10%'>"+
                                    "<button onclick='removesearch("+i+",0)' type='button' class='btn btn-times' style='display: inline'><i class='fas fa-times-circle'></i></button>"+
                                "</span>"+
                            "</div>"+ 
                            "<div class='col-md-9 col-md-offset-3'>"+
                                "<input id='value-1' class='countsearch' type='"+type+"' style='width: 90%'>"+
                                "<span style='width: 10%'>"+
                                    "<button onclick='return addsearch("+i+")' type='button' class='btn btn-plus' style='display: inline'><i class='fas fa-plus-circle'></i></button>"+
                                "</span>"+
                            "</div>"; 
        }
        searchalert(i);        
    }

    function addsearch(i){
        $("#search-"+i).val("");
        for(n=0; n<$(".countsearch").length; n++){
            if(n==0){
                $("#search-"+i).val($("#value-"+n).val()); 
            }else{
                $("#search-"+i).val( $("#search-"+i).val()+", "+$("#value-"+n).val());
            }
        }
        if(($(".countsearch").length==1)&&($("#value-0").val()=="")){
            makeview(i, "add");
        }else{
            makeview(i, "");

        }
    }

    function removesearch(i, v){
        $("#search-"+i).val("");
        for(n=0; n<$(".countsearch").length; n++){
            if(n!=v){
                // if($("#value-"+n).val()!=""){
                    if(n==0){
                        $("#search-"+i).val($("#value-"+n).val()); 
                    }else if((n==1)&&(v==0)){
                        $("#search-"+i).val($("#value-"+n).val());
                    }
                    else{
                        $("#search-"+i).val( $("#search-"+i).val()+", "+$("#value-"+n).val());
                    }
                // }
            }
        }
        makeview(i, "remove");
    }
    
    function searchalert(i){
        Swal.fire({
            title: 'Multiple Search Parameter',
            html: html+"</div>",
            customClass: 'test4',
            confirmButtonText:
                'SELECT',
                cancelButtonText: 'CANCEL',
            showCancelButton: true,
        }).then((result) => {
            if (result.value) {
                
                $("#search-"+i).val("");
                for(n=0; n<$(".countsearch").length; n++){
                    if($("#value-"+n).val()!=""){
                        if(n==0){
                            $("#search-"+i).val($("#value-"+n).val()); 
                        }else{
                            $("#search-"+i).val( $("#search-"+i).val()+", "+$("#value-"+n).val());
                        }
                    }
                }
            }else{
                $("#search-"+i).val("");
            }
        })
    }

    $("#search-date-1").on("change", function(){
        if($("#search-date-2").val()!=""){
            st = $("#search-date-1").val().split('-');
            et =$("#search-date-2").val().split('-');
            if((st[0]*365)+(st[1]*30)+st[2]>(et[0]*365)+(et[1]*30)+et[2]){
                $("#search-date-1").val("");
                $("#search-date-2").val("");
                Swal.fire({
                        icon: 'error',
                        title: 'Date Error',
                text: "Start date cannot be after end date!",
                confirmButtonText:'OK'
                })
            }
        }
    })

    $("#search-date-2").on("change", function(){
        if($("#search-date-1").val()!=""){
            st = $("#search-date-1").val().split('-');
            et =$("#search-date-2").val().split('-');
            if((st[0]*365)+(st[1]*30)+st[2]>(et[0]*365)+(et[1]*30)+et[2]){
                $("#search-date-1").val("");
                $("#search-date-2").val("");
                Swal.fire({
                        icon: 'error',
                        title: 'Date Error',
                text: "End date cannot be before start date!",
                confirmButtonText:'OK'
                })
            }
        }
    })

    function roleparam(i){
        return function(){
            var value = "";
            if($("#search-"+i).val()!=""){
                value = $("#search-"+i).val().split(', ');
            }
            html = "<div class='row text-left'><div class='col-md-3'>Claim Status</div>";
            pv = "";
            pa = "";
            // a = "";
            // d = "";
            // q = "";
            for(v=0; v<(value.length)+1; v++){
                if(value[v]=="Pending Verification"){
                    pv = "checked";
                }else if(value[v]=="Pending Approval"){
                    pa = "checked";}
                // }else if(value[v]=="Approved"){
                //     a = "checked";}
                // }else if(value[v]=="Draft"){
                //     d = "checked";
                // }else if(value[v]=="Query"){
                //     q = "checked";
                // }
            }
            html = html + "<div class='col-md-9'>"+
                            //     "<input id='value-0' class='countsearch' type='checkbox'value='Draft' "+d+"> Draft"+
                            // "</div>"+
                            // "<div class='col-md-9 col-md-offset-3'>"+
                            //     "<input id='value-1' class='countsearch' type='checkbox' value='Query' "+q+"> Query"+
                            // "</div>"+
                            // "<div class='col-md-9 col-md-offset-3'>"+
                                "<input id='value-1' class='countsearch' type='checkbox' value='Pending Verification' "+pv+"> Pending Verification"+
                            "</div>"+
                            "<div class='col-md-9 col-md-offset-3'>"+
                                "<input id='value-2' class='countsearch' type='checkbox' value='Pending Approval' "+pa+"> Pending Approval"+
                            "</div>";
                            // "<div class='col-md-9 col-md-offset-3'>"+
                            //     "<input id='value-3' class='countsearch' type='checkbox' value='Approved' "+a+"> Approved"+
                            // "</div>";
            Swal.fire({
                title: 'Multiple Search Parameter',
                html: html+"</div>",
                customClass: 'test4',
                confirmButtonText:
                    'SELECT',
                    cancelButtonText: 'CANCEL',
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    
                    $("#search-"+i).val("");
                    for(n=0; n<$(".countsearch").length + 1; n++){
                        if($("#value-"+n).is(":checked")){
                            if($("#search-"+i).val()==""){
                                // alert( $("#value-"+n).val());
                                $("#search-"+i).val($("#value-"+n).val()); 
                            }else{
                                $("#search-"+i).val( $("#search-"+i).val()+", "+$("#value-"+n).val());
                            }
                        }
                    }
                }
            })
        }
    }

    function submitsearch(){
        var status = false;
        for(n=1; n<$(".searchman").length+1; n++){
            if($(".searchman-"+n).val()!=""){
                status = true;
            }
        }
        if(($("#search-date-1").val()!="")&&($("#search-date-2").val()!="")){
            status = true;
        }
        if(!(status)){
            Swal.fire({
                
                icon: 'error',
                    title: 'Search Error',
            text: "Please input at least 1 search parameter!",
            confirmButtonText:'OK'
            })
            return false;
        }        
    }

    function submits(){
        // $('input[name="inputact[]"').eq(2).val("A");
            // alert($('#action-3').val());
            // return false;
        Swal.fire({
            title: 'Submitting form',
            html: 'Please wait while we process your submission. DO NOT RELOAD/CLOSE THIS TAB!',
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            showCancelButton: false,
            customClass: "load",
            onBeforeOpen: () => {
            Swal.showLoading()}
        })
        // return false;
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