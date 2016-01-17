<?php get_header(); ?>
	 <?php if(have_posts()) : while(have_posts()) : the_post(); ?>

	 <?php

	 $mods = get_theme_mods();
	 $artwork_np_class = 'artwork_np_thumb';
	 if( isset($mods[TEMPLATE_DOMAIN.'_options']) ){
	 	$artwork_np_class = $mods[TEMPLATE_DOMAIN.'_options']['artwork_np'] ? $mods[TEMPLATE_DOMAIN.'_options']['artwork_np'] : $artwork_np_class ;
	 }

	 ?>
         <section id="artwork-section" class="<?php echo $artwork_np_class ?>" >
<?php


				//cats
			$categories =  (array)get_the_terms( $post->ID, 'category' );
			$nav_cat_id = isset($wp_query->query_vars['mb_cat']) ? $wp_query->query_vars['mb_cat'] : current($categories)->term_id ;
			$nav_cat_term = '';
			foreach($categories as $struct) {
			    if ($nav_cat_id == $struct->term_id) {
			        $nav_cat_term = $struct->name;
			        break;
			    }
			}

			//echo('nav_cat_id: ' .$nav_cat_id);

			$prev  = mbudm_get_previous_artwork( $post->ID,$nav_cat_id);
			if($prev){
				$imgdata = mbudm_get_post_image_tag_and_caption($prev,MBUDM_IMAGESIZE_2);
				$url = add_querystring_var(get_permalink($prev), 'mb_action','prev');
				$url = add_querystring_var($url, 'mb_cat',$nav_cat_id);
				$prevMarkup = '<a class="prev" href="'. $url .'" title="Previous Artwork in '. $nav_cat_term  .': ' . $imgdata['caption']  . '" ><span>'. $imgdata['tag'] .'</span></a>';
			}else{
				$prevMarkup = '' ;
			}

			$next  = mbudm_get_next_artwork( $post->ID,$nav_cat_id);
			if($next){
				$imgdata = mbudm_get_post_image_tag_and_caption($next,MBUDM_IMAGESIZE_2);
				$url = add_querystring_var(get_permalink($next), 'mb_action','next');
				$url = add_querystring_var($url, 'mb_cat',$nav_cat_id);
				$nextMarkup = '<a class="next" href="'. $url .'" title="Next Artwork in '. $nav_cat_term  .': ' . $imgdata['caption']  . '" ><span>'. $imgdata['tag'] .'</span></a>';
			}else{
				$nextMarkup = '' ;
			}

			echo $prevMarkup . $nextMarkup ;

?>
			<div id="artwork-wrap" class="container_24">
<?php

    $existing_attachments = get_post_meta( $post->ID, '_attachments', false );
    $post_attachments = array();
    $thumb_id = get_post_thumbnail_id( $post->ID );

    if( is_array( $existing_attachments ) && count( $existing_attachments ) > 0 )
    {

        foreach ($existing_attachments as $attachment)
        {
            // decode and unserialize the data
            $data = unserialize( base64_decode( $attachment ) );
			//$is_thumb = $thumb_id == $data['id']? true : false ;
            array_push( $post_attachments, array(
                'id' 			=> stripslashes( $data['id'] ),
                'name' 			=> stripslashes( get_the_title( $data['id'] ) ),
                'mime' 			=> stripslashes( get_post_mime_type( $data['id'] ) ),
                'title' 		=> stripslashes( $data['title'] ),
                'caption' 		=> stripslashes( $data['caption'] ),
                'filesize'      => stripslashes( attachments_get_filesize_formatted( get_attached_file( $data['id'] ) ) ),
                'location' 		=> stripslashes( wp_get_attachment_url( $data['id'] ) ),
                'order' 		=> stripslashes( $data['order'] )
                ));

        }

		// sort attachments
        if( count( $post_attachments ) > 1 )
        {
            usort( $post_attachments, "attachments_cmp" );

            $artwork_display_class = 'grid_18 push_6';
            $artwork_meta_display_class = $artwork_display_class;
            $single_image_size = MBUDM_IMAGESIZE_18;
        }else{
        	$artwork_display_class = 'single-attachment no-grid';
            $artwork_meta_display_class = '';
        	$single_image_size = MBUDM_IMAGESIZE_WIDE_18;
        }

      	$default_active_image_id = $thumb_id ? $thumb_id : $post_attachments[0]['id'] ;

      	$active_image_id = isset($wp_query->query_vars['mb_aid']) ? $wp_query->query_vars['mb_aid'] : $default_active_image_id ;

      	?>
				<div class="<?php echo $artwork_display_class ?>" id="artwork-display">
		<?php echo mbudm_get_image($active_image_id,$single_image_size,false,'single-fig'); ?>
				</div>
		<?php if( count( $post_attachments ) > 1 ): ?>
        		<ul id="artwork-image-nav" class="grid_4 pull_18">
<?php
$output = '';
        foreach ($post_attachments as $att){

			$li_class = $att['id'] == $active_image_id  ? 'class="active-image"' : '' ;
			$output .= '<li ' . $li_class  .'> '. mbudm_get_image($att['id'],MBUDM_IMAGESIZE_4,true) . '</li>';
		}
		echo $output;
		?>
				</ul>
<?php
         endif;
	} // end if attachments > 0
 ?>

				</div><!-- # artwork-wrap container 24 -->
         <div id="artwork-content-wrap"  class="container_24">
			<?php

						$content = get_the_content();

						if(strlen($content) > 0) {
							?>
				<article id="artwork-content" class="grid_24">
							<!--<h2><?php echo get_the_title(); ?></h2>-->
							<?php
							$content = apply_filters('the_content', $content);
							$content = str_replace(']]>', ']]&gt;', $content);
							echo $content ?>
							</article>
						<?php
						}else{?>
						<article id="artwork-content" class="grid_24">
							<p><?= the_title() ?></p>
							</article>
						<?php
						}
			?>
			</div><!-- artwork-content-wrap -->
				<!--<aside id="contact-share" class="container_24 ">-->
					<?php get_sidebar('artwork-tabs'); ?>
				<!--</aside>-->
			</section><!-- #artwork-section -->
			<?php endwhile; ?>
			<?php else: ?>

			<article class="container_24 c24slim">
				<?php include (mbudm_get_template_file_path('noposts.php') ); ?>
			</article>

			<?php endif; ?>
			<!-- hide if empty -->
 			<?php get_sidebar('artwork-footer'); ?>

<?php get_footer(); ?>
