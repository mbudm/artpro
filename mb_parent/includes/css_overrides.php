<?php

/* css overrides for any theme mods */
global $mbudm_font_sizes;
$mods = get_theme_mods();
if( isset($mods[TEMPLATE_DOMAIN.'_options']) ){
	$artpro_mods = get_theme_mod(TEMPLATE_DOMAIN.'_options',true);

	include('functions.color.php');
	
	//key color 
	$key_color = $artpro_mods['key_color'];
	
	$key_color_shade = colourBrightness($key_color,-0.2);
	
	//base color 
	$base_color = $artpro_mods['base_color'];
	
	ob_start();
	?>
	<style>
	
	<?php
	if(isset($artpro_mods['artwork_captions']) && !$artpro_mods['artwork_captions']){
	?>
	figcaption{ display:none;}
	<?php
	}
	?>
	header#branding h1,header#branding h2
	{
	font-family:'<?php echo $artpro_mods['branding_font'] ?>',Trebuchet,Arial,Helvetica,sans-serif;
	font-size:<?php echo $artpro_mods['branding_font_size'] ?>;
	letter-spacing:<?php echo $artpro_mods['branding_font_spacing'] ?>;
	}
	body{font-family:'<?php echo $artpro_mods['body_font'] ?>',Trebuchet,Arial,Helvetica,sans-serif;font-size:<?php echo $artpro_mods['body_font_size'] ?>;}
		h1,h2{font-family:'<?php echo $artpro_mods['heading_font'] ?>',
Georgia,Times,serif;}
<?php
	$h_els = array('h1','h2','h3','h4');
	$h1_reached = false;
	$reversed_fs = array_reverse($mbudm_font_sizes);
	foreach($reversed_fs as $fskey => $fsValue){
		if($fskey == $artpro_mods['heading_font_size']){
			$h1_reached = true;
		} 
		if($h1_reached){
			echo current($h_els) . '{font-size:'.$fskey.';}';
			if(!next($h_els)){
				break;
			}
		}
	}
	
?>

	/*nav
	nav#access{
		font-size:<?php echo $artpro_mods['branding_font_size'] ?>;
	} */
		nav#access .menu li>a,
		#sidebar-artwork-tabs>ul{font-family:'<?php echo $artpro_mods['nav_font'] ?>',
Trebuchet,Arial,Helvetica,sans-serif;}
		#menu-toggle,
		nav#access .menu li
		{font-size:<?php echo $artpro_mods['nav_font_size'] ?>;}
		<?php if(isset($artpro_mods['branding_font_size']) ): ?>
		nav#access .menu li>a{
		line-height:<?php echo $artpro_mods['branding_font_size'] ?>;
		}
		<?php endif; ?>
		
		<?php echo '/* nav font type:' .$artpro_mods['nav_font_type'] . '*/'; ?>
		
		/* key color */
		a,
		header#branding #site-description{color:<?php echo $artpro_mods['key_color'] ?>;}
		#artwork-image-nav li.active-image,
		nav#access div.menu>ul>li.current-menu-item>a{border-color:<?php echo $artpro_mods['key_color'] ?>;}
		article.gridIndex footer .cat-links a,
		a.btn,
		.errors{background:<?php echo $artpro_mods['key_color'] ?>;}

		
		/* base color - practically white */
		a.btn,
		input[type=submit],
		button,
		a.comment-reply-link,
