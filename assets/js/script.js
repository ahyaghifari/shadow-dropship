$(document).ready(function () {
    $('#nav-toggle').click(function () {
        $('#nav-list').toggleClass("active");
    })

    
    function toCalendarTime(time) {
        var momentNow = moment(time, "YYYY-MM-DD hh:mm:ss").locale('id').calendar()
        return momentNow;
    }
    
    function toRelativeTime(time) {
        var momentNow = moment(time, "YYYY-MM-DD hh:mm:ss").locale('id').fromNow();
        return momentNow;
    }

    $('.to-calendar').each(function (i, e) {
        var time = e.innerHTML;
        e.innerHTML = toCalendarTime(time);
    });

    $('.to-relative-time').each(function (i, e) {
        var time = e.innerHTML;
        e.innerHTML = "(" + toRelativeTime(time) + ")";
    });

});