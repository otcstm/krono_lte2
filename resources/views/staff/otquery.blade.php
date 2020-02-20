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
@endif
<div class="panel panel-main panel-default">
    <div class="panel-body">
        
        @if(count($otlist)!=0)
        <form action="{{route('ot.query')}}" method="POST" style="display:inline"> 
            @csrf    
            @if($view=='verifier')
            <input type="text" class="hidden" name="typef" value="verifier" required>
            @elseif($view=='approver')
            <input type="text" class="hidden" name="typef" value="approver" required>
            @endif
            <div class="table-responsive">
                <table id="tOTList" class="table table-bordered">
                    <thead style="background: grey">
                        <tr>
                            <th>No</th>
                            <th>Reference No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Start OT</th>
                            <th>End OT</th>
                            <th>Total Hours/Minutes</th>
                            <th>Charge Code</th>
                            <th>Location</th>
                            <th>Amount (Estimated)</th>
                            <th>Status</th>
                            @if(($view=='verifier')||($view=='approver'))
                                @if($view=='approver')
                                <th>Verifier</th>
                                @endif
                            <th>Action</th>
                            <th>Action Remark</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($otlist as $no=>$singleuser)
                        <tr>
                            <input type="text" class="form-control hidden" name="inputid[]" value="{{$singleuser->id}}" required>
                            <td>{{++$no}}</td>
                            <td>
                                <a href="" id="a-{{$no}}" style="font-weight: bold; color: #143A8C" data-id="{{$singleuser->id}}">{{ $singleuser->refno }}</a>
                            </td>
                            <td>{{ $singleuser->name->name }}</td>
                            <td>{{ date("d.m.Y", strtotime($singleuser->date)) }}</td>
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
                            <td>{{ $singleuser->total_hour }}h/{{ $singleuser->total_minute }}m</td>
                            <td>{{$singleuser->charge_type}}</td>
                            <td>@foreach($singleuser->detail as $details){{$details->in_latitude}} {{$details->in_longitude}}<br>@endforeach</td> 
                            <td>RM{{$singleuser->amount}}</td>
                            <td>
                                @if($singleuser->status=="PA")
                                    <p>Pending Approval</p>
                                @elseif($singleuser->status=="PV")
                                    <p>Pending Verification</p>
                                @endif
                            </td>
                            
                            @if(($view=='verifier')||($view=='approver'))
                                @if($view=='approver')
                                    <td>
                                        <span class='hidden' id="show-verifier-cache-{{$no}}">{{$singleuser->verifier->name}}</span>
                                        
                                        <span id="show-verifier-na-{{$no}}" @if($singleuser->verifier->name!="N/A") class="hidden" @endif >{{$singleuser->verifier->name}}</span>
                                        
                                        <a id="show-verifier-a-{{$no}}" href="#" style="font-weight: bold; color: #143A8C" @if($singleuser->verifier->name=="N/A") class="hidden" @endif onclick="showverifier({{$no}})" data-id="{{$singleuser->verifier_id}}"><span id="show-verifier-{{$no}}">{{$singleuser->verifier->name}}</span></a>
                                        
                                    </td>
                                @endif
                            <td>
                                <input type="text" class="hidden"  id="verifier-cache-{{$no}}" value="{{$singleuser->verifier_id}}">
                                <input type="text" class="hidden"  id="verifier-{{$no}}" name="verifier[]" value="{{$singleuser->verifier_id}}">
                                <select name="inputaction[]" id="action-{{$no}}">
                                    <option selected value="">Select Action</option>
                                    <option hidden value="Remove">Remove Verifier</option>
                                    <!-- <option hidden disabled selected value="">Select Action</option> -->
                                    @if($view=="verifier")<option value="PA">Verify</option>
                                    @elseif($view=='approver')
                                        <option value="A">Approve</option>
                                        <option @if($singleuser->verifier_id!=null) hidden @endif  value="Assign" id="assign-{{$no}}">Assign Verifier</option>
                                        
                                    @endif
                                    <option value="Q2">Query</option>
                                </select>
                            </td>
                            <td>
                                <textarea rows = "1" cols="40" type="text"  id="inputremark-{{$no}}" name="inputremark[]" value="" placeholder="" style="resize: none; display: inline" disabled></textarea>
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
            
            @if(($view=='verifier')||($view=='approver'))
            <div id="submitbtn" class="text-center" style="margin: 5vh 0 20vh;" onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')">
                <button type="submit" class="btn btn-primary btn-p">SUBMIT</button>
            </div>
            
            @endif
        </form>
        @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Reference No</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Start OT</th>
                        <th>End OT</th>
                        <th>Total Hours/Minutes</th>
                        <th>Charge Code</th>
                        <th>Location</th>
                        <th>Amount (Estimated)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="11"><div class="text-center"><i>Not available</i></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        
        <form action="{{route('ot.detail')}}" method="POST" class="hidden" id="form">
            @csrf
            <input type="text" class="hidden" name="detailid" id="detailid" value="" required>
            @if($view=='verifier')
            <input type="text" class="hidden" name="type" value="verifier" required>
            @elseif($view=='verifierrept')
            <input type="text" class="hidden" name="type" value="verifierrept" required>
            @elseif($view=='approver')
            <input type="text" class="hidden" name="type" value="approver" required>
            @elseif($view=='approverrept')
            <input type="text" class="hidden" name="type" value="approverrept" required>
            @endif
        </form>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">

    var htmlstring = '<div style="border: 1px solid #DDDDDD; max-height: 60vh; overflow-y: scroll;  overflow-x: hidden;">';
    var no = 0

    $(document).ready(function() {
        $('#tOTList').DataTable({
            "responsive": "true",
            // "order" : [[1, "asc"]],
            "searching": false,
            "bSort": false
        });

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

    function reset(i){
        $("#action-"+i).val("");
        $("#inputremark-"+i).prop('disabled',true);
        $("#inputremark-"+i).val("");
        $("#inputremark-"+i).prop('required',false);
        @if($view=='approver')
            $("#verifier-"+i).val($("#verifier-cache-"+i).val());
            $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
        @endif
    }

    function remove(i){
            $("#verifier-"+i).val("");
            $("#show-verifier-na-"+i).text("N/A");
            $('#show-verifier-na-'+i).removeClass("hidden");
            $('#show-verifier-a-'+i).addClass("hidden");
            $('#action-'+i).val("Remove");
            $("#assign-"+i).prop('hidden',false);
    }


    for(i=1; i<{{count($otlist)+1}}; i++){
        $("#a-"+i).on("click", yes(i));
    }

    function remark(i){
        return function(){
            if($("#action-"+i).val()=="Q2"){
                $('#remark-'+i).css("display", "table-row");
                Swal.fire({
                    title: 'Remarks',
                    html: "<textarea id='remark' rows='4' cols='50' placeholder='This is mandatory field. Please key in remarks here!' style='resize: none;'></textarea><p>Are you sure to query this claim application?</p>",
                    confirmButtonText:
                        'YES',
                        cancelButtonText: 'NO',
                    showCancelButton: true,
                    inputValidator: (result) => {
                        return !result && 'You need to agree with T&C'
                    }
                }).then((result) => {
                        if (result.value) {
                            
                            $("#inputremark-"+i).prop('disabled',false);
                            $("#inputremark-"+i).prop('required',true);
                            $("#inputremark-"+i).val($('#remark').val());
                            @if($view=='approver')
                                $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                                $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                            @endif
                            
                        }else{
                            
                            
                            reset(i);
                        }
                })
            }else if($("#action-"+i).val()==""){
                reset(i);
            }else if($("#action-"+i).val()=="Assign"){
                normal(i, 'none');
            }else{
                Swal.fire({
                    title: 'Terms and Conditions',
                    input: 'checkbox',
                    inputValue: 0,
                    inputPlaceholder:
                        "<p>By clicking on <span style='color: #143A8C'>\"Yes\"</span> button below, you are agreeing to the above related terms and conditions</p>",
                        html: "<p>I hereby certify that my claim is compliance with company's term and condition on <span style='font-weight: bold'>PERJANJIAN BERSAMA, HUMAN RESOURCE MANUAL, and BUSINESS PROCESS MANUAL</span> If deemed falsed, disciplinary can be imposed on me.</p>",
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
                        $("#inputremark-"+i).prop('disabled',true);
                        $("#inputremark-"+i).val("");
                        $("#inputremark-"+i).prop('required',false);
                        @if($view=='approver')
                            $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                            $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                        @endif
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

    function normal(i, block){
        Swal.fire({
            title: "Verifier's Name",
            html: "<div class='text-left'>"+
                    "<input id='namet' placeholder=\"Enter Employee's Name\" style='width: 100%; box-sizing: border-box;' onkeyup='this.onchange();' onchange='return checkstring();'>"+
                        "<button type='button' id='namex' onclick='return cleart()' style='visibility: hidden;display: inline; position: absolute; right: 30px; margin-top: 3px' class='btn-no'>"+
                            "<i class='far fa-times-circle'></i>"+
                        "</button>"+
                        "<button type='button' id='namex' onclick='return searcho("+i+")' style='display: inline; position: absolute; right: 15px; margin-top: 5px' class='btn-no'>"+
                            "<i  class='fas fa-search'></i>"+
                        "</button>"+
                        "<p id='3more' style=' margin-top: 5px; color: #F00000; display: "+block+"'>Search input must be more than 3 alphabets!</p>"+
                        "<a href='' onclick='return advance("+i+")' style='color: #143A8C'><b><u>Advance Search</u></b></a>"+
                    "</div>",
            confirmButtonText:
                'NEXT',
            showCancelButton: false,
            inputValidator: (result) => {
                return !result && 'You need to agree with T&C'
            }
        }).then((result) => {
            if (result.value) {
                $("#inputremark-"+i).val($('#remark').val());
                $("#inputremark-"+i).prop('disabled',true);
                $("#inputremark-"+i).val("");
                $("#inputremark-"+i).prop('required',false);
                @if($view=='approver')
                    $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                    $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                @endif
                return searcho(i);
            }else{
                reset(i);
            }
        });
    }    

    function updateResp(item, index){
        htmlstring = htmlstring + 
            "<button style='border: 1px solid #DDDDDD; min-height: 10vh; width: 100%; padding: 5px; text-align: left; background: transparent' onclick='addverifier(\""+item.persnoo+"\","+index+",\""+item.name+"\");' id='addv-"+index+"'>"+
                "<div style='display: flex; align-items: center; flex-wrap: wrap; width: 95%; margin-left: 3%' padding: 15px>"+
                    "<div class='w-10 text-center'><img src='{{asset('vendor/ot-assets/man.jpg')}}' class='approval-search-img'></div>"+
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
    
    function search(searchn, searchpn, searchsn, searchp, searchcc, searchct, searchpa, searchpsa, searchesg, searche, searchmn, searchon, type, block, i){
        const url='{{ route("ot.search", [], false)}}';
        no = i;
        htmlstring = '<div style="border: 1px solid #DDDDDD; max-height: 60vh; overflow-y: scroll;  overflow-x: hidden;">';
        $.ajax({
            type: "GET",
            url: url+"?name="+searchn+"&persno="+searchpn+"&staffno="+searchsn+"&position="+searchp+"&company="+searchcc+"&cost="+searchct+"&persarea="+searchpa+"&perssarea="+searchpsa+"&empsgroup"+searchesg+"&email="+searche+"&mobile="+searchmn+"&office="+searchon+"&type="+type,
            success: function(resp) {
                if(resp.length>0){
                    number = resp.length;
                    resp.forEach(updateResp);
                    cfm = 'SELECT VERIFIER';
                    yes = true;
                }
                else{
                    htmlstring = "<div style=' width: 100%; padding: 5px; text-align: center; vertical-align: middle'>"+
                                    "<p>No maching records found. Try to search again.</p>"+
                                    "</div>";
                                    
                    cfm = 'NEXT';
                    yes = false;
                }
                Swal.fire({
                    title: "Verifier's Name",
                    customClass: 'test2',
                    // width: '75%',
                    html: "<div class='text-left swollo'>"+
                            "<input id='namet' placeholder=\"Enter Employee's Name\" style='width: 100%; box-sizing: border-box;' onkeyup='this.onchange();' onchange='return checkstring();'>"+
                            "<button type='button' id='namex' onclick='return cleart()' class='approval-search-x btn-no'>"+
                                "<i class='far fa-times-circle'></i>"+
                            "</button>"+
                            "<button type='button' id='namex' onclick='return searcho("+i+")' class='approval-search-icon btn-no'>"+
                                "<i class='fas fa-search'></i>"+
                            "</button>"+
                            "<p id='3more' style=' margin-top: 5px; color: #F00000; display: none'>Search input must be more than 3 alphabets!</p>"+
                            "<a id='margin' href='' onclick='return advance("+i+")' style='margin-left: -20px; color: #143A8C'>"+
                                "<b><u>Advance Search</u></b>"+
                            "</a>"+
                        "</div>"+
                        "<p style=' margin-top: 5px; color: #F00000; display: "+block+"'>Please select verifier!</p>"+
                        "<div class='text-left'>"+htmlstring+"</div>",
                    confirmButtonText:
                        cfm,
                    showCancelButton: yes,
                    cancelButtonText: 'CANCEL',
                }).then((result) => {
                    if (result.value) {
                        $("#inputremark-"+i).val($('#remark').val());
                            // if(yes){
                            //     if($('#verifier').val()!=''){
                            //         $('#formverifier').submit();
                            //     }else{
                            //         search(searchn, 'block', i);
                            //     }
                            // }else{
                            //     return searcho(i);
                            // }
                        $("#inputremark-"+i).prop('disabled',true);
                        $("#inputremark-"+i).val("");
                        $("#inputremark-"+i).prop('required',false);
                    }else{
                        reset(i);
                    }
                });
                
            }
        });   
        
    }

    advance(i);

    function advance(i){
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
                                "<p><b>Personnel Number</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                            "<input type='text' id='spersno' style='width: 100%; box-sizing: border-box;'>"+
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
                                "<p><b>Position</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                            "<input type='text' id='position' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+
                        
                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Company Code</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                                "<input type='text' id='scompc' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+

                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Cost Center</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                            "<input type='text' id='scostc' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+
                        
                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Personnel Area</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                                "<input type='text' id='spersarea' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+

                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Personnel Subarea</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                            "<input type='text' id='sperssarea' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+
                        
                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Employee Subgroup</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                                "<input type='text' id='sempsg' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+

                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Email</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                            "<input type='text' id='semail' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+
                        
                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Mobile Number</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                                "<input type='text' id='smobile' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+

                        "<div class='row'>"+
                            "<div class='col-md-3'>"+
                                "<p><b>Office Number</b></p>"+
                            "</div>"+
                            "<div class='col-md-9'>"+
                            "<input type='text' id='soffice' style='width: 100%; box-sizing: border-box;'>"+
                            "</div>"+
                        "</div>"+
                        
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
                    search($('#sname').val(), $('#spersno').val(), $('#sstaffno').val(), $('#position').val(), $('#scompc').val(), $('#scostc').val(), $('#spersarea').val(), $('#sperssarea').val(), $('#sempsg').val(), $('#semail').val(), $('#smobile').val(), $('#soffice').val(), 'advance', 'none', i);
                }else{
                    advance(i);
                }
            }
        });
        
        return false;
    }

    function searcho(i){
        htmlstring = '<div style="border: 1px solid #DDDDDD; max-height: 60vh; overflow-y: scroll;  overflow-x: hidden;">';
        if(($('#namet').val().length)<3){
                    normal(i, 'block');
        }else{
            search($('#namet').val(), '', '', '', '', '', '', '', '', '', '', '', 'normal', 'none', i);
        }
    }

    function showverifier(id){
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
                                "<div class='w-10 text-center'><img src='{{asset('vendor/ot-assets/man.jpg')}}' class='approval-search-img'></div>"+
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
                    confirmButtonText: 'REMOVE VERIFIER',
                    showCancelButton: yes,
                    cancelButtonText: 'CHANGE VERIFIER',
                }).then((result) => {
                    if (result.value) {
                        remove(id);
                    }else if (result.dismiss === Swal.DismissReason.cancel){
                        normal(id, 'none');
                    }
                });
            }
        });   
    }

    function remark2(i){
        return function(){
            // alert("");
            if($("#action-"+i).val()=="Q2"){
                var str = $("#inputremark-"+i).val();
                Swal.fire({
                    title: 'Remarks',
                    html: "<textarea id='remark' rows='4' cols='50' placeholder='This is mandatory field. Please key in remarks here!' style='resize: none;'>"+str+"</textarea><p>Are you sure to query this claim application?</p>",
                    confirmButtonText:
                        'YES',
                        cancelButtonText: 'NO',
                    showCancelButton: true,
                    inputValidator: (result) => {
                        return !result && 'You need to agree with T&C'
                    }
                }).then((result) => {
                        if (result.value) {
                            
                            $("#inputremark-"+i).prop('disabled',false);
                            $("#inputremark-"+i).prop('required',true);
                            $("#inputremark-"+i).val($('#remark').val());
                            @if($view=='approver')
                                $("#verifier-"+i).val($("#verifier-cache-"+i).val());
                                $("#show-verifier-"+i).text($("#show-verifier-cache-"+i).text());
                            @endif
                        }else{
                            reset(i);   
                        }
                })
            }
        }
    }

    for (i=1; i<{{count($otlist)+1}}; i++) {
        $("#action-"+i).change(remark(i));
        $("#inputremark-"+i).on("click",remark2(i));
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