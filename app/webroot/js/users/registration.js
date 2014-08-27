$(document).ready(function() {
    $('#UserEmail').on('focusout', function() {
        console.log($('#UserEmail').val());
        var filter = /^([\w-\.]+@(?!mail.ru)(?!inbox.ru)(?!list.ru)(?!bk.ru)([\w-]+\.)+[\w-]{2,4})?$/
        console.log(filter.test($('#UserEmail').val()));
        if (!filter.test($('#UserEmail').val())) {
            $('#popup-email-warning').modal({
                containerId: 'gotest-email-warning',
                opacity: 80,
                closeClass: 'gotest-close',
                onOpen: function(e) {
                    e.data.show();
                    e.container.show();
                    e.overlay.fadeIn();
                },
                onClose: function(e) {
                    e.data.hide();
                    e.container.hide();
                    e.overlay.fadeOut(function() {
                        $.modal.close();
                    })
                }
            });
        }
    });
})