<?php
echo ('<!-- include loaded -->');
/* css overrides for any theme mods */
global $mbudm_font_sizes;
$mods = get_theme_mods();
if( isset($mods[TEMPLATE_DOMAIN.'_options']) ){
	$artpro_mods = $mods[TEMPLATE_DOMAIN.'_options'];

	ob_start();
	?>
	<style>
	nav#access .menu li>a{
		line-height:<?php echo $artpro_mods['branding_font_size'] ?>;
	}
	#artwork-section.artwork_np_arrow > a.prev > span{
		border-right-color: <?php echo $artpro_mods['base_dark'] ?>;
	}
	#artwork-section.artwork_np_arrow > a.next > span {
	   border-left-color: <?php echo $artpro_mods['base_dark'] ?>;
	}
	
	#artwork-section.artwork_np_arrow > a.prev:hover > span{
		border-right-color: <?php echo $artpro_mods['base_very_dark'] ?>;
	}
	#artwork-section.artwork_np_arrow > a.next:hover > span {
	   border-left-color: <?php echo $artpro_mods['base_very_dark'] ?>;
	}
	
	</style>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}else{
echo '<!-- no them mods found from child theme -->';
}

?>
