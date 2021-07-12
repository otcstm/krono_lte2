@extends('adminlte::page')

@section('title', 'Public Holiday Entitlement')

@section('content')


<h1>Public Holiday Entitlement</h1>

<div class="panel panel-default panel-main">
	<div class="panel panel-default">
    <div class="panel-heading"><strong>Search Public Holiday</strong></div>
    <div class="panel-body">
       <form action="{{route('dt.list')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-3">
                         <label for="inUserid" >User id </label>
                        </div>
                        <div class="col-md-9"><input type="text" id="fuserid"  name="fuserid" value="{{ old('fuserid') }}"></div>
                    </div>
                </div>
            </div>
            <div class="text-right">
              <br><button type="submit" name="searching" value="filter" class="btn-up">SEARCH</button>
            </div>
            <br>
        </form>

        <div class="line2"></div>
        <h4><b>List of Public Holiday</b></h4>
        <br>
        <div class="table-responsive">
            <table id="tphList" class="table table-bordered">
                <thead>
                    <tr>
                      <th>User id</th>
                      <th>Name</th>
                      <th>Public Holiday</th>
                      <th>Replacement Leave</th>
                      <th>Status</th>
                      <th>Created at</th>
                      <th>Updated at</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($ph_lists as $no=>$ph_ls)
                    <tr>
                        <td>{{ $ph_ls->user_id}}</td>
                        <td>{{ $ph_ls->usertbl->name}}</td>
                        <td>{{ $ph_ls->phdate->format('d/m/Y')}}</td>
                        <td>{{ $ph_ls->date->format('d/m/Y')}}</td>
                        <td>{{ $ph_ls->status}}</td>
                        <td>{{ $ph_ls->created_at}}</td>
                        <td>{{ $ph_ls->updated_at}}</td>
                        <td>
                          <form method="post" action="{{ route('dt.delete', [], false) }}" id="deln-{{$no}}">
                            @csrf
                            <button type="button" class="btn btn-np" id="edit-{{$no}}" title="Edit"
                              data-id="{{$ph_ls->id}}"
                              data-user_id="{{$ph_ls->user_id}}"
                              data-phd="{{$ph_ls->phdate->format('Y-m-d')}}"
                              data-repd="{{$ph_ls->date->format('Y-m-d')}}"
                              data-status="{{$ph_ls->status}}"
                              >
                              <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" id="btn-dell-{{$no}}" class="btn btn-np" title="Delete"
                              data-user_id="{{$ph_ls->user_id}}"
                              data-phd="{{$ph_ls->phdate->format('Y-m-d')}}"
                              data-repd="{{$ph_ls->date->format('Y-m-d')}}"
                              >
                              <i class="fas fa-trash-alt"></i>
                            </button>
                            <input type="hidden" name="inputid" value="{{$ph_ls->id}}">
                          </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
          </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading"><strong>Add Public Holiday</strong></div>
            <div class="panel-body">

      			<form action="{{ route('dt.store', [], false) }}" method="post" class="form-horizontal">
      			@csrf
      			<div class="row">
      				<div class="col-md-6">

      				<div class="row" style="margin-top: 15px;">
      					<div class="col-md-3">
      						<label for="inuserid" >User id </label>
      					</div>
      					<div class="col-md-9">
                  <input id="inuserid" type="text" name="inuserid" value="{{ old('inuserid') }}" required autofocus>
      					</div>
      				</div>
      				<div class="row" style="margin-top: 15px;">
      					<div class="col-md-3">
      						<label for="inputpho">Public Holiday</label>
      					</div>
      					<div class="col-md-9">
      						<input id="inputpho" type="date" name="inputpho" value="{{ old('inputpho') }}" required autofocus>
      					</div>
      				</div>
      				<div class="row" style="margin-top: 15px;">
      					<div class="col-md-3">
      						<label for="inrepd">Replacement Date</label>
      					</div>
      					<div class="col-md-9">
      						<input id="inrepd" type="date" name="inrepd" value="{{ old('inrepd') }}" required autofocus>
      					</div>
      				</div>
      				<div class="row" style="margin-top: 15px;">
      					<div class="col-md-3">
      						<label for="inputstat">Status</label>
      					</div>
      					<div class="col-md-9">
                  <select type="text" name="inputstat" id="inputstat" required autofocus>
                      <option value="ACTIVE">ACTIVE</option>
                      <option value="INACTIVE">INACTIVE</option>
                  </select>
      					</div>
      				</div>
      			</div>
      			</div>
          </div>
      			<div class="panel-footer">
      				<div class="text-right">
      					<button type="submit" class="btn btn-p btn-primary">SUBMIT</button>
      				</div>
      			</div>
      		</form>
      </div>
	</div>


