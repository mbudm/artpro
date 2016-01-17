<?php 

switch($display_mode){
	case MBUDM_HERO_TILE:
		$image_size = MBUDM_IMAGESIZE_6;
		$classes = $display_mode . " container_24";
	break;
	case MBUDM_HERO_LARGE:
		$image_size = MBUDM_IMAGESIZE_8;
		$classes = $display_mode . " container_24";
	break;
	case MBUDM_HERO_SINGLE:
		$image_size = MBUDM_IMAGESIZE_WIDE_18;
		$classes = $display_mode . " container_24";
	break;
	case MBUDM_HERO_SWIPER:
		$image_size = MBUDM_IMAGESIZE_BANNER;
		$classes = $display_mode;
	break;
}

$sticky_array = get_option( 'sticky_posts' );
$args = array(
			'post__in' => $sticky_array,
			'post_type' => MBUDM_PT_ARTWORK,
			'orderby' => 'rand'
	);
$query = new WP_Query( $args );


if( $query->have_posts() ){ 
?>
<ul class="<?php echo $classes ?>" ><?php
while ( $query->have_posts() ) : $query->the_post();
	$fp_id		= get_the_ID();
	?><li>
	<?php echo mbudm_get_post_image($fp_id,$image_size,true); ?>
	</li><?php 
endwhile; ?></ul>
<?php
}

wp_reset_query();

?>
