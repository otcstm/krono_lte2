@extends('adminlte::page')

@section('title', 'Search Project')

@section('content')
<style>
    .sizeX{
        width: 200px;
    }
    .sizeA{
        width: 100px;
    }
</style>


<h1>Display Project</h1>

<div class="panel panel-default panel-main">

    <div class="panel-body">
       <form action="{{route('project.list')}}" method="POST">
            @csrf
            <h4><b>Search Project</b></h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">Project No</div>
                        <!-- <input type="text" style="position: relative; z-index: 8; width: 100%" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}" autofocus> -->
                        <div class="col-md-8"><input type="text"  id="inputpno"  name="inputpno" style="width: 100%; " value="{{ old('inputpno') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Status</div>
                        <div class="col-md-8"><input type="text" id="inputStatus"  name="inputStatus" style="width: 100%; " value="{{ old('inputStatus') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Type</div>
                        <div class="col-md-8"><input type="text" id="inputType"  name="inputType" style="width: 100%; " value="{{ old('inputType') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Cost Center</div>
                        <div class="col-md-8"><input type="text" id="inputcc"  name="inputcc" style="width: 100%; " value="{{ old('inputcc') }}"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">Company Code</div>
                        <!-- <input type="text" style="position: relative; z-index: 8; width: 100%" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}" autofocus> -->
                        <div class="col-md-8"><input type="text" id="inputcocd"  name="inputcocd" style="width: 100%; " value="{{ old('inputcocd') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Network Header</div>
                        <div class="col-md-8"><input type="text" id="inputNetheader"  name="inputNetheader" style="width: 100%; " value="{{ old('inputNetheader') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Network Act No</div>
                        <div class="col-md-8"><input type="text" id="inputActno"  name="inputActno" style="width: 100%; " value="{{ old('inputActno') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Approver Id</div>
                        <div class="col-md-8"><input type="text" id="inputApprver"  name="inputApprver" style="width: 100%; " value="{{ old('inputApprver') }}"></div>
                    </div>
                </div>
            </div>

            <div class="text-right">
              <br>
                <button type="submit" name="searching" value="filter" class="btn-up">SEARCH</button>
            </div>
            <br>
        </form>

        <div class="line2"></div>
        <h4><b>List of Project</b></h4>
        <br>
        <div class="table-responsive">
            <table id="tprojectList" class="table table-bordered">
                <thead>
                    <tr>
                      <th>Project No</th>
                      <th>Descr</th>
                      <th>Status</th>
                      <th>Type</th>
                      <th>Cost Center</th>
                      <th>Company Code</th>
                      <th>Network Header</th>
                      <th>Network Headerdescr</th>
                      <th>Network Act No</th>
                      <th>Network Act Descr</th>
                      <th>Approver Id</th>
                      <th>Budget</th>
                      <th>Updated at</th>
                      <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projectlists as $plist)
                    <tr>
                        <td>{{ $plist->project_no }}</td>
                        <td>{{ $plist->descr }}</td>
                        <td>{{ $plist->status }}</td>
                        <td>{{ $plist->type }}</td>
                        <td>{{ $plist->cost_center }}</td>
                        <td>{{ $plist->company_code }}</td>
                        <td>{{ $plist->network_header}}</td>
                        <td>{{ $plist->network_headerdescr}}</td>
                        <td>{{ $plist->network_act_no}}</td>
                        <td>{{ $plist->network_act_descr}}</td>
                        <td>{{ $plist->approver_id}}</td>
                        <td>{{ $plist->budget}}</td>
                        <td>{{ date('d-m-Y', strtotime($plist->upd_dm)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($plist->created_at)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            </div>
            </div>


  @stop

  @section('js')
  <script type="text/javascript">
    /*$(document).ready(function() {
      $('#tmoList').DataTable({
              "responsive": "true",
              "order" : [[0, "asc"]],
              dom: '<"flext"lB>rtip',
              buttons: [
              'excel'
              ]
      });
    });*/

    $(document).ready(function() {
        $('#tprojectList').DataTable({
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

  /*$(document).ready(function() {
  $('#tStaffList').DataTable({
  "responsive": "true",
  "order" : [[0, "desc"]],
  "columns": [
  null,
  null,
  null,
  null
  @if($auth ?? '')
  @else
  ,null
  @endif
  ,{ "width": "5%" }
  ]
  });
  });*/

  @if(session()->has('feedback'))
  Swal.fire({
  title: "{{session()->get('feedback_title')}}",
  html: "{{session()->get('feedback_text')}}",
  confirmButtonText: 'DONE'
  })
  @endif

  </script>
  @stop
