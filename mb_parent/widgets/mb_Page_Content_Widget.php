<?php
/**
 * Page_Content Widget class. Adds page content as a widget.
 * @Parameters
 *  - title
 *  - page/post grab content from
 */
class mb_Page_Content_Widget extends WP_Widget {
 	var $defaults;
 	
 	 private $mb_widget_class_options;
	 
	/**
	 * Widget setup.
	 */
	function mb_Page_Content_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_page_content', 'description' => esc_html__("A widget that shows the content from a page.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-page-content-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-page-content-widget', esc_html__('MBU: Page/Post Content', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
	
		$this->defaults = array( 
		'title' => '', 
		'post_id' => '',
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		global $post;
		
		extract( $args );

		/* Our variables from the widget settings. */
		$title = $instance['title'];
		$post_id = $instance['post_id'];
		
		$content_post = get_post($post_id);
		$content_formatted = wpautop( $content_post->post_content );
		
		//print_r($post);
		
		//echo("post id " . $post->ID);
		//echo("content id " . $post_id);
		
		if($post->ID != $post_id){

			/* Before widget (defined by themes). */
			echo $before_widget;
	
			?>
		<?php 
			if(!empty($title)){
		?>
		
			<h1><?php echo $title; ?></h1>
			<?php 
			}
	?>
			<?php echo $content_formatted ?>
		
			<?php
	
			/* After widget (defined by themes). */
			echo $after_widget;
		
		}
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for for text inputs only . */
		$instance['title'] = $new_instance['title'];
		$instance['post_id'] = $new_instance['post_id'] ;
		
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
			<small><?php _e('Ok to leave blank - it can handle that.', TEMPLATE_DOMAIN) ?></small>
		</p>
		<?php
		$all_pages_and_posts = get_pages();
		?>
		<p>
		<label for="<?php echo $this->get_field_id('post_id'); ?>"><?php _e( 'Select  a page:' ,TEMPLATE_DOMAIN); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('post_id'); ?>" name="<?php echo $this->get_field_name('post_id'); ?>">
		<?php
		foreach ( $all_pages_and_posts as $item ) {
			$selected = $item->ID == $instance['post_id'] ? 'selected="selected"' : '' ;
			echo '<option value="' . intval($item->ID) . '"'
				. $selected . '>' . $item->post_title . "</option>\n";
		}
		?>
		</select>
		</p>
<?php
	}
}
?>
