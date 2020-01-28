var loginheight = $(window).height() - 152;
// alert($('.login').height()+" "+$(window).height())
if($('.login').height()<=$(window).height()){
    $('.login').height(loginheight);
}

// if (navigator.geolocation) {
//     navigator.geolocation.getCurrentPosition(function (position) {
//         console.log(position);
//         alert(position);
//     });
// }
// alert($('.login').height());