.form-messages{color:<?php echo $artpro_mods['base_white'] ?>;}
		figure a,
		ul.grid-artwork-list li figure,
		.mb_widget_featured_artwork .widget-inner,
		.swiper-parent a.next span, 
		.swiper-parent a.prev span,
		input,
		textarea,
		#artwork-display,
		#artwork-image-nav,
		ul.img-list,
		.simple_single figure,
		#page-content,
		.comment_text{background-color:<?php echo $artpro_mods['base_white'] ?>;}
		ul.fluid-artwork-list figure a,
		.simple_tile li figure a,
		.simple_large li figure a{border-color:<?php echo $artpro_mods['base_white'] ?>;}
		h1,
		h2{text-shadow:-1px 1px 0 rgba(<?php echo hex2rgbstr($artpro_mods['base_white']) ?>,0.5);}
		
		
		/* base color - ultra light */
		body,#sidebar-artwork-tabs>ul li.ui-tabs-active{background:<?php echo $artpro_mods['base_background'] ?>;}
		input[type=submit]:hover,button:hover,
		article.gridIndex footer .cat-links a
		{color:<?php echo $artpro_mods['base_background'] ?>;}
		
	<?php 
	
	$half_white = colourBlend($artpro_mods['base_white'],$artpro_mods['base_background'],0.5);
	
	?>
		
	/* from very light to white */
		ul.menu ul.sub-menu{
			background: -moz-linear-gradient(<?php echo $artpro_mods['base_background'] ?>, <?php echo $half_white ?>); /* FF 3.6+ */ 
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, <?php echo $artpro_mods['base_background'] ?>), color-stop(100%, <?php echo $half_white ?>)); /* Safari 4+, Chrome 2+ */ 
			background: -webkit-linear-gradient(<?php echo $artpro_mods['base_background'] ?>, <?php echo $half_white ?>); /* Safari 5.1+, Chrome 10+ */	
			background: -o-linear-gradient(<?php echo $artpro_mods['base_background'] ?>, <?php echo $half_white ?>); /* Opera 11.10 */ 
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $artpro_mods['base_background'] ?>', endColorstr='<?php echo $half_white ?>'); /* IE6 & IE7 */ 
			-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $artpro_mods['base_background'] ?>', endColorstr='<?php echo $half_white ?>')"; /* IE8+ */ 
			background: linear-gradient(<?php echo $artpro_mods['base_background'] ?>, <?php echo $half_white ?>); /* the standard */
		}
		
		/* horizontal - very light to white and back again */
		#home-content,#artwork-content{
			
			background-image: -o-linear-gradient(left , <?php echo $artpro_mods['base_background'] ?> 0%, <?php echo $half_white ?> 45%, <?php echo $half_white ?> 55%, <?php echo $artpro_mods['base_background'] ?> 100%);
		background-image: -moz-linear-gradient(left , <?php echo $artpro_mods['base_background'] ?> 0%, <?php echo $half_white ?> 45%, <?php echo $half_white ?> 55%, <?php echo $artpro_mods['base_background'] ?> 100%);
			background-image: -webkit-linear-gradient(left , <?php echo $artpro_mods['base_background'] ?> 0%, <?php echo $half_white ?> 45%, <?php echo $half_white ?> 55%, <?php echo $artpro_mods['base_background'] ?> 100%);
			background-image: -ms-linear-gradient(left , <?php echo $artpro_mods['base_background'] ?> 0%, <?php echo $half_white ?> 45%, <?php echo $half_white ?> 55%, <?php echo $artpro_mods['base_background'] ?> 100%);
			background-image: -webkit-gradient(linear, left bottom, right bottom, color-stop(0, <?php echo $artpro_mods['base_background'] ?>), color-stop(0.45, <?php echo $half_white ?>), color-stop(0.55, <?php echo $half_white ?>), color-stop(1, <?php echo $artpro_mods['base_background'] ?>));
			background-image: linear-gradient(left , <?php echo $artpro_mods['base_background'] ?> 0%, <?php echo $half_white ?> 45%, <?php echo $half_white ?> 55%, <?php echo $artpro_mods['base_background'] ?> 100%);
		}
		nav#access div.menu>ul>li.current-menu-item>a:hover{border-color:<?php echo $artpro_mods['base_background'] ?>;}
		
		/* base color - very light */
		.swiper-parent a.next:hover span, 
		.swiper-parent a.prev:hover span,
		.swiper-controller a,
		#sidebar-home-tertiary .widget{background:<?php echo $artpro_mods['base_very_light']  ?>;}
		#home-content{border-color:<?php echo $artpro_mods['base_very_light']  ?>;}
		
		/* base color - light */
		.swiper-parent a.next:hover span, 
		.swiper-parent a.prev:hover span,
		.faux-hr{background:<?php echo $mods['base_light'] ?>;}
		.widget-inner,
		#artwork-content,
		input,textarea, 
		section header,
		#artwork-image-nav,
		#sidebar-artwork-tabs>div,
		.comment_body .inner{border-color:<?php echo $artpro_mods['base_light'] ?>;}
		
		/* base tint */
		#sidebar-home-tertiary .widget:nth-child(even),
		.light-btn-links a{background:<?php echo $artpro_mods['base_tint'] ?>;}
		.fluid-img-list figure, 
		ul.grid-artwork-list li figure,
		.swiper-parent, 
		#artwork-display,ul.img-list,
		#page-content,
		article.gridIndex{
<?php 
		/* only have shadow if there is a difference between background and artwork background  - this allows for a completely one color bg */
		
		if($artpro_mods['base_background'] == $artpro_mods['base_white']){
?>
		-moz-box-shadow: none;
  		-webkit-box-shadow: none;
  		-o-box-shadow: none;
  		box-shadow: none;
<?php
		}else{
			$base_tint_rgb =  hex2rgbstr($artpro_mods['base_tint']); 
?>
		-moz-box-shadow: rgba(<?php echo $base_tint_rgb; ?>, 0.3) 0 0 20px;
		-webkit-box-shadow: rgba(<?php echo $base_tint_rgb; ?>, 0.3) 0 0 20px;
		-o-box-shadow: rgba(<?php echo $base_tint_rgb; ?>, 0.3) 0 0 20px;
		box-shadow: rgba(<?php echo $base_tint_rgb; ?>, 0.3) 0 0 20px;
<?php } ?>
}
<?php 
		/* also only have page padding if there is a difference between background and artwork background  */
		
		if($artpro_mods['base_background'] == $artpro_mods['base_white']){
?>
		#page-content .inner {
			padding:0;
		}
