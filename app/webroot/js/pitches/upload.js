$(document).ready(function() {
    $('input[name=licensed_work]').change(function() {
        if($(this).val() == "0") {
            $('#fine').show();
            $('#works').hide();
        }else if($(this).val() == "1") {
            $('#works').show();
            $('#fine').hide();
        }
    });

    $(document).on('click', '#plusbutton', function() {
        var parent = $(this).parent();
        $(this).remove();
        var counter = $('#works').children().length + 1;
        parent.after('<div style="height:1px;clear:both"></div><div style="margin-top:10px;">'+
            '<input placeholder="Название изображения" value="" type="text" style="width:138px;margin-left:0px;margin-right:14px;float:left;text-transform:none;" name="filename[' + counter + ']">'+
            '<input placeholder="http://" value="" type="text" style="width:200px;margin-left:0px;margin-right:22px;float:left;text-transform:none;" name="source[' + counter + ']" >'+
            '<label style="color:#666666;width:65px;display:block;float:left;margin-right:40px;">'+
            '<input type="checkbox" name="needtobuy[' + counter + ']" style="width:14px;display:block;float:left;margin-top:10px;"><span style="width:46px;display:block;float:left;margin-top:5px;">Нужно<br>покупать</span></label>'+
            '<input type="button" class="button" value="+" id="plusbutton" style="width:40px;float:left;display:block;padding-left:25px;padding-right:25px;font-size:20px;">'+
            '</div>');
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

    $('#solution').fileupload({
        dataType: 'json',
        autoUpload: false,
        singleFileUploads: false,
        add: function(e, data) {
            if(data.files[0].name.match(/(\.|\/)(gif|jpe?g|png)$/i)) {
                e.data.fileupload.myData = data;
                var html = '';
                $.each(data.files, function(index, object) {
                    html += '<li class="fakeinput" style="background: url(/img/attach_icon.png) no-repeat scroll 0px 0px transparent; padding-top: 1px; margin-left: 0px; height:20px; padding-left: 20px;">' + object.name + '</li>';
                })
                $('#filelist').html(html);
            }else {
                return false;
            }
        },
        done: function (e, data) {
            $('#filename').html('Файл не выбран');
            window.location = $('#redirect-value').val();
        },
        progressall: function(e, data) {
            if(data.total > 0) {
                var percent = data.total / 100;
                var completed = Math.round(data.loaded / percent);
                $('#progressbar').text(completed + '%');
                var progresspx = Math.round(3.4 * completed);
                if(progresspx > 330) {
                    progresspx == 330;
                }
                $('#filler').css('width', progresspx);
                if(completed > 95) {
                    $('#progressbarimage').css('background', 'url(/img/indicator_full.png)');
                }
            }
        },
        send: function (e, data) {
            $('#loading-overlay').modal({
                containerId: 'spinner',
                opacity: 80,
                close: false
            });
            $('a[href="#uploading"]').click();
        }
    });

    /*
     $('a[href="#uploading"]').fancybox({
     'autoScale': true,
     'speedIn': 0,
     'speedOut': 0,
     'autoDimensions': true,
     'centerOnScroll': true  // as MattBall already said, remove the comma
     });
     */

    $('a[href="#invalid"]').fancybox({
        'autoScale': true,
        'speedIn': 0,
        'speedOut': 0,
        'autoDimensions': true,
        'centerOnScroll': true  // as MattBall already said, remove the comma
    });

    $('#uploadSolution').click(function() {
        if(($('input[name=tos]').attr('checked') != 'checked') || ($('input[type=radio]:checked').length
            == 0) && ($('#filename').html() != 'Файл не выбран')) {
            //alert('Не все поля заполнены! (соглашение)');
            $('a[href="#invalid"]').click();
        }else if($('#charzone').val().length > 375) {
            alert('Вы ввели слишком длинный комментарий!');
        }else {
            $('#solution').fileupload('uploadByClick');
        }
        return false;
    });
    if(($('#panel').length > 0) && ($('#uploadtype').length == 0)) {
        $('.fileinput-button').css('top', '525px');
    }

    /*$('.uploadblock').on('mouseenter', function() {
        $('#fakebutton').addClass('buttonhover');
    });*/

    $('input[type=file]').on('mouseenter', function() {
        $('#fakebutton').addClass('buttonhover');
    });

    $('.uploadblock').on('mouseout', function() {
        $('#fakebutton').removeClass('buttonhover');
    });

    $('#charzone').charCount({
        "counterElement": $('#charcounter'),
        "allowed": 375,
        "warning": 50
    });

})