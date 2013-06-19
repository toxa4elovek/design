$(document).ready(function() {

    $('#solution').fileupload({
        dataType: 'json',
        autoUpload: false,
        singleFileUploads: false,
        add: function(e, data) {
            if(data.files[0].name.match(/(\.|\/)(doc?x|pdf|rtf)$/i)) {
                e.data.fileupload.myData = data;
                var html = '<li class="fakeinput" style=" padding-top: 1px; margin-left:10px;font-weight: bold;">Список загружаемых файлов:</li>';
                $.each(data.files, function(index, object) {
                    html += '<li class="fakeinput" style=" padding-top: 1px; margin-left:10px;">' + object.name + '</li>';
                })
                $('#filelist').html(html);
            }else {
                alert('Неверный формат файла! Вы можете прикрепить doc, pdf и rtf файлы к вашему решению.');
                return false;
            }
        },
        done: function (e, data) {
            $('#filename').html('Файл не выбран');
            window.location = $('#redirect-value').val();

        },
        send: function (e, data) {
            $('#loading-overlay').modal({
                containerId: 'spinner',
                opacity: 80,
                close: false
            });
            $('a[href="#uploading"]').click();
        },
    });

    $('.order1').on('mouseover', function() {
        $('img', this).attr('src', '/img/order1_hover.png');
    })
    $('.order1').on('mouseout', function() {
        $('img', this).attr('src', '/img/order1.png');
    })

    $('.order2').on('mouseover', function() {
        $('img', this).attr('src', '/img/order2_hover.png');
    })
    $('.order2').on('mouseout', function() {
        $('img', this).attr('src', '/img/order2.png');
    })

    $('a[href="#invalid"]').fancybox({
        'autoScale': true,
        'speedIn': 0,
        'speedOut': 0,
        'autoDimensions': true,
        'centerOnScroll': true  // as MattBall already said, remove the comma
    });

    $('#uploadSolution').click(function() {
        if($('input[name=tos]').attr('checked') != 'checked') {
            alert('Не все поля заполнены! (соглашение)');
        }else if($('#charzone').val().length == 0) {
            alert('Вы не указали идею!');
        }else if($('#charzone').val().length > 375) {
            alert('Вы ввели слишком длинный текст идеи, используйте возможности прикрепления файлов!');
        }else {
            $('#solution').fileupload('uploadByClickNoCheck', $('#solution'), $('#redirect-value').val());
        }
        return false;
    });

    $('#charzone').charCount({
        "counterElement": $('#charcounter'),
        "allowed": 375,
        "warning": 50
    });

    if($('#panel').length > 0){
        $('.fileinput-button').css('top', '720px');
    }

})