@extends('adminlte::page')

@section('title', 'Guide - Calendar')

@section('content')
<h1>Holiday Calendar for Year {{date("Y")}}</h1>
<div class="guide">
<div class="row">
    <div class="col-md-8">
        <div class="row">
        @for($i = 1; $i < 13; $i++)
            @php($m = $i)
            @if($m >7)
                @php(--$m)
            @endif
            @if(($m % 2) == 0)
                @if($m == 2)
                    @if(date("L")==1)
                        @php($mt=30)
                    @else
                        @php($mt=29)
                    @endif
                @else
                    @php($mt=31)
                @endif
            @else
                @php($mt=32)
            @endif
            <div class="col-md-4 guide-calendar text-center">
                <div class="guide-calendar-grid">
                    <h4 style="font-weight: bold" class="text-center">{{date("F", strtotime(date("Y-".$i)))}}</h4>
                    <div class="flexg">
                        <div class="red">S</div>
                        <div>M</div>
                        <div>T</div>
                        <div>W</div>
                        <div>T</div>
                        <div>F</div>
                        <div>S</div>
                        @if(date("N", strtotime(date("Y")."-".$i."-1"))==1)
                            <div></div>
                        @elseif(date("N", strtotime(date("Y")."-".$i."-1"))==2)
                            <div></div><div></div>
                        @elseif(date("N", strtotime(date("Y")."-".$i."-1"))==3)
                            <div></div><div></div><div></div>
                        @elseif(date("N", strtotime(date("Y")."-".$i."-1"))==4)
                            <div></div><div></div><div></div><div></div>
                        @elseif(date("N", strtotime(date("Y")."-".$i."-1"))==5)
                            <div></div><div></div><div></div><div></div><div></div>
                        @elseif(date("N", strtotime(date("Y")."-".$i."-1"))==6)
                            <div></div><div></div><div></div><div></div><div></div><div></div>
                        @endif
                        @for($d = 1; $d < $mt; $d++)
                             @if(date("N", strtotime(date("Y")."-".$i."-".$d))==7)
                                <div style="display: flex; justify-content: space-around;"><div style="width: 23px" class="red @foreach($holiday as $holy) @if($holy->dt==date("Y-m-d", strtotime(date("Y")."-".$i."-".$d))) circle-red @endif @endforeach">{{$d}}</div></div>
                            @else
                                <div style="display: flex; justify-content: space-around;"><div style="width: 23px"  class=" @foreach($holiday as $holy) @if($holy->dt==date("Y-m-d", strtotime(date("Y")."-".$i."-".$d))) circle @endif @endforeach">{{$d}}</div></div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        @endfor
        </div>
    </div>
    <div class="col-md-4">
        <div class="orange">
            <h2 style="font-weight: bold">PUBLIC HOLIDAY</h2>
            <h4 style="font-weight: bold">Select State:</h4>
            <form action="{{route('guide.datecalendar')}}" id="send">
                @csrf
                <select style="width: 100%" name="stet" id="stet">
                    @foreach($state as $singlestate)
                        <option value="{{$singlestate->id}}" @if($ownstate==$singlestate->id) selected @endif>{{str_replace(')', '', str_replace('Malaysia (', '', $singlestate->state_descr))}}</option>
                    @endforeach
                </select>
            </form>
            <div class="row">
                <div class="col-md-12" style="height: 5px;"></div>
            @foreach($holiday as $singleday)
                <div class="col-xs-2" style="font-weight: bold">{{date("m.d", strtotime($singleday->dt))}}</div>
                <div class="col-xs-3">{{date("l", strtotime($singleday->dt))}}</div>
                <div class="col-xs-7">  @if(strpos($singleday->descr, "(MY)") !== false) 
                                            {{str_replace(" (MY)", "", $singleday->descr)}} 
                                        @elseif(strpos($singleday->descr, "MY") !== false)  
                                            {{str_replace(" MY", "", $singleday->descr)}} 
                                        @else 
                                            {{$singleday->descr}} 
                                        @endif

                                        
                </div>
            @endforeach

            
            </div>
        </div>
    </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$("#stet").change(function(){
        $("#send").submit();
    });
</script>
@stop