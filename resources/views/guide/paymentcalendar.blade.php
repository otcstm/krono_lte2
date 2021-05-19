@extends('adminlte::page')

@section('title', 'Guide - Calendar')

@section('content')

<h1>Payment Calendar for Year {{date("Y")}}</h1>
<div class="row">
    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table table-noborder pyds">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Last Submission Date</th>
                        <th>Last Approval Date</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($date as $dates)
                    <tr>
                        <td>{{date("F", strtotime($dates->payment_date))}}</td>
                        <td>{{date("j F Y", strtotime($dates->last_sub_date))}}</td>
                        <td>{{date("j F Y", strtotime($dates->last_approval_date))}}</td>
                        <td>{{date("j F Y", strtotime($dates->payment_date))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <div class="panel panel-default panel-guide">
            <div class="panel-heading"><h4>Today</h4></div>
            <div class="panel-body"><h2 style="color: #707070; font-weight: bold; margin: 20px 0">{{date("j M Y")}}</h2></div>
        </div>
        <div class="panel panel-default panel-guide">
            <div class="panel-heading"><h4>Last Submission</h4></div>
            <div class="panel-body"><h2 style="color: #707070; font-weight: bold; margin: 20px 0">{{date("j M Y", strtotime($lastsub))}}</h2></div>
        </div>
        <div class="panel panel-default panel-guide">
            <div class="panel-heading"><h4>Payment Date</h4></div>
            <div class="panel-body">
                <h2 style="color: #707070; font-weight: bold; margin: 20px 0">{{date("j M Y", strtotime($paymentd))}}</h2>
                @if($dtg>0)<h4>{{$dtg}} @if($dtg>1) Days @else Day @endif to go</h4>@endif
            </div>

        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
</script>
@stop