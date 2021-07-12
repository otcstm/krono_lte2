@extends('adminlte::page')

@section('title', 'Unsuccessful Email Delivery')

@section('content')

<h1>Unsuccessful Email Delivery</h1>

<div class="panel panel-default panel-main">

    <div class="panel-body">
       <form action="{{route('invalidemail.list')}}" method="POST">
            @csrf
            <h4><b>Search Unsuccessful Email Delivery</b></h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">User id</div>
                        <div class="col-md-9"><input type="text" class="form-control"  id="inUserid"  name="inUserid" style="width: 100%; " value="{{ old('inUserid') }}"></div>
                    </div>
                    <div class="row" style="margin-top: 5px">
                        <div class="col-md-3">Approver id</div>
                        <div class="col-md-9"><input type="text" class="form-control" id="inAppid"  name="inAppid" style="width: 100%; " value="{{ old('inAppid') }}"></div>
                    </div>
                </div>
                <div class="col-md-6">
									<div class="row" style="margin-top: 5px">
											<div class="col-md-3">Verifier id</div>
											<div class="col-md-9"><input type="text" class="form-control" id="inVerid"  name="inVerid" style="width: 100%; " value="{{ old('inVerid') }}"></div>
									</div>
									<div class="row" style="margin-top: 5px">
											<div class="col-md-3">Refno(OT)</div>
											<div class="col-md-9"><input type="text" class="form-control" id="inRefno"  name="inRefno" style="width: 100%; " value="{{ old('inRefno') }}"></div>
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
        <h4><b>List of Unsuccessful Email Delivery</b></h4>
        <br>
        <div class="table-responsive">
            <table id="temailList" class="table table-bordered">
                <thead>
                    <tr>
                      <!-- <th>No</th> -->
                      <th>Refno</th>
                      <th>User Id</th>
                      <th>User Email</th>
                      <th>Verifier Id</th>
                      <th>Verifier Email</th>
                      <th>Approver Id</th>
                      <th>Approver Email</th>
                      <th>Submitted at</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($email_lists as $no=>$email_ls)
                    <tr>
                        <!-- <td>{{++$no}}</td> -->
                        <td>{{ $email_ls->refno}}</td>
                        <td>{{ $email_ls->user_id}}</td>
                        <td>{{ $email_ls->user_email}}</td>
                        <td>{{ $email_ls->verifier_id}}</td>
                        <td>{{ $email_ls->verifier_email}}</td>
                        <td>{{ $email_ls->approver_id}}</td>
                        <td>{{ $email_ls->approver_email}}</td>
                        <td>{{ $email_ls->created_at}}</td>
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
	$(document).ready(function() {
  $('#temailList').DataTable({
  "responsive": "true",
  "order" : [[7, "asc"]],
  dom: '<"flext"lB>rtip',
  buttons: [
  'excel'
  ]

  });
  });
  </script>
  @stop
