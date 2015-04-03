$(document).ready(function() {
    var maxHeight = 0;
    var counter = 0;
    var list = [];
    var total = $('.list_portfolio').children().length;

    $.each($('.list_portfolio').children(), function(index, object) {
        //console.log($('.selecting_numb', object).height());
        if($('.selecting_numb', object).height() > maxHeight) {
            maxHeight = $('.selecting_numb', object).height();
        }
        list.push(object);
        counter += 1;
        total -= 1;
        if((counter == 4) || (total == 0)) {
            $.each(list, function(index, object) {
                $('.selecting_numb', object).height(maxHeight);
            });
            list = [];
            maxHeight = 0;
            counter = 0;
        }
    });


    $(document).on('click', '.removeTag', function() {
        $(this).parent().fadeOut(300, function() {
            var tag = $(this).parent().text()
            tag = tag.trim();
            console.log({"tag": tag, "id": $(this).parent().data('solutionid') })
            $.post('/solutions/remove_tag.json', {"tag": tag, "id": $(this).parent().data('solutionid') }, function(response) {
                console.log(response);
            });
            if($(this).parent().children(':visible').length < 5) {
                var solutionid =  $(this).parent().data('solutionid');
                $('input', 'form[data-solutionid="' + solutionid + '"]').fadeIn(300)
            }
        });
        return false;
    });

    $(document).on('submit', '.tag_submit', function() {
        var input = $('input', this);
        var tag = input.val();
        console.log(tag);
        $.post('/solutions/add_tag.json', {"tag": tag, "id": $(this).data('solutionid') }, function(response) {
        });
        var html = '<li style="padding-left: 10px; padding-right: 10px; margin-right:6px; height: 21px; padding-top: 5px; margin-bottom:3px;">' + tag + '<a class="removeTag" href="#" style="margin-left: 10px;"> <img src="/img/delete-tag.png" alt="" style="padding-top: 2px;"></a></li>';
        var ul = $('ul[data-solutionid="' + $(this).data('solutionid') + '"]');
        ul.append(html);
        input.val('');
        if(ul.children(':visible').length > 4) {
            input.fadeOut(300)
        }
        return false;
    })
})