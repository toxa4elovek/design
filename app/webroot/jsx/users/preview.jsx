;(function($) {
    $(function() {
        let maxHeight = 0;
        let counter = 0;
        let list = [];
        let total = $('.list_portfolio').children().length;

        $.each($('.list_portfolio').children(), function(index, object) {
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
                const tag = $(this).text().trim();
                $.post('/solutions/remove_tag.json', {"tag": tag, "id": $(this).parent().data('solutionid') }, function(response) {
                });
                if($(this).parent().children(':visible').length < 5) {
                    const solutionid =  $(this).parent().data('solutionid');
                    $('input', 'form[data-solutionid="' + solutionid + '"]').fadeIn(300)
                }
            });
            return false;
        });

        $(document).on('submit', '.tag_submit', function() {
            const input = $('input', this);
            const tag = input.val();
            $.post('/solutions/add_tag.json', {"tag": tag, "id": $(this).data('solutionid') }, function(response) {
            });
            const html = '<li style="padding-left: 10px; padding-right: 10px; margin-right:6px; height: 21px; padding-top: 5px; margin-bottom:3px;">' + tag + '<a class="removeTag" href="#" style="margin-left: 10px;"> <img src="/img/delete-tag.png" alt="" style="padding-top: 2px;"></a></li>';
            const ul = $('ul[data-solutionid="' + $(this).data('solutionid') + '"]');
            ul.append(html);
            input.val('');
            if(ul.children(':visible').length > 4) {
                input.fadeOut(300)
            }
            return false;
        });

    });
}) ($);