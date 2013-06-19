$(document).ready(function() {

    $( ".slider" ).slider({
        value: 5,
        min: 0,
        max: 5,
        step: 1,
        slide: function( event, ui ) {
            $('input[name=' + $(this).data("field") +']').val(ui.value);
        }
    })

    $('.fakeclick').click(function() {
        $('#afterPitchCommentForm').submit();
        return false;
    })
})