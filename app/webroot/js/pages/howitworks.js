$(document).ready(function(){
	$('#trigger').click(function() {
		$('.trigger-block').toggle();
		if($('#trigger').hasClass('more')) {
			$('#trigger').removeClass('more').addClass('more_down');
		}else {
			$('#trigger').removeClass('more_down').addClass('more');
		}
		return false;
	});

	$('.contacts-form').submit(function() {
	    var info = clientInfo();
	    var els = '';
	    for (var key in info) {
	        els += '<input type="hidden" name="info[' + key + ']" value="' + info[key] + '" />';
	    }
	    $(this).prepend(els);
	    return true;
	});
})