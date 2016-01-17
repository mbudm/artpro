<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage MBP
 * @since MBP 1.0
 */
 
 
get_header(); ?>
<div id="primary" class="container_24">
	<div id="content" role="main" >

			<header class="no-grid">
					<h1 class="entry-title"><?php echo single_cat_title( '', false ); ?></h1>

					<?php
						$category_description = category_description();
						if ( ! empty( $category_description ) )
							echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description .'</div>' );
					?>
				</header>
				
			<?php if ( have_posts() ) : ?>
				
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
	
					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>
			<?php else : ?>

				<?php get_template_part( 'noresult', get_post_format() ); ?>

			<?php endif; ?>

	</div><!-- #content -->
</div><!-- #primary -->
<?php get_footer(); ?>
