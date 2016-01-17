<?php
/**
 * Featured Artwork Widget class.
 * @Parameters
 *  - title
 *  - display_mode
 */
class mb_Featured_Artwork_Widget extends WP_Widget {
	 var $defaults;
	 var $display_modes;
	 
	/**
	 * Widget setup.
	 */
	function mb_Featured_Artwork_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mb_widget_featured_artwork', 'description' => esc_html__("Shows the featured artwork in multiple ways.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-featured-artwork-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-featured-artwork-widget', esc_html__('MBU: Featured Artwork', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->display_modes = array( 
			'simple_tile' => __('Simple Tile', TEMPLATE_DOMAIN), 
			'simple_large' => __('Simple Large', TEMPLATE_DOMAIN),
			'simple_single' => __('Simple Single Image', TEMPLATE_DOMAIN),
			'swiper' => __('Swiper', TEMPLATE_DOMAIN)
		);
	
		$this->defaults = array( 
			'title' => __('', TEMPLATE_DOMAIN), 
			'display_mode' => 'simple_tile',
			'grid_set' => false
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$display_mode = strlen($instance['display_mode']) ? $instance['display_mode'] : 'simple_cycle' ;
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes).*/
		if ( $title )
		    echo $before_title . $title . $after_title;

		// get featured artwork 
		mbudm_get_featured_artwork($display_mode);
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for for text inputs only . */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['display_mode'] = strip_tags( $new_instance['display_mode'] );
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
			<small><?php _e('For this widget the title appears above the hero in lighter smaller text.', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display_mode' ); ?>"><?php _e('Display mode:',TEMPLATE_DOMAIN); ?></label>
			<select id="<?php echo $this->get_field_id( 'display_mode' ); ?>" name="<?php echo $this->get_field_name( 'display_mode' ); ?>" class="widefat">
			<?php 
			foreach($this->display_modes as $k => $v){ ?>
				<option value="<?php echo $k ?>" <?php if ( $k == $instance['display_mode'] ) echo 'selected="selected"'; ?>><?php echo $v ?></option>
				<?php } ?>
			</select>
		</p>
	
		
<?php
	}
}
?>
