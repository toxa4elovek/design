$(document).ready(function(){
    $(".select_checkbox").click(function(){
        /*var list = [];
        $.each($('.select_checkbox:checked'), function(index, obj) {
            list.push($(obj).data('id'));
        });*/

        $.post('/solutions/saveSelected.json', {"selectedSolutions": $(this).data('id')}, function(response) {
        })
        //return false;
    })
})