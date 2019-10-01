@extends('adminlte::page')

@section('title', 'Search Staff')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading panel-primary">Search Staff</div>
    <div class="panel-body">
        <!-- <h3>Search User</h3> -->
        <form action="{{ route('staff.dosearch') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" id="inputstaffid" name="inputstaffid" placeholder="Enter {{ __('adminlte::adminlte.staff_no') }}" value="{{ old('inputstaffid') }}">
            </div>
            <p>or</p>
            <div class="form-group">
                <input type="text" class="form-control" id="inputstaffname" name="inputstaffname" placeholder="Enter {{ __('adminlte::adminlte.staff_name') }}" value="{{ old('inputstaffname') }}">
            </div>
            <button type="submit" class="btn btn-primary">SEARCH</button>
        </form>

        @if($search===1)
            
        <h3>Search Result</h3>
            @if(count($staffs)>0)
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="tStaffList" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Staff Name</th>
                                <th>Staff Email</th>
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
                                    <button type="button" class="btn btn-primary">
                                        MANAGE
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            @endif
        
        <p>{{ $message ?? '' }}</p>
        @endif
    </div>
  </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tStaffList').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]]
    });
} );
</script>
@stop