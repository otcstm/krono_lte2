@extends('adminlte::page')

@section('title', 'Overtime Management')

@section('content')

<h1>Overtime Claim Expiry Date</h1>

<div class="panel panel-default panel-main">
    <div class="panel panel-default">
        <div class="panel-heading panel-primary">Overtime Management</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    <form id="form" action="{{route('oe.show')}}" method="POST">
                        @csrf
                        <input class="hidden" id="formtype" type="text" name="formtype" value="">
                        <div class="form-group">
                            <label for="inputregion">Region:</label>
                            <select name="inputregion" id="inputregion" required>
                                <option hidden disabled selected value="">Select Region</option>
                                <option value="SEM">Semenanjung</option>
                                <option value="SWK">Sarawak</option>
                                <option value="SBH">Sabah</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputcompany">Company:</label>
                            <select name="inputcompany" id="inputcompany" required>
                                <option hidden disabled selected value="">Select Company</option>
                                @foreach($comp as $singlecomp)
                                    <option value="{{$singlecomp->id}}">{{$singlecomp->company_descr}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </form>
                </div>
            </div>
            </div>
         
				<div class="panel-footer">   
            <div class="text-right">
                <!-- <button type="button" class="btn btn-primary" id="btn-eligibility">
                    CONFIGURE ELIGIBILITY
                </button> -->
                <button type="button" class="btn btn-primary" id="btn-expiry">
                    CONFIGURE EXPIRY
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tRoleList').DataTable({
        "responsive": "true",
        "order" : [[3, "desc"]],
        "columns": [
            null,
            null,
            null,
            null,
            { "width": "10%" }
        ]
    });
});
// $("#inputregion").change(function(){
//     const url='{{ route("oe.getcompany", [], false)}}';
    
//     $.ajax({
//     url: url+"?region="+$("#inputregion").val(),
//     type: "GET",
//     success: function(resp) {
//         $( "#inputcompany" ).html("");
//         $( "#inputcompany" ).append('<option hidden disabled selected value="">Select Company</option>');
//         resp.forEach(updateResp);
//     },
//         error: function(err) {
//             // respjson.forEach(myFunction);
//         }
//     });
// });

function updateResp(item, index){
    $( "#inputcompany" ).append('<option value="'+item.id+'">'+item.id+' - '+item.name+'</option>');  
}

function validornot(){
    if($('#inputregion').get(0).checkValidity()==false){
        $('#inputregion').get(0).reportValidity();
    }else if($('#inputcompany').get(0).checkValidity()==false){
        $('#inputcompany').get(0).reportValidity();
    }else{
        $("#form").submit();
    }
}

//when uploading file
$("#btn-eligibility").on('click', function(){
    $("#formtype").val("eligibility");
    return validornot();
});  

$("#btn-expiry").on('click', function(){
    $("#formtype").val("expiry");
    return validornot();
});  

</script>
@stop