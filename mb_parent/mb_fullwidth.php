<?php
/*
Template Name: MBU Full width 
*/
/*
 * Fullwidth Page
 * @package WordPress
 * @subpackage ArtPro
 */

get_header(); ?>
<div id="primary" class="container_24">
	<div id="content" role="main"  class="grid_24">

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
