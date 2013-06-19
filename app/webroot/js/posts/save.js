$(document).ready(function() {

    //window.onbeforeunload = confirmExit;
    function confirmExit()
    {
        return "Вы собираетесь уйти с этой страницы, все несохранённые данные будут потеряны. Точно уходим?";
    }

    $('textarea').tinymce({
        // Location of TinyMCE script
        script_url : '/js/tinymce4/tinymce.min.js',

        // General options
        //theme : "advanced",
        //plugins : "jbimages,images,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,typograf,spellchecker",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        // Theme options
        toolbar : "styleselect fontselect fontsizeselect | bold italic underline strikethrough | justifyleft justifycenter justifyright justifyfull | outdent indent blockquote | paste pastetext pasteword spellchecker typograf | bullist numlist",
        /*theme_advanced_buttons2 : "images,image,|,charmap,link,unlink,anchor,cleanup,help,code,|,undo,redo",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,*/
        content_css : "/css/wysiwyg.css",
        language : "ru",
        height : "480",
        relative_urls: false,
        spellchecker_languages : "+Russian=ru,English=en",
        // Style formats
        style_formats : [
            {title : 'Заголовок 3 bold', inline : 'span', classes: "greyboldheader"},
            {title : 'Заголовок 3  синий', inline : 'span', classes: "blueboldheader"},
            {title : 'Основной текст', inline : 'span', classes: "regular"},
            {title : 'Заголовок 1', inline : 'span', classes: "largest-header"},
            {title : 'Дополнение', inline : 'span', classes: "supplement"},
            {title : 'Дополнение 2', inline : 'span', classes: "supplement2"},
            {title : 'Дополнение 3', inline : 'span', classes: "supplement3"}
        ]



    });


    $( ".datepicker" ).datetimepicker({ dateFormat: "yy-mm-dd", timeFormat: 'hh:mm:ss', showSecond: true});

    $(document).on('click', '#save', function(){
        $.post('/posts/save.json', $('form').serialize(), function(response) {
            if(response.id) {
                $('#id').val(response.id);
                $('#preview').show().attr('href', $('#preview').attr('href') + response.id);
                alert('Пост сохранен!');
            }
        });
        return false;
    });

});