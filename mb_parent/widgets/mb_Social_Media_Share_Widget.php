<?php
/**
 * MBU: Social Media Share Widget class.
 * @Parameters
 *	- title
 *  - selected entities (list of slugs from $entities)
 */
class mb_Social_Media_Share_Widget extends WP_Widget {
	 public $defaults;
	 
	 /* sprintf order ( %1$s %2$s %3$s %4$s)
	 1 url
	 2 title
	 3 description
	 4 host
	 */
	 
	 private $sm_entities = array(
		'delicious' => array(
			'label' => 'Del.icio.us',
			'url' => 'http://del.icio.us/post?url=%1$s&title=%2$s]&notes=%3$s'
		),
		'digg' => array(
			'label' => 'Digg',
			'url' => 'http://www.digg.com/submit?phase=2&url=%1$s&title=%2$s'
		),
		'email'=> array(
			'label' => 'Email',
			'url' => 'mailto:&subject=Check out %2$s!&body=I wanted to share this with you, here\'s the title, description and link:\n\r%2$s\n\r%3$s\n\r%1$s'
		),
		'evernote' => array(
			'label' => 'Evernote',
			'url' => 'http://www.evernote.com/clip.action?url=%1$s&title=%2$s'
		),
		'facebook' => array(
			'label' => 'Facebook',
			'url' => 'http://www.facebook.com/share.php?u=%1$s&title=%2$s'
		),
		'gplus' => array(
			'label' => 'Google+',
			'url' => 'https://plus.google.com/share?url=%1$s'
		),
		'linkedin' => array(
			'label' => 'LinkedIn',
			'url' => 'http://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s&source=%4$s'
		),
		'newsvine' => array(
			'label' => 'Newsvine',
			'url' => 'http://www.newsvine.com/_tools/seed&save?u=%1$s&h=%2$s'
		),
		'pinterest' => array(
			'label' => 'Pinterest',
			'url' => 'http://pinterest.com/pin/create/button/?url=%1$s&media=%1$s&description=%3$s'
		),
		'stumbleupon' => array(
			'label' => 'StumbleUpon',
			'url' => 'http://www.stumbleupon.com/submit?url=%1$s&title=%2$s'
		),
		'tumblr' => array(
			'label' => 'Tumblr',
			'url' => 'http://www.tumblr.com/share?v=3&u=%1$s&t=%2$s'
		),
		'twitter' => array(
			'label' => 'Twitter',
			'url' => 'http://twitter.com/home?status=%2$s+%1$s'
		),
	);
	 
	/**
	 * Widget setup.
	 */
	function mb_Social_Media_Share_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_social_media_share', 'description' => esc_html__("A widget for adding sharing icons, that let your visitors share the current page using social media tools.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-social-media-share-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-social-media-share-widget', esc_html__('MBU: Social Media Sharing', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->defaults = array( 
		'title' => '', 
		'selected_entities' => array()
		);
		
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		
		/* Before widget (defined by themes). */
		echo $before_widget;
		
		if ( $title )
		    echo $before_title . $title . $after_title;
		    
		if(isset($instance['selected_entities']) ){
			
			
			$host = $_SERVER['HTTP_HOST'];
			//TODO add check for https
			$url = 'http://' . $host . $_SERVER['REQUEST_URI'];
			$title =  get_the_title();
			$description =  get_the_excerpt();
			
			
			
			echo '<ul>';
			foreach($instance['selected_entities'] as $entity){
				$entity_label = $this->sm_entities[$entity]['label'];
				$entity_url = sprintf($this->sm_entities[$entity]['url'], $url, $title, $description, $host);
				
				echo '<li >';
				echo '<a class="mb-'.$entity.' mb-smedia" href="'.$entity_url.'" title="Share '. $title .' via '.$entity_label.'" >Share '. $title .' via '.$entity_label.'</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Don't strip tags - this widget is allowed html . */
		$instance['title'] = $new_instance['title'];
		$instance['selected_entities'] = $new_instance['selected_entities'];

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

		<!-- Widget Title: Social Media Share -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat"/>
			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'selected_entities' ); ?>"><?php _e('Sharing tools:',TEMPLATE_DOMAIN); ?></label>
			
			<select id="<?php echo $this->get_field_id( 'selected_entities' ); ?>" name="<?php echo $this->get_field_name( 'selected_entities' ); ?>[]" class="widefat" multiple="multiple" size=10>
			<?php 
			foreach($this->sm_entities as $k=>$v){ 
				$selected = in_array($k,$instance['selected_entities']) ? 'selected="selected"' : '' ;
			
			?>
				<option value="<?php echo $k ?>" <?php echo $selected; ?>><?php echo $v['label']; ?></option>
				<?php } ?>
			</select>
		</p>
		
<?php
	}
}
?>
