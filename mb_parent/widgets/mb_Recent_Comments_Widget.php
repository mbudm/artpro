<?php
/**
 * Recent Comments Widget class.
 * @Parameters
 * - title
 * - number_comments

 */
class mb_Recent_Comments_Widget extends WP_Widget {
 	var $defaults;
	/**
	 * Widget setup.
	 */
	function mb_Recent_Comments_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_recent_comments', 'description' => esc_html__("A widget that displays recent comments, the author and the post title that is commented on.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-recent-comments-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-recent-comments-widget', esc_html__('MBU: Recent Comments', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->defaults = array( 
		'title' => '',
		'number_comments' => '5'
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		
		extract( $args );

		/* Our variables from the widget settings. */
		$title = $instance['title'];
		$num_comments = $instance['number_comments'];
		
		$args = array(
				'number' => $num_comments,
				'status' => 'approve'
			);
		$comments = get_comments($args);
		if(count($comments) == 0){
		?>
		<!-- Note, MBU: Recent Comments Widget not shown. No comments to display -->
		<?php
		}else{
			/* Before widget (defined by themes). */
			echo $before_widget;
			
			?>
			<div id="recent-comments" >
				<h2><?php echo $title ?></h2>
				<ul>
				<?php
				foreach($comments as $comment){
				
					$commented_post = get_post($comment->comment_post_ID); 
					$commented_post_link = convert_chars( get_post_permalink($comment->comment_post_ID) ) . '#comment-' . $comment->comment_ID; 

					?>
					
					<li>
					<a href="<?php echo $commented_post_link ?>"  title="<?php printf(_x('By %1$s on %2$s.',' By [comment author] on [post title]', TEMPLATE_DOMAIN), $comment->comment_author, $commented_post ->post_title); ?>" ><?php echo $comment->comment_content; ?></a>
					</li>
				<?php
				}
				?>
				</ul>
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
		$instance['title'] = $new_instance['title'];
		$instance['number_comments'] = $new_instance['number_comments'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<!-- Widget Title: MBU Recent Comments -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat"/>
			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_comments' ); ?>"><?php _e('Number of comments', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'number_comments' ); ?>" type="text" name="<?php echo $this->get_field_name( 'number_comments' ); ?>" value="<?php echo $instance['number_comments']; ?>" /><br/>
			<small><?php _e('The number of comments to show', TEMPLATE_DOMAIN) ?></small>
		</p>
		
<?php
	}
}
?>
