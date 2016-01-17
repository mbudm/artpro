jQuery(document).ready(function ($) {
	
	$('#menu-toggle').click(function(e) { 
    	$(this).parent().toggleClass('menu-active');
    	e.preventDefault();
  	});
	
	$("figure a").each(function(i){
		//remove title & alt attrs
		$(this).attr('oldtitle',$(this).attr('title'))
		$(this).removeAttr('title');
		
		$(this).find('img').each( function(i){
			$(this).attr('oldtitle',$(this).attr('title'))
			$(this).removeAttr('alt title');
		});
		
		var $tip = $(this).parents('figure').find('figcaption').not(':empty');
		
	});

});
