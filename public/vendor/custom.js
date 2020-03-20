var loginheight = $(window).height() - 152;
if($('.login').height()<=$(window).height()){
    $('.login').height(loginheight);
}

var timestart;
var lat = 0;
var long = 0;
var lat2 = 0;
var long2 = 0;
var startclockt;
var now;
var canstart = false;
var check = new Date(); 
$.ajax({
    url: '/punch/check',
    type: "GET",
    success: function(resp) {
        if(resp.result==true){
            timedif = Date.parse(check)-Date.parse(resp.stime);
            cs = Math.floor(timedif / 1000);
            cm = Math.floor(cs / 60);
            cs = cs % 60;
            ch = Math.floor(cm / 60);
            cm = cm % 60;
            ch = ch % 24;
            if(ch<10){
                chd = "0"+ch;
            }else{
                chd = ch;
            }
            if(cm<10){
                cmd = "0"+cm;
            }else{
                cmd = cm;
            }
            if(cs<10){
                csd = "0"+cs ;
            }else{
                csd = cs;
            }
            timere=chd+":"+cmd+":"+csd;
            
            starttime(resp.stime, resp.stime);
            timestart = setInterval(timer(cs, cm, ch, parseInt(Date.parse(check).toString("ss")), parseInt(Date.parse(check).toString("mm")), parseInt(Date.parse(check).toString("H")), check), 1000);
                        
        }
    },
    error: function(err) {
        puncho();
    }
});

var once =true;
var n = 0;
var timeoutId;
$("#punchb").on('mousedown', function() {
    n=0;
    once=true;
    timeoutId = setTimeout(function(){ n=n+1;}, 500);
}).on('mouseup', function() {
    if(n<1){
        if(once){
            puncho();
        }
    }
    clearTimeout(timeoutId);
});


function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition,showError);
    } else {
        Swal.fire({
            title: 'Error',
            html: "Geolocation is not supported by this browser." ,
            confirmButtonText: "OK"
        })
    }
}   

function showPosition(position) {
    lat =position.coords.latitude;
    long =position.coords.longitude;
    punchman();
}

var showerror = true;
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            if(showerror){
                Swal.fire({
                    title: 'Error',
                    html: "It seems that your browser has blocked Geolocation. Please enable Geolocation in the settings to start your overtime. You may refer to the guideline <a style='font-weight: bold; color: black' href='#' target='_blank'>HERE</a>" ,
                    confirmButtonText: "OK",
                    icon: "error",
                }).then((result) => {
                    if (result.value) {
                        showerror = true;
                    }
                })
                showerror = false;
                getLocation();
            }
        break;
        case error.POSITION_UNAVAILABLE:
            if(showerror){
                Swal.fire({
                    title: 'Error',
                    html: "Location information is unavailable." ,
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.value) {
                        showerror = true;
                    }
                })
                showerror = false;
                getLocation();
            }
        break;
        case error.TIMEOUT:
            if(showerror){
                Swal.fire({
                    title: 'Error',
                    html: "The request to get user location timed out." ,
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.value) {
                        showerror = true;
                    }
                })
                showerror = false;
                getLocation();
            }
        break;
        case error.UNKNOWN_ERROR:
            if(showerror){
                Swal.fire({
                    title: 'Error',
                    html: "An unknown error occurred." ,
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.value) {
                        showerror = true;
                    }
                })
                showerror = false;
                    getLocation();
            }
        break;
    }
}

function getPosition(position){
    lat2 =position.coords.latitude;
    long2 =position.coords.longitude;
    endpunch();
}

