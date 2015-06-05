$(document).ready(function() {

    /*window.onbeforeunload = confirmExit;
    function confirmExit()
    {
        if (tinyMCE.activeEditor.isDirty() || isUndo) {
            return "Вы собираетесь уйти с этой страницы, все несохранённые данные будут потеряны. Точно уходим?";
        }
    }*/

    tinymce.init({
        selector: "textarea",
        content_css: "/css/brief_wysiwyg.css",
        language: "ru",
        height: "540",
        width: '538',
        relative_urls: false,
        remove_script_host: false,
        menubar: false,
        plugins: ["link,lists,charmap,paste,image,code"],
        toolbar: "styleselect,link,bullist,numlist,charmap,image,alignleft,aligncenter,alignright,alignjustify,code",
        style_formats: [
            {title : 'Заголовок 3 bold', inline : 'span', classes: "greyboldheader"},
            {title : 'Заголовок 3  синий', inline : 'span', classes: "blueboldheader"},
            {title : 'Основной текст', inline : 'span', classes: "regular"},
            {title : 'Заголовок 1', inline : 'span', classes: "largest-header"},
            {title : 'Дополнение', inline : 'span', classes: "supplement"},
            {title : 'Дополнение 2', inline : 'span', classes: "supplement2"},
            {title : 'Дополнение 3', inline : 'span', classes: "supplement3"}
        ],
        file_browser_callback: RoxyFileBrowser
    });

    function RoxyFileBrowser(field_name, url, type, win) {
        var roxyFileman = '/js/fileman/index.html';
        if (roxyFileman.indexOf("?") < 0) {
            roxyFileman += "?type=" + type;
        }
        else {
            roxyFileman += "&type=" + type;
        }
        roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
        if(tinyMCE.activeEditor.settings.language){
            roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
        }
        tinyMCE.activeEditor.windowManager.open({
            file: roxyFileman,
            title: 'Roxy Fileman',
            width: 850,
            height: 650,
            resizable: "yes",
            plugins: "media",
            inline: "yes",
            close_previous: "no"
        }, {     window: win,     input: field_name    });
        return false;
    }

    /*
    $('textarea').tinymce({
        // Location of TinyMCE script
        script_url : '/js/tiny_mce/tiny_mce.js',

        // General options
        theme : "advanced",
        plugins : "jbimages,images,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,typograf,spellchecker",

        // Theme options
        theme_advanced_buttons1 : "styleselect,fontselect,fontsizeselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,blockquote,|,paste,pastetext,pasteword,spellchecker,typograf,|,bullist,numlist",
        theme_advanced_buttons2 : "images,image,|,charmap,link,unlink,anchor,cleanup,help,code,|,undo,redo",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        content_css : "/css/wysiwyg.css",
        language : "ru",
        height : "480",
        relative_urls: false,
        remove_script_host: false,
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
        ],
        
        onchange_callback : function(editor) {
            isUndo = true;
        }

        // Example content CSS (should be your site CSS)
        //content_css : "css/content.css",
    });*/

    $( ".datepicker" ).datetimepicker({ dateFormat: "yy-mm-dd", timeFormat: 'hh:mm:ss', showSecond: true});

    $(document).on('click', '#save', function(){
        var data  = {};
        if($('input[name=id]').length > 0) {
            data['id'] = $('input[name=id]').val();
        }
        data['title'] = $('input[name=title]').val();
        data['short'] = tinyMCE.get('short').getContent();
        data['full'] = tinyMCE.get('fulltext').getContent();
        data['created'] = $('input[name=created]').val();
        data['imageurl'] = $('input[name=imageurl]').val();
        data['tags'] = $('input[name=tags]').val();
        data['published'] = $('#published').is(':checked')  ? 1 : 0;
        $.post('/posts/save.json', data, function(response) {
            if(response.id) {
                $('#id').val(response.id);
                var preview = $('#preview');
                preview.show().attr('href', preview.attr('href') + response.id);
                alert('Пост сохранен!');
            }
        });
        return false;
    });
    
    $(document).on('click', '.post_preview', function(e) {
        var data  = {};
        if($('input[name=id]').length > 0) {
            data['id'] = $('input[name=id]').val();
        }
        data['title'] = $('input[name=title]').val();
        data['short'] = tinyMCE.get('short').getContent();
        data['full'] = tinyMCE.get('fulltext').getContent();
        data['created'] = $('input[name=created]').val();
        data['imageurl'] = $('input[name=imageurl]').val();
        data['tags'] = $('input[name=tags]').val();
        $.post('/posts/save.json', data, function(response) { } );
    });

    $('#typeahead').textext({
        plugins : 'tags arrow suggestions autocomplete prompt',
        prompt : 'Выберите из списка',
        tagsItems : existingTags,
        suggestions: commonTags,
        autocomplete: {
            dropdownPosition: 'below'
        }
    }).bind('isTagAllowed', function(e, data){
        var formData = $(e.target).textext()[0].tags()._formData,
        list = eval(formData);

    // duplicate checking
    if (formData.length && list.indexOf(data.tag) >= 0) {
           var message = [ 'Такой тег уже добавлен.' ].join(' ');
           alert(message);

           data.result = false;
    }});

});
