@extends('adminlte::page')
@section('title', 'Verifier Management')
@section('content')
Form add verifier
@stop
@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#userList').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]]
    });
    
    $('#verifierList').DataTable({
    });
    
    $('#subsList').DataTable({
    });
} );

</script>
@stop