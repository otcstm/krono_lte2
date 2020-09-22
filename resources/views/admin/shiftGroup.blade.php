@extends('adminlte::page')

@section('title', 'Shift Groups')

@section('content')

<h1>Shift Group</h1>

<div class="row">
    <div class="col-md-12">
    
      <div class="panel panel-default">
                <div class="panel-heading clearfix">            
             <h3 class="panel-title">Search Planning
              <div class="pull-right"><a target="_blank" class="btn btn-primary btn-outline btn-sm" href="{{ route('admin.downloadAllSp',[],false) }}">Download All SP</a></div>
             </h3>
             
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
    <form class="form-horizontal" action="{{ route('admin.shiftgroup',[],false) }}" method="post">
    @csrf
      <div class="form-group">
        <label class="control-label col-sm-2" for="groupname">Group Name:</label>
        <div class="col-sm-4">
            <select id="slctgcode" class="slctgcode form-control" name="gcode" required>        
            @foreach($gclist as $row_gclist)
            @if($row_gclist['group_code']==$gcode)
                <option value="{{$row_gclist['group_code']}}" selected="selected">
                @php $gc_name = $row_gclist['group_code']." - ".$row_gclist['group_name']; @endphp 
            @else 
                <option value="{{$row_gclist['group_code']}}">
            @endif        
                    {{$row_gclist['group_code']}} - {{ $row_gclist['group_name'] }}
                </option>
            @endforeach
            </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary btn-outline">Search</button>
        </div>
      </div>
    </form> 
    
                </div><!-- /.panel-body -->
              </div><!-- /.panel panel-info -->
    
    </div><!-- /.col-md-12 -->
</div><!-- /.row -->   

    
<div class="row">
    <div class="col-md-12">

<div class="panel panel-default">
<div class="panel-heading"><strong>List of Shift Members @if(isset($gc_name)) - {{ $gc_name }}  @endif</strong></div>
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
            @foreach($gresult as $row_sglist)
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

    </div><!-- /.col-md-12 -->
</div><!-- /.row -->  
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {    
    $('.slctgcode').select2();
    $('#tList').DataTable({
        //dom: '<"html5buttons">Bfrtip',        
        //dom: 'lBfrtip',
        dom: '<"flext"lf><"flext"B>rtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ],
        "responsive": "true"   
    });
});
</script>
@stop
