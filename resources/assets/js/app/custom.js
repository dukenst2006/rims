// ALL CUSTOM SCRIPTS

$(function () {
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: {
            time: "icon-clock",
            date: "icon-calendar",
            up: "icon-arrow-up",
            down: "icon-arrow-down"
        },
        daysOfWeekDisabled: [0, 6]
    });
});
