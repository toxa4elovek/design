$(document).ready(function(){

    if($('li', '#carousel_small').length > 1) {
        //Маленькая карусель
        $('#carousel_small').jCarouselLite({
            auto: 0,
            speed: 500,
            btnPrev: "#prev2",
            btnNext: "#next2",
            visible: 1
        });
    }else {
        $('#prev2').click(function() {
            return false;
        })
        $('#next2').click(function() {
            return false;
        })
    }

    $('.changeStatus').live('click', function() {
        var name = $(this).attr('name');
        var input = $('#' + name);
        if($(this).hasClass('profselectbtnpressed')) {
            $('#' + name).val(0);
            $(this).removeClass('profselectbtnpressed');
        }else {
            $('#' + name).val(1);
            $(this).addClass('profselectbtnpressed');

        }
        return false;
    })

    $('#photoselectpic, #file-uploader-demo1').live('mouseover', function() {
        $('#file-uploader-demo1').show();
    })

    $('.photoselectbox, #file-uploader-demo1').live('mouseleave', function() {
        $('#file-uploader-demo1').hide();
    })

    var uploader = false;
    if(($('#file-uploader-demo1').length > 0) && (uploader == false)) {
            uploader = new qq.FileUploader({
            element: document.getElementById('file-uploader-demo1'),
            action: '/users/avatar.json',
            onComplete: function(id, fileName, responseJSON){
                var avatarUrl = responseJSON.data.images.avatar_normal.weburl + '?' + (Math.round(Math.random() * 11000));
                $('#photoselectpic').attr('src', avatarUrl);
            },
            debug: false
        });           
    }

    $(document).on('click', '#deleteaccount', function() {
       $('#popup-final-step').modal({
           containerId: 'final-step',
           opacity: 80,
           closeClass: 'popup-close'
       });
       return false;
    });

    $(document).on('click', '#confirmWinner', function() {
        window.location = '/users/deleteaccount';
        return false;
    });

});