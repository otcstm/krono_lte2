@extends('adminlte::page')

@section('title', 'Verifier Management')

@section('content')
<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-heading">  
    Group Verifier
    </div><!--- .panel-heading --->
    <div class="panel-body">  
@if (session()->has('sysmsg_type'))
        <div class="alert alert-{{ session()->get('sysmsg_class') }} alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong><i class="{{ session()->get('sysmsg_icon') }} "></i> {{ session()->get('sysmsg_text') }}</strong>
        </div>
@endif
<form class="form-horizontal" action="{{ route('verifier.updateGroup',[],false) }}" method="post">
@csrf 
<input name="gid" type="hidden" value="{{ $groupData->id }}">
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupcode">Group Code:</label>
    <div class="col-sm-4">
      <input name="groupcode" type="text" readonly="readonly" class="form-control" id="groupcode" placeholder="Enter group code" required
      value="{{ $groupData->group_code }}" maxlength="16">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="groupname">Group Name:</label>
    <div class="col-sm-4">
      <input name="groupname" type="text" class="form-control" id="groupname" placeholder="Enter group name" required
       value="{{ $groupData->group_name }}" maxlength="150">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="verifierid">Verifier Name:</label>
    <div class="col-sm-4">
      <select id="selectVerifierId" class="verifierListId form-control" name="verifierId" required>  
        @if(isset($verifierData->name))    
      <option selected="selected" value="{{ $groupData->verifier_id }}">{{ $verifierData->name }} ({{ $groupData->verifier_id }})</option>  
        @endif    
      </select>
      <div class="checkbox">
        <!-- Trigger the modal with a href  : todisable outside close click-> data-backdrop="static" -->
        <a href="#" id="btnModalAdvSearch" data-toggle="modal" data-target="#modalAdvSearch" 
        data-keyboard="false" class="btn btn-primary btn-xs">
        <i class="glyphicon glyphicon-search"></i> Advance Search</a>
    </div>
  
    </div>    
    </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary btn-outline">Update</button>
      <a href="{{ route('verifier.listGroup', [], false) }}"  class="btn btn-primary btn-outline">Back</a>
    </div>
  </div>
</form> 
    </div><!--- .panel-body --->
</div><!--- .panel panel-default --->

</div><!--- .row --->
</div><!--- .col-md-12 --->

<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-heading">  
    Group Members
    </div><!--- .panel-heading --->
    <div class="panel-body">  

    <div class="table-responsive">
    <table id="subord_group" class="table">
    <thead>
      <tr>
      <th></th>
      <th>Staff No</th>
      <th>Name</th>
      <th>Empsgroup</th>
      <th>Action</th>
      </tr>
    </thead>
    <tbody>
@php($rowcount = 0)    
@foreach($groupMember as $groupMember_row)
    <tr>
      <td>{{ $rowcount }}</td>
      <td>{{ $groupMember_row->staff_no }}</td>
      <td>{{ $groupMember_row->name }}</td>
      <td>{{ $groupMember_row->userRecordLatest->empsgroup }}</td>
      <td>
      <form action="{{ route('verifier.removeUser', [], false) }}" method="post"> 
      @csrf             
      <input type="hidden" name="user_id" value="{{ $groupMember_row->id }}">
<input type="hidden" name="group_id" value="{{ $groupData->id }}">
<button type="submit" class="btn btn-np" alt="Remove">
<i class="fas fa-user-minus"></i>
</button>
</form>      
      </td>
    </tr>
@php($rowcount++)     
@endforeach  
    </tbody>
    </table>
    </div>

    </div><!--- .panel-body --->
</div><!--- .panel panel-default --->

</div><!--- .row --->
</div><!--- .col-md-12 --->

<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-heading">  
    Subordinates without group
    </div><!--- .panel-heading --->
    <div class="panel-body">  

