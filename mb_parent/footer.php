<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage ArtPro
 * @since ArtPro 1.0
 */
?>

	</div><!-- #main -->
	<footer id="colophon" role="contentinfo" class="container_24">
			<hr class="grid_24" />
			
			<?php  
			if(is_active_sidebar('footer-sbar' )  ) {
				get_sidebar( 'footer-sbar' );
?>
			<hr class="grid_24" />
<?php
	}
			?>
			
			<?php $footer_menu  = wp_nav_menu( array( 'theme_location' => 'footer_nav', 'depth' => 1, 'container_class' => 'grid_16 push_8' ,'fallback_cb' => false) ); ?>
			
			
			<?php
			
			$copyright_class = $footer_menu ? 'grid_8 pull_16' : 'grid_24' ;
			
			?>
			<?php
			$mods = get_theme_mods();
$theme_mods = isset($mods[TEMPLATE_DOMAIN.'_options']) ? $mods[TEMPLATE_DOMAIN.'_options'] : false ;
if($theme_mods){
?>
			<div id="copyright_notice" class="<?php echo $copyright_class ?>">
				<p><?php echo $theme_mods['copyright_notice'] ?></p>
			</div>
<?php
}
?>
			
			
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
