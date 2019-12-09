@extends('adminlte::page')

@section('title', 'Overtime Management')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Overtime Management</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <form id="formdate" action="{{route('oe.show')}}" method="POST">
                    @csrf
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
                        </select>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            SUBMIT
                        </button>
                    </div>
                </form>
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
$("#inputregion").change(function(){
    const url='{{ route("oe.getcompany", [], false)}}';
    
    $.ajax({
    url: url+"?region="+$("#inputregion").val(),
    type: "GET",
    success: function(resp) {
        $( "#inputcompany" ).html("");
        $( "#inputcompany" ).append('<option hidden disabled selected value="">Select Company</option>');
        resp.forEach(updateResp);
    },
        error: function(err) {
            // respjson.forEach(myFunction);
        }
    });
});

function updateResp(item, index){
    $( "#inputcompany" ).append('<option value="'+item.id+'">'+item.id+' - '+item.name+'</option>');  
}

</script>
@stop