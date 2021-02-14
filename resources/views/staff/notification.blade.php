@extends('adminlte::page')

@section('title', 'Notification')

@section('content')



<div class="table-responsive">
    <table id="tOTList" class="table table-bordered tbot">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>

                <th>Created At</th>
                <th>Content</th>


            </tr>
        </thead>
        <tbody>
            @foreach($noti as $nt)
            <tr>
                <td>

                    <icon class="{{$nt->data['icon']}}">
                </td>

                <td>
                    {{$nt->data['id']}}

                </td>
                <td>
                    {{$nt->created_at}}

                </td>
                <td>
                    <a href="{{ route('notify.read', ['nid' => $nt->id]) }}">
                        {{$nt->data['text']}}
                    </a>

                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

    <!-- delete this after know all the columns 
    <div class="col-md-12 ">
        <div class="box box-solid">
            <div class="box-body">
                {{$noti}}
            </div>
        </div>
    </div>

 end delete this after know all the columns -->
    @stop

    @section('js')
    @stop