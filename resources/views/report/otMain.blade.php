@extends('adminlte::page')
@section('css')
@stop
@section('title', 'User Report Mainpage')
@section('content')

<h1>User Report</h1>

<div class="panel panel-default panel-main"> 
<div class="row row-eq-height"> 

    <div class="col-md-3 col-sm-6 col-xs-12 ">
      <a href="{{ route('rep.viewOTd', [], false) }}">
      <div class="box box-warning box-solid">
      <div class="box-body">
      <div class="media">
        <div class="media-left">
          <img src="vendor/ot-assets/usradm_reports.png" class="media-object" style="width:50px">
        </div>
        <div class="media-body">
          <h4 class="media-heading">Overtime Details</h4>
          <p>Report</p>
        </div>
      </div>
      </div><!-- /.box-body -->
      {{-- <div class="box-header bg-yellow-active color-palette">
      <h3 class="box-title text-left">Overtime Report</h3>
      </div><!-- /.box-header --> --}}
      </div>
    </a>
    </div>

    {{-- <div class="col-md-3 col-sm-6 col-xs-12 ">
        <a href="{{ route('rep.viewStEd', [], false) }}">
        <div class="box box-solid">
        <div class="box-body">
        <div class="media">
          <div class="media-left">
            <img src="vendor/ot-assets/usradm_reports.png" class="media-object" style="width:50px">
          </div>
          <div class="media-body">
            <h4 class="media-heading">Start/End OT Time</h4>
            <p>Report</p>
          </div>
        </div>
        </div><!-- /.box-body -->
        <div class="box-header bg-yellow-active color-palette">
        <h3 class="box-title text-left">List of Start/End OT Time Report</h3>
        </div><!-- /.box-header -->
        </div>
      </a>
      </div> --}}

      <div class="col-md-3 col-sm-6 col-xs-12 ">
        <a href="{{ route('rep.viewOT', [], false) }}">
        <div class="box box-warning box-solid">
        <div class="box-body">
        <div class="media">
          <div class="media-left">
            <img src="vendor/ot-assets/usradm_reports.png" class="media-object" style="width:50px">
          </div>
          <div class="media-body">
            <h4 class="media-heading">Summary of OT Claim</h4>
            <p>Report</p>
          </div>
        </div>
        </div><!-- /.box-body -->
        {{-- <div class="box-header bg-yellow-active color-palette">
        <h3 class="box-title text-left">Summary of OT Claim</h3>
        </div><!-- /.box-header --> --}}
        </div>
      </a>
      </div>

      {{-- <div class="col-md-3 col-sm-6 col-xs-12 ">
        <a href="{{ route('rep.viewOTLog', [], false) }}">
        <div class="box box-solid">
        <div class="box-body">
        <div class="media">
          <div class="media-left">
            <img src="vendor/ot-assets/usradm_reports.png" class="media-object" style="width:50px">
          </div>
          <div class="media-body">
            <h4 class="media-heading">OT Log Changes</h4>
            <p>Report</p>
          </div>
        </div>
        </div><!-- /.box-body -->
        <div class="box-header bg-yellow-active color-palette">
        <h3 class="box-title text-left">OT Log Report</h3>
        </div><!-- /.box-header -->
        </div>
      </a>
      </div> --}}

</div><!-- /.row row-eq-height -->    
</div>


@stop