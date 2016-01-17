<?php
/**
 * Page_Promo Widget class.
 * @Parameters
 *  - title
 *  - page/post to link to
 *  - blurb text - defaults to excerpt
 *  - button label
 *  - mb_widget_classes
 */
class mb_Page_Promo_Widget extends WP_Widget {
 	var $defaults;
 	
 	 private $mb_widget_class_options;
	 
	/**
	 * Widget setup.
	 */
	function mb_Page_Promo_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_page_promo', 'description' => esc_html__("A widget that shows a blurb and a button that links to a page - can be styled four ways.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-page-promo-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-page-promo-widget', esc_html__('MBU: Page/Post Promo', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->mb_widget_class_options = array( 
		'display_mode_orange' => __('Orange', TEMPLATE_DOMAIN),
		'display_mode_blue' => __('Blue', TEMPLATE_DOMAIN),
		'display_mode_green' => __('Green', TEMPLATE_DOMAIN),
		'display_mode_grey' => __('Grey', TEMPLATE_DOMAIN)
		);
		
		$this->defaults = array( 
		'title' => '', 
		'post_id' => '',
		'blurb_text' => '',
		'button_label' => '',
		'mb_widget_classes' => 'display_mode_grey',
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
		$blurb_text = $instance['blurb_text'];
		$button_label = $instance['button_label'];
		
		
		$post_url = get_permalink($post_id);
		$post_title = get_the_title($post_id);
		
		//print_r($post);
		
		//echo("post id " . $post->ID);
		//echo("promo id " . $post_id);
		
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
			<p><?php echo $blurb_text ?></p>
			<a class="btn" href="<?php echo $post_url ?>" title="<?php echo $post_title ?>" ><?php echo $button_label ?></a>
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
		$instance['blurb_text'] = $new_instance['blurb_text'];
		$instance['button_label'] = $new_instance['button_label'];
		$instance['mb_widget_classes'] = $new_instance['mb_widget_classes'];
		
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
		<p>
			<label for="<?php echo $this->get_field_id( 'blurb_text' ); ?>"><?php _e('Promo blurb', TEMPLATE_DOMAIN); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'blurb_text' ); ?>" type="text" name="<?php echo $this->get_field_name( 'blurb_text' ); ?>" class="widefat" rows="4"><?php echo $instance['blurb_text']; ?></textarea>
			<small><?php _e('The call to action text that makes people want to click the button.', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_label' ); ?>"><?php _e('Button label', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'button_label' ); ?>" type="text" name="<?php echo $this->get_field_name( 'button_label' ); ?>" value="<?php echo $instance['button_label']; ?>" class="widefat"/>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('mb_widget_classes'); ?>"><?php _e( 'Style:' ,TEMPLATE_DOMAIN); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('mb_widget_classes'); ?>" name="<?php echo $this->get_field_name('mb_widget_classes'); ?>">
		<?php
		foreach ( $this->mb_widget_class_options as $bg_style=>$label ) {
			$selected = $bg_style == $instance['mb_widget_classes'] ? 'selected="selected"' : '' ;
			echo '<option value="' . $bg_style . '"'
				. $selected . '>' . $label . "</option>\n";
		}
		?>
		</select>
		</p>
<?php
	}
}
?>
