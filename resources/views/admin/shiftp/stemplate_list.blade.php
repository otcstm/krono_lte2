@extends('adminlte::page')

@section('title', 'Shift Template Management')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">Shift Template / Work Pattern</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Code</th>
           <th>Description</th>
           <th>Total Days</th>
           <th>Total Hours</th>
           <th>Is Weekly</th>
           <th>Company</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($p_list as $ap)
         <tr>
           <td>{{ $ap->code }}</td>
           <td>{{ $ap->description }}</td>
           <td>{{ $ap->days_count }}</td>
           <td>{{ $ap->total_hours }}</td>
           <td>
             @if($ap->is_weekly == true)
             &#10004;
             @else
             &#10008;
             @endif
           </td>
           <td style="text-align: left !important;">            
        @if($ap->companies ?? '')
          @foreach($ap->companies as $acompany)           
            {{$acompany->company_descr}} ({{$acompany->id}})<br />
          @endforeach
        @else
            No Company Assigned
        @endif       
            </td>
           <td>
             <form method="post" action="{{ route('sp.delete', [], false) }}" onsubmit='return confirm("Confirm delete?")'>
               @csrf
               <a href="{{ route('sp.view', ['id' => $ap->id], false) }}"><button type="button" class="btn btn-xs btn-warning" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
               {{-- <button type="submit" class="btn btn-xs btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button> --}}
               <input type="hidden" name="id" value="{{ $ap->id }}" />
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
  <div class="panel-heading">Add new shift pattern</div>
  <div class="panel-body">

    <form action="{{ route('sp.add', [], false) }}" method="post">
      @csrf
      
      <div class="form-group has-feedback {{ $errors->has('code') ? 'has-error' : '' }}">
        <label for="code">Code</label>
        <input id="code" type="text" name="code" class="form-control" value="{{ old('code') }}"
               placeholder="work-day code" required autofocus maxlength="10">
        @if ($errors->has('code'))
            <span class="help-block">
                <strong>{{ $errors->first('code') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('description') ? 'has-error' : '' }}">
        <label for="description">Description</label>
        <input id="description" type="text" name="description" class="form-control" value="{{ old('description') }}"
               placeholder="work-day description" required maxlength="200">
        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group has-feedback {{ $errors->has('is_weekly') ? 'has-error' : '' }}">
        <input id="is_weekly" type="checkbox" name="is_weekly" value="{{ old('is_weekly') }}">
        <label for="is_weekly">Normal Schedule? (Check if normal/Uncheck if shift)</label>
        @if ($errors->has('is_weekly'))
            <span class="help-block">
                <strong>{{ $errors->first('is_weekly') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group has-feedback {{ $errors->has('compcode') ? 'has-error' : '' }}">
        <label>Company</label>  
        
        <div class="row">
        @if($comp_list ?? '')
        @foreach($comp_list as $alistcompany)   
        <div class="col-sm-4">
          <label class="btn btn-xs"> 
          <input id="compcb" type="checkbox" name="compcb[]" value="{{$alistcompany->id}}">
          {{$alistcompany->id}}-{{$alistcompany->company_descr}}
        </label>
        </div>
        @endforeach
        @endif
        <br />
        <div class="col-sm-12">
          <label class="btn btn-xs btn-outline">
        <input id="checkall" type="checkbox" onClick="CheckUncheckAll();" value="Check all">
        Check All Company</label>
        </div>
      </div>

        {{-- {{$company->id}} {{$company->id}}-{{$company->company_descr}}     --}}
        {{-- <select class="selectReport form-control" name="fcompany[]" multiple="multiple">
          @if($companies ?? '')
              @foreach($companies as $no=>$company)
        <option value="{{$company->id}}">{{$company->id}}-{{$company->company_descr}}</option>
              @endforeach
          @endif
        </select> --}}
        @if ($errors->has('compcode'))
            <span class="help-block">
                <strong>{{ $errors->first('code') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>

@stop

@section('js')
<script type="text/javascript">

$(document).ready(function() {

  $('#tPunchHIstory').DataTable({
    //dom: '<"html5buttons">Bfrtip',        
        //dom: 'lBfrtip',
        dom: '<"flext"lf><"flext"B>rtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ],
        "responsive": "true"   
  });
  
  $('.selectReport').select2({
      closeOnSelect: false
    });

});

function CheckUncheckAll(){
   var  selectAllCheckbox=document.getElementById("checkall");
   if(selectAllCheckbox.checked==true){
    var checkboxes =  document.getElementsByName("compcb[]");
     for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = true;
     }
    }else {
     var checkboxes =  document.getElementsByName("compcb[]");
     for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = false;
     }
    }
   };

</script>
@stop
