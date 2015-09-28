$(document).ready(function() {
    $('#needassist').on('click', function() {
        $('#loading-overlay2').modal({
            containerId: 'spinner',
            opacity: 80,
            close: false
        });
        $('.simplemodal-wrap').css('overflow', 'visible');
        $('#reqname').focus();
        $('#reqtarget').val(1);
        $('#reqto').val('дизайн консультация (Оксана Девочкина)');
        return false;
    })
})