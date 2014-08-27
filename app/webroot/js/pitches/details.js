$(document).ready(function() {

    $('#pitch_rating').raty({
        path: '/img',
        hintList: ['не то!', 'так себе', 'возможно', 'хорошо', 'отлично'],
        starOn: 'solution-star-on.png',
        starOff: 'solution-star-off.png',
        size: 24,
        readOnly: $('#pitch_rating').data('read'),
        start: $('#pitch_rating').data('rating'),
        click: function(score, evt) {
            $.post('/rating/save.json',
                    {"id": $(this).data('pitchid'), "rating": score}, function(response) {
            });
            $('#take-part').show('fast');
    }});

    $('.btn-success').on('click', function() {
        {
            $.post('/rating/takePart.json', {"id": $(this).data('pitchid')});
            $('#take-part').hide('fast');
        }
    });

});

$(document).mouseup(function(e) {
    var container = $("#take-part");
    if (container.has(e.target).length === 0) {
        container.hide('fast');
    }
});