<?php } ?>
		/* base color - shade */
		figcaption,
		small,
		th,
		ul.menu li a,
		footer #site-generator a,
		h1,
		h2,
		#artwork-meta dt,
		#sidebar-artwork-tabs>ul li a,
		.comment-vcard time span{color:<?php echo $artpro_mods['base_color'] ?>}
		#sidebar-home-tertiary .widget,
		.menu ul.sub-menu{border-color:<?php echo $artpro_mods['base_color'] ?>}
		.light-btn-links a:hover{background:<?php echo $artpro_mods['base_color'] ?>}
		
		/* base color - dark */
		.swiper-controller a:hover,
		input[type=submit],
		button,
		a.comment-reply-link,
		#artwork-section.artwork_np_thumb > a > span{background:<?php echo $artpro_mods['base_dark'] ?>;}
		#sidebar-home-tertiary .widget:nth-child(even) h2,
		.comment-vcard time,
		.light-btn-links a{color:<?php echo $artpro_mods['base_dark'] ?>;}
		
		/* base color - very dark */
		article.gridIndex footer .cat-links a:hover,
		.swiper-controller a.active,
		input[type=submit]:hover,
		button:hover,
		a.comment-reply-link:hover,
		#artwork-section.artwork_np_thumb > a:hover > span,
		.alerts,
		a.btn:hover{background:<?php echo $artpro_mods['base_very_dark'] ?>;}
		
		/* base color - practically black */
		
		header h1 a,
		ul.menu li a:hover,
		ul.menu li a:focus,
		nav#access div.menu>ul>li.current-menu-item>a,
		#sidebar-artwork-tabs>ul li.ui-tabs-active a,
		#sidebar-artwork-tabs>ul li a:hover,
		.light-btn-links a:hover{color:<?php echo $artpro_mods['base_black'] ?>;}
		
		
		article.gridIndex{background:<?php echo $artpro_mods['base_very_dark'] ?>;}
		article.gridIndex:hover,
		article.gridIndex.has-thumb{background:<?php echo $artpro_mods['base_white'] ?>;}
		
		
		article.gridIndex h1 a{color:<?php echo $artpro_mods['base_color'] ?>;}
		
		article.gridIndex:hover h1 a{
			color:<?php echo $artpro_mods['base_very_dark'] ?>;
		}
		
		
		@media only screen and (max-width: 995px) {

			#menu-toggle,
			nav#access .menu-wrap{
			background:<?php echo $artpro_mods['key_color'] ?>;}
			#menu-toggle:hover{
			background:<?php echo $artpro_mods['base_dark'] ?>;}
			#menu-toggle .bar{
			background:<?php echo $artpro_mods['base_white'] ?>;
			}
			nav#access .menu li{
				border-bottom-color:<?php echo $key_color_shade; ?>;
			}
			nav#access .menu li a{
				color:<?php echo $artpro_mods['base_white'] ?>;
			}
			nav#access .menu li a:hover{
				color:<?php echo $artpro_mods['base_black'] ?>;
			}
			nav#access .menu ul.sub-menu{
				background:none;
			}
			nav#access .menu ul.sub-menu li a{
				background: <?php echo $key_color_shade; ?>;
			}
		}
	</style>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
?>
