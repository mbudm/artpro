<?php
/**
 * Generic Page
 * @package WordPress
 * @subpackage ArtPro
 */

get_header(); ?>
<div id="primary" class="container_24">
	<div id="content" role="main"  class="grid_16 push_8">

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
				 <article id="page-content" >
				 <div class="inner">
					<?php get_template_part( 'content', get_post_format() ); ?>
					</div>
				 </article>
				<?php endwhile; ?>
			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'artpro' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'artpro' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
			<aside class="grid_8 pull_16">
	<?php get_sidebar('page');  ?>
	</aside>
</div><!-- #primary -->
<?php get_footer(); ?>