<div class="table-responsive">  
<table id="subord_nogroup" class="table">
    <thead>
      <tr>
      <th></th>
      <th>Staff No</th>
      <th>Name</th>
      <th>Empsgroup</th>
      <th>Action</th>
      </tr>
    </thead>
    <tbody>
@php($rowcount = 0)    
@foreach($freeMember as $freeMember_row)
@php($rowcount++)   
    <tr>
      <td>{{ $rowcount }}</td>
      <td>{{ $freeMember_row->staff_no }}</td>
      <td>{{ $freeMember_row->name }}</td>
      <td>{{ $freeMember_row->userRecordLatest->empsgroup }}</td>
      <td>
      <form action="{{ route('verifier.addUser', [], false) }}" method="post"> 
      @csrf             
      <input type="hidden" name="user_id" value="{{ $freeMember_row->id }}">
    <input type="hidden" name="group_id" value="{{ $groupData->id }}">
<button type="submit" class="btn btn-np" alt="Add">
<i class="fas fa-user-plus"></i></button>
</form>
      
      </td>
    </tr>  
@endforeach  
    </tbody>
    </table>
    </div>
    </div><!--- .panel-body --->
</div><!--- .panel panel-default --->

</div><!--- .row --->
</div><!--- .col-md-12 --->


<!-- Modal -->
<div id="modalAdvSearch" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Advance Search</h4>
      </div>
      <div id="modal-body" class="modal-body">
      <div id="advSearchAlertMsg"></div>

<div id="resultAdvSearch" class="table-responsive"></div>    
<fieldset id="formAdvSearch">
<legend>Search Creteria</legend>
<form class="form-horizontal" method="post" action="{{ route('verifier.advSearchSubord',[],false) }}">      

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_name">Name of Employee</label>  
  <div class="col-md-6">
  <input id="empl_name" name="empl_name" type="text" placeholder="Name of Employee" 
  class="form-control input-md">    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_persno">Personnel Number</label>  
  <div class="col-md-6">
  <input id="empl_persno" name="empl_persno" type="text" placeholder="SAP Personnel Number" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_staffno">Staff No</label>  
  <div class="col-md-6">
  <input id="empl_staffno" name="empl_staffno" type="text" placeholder="Staff No" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_position">Position</label>  
  <div class="col-md-6">
  <input id="empl_position" name="empl_position" type="text" placeholder="Position" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_compcode">Company Code</label>  
  <div class="col-md-6">
  <input id="empl_compcode" name="empl_compcode" type="text" placeholder="Company Code" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_costcenter">Cost Center</label>  
  <div class="col-md-6">
  <input id="empl_costcenter" name="empl_costcenter" type="text" placeholder="ATAC07" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_personalarea">Personal Area</label>  
  <div class="col-md-6">
  <input id="empl_personalarea" name="empl_personalarea" type="text" placeholder="Personal Area" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_personalsubarea">Personal Subarea</label>  
  <div class="col-md-6">
  <input id="empl_personalsubarea" name="empl_personalsubarea" type="text" placeholder="Personal Subarea" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_subgroup">Employee Subgroup</label>  
  <div class="col-md-6">
  <input id="empl_subgroup" name="empl_subgroup" type="text" placeholder="Employee Subgroup" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_email">Email</label>  
  <div class="col-md-6">
  <input id="empl_email" name="empl_email" type="text" placeholder="Email" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_mobilenumber">Mobile Number</label>  
  <div class="col-md-6">
  <input id="empl_mobilenumber" name="empl_mobilenumber" type="text" placeholder="Mobile Number" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="empl_officenumber">Office Number</label>  
  <div class="col-md-6">
  <input id="empl_officenumber" name="empl_officenumber" type="text" placeholder="Office Number" class="form-control input-md">
    
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label"></label>  
  <div class="col-md-6">
  <button id="btnAdvSearch" name="btnAdvSearch" type="button" onclick="advSearchSubmit()"
  class="btn btn-primary btn-outline">Search</button>
  <!-- <input id="btnAdvSearch" name="btnAdvSearch" type="button" onclick="advSearchSubmit()" 
  class="btn btn-primary" value="Search"> -->
  
  <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
  </div>
