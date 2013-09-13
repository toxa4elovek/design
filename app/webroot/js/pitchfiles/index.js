$(document).ready(function() {
    if (typeof iframeResponse != 'undefined') {
        $('#filezone', parent.document).html($('#filezone', parent.document).html() + '<li data-id="' + iframeResponse.id + '"><a style="float:left;width:100px" class="filezone-filename" href="#">' + iframeResponse.basename + '</a><a style="float:right;width:100px;margin-left:0" class="filezone-delete-link" href="#" data-imfromiframe="true">удалить</a><div style="clear:both"></div><p style="font-size:15px;text-decoration: none;">' + iframeResponse['file-description'] + '</p></li>');
        parent.Cart.fileIds.push(+iframeResponse.id);
        if (parent.Cart.id) {
            parent.Cart.saveFileIds();
        }
    }

    $('#fileupload-description').focus(function() {
        if ($(this).val() == $(this).data('placeholder')) {
            $(this).val('');
            $(this).css('color', '#000');
        }
    });
    $('#fileupload-description').blur(function() {
        if ($(this).val() == '') {
            $(this).val($(this).data('placeholder'));
            $(this).css('color', '#CCC');
        }
        if ($(this).val() != $(this).data('placeholder')) {
            $('#file-description').val($(this).val());
        } else {
            $('#file-description').val('');
        }
    });
});
