console.log('test');

$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        url: '/pitchfiles/add.json',
        done: function (e, data) {
            /*$.each(data.result, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });*/
        }
    });
});