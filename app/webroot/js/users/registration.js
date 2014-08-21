$(document).ready(function() {
    $('#UserEmail').on('focusout',function(){
		$('#popup-email-warning').modal({  
			containerId: 'gotest-email-warning',  
			opacity: 80,  
			closeClass: 'gotest-close',  
		});
	});
})