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
                            <input type="text" class="form-control hidden" id="inputid" name="inputid[]" value="{{$singleuser->id}}" required>
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
                                    <td>{{$singleuser->verifier->name}}</td>
                                @endif
                            <td>
                                <select name="inputaction[]" id="action-{{$no}}">
                                    <option selected value="">Select Action</option>
                                    <!-- <option hidden disabled selected value="">Select Action</option> -->
                                    @if($view=="verifier")<option value="PA">Verify</option>
                                    @elseif($view=='approver')
                                        <option value="A">Approve</option>
                                        <option value="Assign">Assign Verifier</option>
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
    var htmlstringshow = '';
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
                            
                        }else{
                            
                            
                            $("#action-"+i).val("");
                            $("#inputremark-"+i).prop('disabled',true);
                            $("#inputremark-"+i).val("");
                            $("#inputremark-"+i).prop('required',false);
                        }
                })
            }else if($("#action-"+i).val()=="Assign"){
                normal(i);
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
                    }else{
                        $("#action-"+i).val("");
                        $("#inputremark-"+i).prop('disabled',true);
                        $("#inputremark-"+i).prop('required',false);
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
        }else{
            $('#namex').css('visibility','hidden');
            $('#3more').css('display','block');
        }
    }

    function normal(i){
        Swal.fire({
            title: "Verifier's Name",
            html: "<div class='text-left'><input id='namet' placeholder=\"Enter Employee's Name\" style='width: 100%; box-sizing: border-box;' onkeyup='this.onchange();' onchange='return checkstring();'><button type='button' id='namex' onclick='return cleart()' style='visibility: hidden;display: inline; position: absolute; right: 30px; margin-top: 3px' class='btn-no'><i class='far fa-times-circle'></i></button><i style='display: inline; position: absolute; right: 15px; margin-top: 5px' class='fas fa-search'></i><p id='3more' style=' margin-top: 5px; color: #F00000; display: none'>Search input must be more than 3 alphabets!</p><a href='' onClick='return advance()' style='color: #143A8C'><b><u>Advance Search</u></b></a></div>",
            confirmButtonText:
                'NEXT',
            showCancelButton: false,
            inputValidator: (result) => {
                return !result && 'You need to agree with T&C'
            }
        }).then((result) => {
            if (result.value) {
                $("#inputremark-"+i).val($('#remark').val());
                if(($('#namet').val().length)<3){
                    normalerror(i);
                }else{
                    search($('#namet').val());
                }
                $("#inputremark-"+i).prop('disabled',true);
                $("#inputremark-"+i).val("");
                $("#inputremark-"+i).prop('required',false);
            }else{
                $("#action-"+i).val("");
                $("#inputremark-"+i).prop('disabled',true);
                $("#inputremark-"+i).prop('required',false);
            }
        });
    }

    function normalerror(i){
        Swal.fire({
            title: "Verifier's Name",
            html: "<div class='text-left'><input id='namet' placeholder=\"Enter Employee's Name\" onkeyup='this.onchange();' onchange=\"$('#namex').css('visibility', 'visible');\" onchange='return checkstring();'><button type='button' id='namex' onclick='return cleart()' style='visibility: hidden;display: inline; position: absolute; right: 30px; margin-top: 3px' class='btn-no'><i class='far fa-times-circle'></i></button><i style='display: inline; position: absolute; right: 15px; margin-top: 5px' class='fas fa-search'></i><p id='3more' style=' margin-top: 5px; color: #F00000'>Search input must be more than 3 alphabets!</p><a href='' onClick='return advance()' style='color: #143A8C'><b><u>Advance Search</u></b></a></div>",
            confirmButtonText:
                'NEXT',
            showCancelButton: false,
            inputValidator: (result) => {
                return !result && 'You need to agree with T&C'
            }
        }).then((result) => {
            if (result.value) {
                $("#inputremark-"+i).val($('#remark').val());
                if(($('#namet').val().length)<3){
                    normalerror(i);
                }else{
                    search($('#namet').val());
                }
                $("#inputremark-"+i).prop('disabled',true);
                $("#inputremark-"+i).val("");
                $("#inputremark-"+i).prop('required',false);
            }else{
                $("#action-"+i).val("");
                $("#inputremark-"+i).prop('disabled',true);
                $("#inputremark-"+i).prop('required',false);
            }
        });
    }
    

    function updateResp(item, index){
        htmlstring = htmlstring + 
            "<button style='border: 1px solid #DDDDDD; min-height: 10vh; width: 100%; padding: 5px; text-align: left; background: transparent'>"+
                "<div style='display: flex; flex-wrap: wrap; width: 95%; margin-left: 3%' padding: 15px>"+
                    "<div class='w-10'><i style='height: 100%' class='fas fa-user-circle'></i>"+index+"</div>"+
                    "<div class='w-30'>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Name</div>"+
                            "<div class='w-70'>: <b>"+item.name+"</b></div>"+
                        "</div>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Personnel No</div>"+
                            "<div class='w-70'>: <b>"+item.persno+"</b></div>"+
                        "</div>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Staff No</div>"+
                            "<div class='w-70'>: <b>"+item.staffno+"</b></div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='w-30'>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Company Code</div>"+
                            "<div class='w-70'>: <b>"+item.companycode+"</b></div>"+
                        "</div>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Cost Center</div>"+
                            "<div class='w-70'>: <b>"+item.costcenter+"</b></div>"+
                        "</div>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Personnel Area</div>"+
                            "<div class='w-70'>: <b>"+item.persarea+"</b></div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='w-30'>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Employee Subgroup</div>"+
                            "<div class='w-70'>: <b>"+item.empsubgroup+"</b></div>"+
                        "</div>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Email</div>"+
                            "<div class='w-70'>: <b>"+item.email+"</b></div>"+
                        "</div>"+
                        "<div style='display: flex; flex-wrap: wrap; width: 100%;'>"+
                            "<div class='w-30'>Mobile No</div>"+
                            "<div class='w-70'>: <b>"+item.mobile+"</b></div>"+
                        "</div>"+
                    "</div>"+
                "</div>"+
            "</button>";
            
    }

    // function pass(htmlstring){
    //     htmlstringshow = htmlstring;
    // }

    function search(searchn){
        const url='{{ route("ot.search", [], false)}}';
        
        $.ajax({
            type: "GET",
            url: url+"?name="+searchn,
            success: function(resp) {
                resp.forEach(updateResp);
                htmlstring = htmlstring + "</tbody></table></div>";
                Swal.fire({
                    title: "Verifier's Name",
                    customClass: 'test2',
                    // width: '75%',
                    html: "<div class='text-left swollo'><input id='namet' placeholder=\"Enter Employee's Name\" style='width: 100%; box-sizing: border-box;' onkeyup='this.onchange();' onchange='return checkstring();'><button type='button' id='namex' onclick='return cleart()' style='visibility: hidden;display: inline; position: absolute; right: 30px; margin-top: 3px' class='btn-no'><i class='far fa-times-circle'></i></button><i style='display: inline; position: absolute; right: 15px; margin-top: 5px' class='fas fa-search'></i><p id='3more' style=' margin-top: 5px; color: #F00000; display: none'>Search input must be more than 3 alphabets!</p><a href='' onClick='return advance()' style='color: #143A8C'><b><u>Advance Search</u></b></a></div><div class='text-left'>"+htmlstring+"</div>",
                    confirmButtonText:
                        'NEXT',
                    showCancelButton: false,
                    inputValidator: (result) => {
                        return !result && 'You need to agree with T&C'
                    }
                }).then((result) => {
                    if (result.value) {
                        $("#inputremark-"+i).val($('#remark').val());
                        if(($('#namet').val().length)<3){
                            normalerror(i);
                        }else{
                            search($('#namet').val());
                        }
                        $("#inputremark-"+i).prop('disabled',true);
                        $("#inputremark-"+i).val("");
                        $("#inputremark-"+i).prop('required',false);
                    }else{
                        $("#action-"+i).val("");
                        $("#inputremark-"+i).prop('disabled',true);
                        $("#inputremark-"+i).prop('required',false);
                    }
                });
                $('#tsearch').DataTable({
                    "responsive": "true",
                    // "order" : [[1, "asc"]],
                    // "bLengthChange": false,
                    // "pageLength" : 3,
                    "searching": false,
                    "bSort": false
                });
                
            }
        });      
        
    }

    function advance(){
        alert("kon");
        return false;
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
                            
                        }else{
                            
                            
                        $("#action-"+i).val("");
                            $("#inputremark-"+i).prop('disabled',true);
                            $("#inputremark-"+i).val("");
                            $("#inputremark-"+i).prop('required',false);
                            
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