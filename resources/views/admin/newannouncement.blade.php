@extends('adminlte::page')

@section('title', 'Add Announcement')

@section('content')

<h1>@if($edit) Edit @else Add New @endif Announcement</h1>

@if($edit)
<form action="{{ route('announcement.save') }}" method="POST" id="edit">
@else
<form action="{{ route('announcement.create') }}" method="POST" id="edit">
@endif
    @csrf
    
    @if($edit)
        <input type="hidden" name="inputid" value="{{$an->id}}">
    @endif
    <div class="panel panel-default panel-main">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>@if($edit) Edit @else Create New @endif Announcement</strong></div>
            <div class="panel-body">
                <p style="font-weight: bold">Fill in the subject and body of the announcement below.</p>
                <div class="row">
                    <div class="col-md-1"><p style="font-weight: bold">Start Date</p></div>
                    <div class="col-md-3">
                        @if($edit)
                            <input type="date" id="sd" name="sd" value="{{$sd}}" disabled required> 
                        @else
                            @if($sd == null)
                            <input type="date" id="sd" name="sd" @php($sd=date('Y-m-d')) min="{{date('Y-m-d', strtotime($sd . ' +1 day'))}}"  value="{{date('Y-m-d', strtotime($sd . ' +1 day'))}}" required>
                            @else
                            <input type="date" id="sd" name="sd" min="{{$sd}}"  value="{{$sd}}" required>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"><p style="font-weight: bold">End Date</p></div>
                    <div class="col-md-3">
                        @if($edit)
                            <input type="date" id="ed" name="ed" value="{{$ed}}" disabled required> 
                        @else
                            @if($ed == null)
                                <input type="date" id="ed" name="ed" min="{{date('Y-m-d', strtotime($sd . ' +2 day'))}}" value="{{date('Y-m-d', strtotime($sd . ' +2 day'))}}" required>
                            @else
                                <input type="date" id="ed" name="ed" min="{{$ed}}"  value="{{$ed}}" required>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"><p style="font-weight: bold">Title</p></div>
                    <div class="col-md-6">
                        <input type="text" placeholder="Enter title" style="width: 100%" id="title" name="title" @if($edit) value="{{$an->title}}" @endif required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"><p style="font-weight: bold">Announcement</p></div>
                    <div class="col-md-6">
                        <textarea onkeydown="this.onchange();"  onkeyup="this.onchange();" onchange='return checkstringx();'type="text" rows="10" placeholder="Enter announcement "maxlength="1000" style="width: 100%; resize: vertical" id="announce" name="announce" > @if($edit) {{nl2br($an->announcement)}} @endif</textarea required>
                        <div class="text-right"><p style="float: right" class="small">Text remaining: <span id="textremain">1000</span></p></div>
                    </div>
                </div>
                
            </div>
            <div class="panel-footer">
                <div class="text-right">
                    <a href="{{ route('announcement.show', [], false) }}" type="submit" class="btn btn-p btn-outline">CANCEL</a>		
                    <button type="submit" class="btn btn-p btn-primary">SUBMIT</button>		
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tRoleList').DataTable({
        "responsive": "true",
        // "order" : [[2, "asc"]],
        "columns": [
            null,
            null,
            null,
            null,
            { "width": "10%" }
        ]
    });
});

function checkstringx(){
    $("#textremain").text(1000-$("#announce").val().length);
}

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif

var editor = new Simditor({
    
  textarea: $('#announce'),
  placeholder: '',
  toolbar: [
  'bold',
  'italic',
  
  'underline',
  'strikethrough',
  'color',
  'blockquote',
  'hr',    
  'indent',
  'outdent',
  'alignment'
]
  //optional options
});

</script>

@stop
