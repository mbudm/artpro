<?php
/**
 * Recent Posts Widget class.
 * @Parameters
 *  - number_posts
 *  - more_text
 */
class mb_Recent_Posts_Widget extends WP_Widget {
 	var $defaults;
	/**
	 * Widget setup.
	 */
	function mb_Recent_Posts_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_recent_posts', 'description' => esc_html__("A recent posts widget that displays dates and titles.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-recent-posts-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-recent-posts-widget', esc_html__('MBU: Recent Posts', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->defaults = array( 
		'title' => '', /* if empty then it uses the get_option('page_for_posts'); */
		'show_dates' => true,
		'number_posts' => 5, 
		'more_text' => 'More posts'
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = $instance['title'];
		$show_dates = $instance['show_dates'];
		$num_posts = $instance['number_posts'];
		$more_text = $instance['more_text'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		$posts_page_id = get_option('page_for_posts');
		if($posts_page_id){
			$posts_page = get_page( $posts_page_id);
			if(empty($title))
				$title = $posts_page->post_title;
			$posts_page_url = get_permalink( $posts_page_id ); 
		}
		if(!empty($title)){
		?><h3><?php echo $title; ?></h3><?php } ?>
					<ul class="<?php if($show_dates){ echo 'show_dates'; }else{ echo 'no_dates'; } ?>" >
					<?php
						$recent_args = array('post_type'=>'post','posts_per_page'=>$num_posts);
						$recent = new WP_Query($recent_args );
						while($recent->have_posts()) : $recent->the_post();
					?>
						<li>
						<?php if($show_dates): ?><div><?php the_date('d M Y'); ?></div><?php endif; ?><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						</li>
   					<?php endwhile;
   					
   					// Reset Post Data
					wp_reset_postdata();
					if($posts_page_id){
					 ?>
						<li class="more"><a href="<?php echo $posts_page_url; ?>" title="<?php echo $title; ?>"> <?php echo $more_text; ?></a></li><?php } ?>
					</ul>
		<?php
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for for text inputs only . */
		$instance['title'] = $new_instance['title'];
		$instance['show_dates'] = $new_instance['show_dates'];
		$instance['number_posts'] = $new_instance['number_posts'];
		$instance['more_text'] = strip_tags( $new_instance['more_text'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<!-- Widget Title: MBU Recent Posts -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat"/>
			<small><?php _e('If left blank the title of the page for posts will be used', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_dates' ); ?>">
			    <input class="checkbox" type="checkbox" <?php checked( $instance['show_dates'], true ); ?> id="<?php echo $this->get_field_id( 'show_dates' ); ?>" name="<?php echo $this->get_field_name( 'show_dates' ); ?>" value="1" <?php checked('1', $instance['show_dates']); ?> />
			    <?php _e('Show the dates published', TEMPLATE_DOMAIN); ?>
			</label>
			</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_posts' ); ?>"><?php _e('Number of posts', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'number_posts' ); ?>" type="text" name="<?php echo $this->get_field_name( 'number_posts' ); ?>" value="<?php echo $instance['number_posts']; ?>" /><br/>
			<small><?php _e('The number of post links to show', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'more_text' ); ?>"><?php _e('More  link text', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'more_text' ); ?>" type="text" name="<?php echo $this->get_field_name( 'more_text' ); ?>" value="<?php echo $instance['more_text']; ?>" class="widefat" />
			<small><?php _e('The more link appears below the recent posts (only if there are more posts to show).', TEMPLATE_DOMAIN) ?></small>
		</p>
		
<?php
	}
}
?>
