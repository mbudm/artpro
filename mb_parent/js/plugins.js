jQuery(document).ready(function ($) {

	$.QueryString = (function(a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i)
        {
            var p=a[i].split('=');
            if (p.length != 2) continue;
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    })(window.location.search.substr(1).split('&'));
    
});



/* 
   mbSwiper for jQuery v1.0.
   Written by Steve Roberts using a design pattern from Keith Wood (kwood{at}iinet.com.au) http://keith-wood.name/maxlength.html May 2009.
   Dual licensed under the GPL (http://dev.jquery.com/browser/trunk/jquery/GPL-LICENSE.txt) and 
   MIT (http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt) licenses. 
   Please attribute the author if you use it. */

(function($) { // Hide scope, no $ conflict

var PROP_NAME = 'mbswiper';

/* mbswiper manager. */
function MbSwiper() {
	
	this._defaults = {
		child_el: "li",
		child_target_el: "a",
		child_target_att: "title"
	}
	this._instances = [];
}

$.extend(MbSwiper.prototype, {
	/* Class name added to elements to indicate already configured with mbswiper. */
	markerClassName: 'mbsw-on',
	parentClassName: 'swiper-parent',
	controllerClasses: 'swiper-controller',
	queryParameter: 'mb_swidx',
	pagination_scale: 10,
	
	
	/* Override the default settings for all mbswiper instances.
	   @param  settings  (object) the new settings to use as defaults
	   @return  (MbSwiper) this object */
	setDefaults: function(settings) {
		$.extend(this._defaults, settings || {});
		return this;
	},

	/* Attach the mbswiper functionality to an element.
	   @param  target    (element) the control to affect
	   @param  settings  (object) the custom options for this instance */
	_attachSwiper: function(target, settings) {
		target = $(target);
		if (target.hasClass(this.markerClassName)) {
			return;
		}
		
		var inst = {settings: $.extend({}, this._defaults)};
		$.data(target[0], PROP_NAME, inst);
		
		/* Required: target must have 1 or more swiper children */
		if(target.children(settings.child_el).length == 0){
			console.log("ALERT: mbswiper cannot be attached. target has 0 child "+settings.child_el+" els:",target);
			return;
		}
		
		target.addClass(this.markerClassName);
		
		this._instances.push(target);
	
		this._createSwiper(target, settings);
	},

	/* Create the elements required for a mbswiper control. Reconfigure the settings.
	   @param  target    (element) the control to affect
	   @param  settings  (object) the new options for this instance or
	                     (string) an individual property name
	   @param  value     (any) the individual property value (omit if settings is an object) */
	_createSwiper: function(target, settings, value) {
		target = $(target);
		if (!target.hasClass(this.markerClassName)) {
			return;
		}
		var me = this; //ref to here for loops
		settings = settings || {};
		if (typeof settings == 'string') {
			var name = settings;
			settings = {};
			settings[name] = value;
		}
		var inst = $.data(target[0], PROP_NAME);
		$.extend(inst.settings, settings);
		
		/* has valid parent? The swiper needs a parent block element equal in size to the swiper element. If this doesn't pass then create one.*/
		if(target.parent().css('display') !=  "block"  || target.parent().width() > target.width()){
			target.wrap('<div />');
			//if this fails then user css is screwing it up, so quit.
			if(target.parent().css('display') !=  "block"  || target.parent().width() != target.width()){
				console.log("ALERT: mbswiper cannot be created. failed in attempt to create a parent element of same size as target and display:block :",target);
				console.log('display:'+target.parent().css('display') )
				console.log('target w:'+target.width())
				console.log('target parent w:'+target.parent().width())
				return;
			}
		}
		target.parent().addClass(this.parentClassName)
		
		/* store a ref to the target in the trigger elements data object */
		var trigInst = {mbswiperTarget: target};
		
		// get current item - it may be a query parameter
		var startItem  = 0;
		if(jQuery.QueryString && jQuery.QueryString[this.queryParameter]){
			startItem = jQuery.QueryString[this.queryParameter];
		}
		
		/* create elements: swiper twin, pagination, next / prev  */
		//twin
		var target_twin = target.clone()
		var target_triplet = target.clone()
		target.parent().append(target_twin)
		target.parent().append(target_triplet)
		target_twin.css( 'left' , (-target.width()))
		target_triplet.css( 'left' , (target.width()))
		
		//pagination
		var $pagination = $('<div class="'+this.controllerClasses+'"></div>');
		target.parent().after($pagination);
		target.children('li').each(function(i){
			var w = $(this).width() / me.pagination_scale;
			var $nav = $('<a />');
			var nav_title = $(this).find(inst.settings.child_target_el).first().attr(inst.settings.child_target_att)
			$nav.attr('title',nav_title)
			$pagination.append($nav);
			$nav.width(Math.round(w));
			$.data($nav[0], PROP_NAME, trigInst);
			$nav.bind('click.mbswiper',{t: target},function(event) { 
				$.mbswiper._updatePosition(event.data.t,null,$(this).index());
			});
			if(i == startItem){
				$nav.addClass('active');
			}
		});
		//next and prev elements
		var $next = $('<a class="next" href="#next"><span>Next</span></a>');
		$next.width($pagination.offset().left);
		target.parent().append($next);
		$.data($next[0], PROP_NAME, trigInst);
		$next.bind('click.mbswiper',{t: target},function(event) { 
			$.mbswiper._updatePosition(event.data.t,'next',null);
		});
		var $prev = $('<a class="prev" href="#prev"><span>Previous</span></a>');
		$prev.width($pagination.offset().left);
		target.parent().append($prev);
		$.data($prev[0], PROP_NAME, trigInst);
		$prev.bind('click.mbswiper',{t: target},function(event) { 
			$.mbswiper._updatePosition(event.data.t,'prev',null);
		});
		
		/* store refs for easy access */
		inst.settings.pagination = $pagination;
		inst.settings.targets = [target_twin,target,target_triplet]; // order is important, reflects their physical (l-r) order
		inst.settings.curr_swiper_index = 1;//always the middle one
		inst.settings.currItem = startItem;
		
		$.mbswiper._updatePosition(target,null,startItem);
	},

	
	/* swiper update position  function 
	   @param  target  (element) the original swiper element
	   @param  direction  (String) 'next' or 'previous' (optional) 
	   @param  newIndex  (Boolean) swiper child index to move to (optional)  */
	_updatePosition: function(target,direction,newItem) {
		target = $(target);
		var inst = $.data(target[0], PROP_NAME);
		var default_left = inst.settings.pagination.offset().left + parseInt(inst.settings.pagination.css( 'padding-left' ))
		var swiper_w = target.width()
		
		var curr_slide_left = inst.settings.targets[inst.settings.curr_swiper_index].children().eq(inst.settings.currItem).position().left
		var curr_swiper_left = inst.settings.targets[inst.settings.curr_swiper_index].position().left
		var curr_left = curr_swiper_left + curr_slide_left
		
		var default_align = 0;
		if(curr_left != default_left){
			default_align = default_left - curr_left
		}
		
		var newMiddle;
		var newSwiperIndex;
		
		if(direction){
			//animate in the direction of the newItem
			//this may require to shuffle a swiper left or right
			switch(direction){
				case 'next':
					newItem = inst.settings.currItem + 1
					if(newItem == target.children().length){
						newItem = 0
						var leftmost = inst.settings.targets.shift()
						inst.settings.targets.push(leftmost)
						leftmost.css( 'left' , (curr_swiper_left + (2 * swiper_w) )  )
					}
				break;
				case 'prev':
					newItem = inst.settings.currItem - 1
					if(newItem < 0){
						newItem = target.children().length -1
						var rightmost = inst.settings.targets.pop()
						inst.settings.targets.unshift(rightmost)
						rightmost.css( 'left' , (curr_swiper_left - (2 * swiper_w) )  )
					}
				break;
			}
		}else{
			//simple - animate to the current item within the focused swiper
			direction = inst.settings.currItem > newItem ? 'prev' : 'next' ;
		}
		
		var newItemLeft = target.children().eq(newItem).position().left;
		var new_left = inst.settings.targets[inst.settings.curr_swiper_index].position().left + newItemLeft - default_align
		
		var dist = curr_left - new_left;
		if(dist != 0){
			// animate the swiper parent so that target slide of target swiper is current.
			var dur = Math.round((Math.log(Math.abs(dist)) * 100));
			for(var i = 0; i < inst.settings.targets.length; i++){
				var tgt = inst.settings.targets[i]
				var thisleft = parseInt(tgt.css('left'))
				var calc_pos = thisleft + dist;
				tgt.animate({left: calc_pos },dur);
				console.log(i + " - thisLeft:"+ thisleft+" calc_pos:"+calc_pos+" dur:"+dur); 
			}
		}
		// show selected
		inst.settings.pagination.children('a').each(function(){
			$(this).removeClass('active');
		});
		inst.settings.pagination.children('a').eq(newItem).addClass('active');
		
		inst.settings.currItem = newItem;
	},
	
	/* Remove the mbswiper functionality from a control.
	   @param  target  (element) the control to affect */
	_destroySwiper: function(target) {
		target = $(target);
		if (!target.hasClass(this.markerClassName)) {
			return;
		}
		target.removeClass(this.markerClassName).
			unbind('.mbswiper');
		$.removeData(target[0], PROP_NAME);
		
		/* TODO: remove created elements */
	},

	/* Retrieve the current instance settings.
	   @param  target  (element) the control to check
	   @return  (object) the current instance settings */
	_settingsSwiper: function(target) {
		var inst = $.data(target, PROP_NAME);
		return inst.settings;
	}
});

// The list of commands that return values and don't permit chaining
var getters = ['settings'];

/* Attach the mbswiper functionality to a jQuery selection.
   @param  command  (string) the command to run (optional, default 'attach')
   @param  options  (object) the new settings to use for these instances (optional)
   @return  (jQuery) for chaining further calls */
$.fn.mbswiper = function(options) {
	var otherArgs = Array.prototype.slice.call(arguments, 1);
	if ($.inArray(options, getters) > -1) {
		return $.mbswiper['_' + options + 'Swiper'].
			apply($.mbswiper, [this[0]].concat(otherArgs));
	}
	return this.each(function() {
		if (typeof options == 'string') {
			$.mbswiper['_' + options + 'Swiper'].
				apply($.mbswiper, [this].concat(otherArgs));
		}
		else {
			$.mbswiper._attachSwiper(this, options || {});
		}
	});
};

/* Initialise the mbswiper functionality. */
$.mbswiper = new MbSwiper(); // singleton instance

})(jQuery);



