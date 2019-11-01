@extends('adminlte::page')
@section('content')
<table border="1" style="width:100%">
<tr>
  @foreach ($header as $h)
  <th>{{ $h }}</th>
  @endforeach
  <th>Edit</th>
</tr>
@foreach ($content as $cmain)

<tr>
  @foreach ($cmain as $c)
  <td>{{ $c }}</td>
  @endforeach
  <th>{{$cmain[0]}}</th>
</tr>

@endforeach
</table>
Legends <br/>
@foreach ($states as $state)

{{ $state->id }} :{{$state->state_descr}} <br/>
 @endforeach


@endsection
@section('js')
@endsection
