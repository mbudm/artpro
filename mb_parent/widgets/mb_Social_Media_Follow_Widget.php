<?php
/**
 * MBU: Social Media Follow Widget class.
 * @Parameters
 *	- title
 *  - selected entities (list of slugs from $entities)
 */
class mb_Social_Media_Follow_Widget extends WP_Widget {
	 public $defaults;
	 
	 public $friendlyLabels = array( 
		'delicious' => 'Del.icio.us',
		'evernote' => 'Evernote',
		'facebook' => 'Facebook',
		'flickr' => 'Flickr',
		'gplus' => 'Google +',
		'linkedin' => 'LinkedIn',
		'newsvine' => 'Newsvine',
		'pinterest' => 'Pinterest',
		'tumblr' => 'Tumblr',
		'twitter' => 'Twitter',
		'youtube' => 'YouTube',
		'vimeo' => 'Vimeo'
	);
	 
	/**
	 * Widget setup.
	 */
	function mb_Social_Media_Follow_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_social_media_follow', 'description' => esc_html__("A widget for adding follow icons, that let your visitors visit your social media profiles.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-social-media-follow-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-social-media-follow-widget', esc_html__('MBU: Social Media Following', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->defaults = array( 
		'title' => '', 
		'delicious' => '',
		'facebook' => '',
		'flickr' => '',
		'gplus' => '',
		'linkedin' => '',
		'newsvine' => '',
		'pinterest' => '',
		'tumblr' => '',
		'twitter' => '',
		'youtube' => '',
		'vimeo' => ''
		);
		
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		
		$title = apply_filters('widget_title', $instance['title'] );
		
		echo $before_widget;
		if ( $title )
		    echo $before_title . $title . $after_title;
		
		echo '<ul >';
		foreach($instance as $key=>$val){
			if($key != 'title' && $key != 'grid' && strlen($val) > 0){
				echo '<li>';
				$label = $this->friendlyLabels[$key];
				echo '<a class="mb-'.$key.' mb-smedia" href="'.$val.'" title="Follow '. get_bloginfo( 'name' ) .' on '. $label .'" >Follow '. get_bloginfo( 'name' ) .' on '. $label .'</a>';
			}
		}
		echo '</ul>';
		echo $after_widget;

	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['delicious'] = $new_instance['delicious'];
		$instance['evernote'] = $new_instance['evernote'];
		$instance['facebook'] = $new_instance['facebook'];
		$instance['flickr'] = $new_instance['flickr'];
		$instance['gplus'] = $new_instance['gplus'];
		$instance['linkedin'] = $new_instance['linkedin'];
		$instance['newsvine'] = $new_instance['newsvine'];
		$instance['pinterest'] = $new_instance['pinterest'];
		$instance['tumblr'] = $new_instance['tumblr'];
		$instance['twitter'] = $new_instance['twitter'];
		$instance['youtube'] = $new_instance['youtube'];
		$instance['vimeo'] = $new_instance['vimeo'];
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); 
		?>
		<!-- Widget Title: Social Media Follow -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title',TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat"/>
			
		</p>
		<h3>Your links</h3>
		<p><?php _e('Add any URLs to your social media profiles. Leave blank any that you do not wish to show.', TEMPLATE_DOMAIN) ?></p>
		<?
		foreach($this->defaults as $key=>$val ){
			if($key != 'title' ){
				$label = $this->friendlyLabels[$key];	
		?>
		<!-- Widget Title: Social Media Follow -->
		<p>
			<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $label; ?></label>
			<input id="<?php echo $this->get_field_id( $key ); ?>" type="text" name="<?php echo $this->get_field_name( $key ); ?>" value="<?php echo $instance[$key]; ?>" class="widefat"/>
			
		</p>
<?php
			}
		}
		
	}
}
?>
