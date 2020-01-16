@extends('adminlte::page')

@section('title', 'Verifier')

@section('content')
     <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Search Staff</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              <form action="{{ route('verifier.viewuser') }}" method="POST">
                @csrf
                @method('PUT')
              <select class="itemName form-control" name=userId></select>
              </div>
              </form>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
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
        ajax: {
          url: '/verifier/staff/persno',
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
</script>
@stop