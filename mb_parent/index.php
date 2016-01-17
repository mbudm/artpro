<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ArtPro
 */

get_header(); ?>
<div id="primary" class="container_24">
<?php if(is_single()){ ?>

	<div id="content" role="main"  class="grid_16 push_8">
	<?php }else{ ?>
	<div id="content" role="main" >
	<?php } ?>

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>
			<?php else : ?>

				  <?php get_template_part( 'noresult', get_post_format() ); ?>

			<?php endif; ?>

			</div><!-- #content -->
			
	<?php if(is_single()){ ?>
	<aside class="grid_8 pull_16">
	<?php
	get_sidebar(); 
	?>
	</aside>
</div><!-- #primary -->
<section class="container_24">
	<?php comments_template( '/comments.php' ); ?>
</section>
	<?php }else{ ?>
</div><!-- #primary -->
<?php } ?>
<?php get_footer(); ?>