var showerror2 = true;
function showError2(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            if(showerror2){
                Swal.fire({
                    title: 'Error End Overtime',
                    html: "It seems that your browser has blocked Geolocation. Please enable Geolocation in the settings to end your overtime. You may refer to the guideline <a style='font-weight: bold; color: black' href='#' target='_blank'>HERE</a>" ,
                    confirmButtonText: "OK",
                    icon: "error",
                }).then((result) => {
                    if (result.value) {
                        location.reload();
                    }
                })
                showerror2 = false;
            }
        break;
        case error.POSITION_UNAVAILABLE:
            if(showerror2){
                Swal.fire({
                    title: 'Error',
                    html: "Location information is unavailable." ,
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.value) {
                        location.reload();
                    }
                })
                showerror2 = false;
            }
        break;
        case error.TIMEOUT:
            if(showerror2){
                Swal.fire({
                    title: 'Error',
                    html: "The request to get user location timed out." ,
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.value) {
                        location.reload();
                    }
                })
                showerror2 = false;
            }
        break;
        case error.UNKNOWN_ERROR:
            if(showerror2){
                Swal.fire({
                    title: 'Error',
                    html: "An unknown error occurred." ,
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.value) {
                        location.reload();
                    }
                })
                showerror2 = false;
            }
        break;
    }
}

function punchman(){
    $.ajax({
        url: '/punch/start?time='+startclock+'&lat='+lat+'=&long='+long,
        type: "GET",
        success: function(resp) {
            starttime(now, startclock);
            timestart = setInterval(timer(0, 0, 0, parseInt(Date.parse(now).toString("ss")), parseInt(Date.parse(now).toString("mm")), parseInt(Date.parse(now).toString("H")), now), 1000);
        },
        error: function(err) {
            puncho();
        }
    });
}

function puncho(){
    // var now = new Date(); 
    once =false;
    now = new Date(); 
    startclock = Date.parse(now).toString("yyyy-MM-dd HH:mm");
    startclock = startclock+":00";
    $.ajax({
        url: '/punch/checkday?date='+startclock,
        type: "GET",
        success: function(resp) {
            if(resp.result==true){
                timere = "00:00:00";
                Swal.fire({
                        title: 'Start Overtime',
                        html: "Are you sure you want to <b style='color:#143A8C'>start</b> your overtime at <b style='color:#143A8C'>"+Date.parse(now).toString("HHmm")+"</b> on <b style='color:#143A8C'>"+Date.parse(now).toString("dd.MM.yyyy")+"</b>?",
                        showCancelButton: true,
                        confirmButtonText:
                                            'YES',
                                            cancelButtonText: 'NO',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6'
                        }).then((result) => {
                            //startot ajx
                            if (result.value) {
                                getLocation();
                                
                            }
                        })  
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Start Overtime Error',
                    text: "You are not allowed to start overtime during working hours!",
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                })
            }
        },
        error: function(err) {
            // puncho();
        }
    });
     
}
    
var future
function starttime(now, startclock){
    startclockt = startclock;
    future = new Date(); 
    endclock = Date.parse(future).toString("yyyy-MM-dd HH:mm");
    endclock = endclock+":00";
    Swal.fire({
        title: 'Overtime',
        customClass: 'test',
        html: "<p><b>OT TIMER : <span style='color: #143A8C'><span id='timerd'>"+Date.parse(now).toString("dd.MM.yyyy")+"</span> <span id='timerh' style='margin-left: 3px'>"+timere+"</span></span></b></p>",
        showCancelButton: true,
        confirmButtonText:
                            'END OVERTIME',
                            cancelButtonText: 'CANCEL',
        confirmButtonColor: '#F00000',
        cancelButtonColor: '#3085d6',
        allowOutsideClick: false
        }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'End Overtime',
                html: "Are you sure you want to <b style='color:#143A8C'>end</b> your overtime at <b style='color:#143A8C'>"+Date.parse(future).toString("HHmm")+"</b> on <b style='color:#143A8C'>"+Date.parse(future).toString("dd.MM.yyyy")+"</b>?",
                showCancelButton: true,
                confirmButtonText: 'YES',
                                    cancelButtonText: 'NO',
                confirmButtonColor: '#F00000',
                cancelButtonColor: '#3085d6',
                allowOutsideClick: false
                }).then((result) => {
                if (result.value) {
                    navigator.geolocation.getCurrentPosition(getPosition,showError2);
                }else{
                    
                    starttime(now, startclock);
                }
            })
        }else{
            Swal.fire({
                title: 'Cancel Overtime',
                html: "Are you sure you want to <b style='color:#143A8C'>cancel</b> your overtime at <b style='color:#143A8C'>"+Date.parse(now).toString("HHmm")+"</b> on <b style='color:#143A8C'>"+Date.parse(now).toString("dd.MM.yyyy")+"</b>?",
                showCancelButton: true,
                confirmButtonText:'YES',
                cancelButtonText: 'NO',
                confirmButtonColor: '#F00000',
                cancelButtonColor: '#3085d6',
                allowOutsideClick: false
                }).then((result) => {
                if (result.value) {
                    clearInterval(timestart); 
                    $.ajax({
                        url: '/punch/cancel?time='+startclock,
                        type: "GET",
                        success: function(resp) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Overtime Cancelled',
                                text: "Your overtime has been cancelled!",
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })

                        },
                        error: function(err) {
                        }
                    });
                }else{
                    starttime(now, startclock);
                }
            })
        }            
    })
}

