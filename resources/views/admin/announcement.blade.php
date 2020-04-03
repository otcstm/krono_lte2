@extends('adminlte::page')

@section('title', 'Announcement List')

@section('content')

<h1>Announcement Management</h1>

<div class="panel panel-default panel-main">
    <div class="panel panel-default">
		<div class="panel-heading"><strong>List of Announcement</strong></div>
        <div class="panel-body">
            <div class="text-right">
                <a href="{{ route('announcement.add', [], false) }}" class="btn btn-up">CREATE NEW ANNOUNCEMENT</a>
            </div>
            <div class="table-responsive">
                <table id="tRoleList" class="table table-bordered default">
                    <thead>
                        <tr>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Title</th>
                            <th>Announcement</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($an as $no => $singleuser)
                        <tr>
                            <td>{{ $singleuser->start_date}}</td>
                            <td>{{ $singleuser->end_date }}</td>
                            <td>{{ $singleuser->title }}</td>
                            <td class="default">{!! nl2br($singleuser->announcement) !!}</td>
                            <td>
                                <form method="post" action="{{ route('announcement.edit', [], false) }}" id="formedit-{{$no}}" style="display: inline">
                                    @csrf
                                    <button type="submit" class="btn btn-np" title="Edit" id="edit-{{$no}}" data-id="{{$singleuser['id']}}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <input type="hidden" name="inputid" value="{{$singleuser->id}}">
                                </form>
                                <form method="post" action="{{ route('announcement.delete', [], false) }}" id="formdelete-{{$no}}" style="display: inline">
                                    @csrf
                                    <button type="button" class="btn btn-np" title="Delete" data-title="{{$singleuser['title']}}"data-announce="{{nl2br($singleuser['announcement'])}}" id="del-{{$no}}">
                                            <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <input type="hidden" name="inputid" value="{{$singleuser->id}}">
                                </form>
                            </td>
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

for(i = 0; i<{{count($an)}}+1; i++){
	$("#del-"+i).on("click", deleteid(i));
}

function deleteid(i){
	return function(){

        var title = $("#del-"+i).data('title');
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete announcement "+title+"?",
            customClass: 'initial',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'DELETE',
            cancelButtonText: 'CANCEL'
            }).then((result) => {
            if (result.value) {
                $("#formdelete-"+i).submit();
            }
        })
    }
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
