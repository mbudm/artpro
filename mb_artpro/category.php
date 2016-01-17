<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage ArtPro
 * @since ArtPro 1.0
 */

get_header(); ?>

			<?php 
			//query_posts( array('post_type' => array(MBUDM_PT_ARTWORK,'posts'), 'cat' => $cat) );
			$alist_args = array('tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field' => 'id',
						'terms' => $cat
					)
				),
				'post_type' => 'mbudm_artwork',
				'posts_per_page'=>-1,
				'orderby' => 'title',
				'order' => 'ASC'
			);
			// The Query
			$the_alist_query = new WP_Query( $alist_args );
			
			$mods = get_theme_mods();
			$artwork_category_size = $mods[TEMPLATE_DOMAIN.'_options']['artwork_category_size'];
			$artwork_category_size  = $artwork_category_size ? $artwork_category_size : MBUDM_IMAGESIZE_WIDE_6 ;
			$omega_li = false;
			switch($artwork_category_size ){
				case MBUDM_IMAGESIZE_WIDE_6 :
					$ul_class = 'fluid-artwork-list';
					$li_class = '';
					$img_size = $artwork_category_size;
				break;
				case MBUDM_IMAGESIZE_5:
					$ul_class = 'grid-artwork-list';
					// use MBUDM_IMAGESIZE_PREFIX to strip out and leave grid number
					
					$li_class = 'grid_fifth';
					//$omega_li = 4; // zero based index
					$img_size = MBUDM_IMAGESIZE_5;
				break;
				default:
					$ul_class = 'grid-artwork-list';
					// use MBUDM_IMAGESIZE_PREFIX to strip out and leave grid number
					
					$li_class =  str_replace(MBUDM_IMAGESIZE_PREFIX,'grid_',$artwork_category_size);
					
					$img_size = $artwork_category_size;
				break;
			}
			
			if($the_alist_query->post_count > 0):
			
			//if ( have_posts() ) : ?>
			<section class="container_24">
				<header class="no-grid">
					<h1 class="entry-title"><?php echo single_cat_title( '', false ); ?></h1>

					<?php
						$category_description = category_description();
						if ( ! empty( $category_description ) )
							echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description .'</div>' );
					?>
				</header>
				<?php /* Start the Loop */ ?>
				<ul class="<?php echo $ul_class ?>"><?php 
				
				$cat_meta = get_option( "taxonomy_" . $cat  ); 
				$order_arr = explode(",",$cat_meta['cat_artwork_order']);
				
				if(count($order_arr) == $the_alist_query->post_count ){
					foreach($order_arr as $key=>$aid){
					
					?><li class="<?php 
					echo $li_class;
					if($omega_li !== false && $key == $omega_li){ echo ' omega';}
					?>"><?php 
						echo mbudm_get_post_image($aid,$img_size ,true); 
				?></li><?php 
					}
				}else{
					while ( $the_alist_query->have_posts() ) : $the_alist_query->the_post();
					?><li class="<?php echo $li_class ?>"><?php 
						echo mbudm_get_post_image(get_the_ID(),$img_size ,true); 
				?></li><?php 
					endwhile; 
					
					// Reset Post Data
					wp_reset_postdata();
				}
					?></ul>
					</section>
			<?php else : ?>

				<article id="post-0" class="post no-results not-found container_24">
					<header>
						<h1 class="entry-title"><?php echo single_cat_title( '', false ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no artwork was found for the ' . single_cat_title( '', false ) . ' category. Perhaps searching will help find something you\'ll like.', 'artpro' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>
			
	<aside class="container_24">
	<?php get_sidebar('category'); ?>
	</aside>
<?php get_footer(); ?>
