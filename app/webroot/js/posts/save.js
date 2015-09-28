"use strict";

;(function ($, tinymce) {

    $(function () {

        var pressedKeys = {};

        tinymce.init({
            selector: "textarea",
            content_css: "/css/brief_wysiwyg.css",
            language: "ru",
            height: "540",
            width: '538',
            relative_urls: false,
            remove_script_host: false,
            menubar: false,
            plugins: ["link,lists,charmap,paste,image,code,spellchecker,visualchars"],
            toolbar: ["styleselect,bold,italic,underline,strikethrough,link,bullist,numlist,charmap,image", "alignleft,aligncenter,alignright,alignjustify,outdent,indent,blockquote,spellchecker,code,removeformat,visualchars"],
            style_formats: [{ title: 'Заголовок 3 bold', inline: 'span', classes: "greyboldheader" }, { title: 'Заголовок 3  синий', inline: 'span', classes: "blueboldheader" }, { title: 'Основной текст', inline: 'span', classes: "regular" }, { title: 'Заголовок 1', inline: 'span', classes: "largest-header" }, { title: 'Дополнение', inline: 'span', classes: "supplement" }, { title: 'Дополнение 2', inline: 'span', classes: "supplement2" }, { title: 'Дополнение 3', inline: 'span', classes: "supplement3" }],
            spellchecker_language: 'ru_RU',
            spellchecker_rpc_url: 'spellcheck.php',
            file_browser_callback: RoxyFileBrowser,
            setup: function setup(editor) {
                editor.on('keydown', function (e) {
                    pressedKeys[e.which] = true;
                    if (pressedKeys[18] == true && pressedKeys[32] == true) {
                        e.preventDefault();
                        editor.execCommand('mceInsertContent', false, '&nbsp;');
                    }
                });

                editor.on('keyup', function (e) {
                    delete pressedKeys[e.which];
                });
            }
        });

        function RoxyFileBrowser(field_name, url, type, win) {
            var roxyFileman = '/js/fileman/index.html';
            if (roxyFileman.indexOf("?") < 0) {
                roxyFileman += "?type=" + type;
            } else {
                roxyFileman += "&type=" + type;
            }
            roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
            if (tinyMCE.activeEditor.settings.language) {
                roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
            }
            tinyMCE.activeEditor.windowManager.open({
                file: roxyFileman,
                title: 'Загрузка картинок',
                width: 850,
                height: 650,
                resizable: "yes",
                plugins: "media",
                inline: "yes",
                close_previous: "no"
            }, { window: win, input: field_name });
            return false;
        }

        $(".datepicker").datetimepicker({ dateFormat: "yy-mm-dd", timeFormat: 'hh:mm:ss', showSecond: true });

        $(document).on('click', '#save', function () {
            var data = {};
            if ($('input[name=id]').length > 0) {
                data['id'] = $('input[name=id]').val();
            }
            data['title'] = $('input[name=title]').val();
            data['short'] = tinyMCE.get('short').getContent();
            data['full'] = tinyMCE.get('fulltext').getContent();
            data['created'] = $('input[name=created]').val();
            data['imageurl'] = $('input[name=imageurl]').val();
            data['tags'] = $('input[name=tags]').val();
            data['published'] = $('#published').is(':checked') ? 1 : 0;
            $.post('/posts/save.json', data, function (response) {
                if (response.id) {
                    $('#id').val(response.id);
                    var preview = $('#preview');
                    preview.show().attr('href', preview.attr('href') + response.id);
                    alert('Пост сохранен!');
                }
            });
            return false;
        });

        $(document).on('click', '.post_preview', function (e) {
            var data = {};
            if ($('input[name=id]').length > 0) {
                data['id'] = $('input[name=id]').val();
            }
            data['title'] = $('input[name=title]').val();
            data['short'] = tinyMCE.get('short').getContent();
            data['full'] = tinyMCE.get('fulltext').getContent();
            data['created'] = $('input[name=created]').val();
            data['imageurl'] = $('input[name=imageurl]').val();
            data['tags'] = $('input[name=tags]').val();
            $.post('/posts/save.json', data);
        });

        $('#typeahead').textext({
            plugins: 'tags arrow suggestions autocomplete prompt',
            prompt: 'Выберите из списка',
            tagsItems: existingTags,
            suggestions: commonTags,
            autocomplete: {
                dropdownPosition: 'below'
            }
        }).bind('isTagAllowed', function (e, data) {
            var formData = $(e.target).textext()[0].tags()._formData,
                list = eval(formData);
            if (formData.length && list.indexOf(data.tag) >= 0) {
                var message = ['Такой тег уже добавлен.'].join(' ');
                alert(message);
                data.result = false;
            }
        });

        setInterval(function () {
            if ($('input[name=id]').length > 0 && $('input[name=id]').val() != '') {
                $.get('/posts/updateEditTime/' + $('input[name=id]').val() + '.json');
            }
        }, 30000);
    });
})($, tinymce);