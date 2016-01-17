<?php
/**
 * Text Widget class.
 * @Parameters
 *  - inverted scheme
 *  - Free text
 *  - style mode
 *  - parsed for shortcodes
 */
class mb_Text_Widget extends WP_Widget {
	 public $defaults;
	 
	/**
	 * Widget setup.
	 */
	function mb_Text_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_text', 'description' => esc_html__("A text widget that has no title and has a variety of styles", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-text-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-text-widget', esc_html__('MBU: Free Text', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$default_dm = 'plain';
		$this->display_modes = array( 
			$default_dm => __('Plain', TEMPLATE_DOMAIN), 
			'edged' => __('Edged', TEMPLATE_DOMAIN),
			'shaded' => __('Shaded', TEMPLATE_DOMAIN),
			'edged-shaded' => __('Edged and Shaded', TEMPLATE_DOMAIN)
		);
		
		$this->defaults = array( 
			'invert_color' => false, 
			'freetext' => '',			
			'display_mode' => $default_dm
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$class_name = $instance['invert_color'] ? 'inverted ' : ' ' ;
		$class_name .=$instance['display_mode'];
		$freetext = do_shortcode( $instance['freetext'] );
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		echo '<div class="'. $class_name .'" >'. $freetext .'</div>';
	
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Don't strip tags - this widget is allowed html . */
		$instance['invert_color'] = $new_instance['invert_color'];
		$instance['freetext'] = $new_instance['freetext'];
		$instance['display_mode'] = $new_instance['display_mode'];

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
			<label for="<?php echo $this->get_field_id( 'invert_color' ); ?>">
			    <input class="checkbox" type="checkbox" <?php checked( $instance['invert_color'], true ); ?> id="<?php echo $this->get_field_id( 'invert_color' ); ?>" name="<?php echo $this->get_field_name( 'invert_color' ); ?>" value="1" <?php checked('1', $instance['invert_color']); ?> />
			    <?php _e('Invert the color scheme', TEMPLATE_DOMAIN); ?>
			</label>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id( 'freetext' ); ?>"><?php _e('Free text area', TEMPLATE_DOMAIN); ?></label><textarea id="<?php echo $this->get_field_id( 'freetext' ); ?>" type="text" name="<?php echo $this->get_field_name( 'freetext' ); ?>" class="widefat" rows="4"><?php echo $instance['freetext']; ?></textarea>
			</p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display_mode' ); ?>"><?php _e('Style mode:',TEMPLATE_DOMAIN); ?></label>
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
