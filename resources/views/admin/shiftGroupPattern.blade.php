@extends('adminlte::page')

@section('title', 'Shift Group Patterns')

@section('content')

<h1>Shift Group Patterns</h1>

<div class="row">
    <div class="col-md-12">
    
      <div class="panel panel-default">
                <div class="panel-heading clearfix">            
             <h3 class="panel-title">Search Group Patern
              <div class="pull-right"><a target="_blank" class="btn btn-primary btn-outline btn-sm" href="{{ route('admin.downloadAllSgp',[],false) }}">Download All Shift Group Patterns</a></div>
             </h3>
             
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
    <form class="form-horizontal" action="{{ route('admin.shiftGroupPattern',[],false) }}" method="post">
    @csrf
      <div class="form-group">
        <label class="control-label col-sm-2" for="groupname">Group Name:</label>
        <div class="col-sm-4">
            <select id="slctgcode" class="slctgcode form-control" name="gcode">   
                <option value="" selected="selected"></option>
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
        <label class="control-label col-sm-2" for="gshiftpattern">Shift Pattern:</label>        
        <div class="col-sm-4">
            <select id="slctgspattern" class="slctgspattern form-control" name="gshiftpattern">
                <option value="" selected="selected"></option>
            @foreach($gsplist as $row_gsplist)
            @if($row_gsplist['sp_code']==$gspcode)
                <option value="{{$row_gsplist['sp_code']}}" selected="selected">
                @php $gc_name = $row_gsplist['sp_code']." - ".$row_gsplist['sp_desc']; @endphp 
            @else 
                <option value="{{$row_gsplist['sp_code']}}">
            @endif        
                    {{$row_gsplist['sp_code']}} - {{ $row_gsplist['sp_desc'] }}
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
<div class="panel-heading"><strong>List of Shift Group Pattern @if(isset($gc_name)) - {{ $gc_name }}  @endif</strong></div>
    <div class="panel-body">
        <div class="table-responsive">
            
            {{-- //table column
            group_code,group_name,
            go_persno,go_name,go_staffno,
            sp_persno,sp_name,sp_staffno,
            sp_id,sp_code,sp_desc,DAY_01,DAY_02,DAY_03,DAY_04,DAY_05,DAY_06,DAY_07,DAY_08,
            sp_created_at,sp_createdby_name,sp_createdby_staffno 
            --}}

        <table id="tList" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th></th>
                    {{-- <th>Group Code</th>
                    <th>Group Name</th>
                    <th>Owner Name</th>
                    <th>Owner Staffno</th>
                    <th>Owner Persno</th>
                    <th>Planner Name</th>
                    <th>Planner Staffno</th>
                    <th>Planner Persno</th> --}}
                    <th>Group</th>
                    <th>Pattern Code</th>
                    <th>Pattern Desc</th>
                    <th>Day 1</th>
                    <th>Day 2</th>
                    <th>Day 3</th>
                    <th>Day 4</th>
                    <th>Day 5</th>
                    <th>Day 6</th>
                    <th>Day 7</th>
                    <th>Day 8</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
            @php $counter = 0 @endphp
            @foreach($gresult as $row_sglist)
                <tr>
                    <td>{{++$counter}}</td>                    
                    {{-- <td>{{$row_sglist->group_code}}</td>
                    <td>{{$row_sglist->group_name}}</td>
                    <td>{{$row_sglist->go_name}} ({{$row_sglist->go_persno}})</td>
                    <td>{{$row_sglist->go_staffno}}</td>
                    <td>@if($row_sglist->sp_name ?? '')
                        {{$row_sglist->sp_name}} ({{$row_sglist->sp_persno}})
                    @endif</td>
                    <td>{{$row_sglist->sp_staffno}}</td> --}}     
                   <td>{{$row_sglist->group_name}}<Br/>[{{$row_sglist->group_code}}]</td>    
                    <td>{{$row_sglist->sp_code}}<Br/>[{{$row_sglist->sp_id}}]</td>
                    <td>{{$row_sglist->sp_desc}}</td>
                    <td>{{$row_sglist->DAY_01}}</td>
                    <td>{{$row_sglist->DAY_02}}</td>
                    <td>{{$row_sglist->DAY_03}}</td>
                    <td>{{$row_sglist->DAY_04}}</td>
                    <td>{{$row_sglist->DAY_05}}</td>
                    <td>{{$row_sglist->DAY_06}}</td>
                    <td>{{$row_sglist->DAY_07}}</td>
                    <td>{{$row_sglist->DAY_08}}</td>
                    <td>{{$row_sglist->sp_created_at}}</td>
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
    $('.slctgcode').select2({         
        placeholder: 'Type a group code',
        allowClear: true,
    }); 
    $('.slctgspattern').select2({                
        placeholder: 'Type a shift pattern',
        allowClear: true,
    });
    $('#tList').DataTable({
        //dom: '<"html5buttons">Bfrtip',        
        //dom: 'lBfrtip',
        dom: '<"flext"lf><"flext"B>rtip',
        buttons: [
            'csv', 'excel'
        ],
        "responsive": "true"   
    });
});
</script>
@stop
