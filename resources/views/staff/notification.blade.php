@extends('adminlte::page')

@section('title', 'Notification')

@section('content')



<div class="table-responsive">

<a  onclick="return clearnotiPage();" href="##">
                      <b style="color: #FF4949">Clear Notification</b>
                    </a>


        <table id="tOTList" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th></th>
                    <th>ID</th>

                    <th>Created At</th>
                    <th>Content</th>


                </tr>
            </thead>
            <tbody>
                @php($count1 = 0)
                @foreach($noti as $nt)
                @php($count1 = $count1+1)
                <tr>
                    <td>{{$count1}}</td>
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

        <script type="text/javascript">
        function clearnotiPage() {
            Swal.fire({
                title: 'Clear Notification',
                html: "<p>Are you sure to clear your notification(s)?</p>",
                confirmButtonText: 'YES',
                cancelButtonText: 'NO',
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: '{{ route("noti.clear", [], false)}}',
                        success: function(resp) {
                            location.reload();
                        }
                    });
                }
            })
        }
        </script>

        @stop