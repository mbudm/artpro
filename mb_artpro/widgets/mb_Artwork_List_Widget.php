<?php
/**
 * Artwork List Widget class.
 * @Parameters
 *  - title
 *  - sticky_mode
 *  - category
 *  - thumb_size
 *  - number_posts
 */
class mb_Artwork_List_Widget extends WP_Widget {
	 var $defaults;
	 
	 
	/**
	 * Widget setup.
	 */
	function mb_Artwork_List_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mb_widget_artwork_list', 'description' => esc_html__("Shows the artwork as a simple list. Can show all, featured or unfeatured artwork.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-artwork-list-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-artwork-list-widget', esc_html__('MBU: Artwork List', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->sticky_mode = array( 
			'all' => __('All artwork', TEMPLATE_DOMAIN), 
			'featured' => __('Featured (sticky) artwork', TEMPLATE_DOMAIN),
			'unfeatured' => __('Unfeatured (not sticky) artwork', TEMPLATE_DOMAIN)
		);
		
		$this->defaults = array( 
			'title' => __('', TEMPLATE_DOMAIN), 
			'sticky_mode' => 'all',
			'category' => '',
			'thumb_size' => '',
			'number_posts' => 5
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$sticky_mode = $instance['sticky_mode'];
		$category = $instance['category'];
		$thumb_size = $instance['thumb_size'];
		$number_posts = $instance['number_posts'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes).*/
		if ( $title )
		    echo $before_title . $title . $after_title;
		
		mbudm_get_artwork_list($sticky_mode,$category,$thumb_size,$number_posts);
		
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
		$instance['sticky_mode'] =  $new_instance['sticky_mode'] ;
		$instance['category'] = $new_instance['category'] ;
		$instance['thumb_size'] = $new_instance['thumb_size'] ;
		$instance['number_posts'] = $new_instance['number_posts'] ;
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		global $mbudm_image_sizes;
		
		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<!-- Widget Title: MBU Artwork List-->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'sticky_mode' ); ?>"><?php _e('Featured (sticky) mode:', TEMPLATE_DOMAIN); ?></label>
			<select id="<?php echo $this->get_field_id( 'sticky_mode' ); ?>" name="<?php echo $this->get_field_name( 'sticky_mode' ); ?>" class="widefat">
			<?php 
			foreach($this->sticky_mode as $k => $v){ ?>
				<option value="<?php echo $k ?>" <?php if ( $k == $instance['sticky_mode'] ) echo 'selected="selected"'; ?>><?php echo $v ?></option>
				<?php } ?>
			</select>
		</p>
<?php
		$cats = get_terms( 'category' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Select a category:' ,TEMPLATE_DOMAIN); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
		<option value=""><?php _e('All categories', TEMPLATE_DOMAIN); ?></option>
		<?php
		foreach ( $cats as $cat ) {
			echo '<option value="' . intval($cat->term_id) . '"'
				. ( $cat->term_id == $instance['category'] ? ' selected="selected"' : '' )
				. '>' . $cat->name . "</option>\n";
		}
		?>
		</select>
		</p>
<?php
			
	$sizes_reversed = array_reverse( $mbudm_image_sizes );
?>
	<p><label for="<?php echo $this->get_field_id('thumb_size'); ?>"><?php _e(' Thumbnail size:', TEMPLATE_DOMAIN); ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>">
	<?php
	foreach($sizes_reversed as $sizeslug => $sizename) {
	$selected = $instance['thumb_size'] == $sizeslug ? ' selected="selected" ' : '' ;
	echo "<option value='".$sizeslug."'". $selected. ">".$sizename."</option>";
	}
?>
</select></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_posts' ); ?>"><?php _e('Number of artworks', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'number_posts' ); ?>" type="text" name="<?php echo $this->get_field_name( 'number_posts' ); ?>" value="<?php echo $instance['number_posts']; ?>" /><br/>
			<small><?php _e('The number of artwork thumbs to show', TEMPLATE_DOMAIN) ?></small>
		</p>
<?php
	}
}
?>
