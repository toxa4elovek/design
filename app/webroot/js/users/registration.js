$(document).ready(function() {
    $('#UserEmail').on('focusout',function(){
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
				e.overlay.fadeOut(function(){
					$.modal.close();
				})
            }
		});
	});
})