
$(function () {
    function toTimes(origin, dest) {
        $(origin).find(':selected').appendTo(dest);
    }
    function toOptions(origin, dest) {
        $(origin).children().appendTo(dest);
    }
});
