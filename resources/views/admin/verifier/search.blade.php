@extends('adminlte::page')

@section('title', 'Verifier')

@section('content')
     <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Search Staff</h3>
            </div>
            <!-- /.box-header -->
              <form action="{{ route('verifier.staff') }}" method="POST">
                @csrf
                @method('POST')
            <div class="box-body">

              <select class="userId form-control" name=userId></select>
             
            </div>
            <!-- /.box-body -->
              <div class="box-footer clearfix">
              <button type="submit" class="btn btn-primary btn-outline">Get Staff</button>
            </div>


              
          </form>
           <!--  <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div> -->
            <!-- /.box-footer -->
            
          </div>
          @stop

@section('js')
<script type="text/javascript">
      $('.userId').select2({
        placeholder: 'Select an item',
        minimumInputLength: 3,
        ajax: {
          url: '/admin/verifier/staffsearch',
          dataType: 'json',
          minLength: 3,
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
</script>
@stop