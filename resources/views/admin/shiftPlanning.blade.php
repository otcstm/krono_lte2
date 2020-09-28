@extends('adminlte::page')

@section('title', 'All Shift Planning')

@section('content')
<div class="row">
    <div class="col-md-12">
    
      <div class="panel panel-default">
                <div class="panel-heading clearfix">            
             <h3 class="panel-title">Search Group Planning
              <div class="pull-right"><a target="_blank" class="btn btn-primary btn-outline btn-sm" href="{{ route('admin.downloadAllSp',[],false) }}">Download All SP</a></div>
             </h3>
             
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
    <form class="form-horizontal" action="{{ route('admin.shiftplanning',[],false) }}" method="post">
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
              {{$row_gclist['group_code']}} - {{ $row_gclist['group_name'] }}</option>
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
            <div class="panel-heading">            
         <h3 class="panel-title pull-left">Shift Planning
            </h3>
        <div class="clearfix"></div>

            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
{{-- @if (session()->has('sysmsg_type'))
        <div class="alert alert-{{ session()->get('sysmsg_class') }} alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><i class="{{ session()->get('sysmsg_icon') }} "></i> {{ session()->get('sysmsg_text') }}</strong>
        </div>
@endif --}}
<div class="table-responsive">
<table id="tList" class="table table-bordered table-hover">
            <thead>
                <tr>
                  <th></th>
                    <th>User</th>
                    <th>Plan Month</th>
                    <th>Group</th>
                    <th>Group Owner</th>
                    <th>Shift Planner</th>
                    {{-- <th>creator_id</th> --}}
                    <th>Plan Approver</th>
                    <th>Status</th>
                    <th>Approved Date</th>
                    <th>start_date</th>
                    <th>end_date</th>
                    <th>total_minutes</th>
                    <th>total_days</th>
                    <th>code</th>
                    <th>description</th>
                    <th>work_date</th>
                    <th>work_start_time</th>
                    <th>work_end_time</th>
                    <th>is_work_day</th>
                    <th>day_code</th>
                    <th>day_type</th>
                    <th>day_start_time</th>
                    <th>day_dur_hour</th>
                    <th>day_dur_minute</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                  <th></th>
                    <th>User</th>
                    <th>Plan Month</th>
                    <th>Group</th>
                    <th>Group Owner</th>
                    <th>Shift Planner</th>
                    {{-- <th>creator_id</th> --}}
                    <th>Plan Approver</th>
                    <th>Status</th>
                    <th>Approved Date</th>
                    <th>start_date</th>
                    <th>end_date</th>
                    <th>total_minutes</th>
                    <th>total_days</th>
                    <th>code</th>
                    <th>description</th>
                    <th>work_date</th>
                    <th>work_start_time</th>
                    <th>work_end_time</th>
                    <th>is_work_day</th>
                    <th>day_code</th>
                    <th>day_type</th>
                    <th>day_start_time</th>
                    <th>day_dur_hour</th>
                    <th>day_dur_minute</th>
                </tr>
              </tfoot>
            <tbody>
            @php $counter = 0 @endphp
            @foreach($gresult as $row_splist)
                <tr>
                    <td>{{++$counter}}</td>
                    <td>{{$row_splist->user_name}} ({{$row_splist->user_staffno}})</td>
                    <td>{{$row_splist->plan_month}}</td>
                    <td>{{$row_splist->group_name}} ({{$row_splist->group_code}})</td>
                    <td>{{$row_splist->go_name}} ({{$row_splist->go_staffno}})</td>
                    <td>{{$row_splist->sp_name}} ({{$row_splist->sp_staffno}})</td>
                    {{-- <td>{{$row_splist->creator_id}}</td> --}}
                    <td>{{$row_splist->sp_appr_name}} ({{$row_splist->sp_appr_staffno}})</td>
                    <td>{{$row_splist->status}}</td>
                    <td>{{$row_splist->approved_date}}</td>
                    <td>{{$row_splist->start_date}}</td>
                    <td>{{$row_splist->end_date}}</td>
                    <td>{{$row_splist->total_minutes}}</td>
                    <td>{{$row_splist->total_days}}</td>
                    <td>{{$row_splist->code}}</td>
                    <td>{{$row_splist->description}}</td>
                    <td>{{$row_splist->work_date}}</td>
                    <td>{{$row_splist->work_start_time}}</td>
                    <td>{{$row_splist->work_end_time}}</td>
                    <td>{{$row_splist->is_work_day}}</td>
                    <td>{{$row_splist->day_code}}</td>
                    <td>{{$row_splist->day_type}}</td>
                    <td>{{$row_splist->day_start_time}}</td>
                    <td>{{$row_splist->day_dur_hour}}</td>
                    <td>{{$row_splist->day_dur_minute}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
</div>
            </div><!-- /.panel-body -->
          </div><!-- /.panel panel-info -->
</div><!-- /.col-md-12 -->
</div><!-- /.row -->    


@stop

@section('js')
<style>
   tfoot {
    display: table-header-group;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('.slctgcode').select2();

    // $('#tList tfoot th').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    // } );// Setup - add a text input to each header cell with header name
    // $('#tList tfoot th').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    // } );    

    var otable = $('#tList').DataTable({
      
        //dom: '<"html5buttons">Bfrtip',        
        //dom: 'lBfrtip',
        dom: '<"flext"lf><"flext"B>rtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ],        
        "responsive": "true",
        initComplete: function() {
          // for column text search input
          this.api().columns().every(function() {
             var that = this;
             var input = $('')
               .appendTo($(this.footer()).empty())

               .on('keyup change', function() {
                 if (that.search() !== this.value) {
                   that
                     .search(this.value)
                     .draw();
                 }
               });
           });

          this.api().columns([1, 2]).every(function() {
            var column = this;
            var select = $('<select><option value=""></option></select>')
              .appendTo($(column.footer()).empty())
              .on('change', function() {
                var val = $.fn.dataTable.util.escapeRegex(
                  $(this).val()
                );

                column
                  .search(val ? '^' + val + '$' : '', true, false)
                  .draw();
              });

            column.data().unique().sort().each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
          });

         
        }   
    });

    // Apply the search
    // otable.columns([2, 3]).every( function () {
    //     var that = this;
    //     $( 'input', this.footer() ).on( 'keyup change clear', function () {
    //         if ( that.search() !== this.value ) {
    //             that
    //                 .search( this.value )
    //                 .draw();
    //         }
    //     } );
    // } );

    
    // $('.slctspgc').select2({
    //     placeholder: 'Select an item',
    //     minimumInputLength: 3,
    //     ajax: {
    //       url: '/admin/verifier/staffsearch',
    //       dataType: 'json',
    //       minLength: 3,
    //       delay: 250,
    //       processResults: function (data) {
    //         return {
    //           results:  $.map(data, function (item) {
    //                 return {
    //                     text: item.name+' ('+item.id+')',
    //                     id: item.id
    //                 }
    //             })
    //         };
    //       },
    //       cache: true
    //     }
    //   });
            
});
</script>
@stop