<form action="{{ route('dt.edit') }}" method="POST" class="hidden" id="editn">
  @csrf
  <input type="text" id="editid" name="inputid" value="">
  <input name="inuid" id="edituid" required>
  <input type="date" name="inputphd" id="editphd"  value="" required autofocus>
  <input type="date" name="inputrepd" id="editrepd" value="" required autofocus>
  <input type="text" name="inputstatus" id="editstatus" value="" required autofocus>
</form>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
  $('#tphList').DataTable({
  "responsive": "true",
  // "order" : [[2, "asc"]],
  dom: '<"flext"lB>rtip',
  buttons: ['excel']
  });
});

for(i = 0; i<{{count($ph_lists)}}+1; i++){
 $("#edit-"+i).on("click", edit(i));
 $("#btn-dell-"+i).on("click", deleteid(i));
}


//function edit
function edit(i){
return function(){
  var id = $("#edit-"+i).data('id');
  var user_id = $("#edit-"+i).data('user_id');
  var phd = $("#edit-"+i).data('phd');
  var repd = $("#edit-"+i).data('repd')
  var status = $("#edit-"+i).data('status')
  var html =
          "<div class='row' style='margin-top: 5px;'>"+
          "<div class='col-md-4'>"+
            "<p>User ID: </p>"+
          "</div>"+
          "<div class='col-md-8'>"+
            "<input type='text' id='user_ids' class='check-0' value='"+user_id+"' style='width: 100%' disabled>"+
          "</div>"+
        "</div>"+
        "<div class='row' style='margin-top: 5px;'>"+
        "<div class='col-md-4'>"+
          "<p>Public Holiday: </p>"+
        "</div>"+
        "<div class='col-md-8'>"+
          "<input type='date' id='phds' class='check-1' value='"+phd+"' style='width: 100%' >"+
        "</div>"+
      "</div>"+
            "<div class='row' style='margin-top: 5px;'>"+
          "<div class='col-md-4'>"+
            "<p>Replacement Date: </p>"+
          "</div>"+
          "<div class='col-md-8'>"+
            "<input type='date' id='repds' class='check-2' value='"+repd+"' style='width: 100%' >"+
          "</div>"+
        "</div>"+
            "<div class='row' style='margin-top: 5px;'>"+
          "<div class='col-md-4'>"+
            "<p>Status: </p>"+
          "</div>"+
          "<div class='col-md-8'>"+
            "<select id='statuss' class='check-3' value='"+status+"' style='width: 100%'>";

                if(status=='ACTIVE'){
                  html = html + "<option value='ACTIVE' selected>ACTIVE</option>"+
                  "<option value='INACTIVE'>INACTIVE</option>";
                }else{
                  html = html + "<option value='INACTIVE' selected>INACTIVE</option>"+
                  "<option value='ACTIVE'>ACTIVE</option>";
                }

          html = html + "</select>"+

          "</div>"+
        "</div>";
  var submit = true;
  Swal.fire({
    title: 'Edit Public Holiday',
    html: "<div class='text-left'>"+html+
      "</div>",
    showCancelButton: true,
    customClass:'test4',
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'SAVE',
    cancelButtonText: 'CANCEL'
    }).then((result) => {
    if (result.value) {
      for(i = 0; i<4; i++){
        if($('.check-'+i).get(0).checkValidity()==false){
          submit = false;
        }
      }
      if(submit){
        $('#editid').val(id);
        $('#edituid').val($("#user_ids").val());
        $('#editphd').val($("#phds").val());
        $('#editrepd').val($("#repds").val());
        $('#editstatus').val($("#statuss").val());
        $('#editn').submit();
      }else{
        Swal.fire({
          title: "Incomplete Form",
          html: "Please fill in all input fields before saving",
          confirmButtonText: 'OK'
        }).then((result) => {
          edit(i);
        });
      }
    }
  })
}
};
// function delete
function deleteid(i){
	return function(){
		var user_id = $("#btn-dell-"+i).data('user_id');
		var phd = $("#btn-dell-"+i).data('phd');
		var repd = $("#btn-dell-"+i).data('repd');
		Swal.fire({
			title: 'Are you sure?',
			html: "<div class='text-left'>Delete item with details below?<br><br>"+
					"User ID: "+user_id+"<br>"+
					"Public Holiday: "+phd+"<br>"+
					"Replacement date: "+repd+"<br>",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'DELETE',
			cancelButtonText: 'CANCEL'
			}).then((result) => {
			if (result.value) {
				$("#deln-"+i).submit();
			}
		})
	}
}
   @if(session()->has('feedback'))
    Swal.fire({
			icon: "{{session()->get('a_icon')}}",
			html: "{{session()->get('a_text')}}",
			confirmButtonText: 'OK'
    })
@endif
</script>
@stop
