jQuery(document).ready( function($) {
	var selected = jQuery('#widget_column').val();
	jQuery('.theme-footer-columns').find('a').each(function(){
		if(jQuery(this).attr('rel')==selected){
			jQuery(this).addClass('current');
		}
		jQuery(this).click(function(){
			jQuery('#widget_column').val(jQuery(this).attr('rel'));
			jQuery('.theme-footer-columns').find('.current').removeClass('current');
			jQuery(this).addClass('current');
		})
	});
	
});