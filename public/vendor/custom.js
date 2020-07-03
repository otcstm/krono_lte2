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
var swtime;
var enddate;
var canstart = false;
var check = new Date(); 
var wtm;
var wtms;
var nework = false;
var once =true;
var n = 0;
var timeoutId;
var showerror = true;


//every page load check for existing oT
checkOTClocked();

function checkOTClocked(){
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
                var stime = resp.stime;
                
                wtms = Date.parse(check).toString("yyyy-MM-dd HH:mm");
                $.ajax({
                    url: '/punch/checkworktime?time='+wtms,
                    type: "GET",
                    success: function(resp) {
                        enddate = (Date.parse(check).addDays(resp.addday).toString("dd.MM.yyyy"));
                        starttime(stime, stime);
                        timestart = setInterval(timer(cs, cm, ch, parseInt(Date.parse(check).toString("ss")), parseInt(Date.parse(check).toString("mm")), parseInt(Date.parse(check).toString("H")), check, resp.swtime), 1000);
                    },
                    error: function(err) {
                        puncho();
                    }  
                });    
            }
        },
        error: function(err) {
            puncho();
        }
    });
}

//when click start OT;
$("#punchb").on('mousedown', function() {
    n=0;
    once=true;
    timeoutId = setTimeout(function(){ n=n+1;}, 500);
}).on('mouseup', function() {
    if(n<1){
        if(once){
            // puncho();
            checkeligible();
        }
    }
    clearTimeout(timeoutId);
});

function checkeligible(){
    $.ajax({
        url: '/punch/eligible',
        type: "GET",
        success: function(resp) {
            // alert(resp.result);
            if(resp.result==true){
                $.ajax({
                    url: '/punch/checkstart',
                    type: "GET",
                    success: function(resp) {
                        // alert(resp.result);
                        if(resp.check==true){
                            puncho();
                        }else{
                            checkOTClocked();
                        }
                    },
                    error: function(err) {
                        // puncho();
                    }
                });
                
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Start Overtime Error',
                    text: "You are not eligible to start overtime!",
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

function puncho(){
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
                                $.ajax({
                                    url: '/punch/checkstart',
                                    type: "GET",
                                    success: function(resp) {
                                        // alert(resp.result);
                                        if(resp.check==true){
                                            getLocation();
                                        }else{
                                            checkOTClocked();
                                        }
                                    },
                                    error: function(err) {
                                        // puncho();
                                    }
                                });
                                
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

//start OT
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

//start OT succeed
function showPosition(position) {
    lat =position.coords.latitude;
    long =position.coords.longitude;
    // alert(now);
    punchman();
}

//start OT failed
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
    // alert(now);
    wtm = Date.parse(now).toString("yyyy-MM-dd HH:mm");
    $.ajax({
        url: '/punch/start?time='+startclock+'&lat='+lat+'=&long='+long,
        type: "GET",
        success: function(resp) {
            // alert(resp.test);
            $.ajax({
                url: '/punch/checkworktime?time='+wtm,
                type: "GET",
                success: function(resp) {
                    // alert(resp.swtime);
                    dayadd = now;
                    enddate = (Date.parse(dayadd).addDays(resp.addday).toString("dd.MM.yyyy"));
                    now = (Date.parse(dayadd).addDays(-resp.addday).toString("dd.MM.yyyy"));
                    timestart = setInterval(timer(0, 0, 0, parseInt(Date.parse(now).toString("ss")), parseInt(Date.parse(now).toString("mm")), parseInt(Date.parse(now).toString("H")), now, resp.swtime), 1000);
                    starttime(now, startclock);
                },
                error: function(err) {
                    puncho();
                }  
            });    
        },
        error: function(err) {
            puncho();
        }
    });
}
    
var future
function starttime(now, startclock){
    startclockt = startclock;
    // alert(now);
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
            future = new Date(); 
            endclock = Date.parse(future).toString("yyyy-MM-dd HH:mm");
            endclock = endclock+":00";
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
                    $.ajax({
                        url: '/punch/checkstart',
                        type: "GET",
                        success: function(resp) {
                            // alert(resp.result);
                            if(resp.check==false){
                                navigator.geolocation.getCurrentPosition(getPosition,showError2);
                            }
                        },
                        error: function(err) {
                            // puncho();
                        }
                    });
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
                    $.ajax({
                        url: '/punch/checkstart',
                        type: "GET",
                        success: function(resp) {
                            // alert(resp.result);
                            if(resp.check==false){
                                

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
                            }
                        },
                        error: function(err) {
                            // puncho();
                        }
                    });
                }else{
                    starttime(now, startclock);
                }
            })
        }            
    })
}
var displayonce = true;
function endpunch(){
    sstime = Date.parse(startclockt).toString("mm");
    eetime = Date.parse(future).toString("mm");
    // alert(parseInt(eetime));
    // alert(parseInt(sstime));
    // alert(eetime+"-"+sstime+"="+(parseInt(eetime)-parseInt(sstime)));
    
    if(displayonce){
        if(((parseInt(eetime)-parseInt(sstime))>0)||(parseInt(eetime)-parseInt(sstime))<0){
            $.ajax({
                url: '/punch/end?stime='+startclockt+'&etime='+endclock+'&lat='+lat+'&long='+long+'&lat2='+lat2+'&long2='+long2,
                type: "GET", 
                success: function(resp) {
                    clearInterval(timestart); 
                    if(nework){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Overtime Ended',
                            text: "New working hour has staterd",
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                        }).then((result) => {
                            if (result.value) {
                                var path = window.location.pathname;
                                if(path=="/punch"){
                                    location.reload();
                                }
                            }
                        })      
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

        displayonce = false;
    }
}
    

function timer(psecond, pminute, phour, dsecond, dminute, dhour, now, swtime){
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
        // if((((dhour*60*60)+(dminute*60)+dsecond)+((phour*60*60)+(pminute*60)+psecond))==86400){
        //     $("#timerd").text(Date.parse(now).addDays(1).toString("dd.MM.yyyy"));
        // }
        // if minutes
        $("#timerh").text(phours+":"+pminutes+":"+pseconds);
        timere = phours+":"+pminutes+":"+pseconds;
        var cnow = new Date();
        $("#timerd").text(Date.parse(cnow).toString("dd.MM.yyyy"));
        swtimes = swtime.split(":");
        ctime = Date.parse(cnow).toString("HH:mm");
        ctimes = ctime.split(":");
        // Date.parse(now).addDays(1)
        // alert(enddate);
        // console.log(cnow+" "+enddate+" "+parseInt(ctimes[0]*60)+parseInt(ctimes[1])+" >= "+(parseInt(swtimes[0]*60)+parseInt(swtimes[1])));
        if(Date.parse(cnow).toString("dd.MM.yyyy")==enddate){
            if(parseInt(ctimes[0]*60)+parseInt(ctimes[1])>=(parseInt(swtimes[0]*60)+parseInt(swtimes[1]))){
                nework = true;
                endclock = Date.parse(cnow).toString("yyyy-MM-dd")+" "+swtime+":00";
                future = Date.parse(cnow).toString("yyyy-MM-dd")+" "+swtime+":00";
                navigator.geolocation.getCurrentPosition(getPosition,showError2);
                // alert("gojok");
            }
        }
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