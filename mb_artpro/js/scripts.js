jQuery(document).ready(function ($) {
	
	$('.swiper').mbswiper();

	$(".swiper-controller a").qtip()
	
	/* prepare markup for jquery ui tabs */
	var $artTabs = $("#sidebar-artwork-tabs").first();
	if($artTabs && !$artTabs.hasClass('ui-tabs')){
		var $ul = $('<ul class="" />');
		var $divs = $artTabs.children()
		$divs.each(function(i){
			$li = $('<li />');
			$heading = $(this).find('h2').first().text()
			var tabHash = '#'+$(this).attr('id');
			//add the hash to any form actions in the tab
			$(this).find('form[action]').each(function(idx){
				var hashAction = $(this).attr('action') + tabHash;
				$(this).attr('action',hashAction);
			});
			
			$a = $('<a >'+$heading+'</a>');
			$a.attr('href',tabHash)
			$li.append($a)
			$ul.append($li)
		});
		$artTabs.prepend($ul);
		
		//check for a selected tab
		var active = 0;
		
		$artTabs.tabs();
		
		$artTabs.bind("tabsshow", function(event, ui) { 
    		if(history.pushState) {
    			history.pushState(null, null, ui.tab.hash);
			}else{
				window.location.hash = ui.tab.hash;
			}
  		});
	}
	
	/* artwork n/p */
	if($('#artwork-section.artwork_np_thumb').length > 0){
		var outerSpace = $('#artwork-section .container_24').offset().left;
		$("#artwork-section > a").width(outerSpace);
		
		
		$("#artwork-section > a").hover(function () {
			if($(this).hasClass('next')){
			 var span = $(this).children('span');
				$(this).children('span').animate({width: 70 },200);
			}else{
				$(this).children('span').animate({textIndent:0,width:70},200);
			}
		  },
		  function () {
			if($(this).hasClass('next')){
				$(this).children('span').animate({width: 0 },200);
			}else{
				$(this).children('span').animate({textIndent:-70,width:0},200);
			}
		  }
		);
		
		// get mb_action  - trigger a hover so user doesn't have to move mouse to get hover state (there mouse is likely still over the next or prev button)
		if(jQuery.QueryString && jQuery.QueryString['mb_action']){
			var mb_action = jQuery.QueryString['mb_action'];
			switch(mb_action){
				case 'next':
					$("#artwork-section > a.next").trigger('mouseover');
				break;
				case 'prev':
					$("#artwork-section > a.prev").trigger('mouseover');
				break;
			}
		}
	}

});

