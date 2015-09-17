;(function($) {
    $(function () {

        $('#nofile').click(function () {
            $('#nofiles-warning').modal({
                containerId: 'generic-popup',
                opacity: 80,
                closeClass: 'popup-close'
            });
            return false;
        });

        $('#confirm').click(function () {
            $('#important-confirm').modal({
                containerId: 'generic-popup',
                opacity: 80,
                closeClass: 'popup-close'
            });
            return false;
        });

        $('#confirmWinner').click(function () {
            window.location = ($('#confirm').attr('href'));
        });

    });
}) ($);