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

    

    </div><!--- .panel-body --->
</div><!--- .panel panel-default --->

</div><!--- .row --->
</div><!--- .col-md-12 --->

<div class="row">
<div class="col-md-12">

<div class="panel panel-default bg-red color-palette">
    <div class="panel-heading">  
    Subordinates without group
    </div><!--- .panel-heading --->
    <div class="panel-body">  

    

    </div><!--- .panel-body --->
</div><!--- .panel panel-default --->

</div><!--- .row --->
</div><!--- .col-md-12 --->


@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    
    $('#verifierList').DataTable({
    });

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
</script>
@stop