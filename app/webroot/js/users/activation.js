$(document).ready(function() {
var success = getUrlVar('success')
	if (success=='false') {
		$('#popup-confirm-email-false').modal({  
			containerId: 'confirm-email-false',  
			opacity: 80,  
			closeClass: 'false-close',  
		});
	} else if (success=='true') {
		$('#popup-confirm-email-true').modal({  
			containerId: 'confirm-email-true',  
			opacity: 80,  
			closeClass: 'true-close',  
		});
	}
})

function getUrlVar(key){
	var result = new RegExp(key + "=([^&]*)", "i").exec(window.location.search);
	return result && unescape(result[1]) || "";
}