/* 
   Minimiser for jQuery v1.0.1.
   Written by Steve Roberts (steve{at}mbudm.com) using a design pattern from Keith Wood (kwood{at}iinet.com.au) http://keith-wood.name/maxlength.html May 2009.
   Dual licensed under the GPL (http://dev.jquery.com/browser/trunk/jquery/GPL-LICENSE.txt) and 
   MIT (http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt) licenses. 
   Please attribute the author if you use it. */

(function($) { // Hide scope, no $ conflict

var PROP_NAME = 'minimiser';

/* minimiser manager. */
function Minimiser() {
	
	this._defaults = {
		trigger_element: ":first",
		enable_width: 9999,
		isEnabled: false,
		isOpen: true,
		accordionMode: false
	}
	this._instances = [];
}

$.extend(Minimiser.prototype, {
	/* Class name added to elements to indicate already configured with mobify. */
	markerClassName: 'minimiser',
	isOpenClass: 'minIsOpen',
	triggerClassName: 'minTrigger',
	
	
	/* Override the default settings for all minimiser instances.
	   @param  settings  (object) the new settings to use as defaults
	   @return  (Minimiser) this object */
	setDefaults: function(settings) {
		$.extend(this._defaults, settings || {});
		return this;
	},

	/* Attach the minimiser functionality to an element.
	   @param  target    (element) the control to affect
	   @param  settings  (object) the custom options for this instance */
	_attachMinimiser: function(target, settings) {
		target = $(target);
		if (target.hasClass(this.markerClassName)) {
			return;
		}
		target.addClass(this.markerClassName);
		
				
		var inst = {settings: $.extend({}, this._defaults)};
		$.data(target[0], PROP_NAME, inst);
		
	
		this._instances.push(target);
	
		this._changeMinimiser(target, settings);
	},

	/* Reconfigure the settings for a minimiser control.
	   @param  target    (element) the control to affect
	   @param  settings  (object) the new options for this instance or
	                     (string) an individual property name
	   @param  value     (any) the individual property value (omit if settings is an object) */
	_changeMinimiser: function(target, settings, value) {
		target = $(target);
		if (!target.hasClass(this.markerClassName)) {
			return;
		}
		settings = settings || {};
		if (typeof settings == 'string') {
			var name = settings;
			settings = {};
			settings[name] = value;
		}
		var inst = $.data(target[0], PROP_NAME);
		$.extend(inst.settings, settings);
		
		/* bind to resize event */
		$(window).bind('resize.minimiser' ,{t: target},function(event) {
		 	$.minimiser._enableMinimiser(event.data.t);
		 });
		
		/* store the trigger element */
		inst.settings.trigElt = target.children(inst.settings.trigger_element);
		
		/* store a ref to the target in the trigElt data object */
		var trigInst = {minimiserTarget: target};
		$.data(inst.settings.trigElt[0], PROP_NAME, trigInst);
		
		if(!inst.settings.trigElt.hasClass(this.triggerClassName)){
			inst.settings.trigElt.addClass(this.triggerClassName);
		}
		
		/* apply bind to trigger element */
		inst.settings.trigElt.bind('click.minimiser',{t: target},function(event) { 
				$.minimiser._updateMinimiser(event.data.t);
				//$(this).siblings().slideToggle(); 
		});
		$.minimiser._enableMinimiser(target);
	},
	/* bound to the resize event - each instance does an enable check
	   @param  target  (element) the control to check */
	_enableMinimiser: function(target) {
		target = $(target);
		var inst = $.data(target[0], PROP_NAME);
		var enableCheck = $(window).width() <= inst.settings.enable_width ? true : false ;
		if(enableCheck !== inst.settings.isEnabled){
			inst.settings.isEnabled = enableCheck;
			$.minimiser._updateMinimiser(target);
		}else{
			
		}
	},
	
	/* slidetoggle wrapper function 
	   @param  target  (element) the control to check
	   @param  showOrhide  (Boolean) control the hide/show state  */
	_updateMinimiser: function(target,showOrhide) {
		target = $(target);
		var inst = $.data(target[0], PROP_NAME);
		
		/* opening or closing ? */
		if(inst.settings.isEnabled){
			if(showOrhide === undefined){ 
				inst.settings.isOpen = !inst.settings.isOpen
			}else{
				inst.settings.isOpen = showOrhide;
			}
		}else{
			//show 
			inst.settings.isOpen = true;
		}
			
		/* do open/close and set class */
		if(inst.settings.isOpen){
			var sibs = inst.settings.trigElt.siblings();
			sibs.removeClass('hidden');
			sibs.slideDown('fast'); 
			if (!target.hasClass(this.isOpenClass)) {
				target.addClass(this.isOpenClass);
			}
			if(inst.settings.accordionMode){
				//close everything but this one
				$.minimiser._accordionMinimiser(target);
			}
		}else{
			var sibs = inst.settings.trigElt.siblings();
			sibs.slideUp('fast', function() {
			   // Animation complete.
			   $(this).addClass('hidden');
			}); 
			if (target.hasClass(this.isOpenClass)){
				target.removeClass(this.isOpenClass);	
			}
		}
	},
	/* Close all instances except for target.
	   @param  target  (element) the control to affect */
	_accordionMinimiser: function(target) {
		target = $(target);
		for(var inst in this._instances){
			if(this._instances[inst][0] != target[0]){
				$.minimiser._updateMinimiser(this._instances[inst],false);
			}
		}
	},
	/* Remove the minimiser functionality from a control.
	   @param  target  (element) the control to affect */
	_destroyMinimiser: function(target) {
		target = $(target);
		if (!target.hasClass(this.markerClassName)) {
			return;
		}
		target.removeClass(this.markerClassName).
			unbind('.minimiser');
		$.removeData(target[0], PROP_NAME);
	},

	/* Retrieve the current instance settings.
	   @param  target  (element) the control to check
	   @return  (object) the current instance settings */
	_settingsMinimiser: function(target) {
		var inst = $.data(target, PROP_NAME);
		return inst.settings;
	}
});

// The list of commands that return values and don't permit chaining
var getters = ['settings'];

/* Attach the minimiser functionality to a jQuery selection.
   @param  command  (string) the command to run (optional, default 'attach')
   @param  options  (object) the new settings to use for these instances (optional)
   @return  (jQuery) for chaining further calls */
$.fn.minimiser = function(options) {
	var otherArgs = Array.prototype.slice.call(arguments, 1);
	if ($.inArray(options, getters) > -1) {
		return $.minimiser['_' + options + 'Minimiser'].
			apply($.minimiser, [this[0]].concat(otherArgs));
	}
	return this.each(function() {
		if (typeof options == 'string') {
			$.minimiser['_' + options + 'Minimiser'].
				apply($.minimiser, [this].concat(otherArgs));
		}
		else {
			$.minimiser._attachMinimiser(this, options || {});
		}
	});
};

/* Initialise the minimiser functionality. */
$.minimiser = new Minimiser(); // singleton instance

})(jQuery);
