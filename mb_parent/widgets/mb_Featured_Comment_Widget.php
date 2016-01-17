<?php
/**
 * Featured Comment Widget class.
 * @Parameters
 * - comment_phrase
 * - comment_id
 */
class mb_Featured_Comment_Widget extends WP_Widget {
 	var $defaults;
	/**
	 * Widget setup.
	 */
	function mb_Featured_Comment_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_featured_comment', 'description' => esc_html__("A widget that displays one comment from an artwork along with a thumbnail of the artwork.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-featured-comment-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-featured-comment-widget', esc_html__('MBU: Featured Comment', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->defaults = array( 
		'comment_phrase' => '',
		'comment_id' => ''
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		
		extract( $args );

		/* Our variables from the widget settings. */
		$comment_phrase = $instance['comment_phrase'];
		 
		$comment_id = $instance['comment_id'];
		
		$comment = get_comment($comment_id);
		if(!$comment){
		?>
		<!-- Note, MBU: Featured Comment Widget not shown. No comment to display -->
		<?php
		}else{
			$author = '<cite>'.$comment->comment_author .'</cite>';
			$artwork_post = get_post( $comment->comment_post_ID); 

			$post_link = '<a href="'. get_post_permalink($comment->comment_post_ID) .'" title="'.$artwork_post->post_title .'" >' . $artwork_post->post_title . '</a>';
			$comment_phrase_replaced = str_replace('#author#', $author, $comment_phrase );
			$comment_phrase_replaced = str_replace('#title#', $post_link, $comment_phrase_replaced );
			
			/* Before widget (defined by themes). */
			echo $before_widget;
			
			?>
			<div id="featured-comment" >
				<blockquote>
				 <?php echo $comment->comment_content; ?>
				</blockquote>
				<div class="grid_2">
				<?php echo mbudm_get_post_image($comment->comment_post_ID,'artwork-2',true,''); ?>
				</div>
				<p>
				<?php echo $comment_phrase_replaced; ?>
				</p>
			</div>
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
		$instance['comment_phrase'] = $new_instance['comment_phrase'];
		$instance['comment_id'] = $new_instance['comment_id'];

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

		<!-- Widget Title: MBU Recent Comments -->
		<p>
			<label for="<?php echo $this->get_field_id( 'comment_phrase' ); ?>"><?php _e('Comment phrase', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'comment_phrase' ); ?>" type="text" name="<?php echo $this->get_field_name( 'comment_phrase' ); ?>" value="<?php echo $instance['comment_phrase']; ?>" class="widefat"/>
			<small><?php _e('How the comment author is presented. Eg, \'A comment by #author# on #title#\'', TEMPLATE_DOMAIN) ?></small>
		</p>
		<?php
		
				
			$comments = get_comments( array( 'status' => 'approve', 'post_status' => 'publish', 'post_type' => 'mbudm_artwork' ) );

		?>
		<p><label for="<?php echo $this->get_field_id('comment_id'); ?>"><?php _e(' Comment:', TEMPLATE_DOMAIN); ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id('comment_id'); ?>" name="<?php echo $this->get_field_name('comment_id'); ?>">
	<?php
	foreach($comments as $comment) {
	
		$selected = $instance['comment_id'] == $comment->comment_ID ? ' selected="selected" ' : '' ;
		$cstr = $comment->comment_author . ': ' .substr($comment->comment_content, 0, 50);
		echo '<option value="'.$comment->comment_ID.'" '. $selected. '>'.$cstr.'</option>';
	
	}
?>
</select></p>
		
<?php

	}
}
?>
