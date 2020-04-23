@extends('adminlte::page')
@section('title', 'Notification List')
@section('content')


<table class="table">

<thead>
    <tr>
      <th scope="col">Category of Todo</th>
      <th scope="col">Descr</th>
      <th scope="col">Message</th>
  
    </tr>
  </thead>
    <tr>
        <td>
            Draft
        </td>
        <td>For status Draft & Query</td>
        <td>Claim List ({{$draftCount}})</td>
    </tr>

    <tr>
        <td>
            Verification
        </td>
        <td>Claim Verification</td>
        <td>Claim Verification ({{$draftCount}})</td>
    </tr>
</table>













    @stop

    @section('js')


    @stop