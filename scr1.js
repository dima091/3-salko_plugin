jQuery(document).ready(function() {
	/*alert(jQuery("#stw").html());
	if (jQuery("#stw").html() == 'Too match updates!' || jQuery("#stw").html() == 'Incorrect twitter information') {
		stw = 30;
	}
	else {
		stw = parseFloat(jQuery("#stw").css('height'));
	}*/
	jQuery('#twit_btn').attr('disabled', true);
	setTimeout(function () {jQuery('#twit_btn').attr('disabled', false);}, 4000);
	jQuery("#salko-twitter-feed-widget").css('height', parseFloat(jQuery("#salko-twitter-feed-widget").css('height')) + parseFloat(jQuery("#stw").css('height')) + 'px');
	jQuery('#twit_btn').click(function() {
		jQuery('#twit_btn').attr('disabled', true);
		jQuery("#salko-twitter-feed-widget").removeClass('salko-twitter-feed-widget-in');
		jQuery("#salko-twitter-feed-widget").addClass('salko-twitter-feed-widget-out');
		
		setTimeout(function () {
		jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			type: "POST",
			data: "action=my_sajax",
			success: function(html){
				jQuery("#stw").html(html);
				stw = parseFloat(jQuery("#stw").css('height'));
				jQuery("#salko-twitter-feed-widget").removeClass('salko-twitter-feed-widget-out');
				
				jQuery("#salko-twitter-feed-widget").addClass('salko-twitter-feed-widget-in');
				jQuery("#salko-twitter-feed-widget").css('height', parseFloat(jQuery("#salko-twitter-feed-widget").css('height')) + stw + 'px');
				} 
			}); 
			setTimeout(function () {jQuery('#twit_btn').removeAttr('disabled');}, 6000);
		}, 3000);
	});
	
});