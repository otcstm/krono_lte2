@extends('adminlte::page')

@section('title', 'Payment Schedule')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">Payment Schedule</div>
  <div class="panel-body">
    @if (session()->has('alert'))
    <div class="alert alert-{{ session()->get('a_type') }} alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ session()->get('alert') }}</strong>
    </div>
    @endif
    <form action="{{ route('paymentsc.store', [], false) }}" method="post" class="form-horizontal">
      @csrf
      <div class="form-group has-feedback {{ $errors->has('last_sub_date') ? 'has-error' : '' }}">
        <label for="last_sub_date" class="control-label col-sm-2">Plan Month</label>
        <div class="col-sm-10">
          <input id="last_sub_date" type="date" name="last_sub_date" value="{{ old('last_sub_date', $curdate) }}" required autofocus>
        </div>
        @if ($errors->has('last_sub_date'))
            <span class="help-block">
                <strong>{{ $errors->first('last_sub_date') }}</strong>
            </span>
        @endif
      </div>
			<div class="form-group has-feedback {{ $errors->has('last_approval_date') ? 'has-error' : '' }}">
        <label for="last_approval_date" class="control-label col-sm-2">Last Approval Date</label>
        <div class="col-sm-10">
          <input id="last_approval_date" type="date" name="last_approval_date" value="{{ old('last_approval_date', $curdate) }}" required autofocus>
        </div>
        @if ($errors->has('last_approval_date'))
            <span class="help-block">
                <strong>{{ $errors->first('last_approval_date') }}</strong>
            </span>
        @endif
      </div>
			<div class="form-group has-feedback {{ $errors->has('interface_date') ? 'has-error' : '' }}">
        <label for="interface_date" class="control-label col-sm-2">Interface Date</label>
        <div class="col-sm-10">
          <input id="interface_date" type="date" name="interface_date" value="{{ old('interface_date', $curdate) }}" required autofocus>
        </div>
        @if ($errors->has('interface_date'))
            <span class="help-block">
                <strong>{{ $errors->first('interface_date') }}</strong>
            </span>
        @endif
      </div>
			<div class="form-group has-feedback {{ $errors->has('interface_date') ? 'has-error' : '' }}">
        <label for="payment_date" class="control-label col-sm-2">Payment Date</label>
        <div class="col-sm-10">
          <input id="payment_date" type="date" name="payment_date" value="{{ old('payment_date', $curdate) }}" required autofocus>
        </div>
        @if ($errors->has('payment_date'))
            <span class="help-block">
                <strong>{{ $errors->first('payment_date') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group text-center">

        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Payment Schedule/div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="tpayment_sche" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>last_sub_date</th>
           <th>last_approval_date</th>
           <th>interface_date</th>
           <th>payment_date</th>
           <th>created_by</th>
         </tr>
       </thead>
       <tbody>
         @foreach($ps_list as $ps)
         <tr>
           <td>{{ $ps->last_sub_date->format('M Y') }}</td>
					 <td>{{ $ps->last_approval_date->format('M Y') }}</td>
					 <td>{{ $ps->interface_date->format('M Y') }}</td>
					 <td>{{ $ps->payment_date->format('M Y') }}</td>
           <td>{{ $ap->created_by }}</td>
           <td>
             <form method="post" action="{{ route('paymentsc.delete', [], false) }}" onsubmit='return confirm("Confirm delete?")'>
               @csrf
               <a href="{{ route('paymentsc.edit', ['id' => $ps->id], false) }}"><button type="button" class="btn btn-xs btn-warning" title="Edit"><i class="fas fa-pencil-alt"></i></button></a>
               <button type="submit" class="btn btn-xs btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
               <input type="hidden" name="id" value="{{ $ps->id }}" />
             </form>

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

$(document).ready(function() {

  $('#tpayment_sche').DataTable({
    "responsive": "true"
  });
} );

</script>
@stop
