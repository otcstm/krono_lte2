<script src="{{ secure_asset('vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ secure_asset('vendor/jqueryui/jquery-ui.min.js') }}"></script>
Visitors: {{$counter}} / <span id="cval">{{$counter}}</span>

<script type="text/javascript">
function loadValue(){

    var url = "{{ route('pageCounter.count',$tag)}}"; 
    $.getJSON(url, function (data) {
      console.log(data);
      $("#cval").text(data);
     });
    }

    setInterval(function(){
        loadValue() // this will run after every 5 seconds
}, 10000);


</script>

