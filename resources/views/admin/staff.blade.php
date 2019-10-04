@extends('adminlte::page')

@section('title', 'Search Staff')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Search for Staff</div>
    <div class="panel-body">
        <!-- <h3>Search User</h3> -->
        <form action="{{ route('staff.dosearch') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}">
            </div>
            <button type="submit" class="btn btn-primary">SEARCH</button>
        </form>
    </div>
</div>
<div class="panel panel-default">    
    <div class="panel-heading panel-primary">List of Staff</div>
    <div class="panel-body">
        @if($search ?? '')
        <h3>Search Result</h3>
        <p>{{ $message ?? '' }}</p>
        @endif
        @if(count($staffs)>0)
        <div class="table-responsive">
            <table id="tStaffList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $singleuser)
                    <tr>
                        <td>{{ $singleuser->staff_no }}</td>
                        <td>{{ $singleuser->name }}</td>
                        <td>{{ $singleuser->email }}</td>
                        <td>
                            @if($admin)
                            <button type="button" class="btn btn-primary">
                                MANAGE
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>            
        @endif
    </div>
</div>

@if(session()->has('feedback'))
<div id="feedback" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-{{session()->get('feedback_icon')}}" style="color: {{session()->get('feedback_color')}}; font-size: 32px;"></div>
                <p>{{session()->get('feedback_text')}}<p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
@endif

@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tStaffList').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]]
    });

    @if(session()->has('feedback'))
        $('#feedback').modal('show');   
    @endif
} );
</script>
@stop