<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage ArtPro
 * @since ArtPro 1.0
 */
 $gridIndex = false;
 $hasthumb = has_post_thumbnail() ? 'has-thumb' : '';
 
 if(!is_single() || !is_page() || !is_search()){
	 $gridIndex = $wp_query->post_count < 4 ? false : true ;
 }
 
 $extra_post_class = $gridIndex ? 'gridIndex grid_6 '.$hasthumb : 'plainIndex' ;
 $thumbSize =  $gridIndex ? MBUDM_IMAGESIZE_THUMB_6 : MBUDM_IMAGESIZE_4 ;
 $idStr = is_single() ? 'page-content' : 'post-'. get_the_ID();
?>

	<article id="<?php echo $idStr; ?>" <?php post_class($extra_post_class); ?>>
		<div class="inner">
		<header class="entry-header cfix">
			<?php if ( is_sticky() ) : ?>
				<hgroup>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'artpro' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<h3 class="entry-format"><?php _e( 'Featured', 'artpro' ); ?></h3>
				</hgroup>
			<?php else : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'artpro' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<?php endif; ?>

			<?php if ( is_single() ) : ?>
			
				<?php if ( comments_open() && ! post_password_required() ) : ?>
				<div class="comments-link light-btn-links">
					<?php comments_popup_link( '<span class="leave-reply">' . __( 'Reply', 'artpro' ) . '</span>', _x( '1', 'comments number', 'artpro' ), _x( '%', 'comments number', 'artpro' ) ); ?>
				</div>
				<?php endif; ?>
				
				<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php mbudm_posted_on(); ?>
				</div><!-- .entry-meta -->
				<?php endif; ?>
	
				
			<?php endif; // End if_single ?>
		</header><!-- .entry-header -->
		<?php if ( is_single() || is_page() ) : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'artpro' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'artpro' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php elseif ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<?php mb_featured_image($thumbSize); ?>
		<div class="entry-summary">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'artpro' ) ); ?>
		</div><!-- .entry-summary -->
		<?php endif; ?>
		<?php
				/* translators: used between list items, there is a space after the comma 				*/
				$categories_list = get_the_category_list( __( ', ', 'artpro' ) );
			
			?>
		<?php if ( is_single() || !$gridIndex ) : ?>
		<footer class="entry-meta cfix">
			<small>
			<?php 	if ( $categories_list ): ?>
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			
			<span class="cat-links">
				<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'artpro' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
				 ?>
			</span>
			<?php endif; // End if categories ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'artpro' ) );
				if ( $tags_list ):
				?>
			<span class="tag-links">
				<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'artpro' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
				?>
			</span>
			<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == get_post_type() ?>
			
			</small>
			
			<?php if ( comments_open() ) : ?>
			
			<span class="comments-link light-btn-links"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'artpro' ) . '</span>', __( '<b>1</b> Reply', 'artpro' ), __( '<b>%</b> Replies', 'artpro' ) ); ?></span>
			<?php endif; // End if comments_open() ?>

			<?php edit_post_link( __( 'Edit', 'artpro' ), '<span class="edit-link light-btn-links">', '</span>' ); ?>
		</footer><!-- #entry-meta -->
		<?php else: ?>
		
			<footer class="entry-meta">
			<span class="cat-links">
			<?php echo get_the_category_list(); ?>
			</span>
			</footer>
		<?php endif; // End if_single ?>
		</div> <!-- end inner -->
	</article><!-- #post-<?php the_ID(); ?> -->
