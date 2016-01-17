<?php
/*
Template Name: MBU Home 
*/
?>

<?php get_header(); ?> 
			<!-- home-top - for full width banner -->
			<?php get_sidebar('home-hero'); ?>
			
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
			
			$cc = get_the_content();
			if($cc !== ''){
			?>  
			
			<article id="home-content" class="container_24 c24slim">
   			    <?php the_content(); ?>
			</article>
   			<?php 
   			}
   			endwhile; endif; ?> 
			
			<?php  get_sidebar('home-secondary'); ?>
			
			<?php  get_sidebar('home-tertiary'); ?>
			
<?php get_footer(); ?>  