</div>
</form>
</fieldset>

      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>

@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {

    var t = $('#subord_nogroup').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    } );
 
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

var t = $('#subord_group').DataTable( {
    "columnDefs": [ {
        "searchable": false,
        "orderable": false,
        "targets": 0
    } ],
    "order": [[ 1, 'asc' ]]
} );

t.on( 'order.dt search.dt', function () {
    t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
} ).draw();

    $('.verifierListId').select2({
        placeholder: 'Type a name',
        minimumInputLength: 3,
        ajax: {
          url: '/admin/verifier/subordSearch',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.name+' ('+item.id+')',
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
      });

} );

//select verifier id to selection
function slctVerifier(vid,vname){
  //alert(vname+' ('+vid+')');

  //remove existing value 
  $('#selectVerifierId').children('option:not()').remove();

  //method 1
  $('#selectVerifierId').append(
     $('<option>').val(vid).text(vname+' ('+vid+')')
     );

  //method 2
  //$('#selectVerifierId').append(new Option(vname, vid));
  
  //method 3
  // var o = new Option(vname, vid);
  // /// jquerify the DOM object 'o' so we can use the html method
  // $(o).html(vname);
  // $("#selectVerifierId").append(o);

  $('#modalAdvSearch').modal('toggle');
}

function advSearchSubmit(){

var dataSearch = $('#formAdvSearch').serializeArray();

var results = dataSearch.map(function(item) {
  return item.value;
}),
checkInput = 0;

$.each(results,function(){
  checkInput+=parseFloat(this.length) || 0;
});
//console.log(checkInput);
if(checkInput==0){
  $("#advSearchAlertMsg").scroll();
  $("#advSearchAlertMsg").html("<div class='alert alert-warning'>Atleast has 1 input (advSearchSubmit)</div>");  
}
else{
  $.get("{{ route('verifier.ajaxAdvSearchSubord') }}",{dataSearch}, 
  function(data){
    //alert( "Load was performed." );
    console.log(data);   
    var imgProfile = '<img class="profile-user-img img-responsive img-circle" src="/vendor/images/useravatar.png" alt="User profile picture">';
    var html = '';
        html += '<fieldset><legend>Result Search';
        html += '<button class="btn btn-primary btn-outline pull-right" onclick="goToSearchForm()">Modify search</button></legend>';
    //create table
    html += '<input id="myInput" type="text" placeholder="Search.."><br><br>';
    html += '<div id="divOut" style="border: 1px solid #DDDDDD; max-height: 60vh; overflow-y: scroll;  overflow-x: hidden">';
    
    html += '<table id="resultAdvSearchTbl" class="table"><thead>';
    html += '<th>Creteria</th>';
    // html += '<th>Persno</th>';
    // html += '<th>Staffno</th>';
    // html += '<th>Sub Group</th>';
    // html += '<th>Company Code</th>';
    // html += '<th>Personal Area</th>';
    // html += '<th>Personal Subrea</th>';
    // html += '<th>Email</th>';
    //html += '<th>Action</th>';
    html += '</thead><tbody>'; 

      if(data.length > 0)
      {
      for(var count = 0; count < data.length; count++)
      {          
        html += '<tr>';
        // html += '<td>'+data[count].name+'</td>';   
        // html += '<td>'+data[count].id+'</td>';
        // html += '<td>'+data[count].staff_no+'</td>';
        // html += '<td>'+data[count].empsgroup+'</td>';
        // html += '<td>'+data[count].company_id+'</td>';
        // html += '<td>'+data[count].persarea+'</td>';
        // html += '<td>'+data[count].perssubarea+'</td>';
        // html += '<td>'+data[count].email+'</td>';
        html += 
          "<td>"+
              "<div style='display: flex; align-items: center; flex-wrap: wrap; width: 95%; margin-left: 3%' padding: 15px>"+
                  "<div class='w-10 text-center'><img src='/user/image/"+data[count].staffno.replace(' ','')+"' class='approval-search-img'></div>"+
                  "<div class='w-30 m-15'>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Name<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'>: </span><b>"+data[count].name+"</b></div>"+
                      "</div>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Personnel No<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'><span class='dm'>: </span></span><b>"+data[count].persno+"</b></div>"+
                      "</div>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Staff No<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'>: </span><b>"+data[count].staffno+"</b></div>"+
                      "</div>"+
                  "</div>"+
                  "<div class='w-30 m-15'>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Company Code<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'>: </span><b>"+data[count].companycode+"</b></div>"+
                      "</div>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Cost Center<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'>: </span><b>"+data[count].costcenter+"</b></div>"+
                      "</div>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Personnel Area<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'>: </span><b>"+data[count].persarea+"</b></div>"+
                      "</div>"+
                  "</div>"+
                  "<div class='w-30 m-15'>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Employee Subgroup<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'>: </span><b>"+data[count].empsubgroup+"</b></div>"+
                      "</div>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Email<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'>: </span><b>"+data[count].email+"</b></div>"+
                      "</div>"+
                      "<div class='approval-search-item'>"+
                          "<div class='w-30'>Mobile No<span class='dmx'>:</span></div>"+
                          "<div class='w-70'><span class='dm'>: </span><b>"+data[count].mobile+"</b></div>"+
                      "</div>"+
                  "</div>"+
                    '<div class="w-10 text-center"><a class="btn btn-np" onclick="slctVerifier('+data[count].persno+',\''+data[count].name+'\')"><i class="fas fa-user-plus"></i> Add</a></div>'+
              "</div>"+
          "</td>";
        //html += '<td><a class="btn btn-np" onclick="slctVerifier('+data[count].persno+',\''+data[count].name+'\')"><i class="fas fa-user-plus"></i></a></td>';
        html += '<tr>';

      }
    }
      else
      {
      //html += '<tr><td colspan="9">No Data Found</td></tr>';
      };
      html += '</tbody></table>';  
      html += '</div>';
      html += '</fieldset><br /><br />';

    $("#resultAdvSearch").html(html);
    $("#advSearchAlertMsg").html("<div class='alert alert-success'>Success fetch "+data.length+" records</div>");
    $('#advSearchAlertMsg').fadeIn('slow'); 
    if(data){
    // $('#resultAdvSearchTbl').DataTable({
    //   "ordering": false
    // });
    }      
    $("#resultAdvSearch").css("margin-bottom", "10px");
    $('html').scrollTop(0);
    $('#modalAdvSearch').animate({ scrollTop: -10 }, 1000);     
    setTimeout(function() { 
                  $('#advSearchAlertMsg').fadeOut('slow'); 
              }, 3000);  

$("#myInput").on("keyup", function() {
  var value = $(this).val().toLowerCase();
  $("#resultAdvSearchTbl tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  }).css('color','red');
});

  })//.get
}; //if checkInput

} //advSearchSubmit

//select verifier id to selection
function slctVerifier(vid,vname){
//alert(vname+' ('+vid+')');

//remove existing value 
$('#selectVerifierId').children('option:not()').remove();

//method 1
$('#selectVerifierId').append(
   $('<option>').val(vid).text(vname+' ('+vid+')')
   );

//method 2
//$('#selectVerifierId').append(new Option(vname, vid));

//method 3
// var o = new Option(vname, vid);
// /// jquerify the DOM object 'o' so we can use the html method
// $(o).html(vname);
// $("#selectVerifierId").append(o);

$('#modalAdvSearch').modal('toggle');
}


function goToSearchForm(){  
//$('#formAdvSearch').animate({ scrollTop: -10 }, 1000);  
$('#modalAdvSearch').animate({
      scrollTop: $('#formAdvSearch').offset().top -10
  }, 'slow');
$('#empl_name').focus();
  
}
</script>
@stop