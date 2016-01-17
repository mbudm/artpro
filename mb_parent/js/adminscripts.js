jQuery(document).ready(function ($) {
    $( "#artwork-order ul" ).sortable({
      placeholder: "ui-state-highlight",
   	  update: function( event, ui ) {
   	  	//console.log('sortable update',event, ui,$(this));
   	  	var newOrder = [];
   	  	$(this).children('li').each(function(i){
   	  		newOrder.push($(this).attr('id').replace('mb_artwork_','') )
   	  	})
   	  	var fieldName = $(this).attr('id');
   	  	var field = $('input[name="'+fieldName+'"]');
   	  	field.val(newOrder.join(','));
   	  	console.log('sortable update',fieldName,field,newOrder);
   	  }
    });
    $( "#artwork-order ul" ).disableSelection();
});
