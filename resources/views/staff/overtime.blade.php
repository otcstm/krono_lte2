@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<h1>List of Overtime Claim</h1>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="text-right" style="margin-bottom: 15px">
            <form action="{{route('ot.formnew')}}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-up" style=" margin-top: 15px">APPLY NEW OVERTIME</button>
            </form>
        </div>
        {{--@if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif--}}
        <div class="table-responsive">
            <table id="tOTList" class="table table-bordered tbot">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <!-- <th>Reference No</th> -->
                        <th>OT Date</th>
                        <th>Start OT</th>
                        <th>End OT</th>
                        <th>Total Hours/Minutes</th>
                        <th>Day Type</th>
                        <th>Charge Code</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($otlist as $no=>$singleuser)
                        <tr>
                            <td>@if(($singleuser->status=="D2")||($singleuser->status=="Q2"))<input type="checkbox" id="checkbox-{{$no}}" value="{{$singleuser->id}}"> @endif</td>
                            <td></td>
                            <!-- <td>{{-- $singleuser->refno --}}</td> -->
                            <td>{{ date("d.m.Y", strtotime($singleuser->date)) }}</td>
                            <td>@foreach($singleuser->detail as $details) @if($details->checked=="Y") {{date('Hi', strtotime($details->start_time)) }}<br> @endif @endforeach</td>
                            <td>@foreach($singleuser->detail as $details) @if($details->checked=="Y") {{ date('Hi', strtotime($details->end_time))}}<br> @endif @endforeach</td>
                            <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
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
                                @if($singleuser->charge_type!=null)
                                    {{ $singleuser->charge_type }}
                                @else
                                    N/A
                                @endif
                            </td> 
                            <td>@if(count($singleuser->detail)) 
                                    @foreach($singleuser->detail as $details) 
                                        @if($details->clock_in=="")
                                         - 
                                        @else 
                                            @if($details->checked=="Y")
                                                <a href = "https://www.google.com/maps/search/?api=1&query={{$details->in_latitude}},{{$details->in_longitude}}" target="_blank" style="font-weight: bold; color: #143A8C"> {{$details->in_latitude}} {{$details->in_longitude}}</a><br> 
                                            @endif 
                                        @endif 
                                    @endforeach 
                                @else 
                                    - 
                                @endif</td> 
                            <td 
                                @foreach($singleuser->log as $logs) 
                                    @if(strpos($logs->message,"Queried")!==false) 
                                        @php($query = $logs->message) 
                                    @endif 
                                @endforeach 
                                @if(($singleuser->status=="Q2")||($singleuser->status=="Q1"))
                                    title = "{{str_replace('"', '', str_replace('Queried with message: "', '', $query))}}"
                                @endif> 
                                @if(($singleuser->status=="D2")||($singleuser->status=="D1"))
                                    Draft @if($singleuser->date_expiry!="")<p style="color: red">Due: {{$singleuser->date_expiry}}</p> @endif
                                @elseif(($singleuser->status=="Q2")||($singleuser->status=="Q1"))
                                    @php($query = "") <p>Query</p>
                                @elseif($singleuser->status=="PA")
                                    <p>Pending Approval</p>
                                @elseif($singleuser->status=="PV")
                                    <p>Pending Verification</p>
                                @elseif($singleuser->status=="A")
                                    <p>Approved</p>
                                @else 
                                    {{ $singleuser->status}}
                                @endif
                            </td>
                            <td class="td-btn">
                                <form action="{{route('ot.detail')}}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="text" class="hidden" name="detailid" value="{{$singleuser->id}}" required>
                                    <input type="text" class="hidden" name="type" value="ot" required>
                                    <button type="submit" class="btn btn-np"><i class="fas fa-info-circle"></i></button>
                                </form>
                                @if(in_array($singleuser->status, $array = array("D1", "D2", "Q2", "Q1")))
                                    <form action="{{route('ot.update')}}" method="POST" style="display:inline">
                                        @csrf
                                        <input type="text" class="hidden"  name="inputid" value="{{$singleuser->id}}" required>
                                        <button type="submit" class="btn btn-np"><i class="fas fa-edit"></i></button>
                                    </form>
                                    <button type="button" class="btn btn-np" data-toggle="modal" data-target="#delOT" id="del-{{$no}}" data-id="{{$singleuser->id}}" data-date="{{$singleuser->date}}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                {{--@else
                                <form action="{{route('ot.detail')}}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="text" class="hidden" name="detailid" value="{{$singleuser->id}}" required>
                                    <input type="text" class="hidden" name="type" value="ot" required>
                                    <button type="submit" class="btn btn-np"><i class="fas fa-info-circle"></i></button>
                                </form>--}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
    <div id="submitbtn" class="panel-footer" style="display: none">
        <div class="text-right">
            <form id="submitform" action="{{route('ot.submit')}}" method="POST"  style="display:inline">
                @csrf
                <input type="text" class="hidden" id="submitid" name="submitid" value="" required>
                <input type="text" class="hidden" id="multi" name="multi" value="yes" required>
                <button type="button" onclick="return submission()" class="btn btn-primary">SUBMIT</button>
            </form>
        </div>
    </div>
