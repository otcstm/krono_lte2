@extends('adminlte::page')

@section('title', 'Search Maintenance Order')

@section('content')
<style>
    .sizeX{
        width: 200px;
    }
    .sizeA{
        width: 100px;
    }
</style>


<h1>Display Maintenance Order</h1>

<div class="panel panel-default panel-main">

    <div class="panel-body">
        <form action="{{route('mo.list')}}" method="POST">
            @csrf
            <h4><b>Search Maintenance Order</b></h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">ID</div>
                        <!-- <input type="text" style="position: relative; z-index: 8; width: 100%" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}" autofocus> -->
                        <div class="col-md-8">


                          <input type="text" class="form-control" id="inputid"  name="inputid" style="width: 100%; " value="{{ old('inputid') }}">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Type</div>
                        <div class="col-md-8">
                          <input type="text" class="form-control" id="inputType"  name="inputType" style="width: 100%; " value="{{ old('inputType') }}">
                          <!-- <select id="inputType" class="moTp form-control" name="inputType[]" multiple="multiple"></select> -->

                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Status</div>
                        <div class="col-md-8"><input type="text" class="form-control" id="inputStatus"  name="inputStatus" style="width: 100%; " value="{{ old('inputStatus') }}"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">Cost Center</div>
                        <!-- <input type="text" style="position: relative; z-index: 8; width: 100%" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}" autofocus> -->
                        <div class="col-md-8">
                          <input class="form-control" type="text" id="inputcc"  name="inputcc" style="width: 100%; " value="{{ old('inputcc') }}">
                          <!-- <select id="inputcc" class="moCS form-control" name="inputcc[]" multiple="multiple"></select> -->

                        </div>

                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Company Code</div>
                        <div class="col-md-8">
                          <input class="form-control" type="text" id="inputcocd"  name="inputcocd" style="width: 100%; " value="{{ old('inputcocd') }}">
                          <!-- <select id="inputcocd" class="moCC form-control" name="inputcocd[]" multiple="multiple"></select> -->

                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-4">Approver ID</div>
                        <div class="col-md-8"><input type="text" class="form-control" id="inputapprver"  name="inputapprver" style="width: 100%; " value="{{ old('inputapprver') }}"></div>
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
        <h4><b>List of Maintenance Order</b></h4>
        <br>
        <div class="table-responsive">
            <table id="tmoList" class="table table-bordered">
                <thead>
                    <tr>
                      <th>ID</th>
                      <th>Descr</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Cost Center</th>
                      <th>Company Code</th>
                      <th>Approver Id</th>
                      <th>Budget</th>
                      <th>Updated at</th>
                      <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($molists as $molist)
                    <tr>
                        <td>{{ $molist->id }}</td>
                        <td>{{ $molist->descr }}</td>
                        <td>{{ $molist->type }}</td>
                        <td>{{ $molist->status }}</td>
                        <td>{{ $molist->cost_center }}</td>
                        <td>{{ $molist->company_code }}</td>
                        <td>{{ $molist->approver_id }}</td>
                        <td>{{ $molist->budget }}</td>
                        <td>
                          @if( $molist->upd_dm == '')

                          @else
                          {{ date('d-m-Y', strtotime($molist->upd_dm)) }}
                          @endif
                        </td>
                        <td>
                          @if( $molist->created_at == '')

                          @else
                          {{ date('d-m-Y', strtotime($molist->created_at)) }}
                          @endif
                        </td>
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



    $('.moCS').select2({
        placeholder: '',
        minimumInputLength: 4,
        ajax: {
          url: '/admin/csearch',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                      text: item.cost_center,
                      id: item.cost_center                  }
                })
            };
          },
          cache: true
        }
      });
    $('.moTp').select2({
        placeholder: '',
        minimumInputLength: 3,
        ajax: {
          url: '/admin/tpsearch',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                      text: item.type,
                      id: item.type                  }
                })
            };
          },
          cache: true
        }
      });
    $('.moCC').select2({
        placeholder: '',
        minimumInputLength: 1,
        ajax: {
          url: '/admin/cocdsearch',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                      text: item.company_code,
                      id: item.company_code                  }
                })
            };
          },
          cache: true
        }
      });
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
        $('#tmoList').DataTable({
            dom: '<"html5buttons">Bfrtip',
            language: {
                    buttons: {
                        colvis : 'show / hide', // label button show / hide
                        colvisRestore: "Reset column" //lael untuk reset kolom ke default
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
