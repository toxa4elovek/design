$(document).ready(function(){
	$('#trigger').click(function() {
		$('.trigger-block').toggle();
		if($('#trigger').hasClass('more')) {
			$('#trigger').removeClass('more').addClass('more_down');
		}else {
			$('#trigger').removeClass('more_down').addClass('more');
		}
		return false;
	})
})