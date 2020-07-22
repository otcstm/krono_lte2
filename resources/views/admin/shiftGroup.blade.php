@extends('adminlte::page')

@section('title', 'Shift Groups All')

@section('content')

<h1>Shift Group All</h1>

<div class="panel panel-default">
<div class="panel-heading"><strong>List of Shift Group</strong></div>
    <div class="panel-body">
        <div class="table-responsive">
        <table id="tList" class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Group Code</th>
                    <th>Group Name</th>
                    <th>Owner Name</th>
                    <th>Owner Staffno</th>
                    <th>Planner Name</th>
                    <th>Planner Staffno</th>
                    <th>Member Name</th>
                    <th>Member Staffno</th>
                </tr>
            </thead>
            <tbody>
            @php $counter = 0 @endphp
            @foreach($sglist as $row_sglist)
                <tr>
                    <td>{{++$counter}}</td>
                    <td>{{$row_sglist->group_code}}</td>
                    <td>{{$row_sglist->group_name}}</td>
                    <td>{{$row_sglist->go_name}} ({{$row_sglist->go_persno}})</td>
                    <td>{{$row_sglist->go_staffno}}</td>
                    <td>@if($row_sglist->sp_name ?? '')
                        {{$row_sglist->sp_name}} ({{$row_sglist->sp_persno}})
                    @endif</td>
                    <td>{{$row_sglist->sp_staffno}}</td>
                    <td>{{$row_sglist->u_name}} ({{$row_sglist->u_persno}})</td>
                    <td>{{$row_sglist->u_staffno}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tList').DataTable({
        dom: '<"html5buttons">Bfrtip',
        language: {
                buttons: {
                    colvis : 'show / hide', // label button show / hide
                    colvisRestore: "Reset Kolom" //lael untuk reset kolom ke default
                }
        },
        
        buttons : [
                    // {extend:'csv'},
                    // {extend: 'pdf', title:'Contoh File PDF Datatables'},
                    {extend: 'excel'}
        ],
        "responsive": "true"   
    });
});
</script>
@stop
