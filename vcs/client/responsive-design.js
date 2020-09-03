$(function(){
	$('.main-menu .how-to').click(function(){
		$('.how-to-desc').slideToggle('fast');
	});
	$('.main-menu .exchange').click(function(){
		$('.exchange-desc').slideToggle('fast');
	});
	$('.main-menu .create').click(function(){
		if ( $('.room-config').css('display') == 'none' ) {
			$('.user-config').slideUp('fast');
		}
		else {
			$('.user-config').slideDown('fast');
		}
		$('.room-config').slideToggle('fast');
	});
	
});