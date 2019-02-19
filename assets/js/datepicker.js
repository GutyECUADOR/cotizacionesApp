$('.pickyDate').datepicker({
    format: "yyyy-mm-dd",
    language: "es",
    autoclose: true,
    todayHighlight: true
});

$('.input-daterange input').each(function() {
    $(this).datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true,
        todayHighlight: true
    });
});