</div>
<!-- 
<div id="delOT" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Claim Time</h4>
            </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-warning-sign" style="color: #F0AD4E; font-size: 32px;"></div>
                <p>Are you sure you want to delete claim for date <span id="deldate"></span>?<p>
                <form action="{{ route('ot.remove') }}" method="POST">
                    @csrf
                    <input type="text" class="hidden" id="delid" name="delid" value="" required>
                    <button type="submit" class="btn btn-primary">DELETE</button>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->

<form action="{{ route('ot.remove') }}" method="POST" class="hidden" id="form">
    @csrf
    <input type="text" class="hidden" id="delid" name="delid" value="" required>
    <button type="submit" class="btn btn-primary">DELETE</button>
</form>
@stop

@section('js')
<script type="text/javascript">

// alert("{{count($otlist)}}");

$(document).ready(function() {
    var t = $('#tOTList').DataTable({
        "responsive": "true",
        "order" : [[0, "desc"]],
        dom: '<"flext"lB>rtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ]
    });

    t.on( 'order.dt search.dt', function () {
        t.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
});

var whensubmit = false;

$('#delOT').on('show.bs.modal', function(e) {
    var id = $(e.relatedTarget).data('id');
    var date = $(e.relatedTarget).data('date');
    $("#delid").val(id);
    $("#deldate").text(date);
})

function deletec(i){
    return function(){
        var id = $("#del-"+i).data('id');
        var date = $("#del-"+i).data('date');
        var d = Date.parse(date).toString("dd.MM.yyyy");  
        $("#delid").val(id);
        Swal.fire({
            title: 'Claim Deletion',
            html: "Are you sure you want to delete claim application for date <b>"+d+"</b>?",
            showCancelButton: true,
            confirmButtonText:
                                'YES',
                                cancelButtonText: 'NO',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            }).then((result) => {
            if (result.value) {
                $("#form").submit();
            }
        })
    }
}

for(i=0; i<{{count($otlist)}}+1; i++){
    $("#del-"+i).on('click', deletec(i));
}

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif

var show = 0;

function submitval(i){
    return function(){
        if ($('#checkbox-'+i).is(':checked')) {
            $("#submitid").val(function() {
                return this.value + $('#checkbox-'+i).val()+" ";
            });
            show++;
        }else{
            var str = ($('#submitid').val()).replace($('#checkbox-'+i).val()+" ",'');
            $('#submitid').val(str);
            show--;
        }
        if(show>0){
            $('#submitbtn').css("display","block");
        }else{
            $('#submitbtn').css("display","none");
        }
    };
};

for(i=0; i<{{count($otlist)}}; i++) {
    $("#checkbox-"+i).change(submitval(i));
};

function submission(){
    // alert("x");
    
    // whensubmit = true;
    // if(whensubmit){
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
                $("#submitform").submit();
            }
        })
        
        return false;
    // }
}

</script>
@stop