function endpunch(){
    sstime = Date.parse(startclockt).toString("mm");
    eetime = Date.parse(future).toString("mm");
    // alert(parseInt(eetime)-parseInt(sstime));
    if(parseInt(eetime)-parseInt(sstime)>0){
        $.ajax({
            url: '/punch/end?stime='+startclockt+'&etime='+endclock+'&lat='+lat+'&long='+long+'&lat2='+lat2+'&long2='+long2,
            type: "GET", 
            success: function(resp) {
                clearInterval(timestart); 
                var path = window.location.pathname;
                if(path=="/punch"){
                    location.reload();
                }
            },
                error: function(err) {
                    starttime(now, startclockt);
                }
            }
        );
    }else{
        clearInterval(timestart); 
        $.ajax({
            url: '/punch/cancel?time='+startclockt,
            type: "GET",
            
            success: function(resp) {
                Swal.fire({
                    icon: 'error',
                    title: 'Overtime Cancelled',
                    text: "Your overtime duration is less than a minute!",
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.value) {
                        location.reload();
                    }
                })
            },
            error: function(err) {
            }
        });
    }
}

function timer(psecond, pminute, phour, dsecond, dminute, dhour, now){
    return function(){
        psecond++;
        if(psecond==60){
            pminute++;
            psecond=0;
        }
        if(pminute==60){
            phour++;
            pminute=0;
        }
        var phours = phour;
        var pminutes = pminute;
        var pseconds = psecond;
        if(phours < 10){
            phours = "0"+phours;
        }
        if(pminutes < 10){
            pminutes = "0"+pminutes;
        }
        if(psecond < 10){
            pseconds = "0"+pseconds;
        }
        if((((dhour*60*60)+(dminute*60)+dsecond)+((phour*60*60)+(pminute*60)+psecond))==86400){
            $("#timerd").text(Date.parse(now).addDays(1).toString("dd.MM.yyyy"));
        }
        // if minutes
        $("#timerh").text(phours+":"+pminutes+":"+pseconds);
        timere = phours+":"+pminutes+":"+pseconds;
    }
}


// $( "#punchb" ).draggable();  
// $(function() {  
//     $( ".punchdiv" ).draggable();  
//   }); 

// $( "#punchb" ).draggable();  
// $(function() {  
//     $( "#punchb" ).draggable();  
//   }); 
// setInterval(function() {
//         $("#x").text((new Date - start) / 1000 + " Seconds");
//         //  $('.Timer').text((new Date - start) / 1000 + " Seconds");
//     }, 1000);</script>
var title = document.title;

if(title != 'OTCS - Dashboard'){

    dragElement(document.getElementById("punchb"));

    function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
    } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }
    }
}