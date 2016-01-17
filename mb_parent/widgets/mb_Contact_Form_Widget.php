<?php
/**
 * Contact Form Widget class.
 * @Parameters
 *  - form_title
 *  - form_message
 *  - hidden_field
 *  - submit_btn_label
 *  - submit_thankyou_message
 *  - message_label
 *  - include_message
 *  - email_label
 *  - name_label
 */
class mb_Contact_Form_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	 var $defaults;
	 
	function mb_Contact_Form_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_contact_form', 'description' => esc_html__("A simple contact form.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-contact-form-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-contact-form-widget', esc_html__('MBU: Contact Form', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->defaults = array( 
		'form_title' => 'Contact Us', 
		'form_message' => 'Send an enquiry about #pagetitle#.',
		'hidden_field' => 'My Contact Form',
		'submit_btn_label' => 'Send a message',
		'submit_thankyou_message' => 'Thanks #name# for sending a message. We\'ll get back to you on #email# soon. Here\'s the message you sent: #message#.',
		'message_label' => 'Your message',
		'include_message' => '1',
		'email_label'  => 'Your email',
		'name_label'  => 'Your name'
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$form_title = apply_filters('widget_title', $instance['form_title'] );
		$form_message =  $instance['form_message'];
		$title =  get_the_title();
		$form_message = str_replace("#pagetitle#", $title, $form_message );
		$hidden_field = $instance['hidden_field'];
		$submit_btn_label = $instance['submit_btn_label'];
		$submit_thankyou_message = $instance['submit_thankyou_message'];
		$message_label = $instance['message_label'];
		$include_message = $instance['include_message'];
		$email_label = $instance['email_label'];
		$name_label = $instance['name_label'];
		
		$submit_url = $_SERVER['REQUEST_URI'];
		
		
		/* Before widget (defined by themes). */
		echo  $before_widget ;
		
		$cf_submit_thankyou = '';
		/* handle any form submits */
		if(isset($_POST['mbudm_contact_form']) ){
		
			$cf_errors = array();
			$cf_alerts = array();
			
			// check email and username vars
			if(isset($_POST['useremail'])){
				$post_email = strip_tags( $_POST['useremail'] );
				$post_email_valid = filter_var($post_email, FILTER_VALIDATE_EMAIL);
			}
			if(isset($_POST['username'])){
				$post_name = strip_tags($_POST['username'] );
				$post_name_valid = !empty($post_name);
			}
			
			if(isset($_POST['usermessage'])){
				$post_message = strip_tags($_POST['usermessage'] );
				$post_message_valid = !empty($post_message);
			}
			
			/* add errors or populate submit thankyou msg with #name#, #email# or #message# */
			if(!$post_email_valid){
				$cf_errors[] = __("That email doesn't look valid",TEMPLATE_DOMAIN);
			}else{
				$submit_thankyou_message = str_replace("#email#", $post_email, $submit_thankyou_message );
			}
			if(!$post_name_valid){
				$cf_errors[] = __("Can you enter a name?",TEMPLATE_DOMAIN);
			}else{
				$submit_thankyou_message = str_replace("#name#", $post_name, $submit_thankyou_message );
			}
			if(!$post_message_valid){
				$cf_errors[] = __("You need to add a message so we can respond to your enquiry.",TEMPLATE_DOMAIN);
			}else{
				$submit_thankyou_message = str_replace("#message#", $post_message, $submit_thankyou_message );
			}
			
			
			
			$message = "You have a message from a visitor to your website (".get_bloginfo( 'name' ) .").\r\n";
			
			if(isset($hidden_field) && strlen($hidden_field) > 0){
				$message .= "Hidden field: \r\n";
			}
			
			$message .= "Name: ". $post_name ."\r\n";
			$message .= "Email: ". $post_email ."\r\n";
			if(isset($include_message) && $include_message == 1){
				$message .= "Message: ". $post_message ."\r\n";
			}
			$message .= "\r\nForm location: ". $submit_url ."\r\n";
			
			// send confirmation email
			$subject = __("Message from " . $post_name ,TEMPLATE_DOMAIN);
			$headers = 'From: Site Admin <'.  get_site_option('admin_email') .'>' . "\r\n";
			wp_mail( get_site_option('admin_email'), $subject, $message, $headers);
					
			$cf_submit_thankyou = '<div class="alerts" ><p>' . $submit_thankyou_message . '</p></div>';
		} // end  handlers for any form submits
		
		//format error/alert strings
		$cf_alerts_msg = '';
		$cf_errors_msg = '';
		if(!empty($cf_alerts)){
			$cf_alerts_msg = '<div class="alerts" >';
			foreach($cf_alerts as $cf_alert){
				$cf_alerts_msg .= '<p>' . $cf_alert . '</p>';
			}
			$cf_alerts_msg .= '</div>';
		} 
		if(!empty($cf_errors)){
			$cf_errors_msg = '<div class="errors" >';
			foreach($cf_errors as $cf_error){
				$cf_errors_msg .= '<p>' . $cf_error . '</p>';
			}
			$cf_errors_msg .= '</div>';
		} 
		
		
		
		$submit_url = $_SERVER['REQUEST_URI'];
		
	
		/* print out forms */
		
		?>
			<h2><?php echo $form_title ?></h2>
			<form action='<?php echo $submit_url ?>' METHOD='POST' id="contact_form">
				<p><?php echo $form_message ?></p>
				<?php
				if(strlen($cf_alerts_msg) + strlen($cf_errors_msg) + strlen($cf_submit_thankyou) > 0 ){
					echo '<div class="form-messages" >';
					echo $cf_alerts_msg;
					if(strlen($cf_errors_msg) > 0 ){
						echo $cf_errors_msg;
					}else{
						echo $cf_submit_thankyou;
					}
					echo '</div>';
				}
				?>
				<ul>
					<li>
						<label for="cf-username" ><?php echo $name_label ?></label>
						<input type="text" id="cf-username" name="username" value="" placeholder="<?php echo $name_label ?>" />
					</li>
					<li>
						<label for="cf-useremail" ><?php echo $email_label ?></label>
						<input type="text" id="cf-useremail" name="useremail" value="" placeholder = "<?php echo $email_label ?>" />				
					</li>
					<?php if(isset($include_message) && $include_message == 1): ?>
					<li>
						<label for="cf-usermessage" ><?php echo $message_label ?></label>
						<textarea id="cf-usermessage" name="usermessage" placeholder = "<?php echo $message_label ?>" ></textarea>				
					</li>
					<?php endif; ?>
					<li>
						<input type="submit" class="btn submit-btn" value="<?php echo $submit_btn_label ?>" />
						<input type="hidden" name="mbudm_contact_form" value="1"/>
						<?php if(isset($hidden_field) && strlen($hidden_field) > 0): ?>
						<input type="hidden" name="cf_hidden_field" value="<?php  echo $hidden_field ?>"/>
						<?php endif; ?>
					</li>
				</ul>
			</form>
			
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
		$instance['form_title'] = strip_tags( $new_instance['form_title'] );
		$instance['form_message'] = strip_tags( $new_instance['form_message'] );
		$instance['hidden_field'] = strip_tags( $new_instance['hidden_field'] );
		$instance['submit_thankyou_message'] = strip_tags( $new_instance['submit_thankyou_message'] );
		$instance['message_label'] = strip_tags( $new_instance['message_label'] );
		$instance['include_message'] = $new_instance['include_message'];
		$instance['email_label'] = strip_tags( $new_instance['email_label'] );
		$instance['name_label'] = strip_tags( $new_instance['name_label'] );
		$instance['submit_btn_label'] = strip_tags( $new_instance['submit_btn_label'] );
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
/*
form_title
 *  - form_message
 *  - hidden_field
 *  - submit_btn_label
 *  - submit_thankyou_message
 *  - message_label
 *  - include_message
 *  - email_label
 *  - name_label
 */
		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<!-- Widget Title: MBU Contact Form -->
		<p>
			<label for="<?php echo $this->get_field_id( 'form_title' ); ?>"><?php _e('Form title', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'form_title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'form_title' ); ?>" value="<?php echo $instance['form_title']; ?>" /><br/>
			<small><?php _e('The \'call to action\' for this form.', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'form_message' ); ?>"><?php _e('Form introduction', TEMPLATE_DOMAIN); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'form_message' ); ?>" type="text" name="<?php echo $this->get_field_name( 'form_message' ); ?>" class="widefat" rows="4"><?php echo $instance['form_message']; ?></textarea><br/>
			<small><?php _e('Describe what you\'d like people to use this contact form for. You can use the page title in your message by using #pagetitle#.', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'email_label' ); ?>"><?php _e('Email Label', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'email_label' ); ?>" type="text" name="<?php echo $this->get_field_name( 'email_label' ); ?>" value="<?php echo $instance['email_label']; ?>" /><br/>
			<small><?php _e('The label for the email field', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'name_label' ); ?>"><?php _e('Name Label', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'name_label' ); ?>" type="text" name="<?php echo $this->get_field_name( 'name_label' ); ?>" value="<?php echo $instance['name_label']; ?>" /><br/>
			<small><?php _e('The label for the name field', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p><!-- Show message checkbox -->
			<label for="<?php echo $this->get_field_id( 'include_message' ); ?>">
			    <input class="checkbox" type="checkbox" <?php checked( $instance['include_message'], true ); ?> id="<?php echo $this->get_field_id( 'include_message' ); ?>" name="<?php echo $this->get_field_name( 'include_message' ); ?>"  />
			    <?php _e('Include a message box on this form', TEMPLATE_DOMAIN); ?>
			</label>
			</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'message_label' ); ?>"><?php _e('Message Label', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'message_label' ); ?>" type="text" name="<?php echo $this->get_field_name( 'message_label' ); ?>" value="<?php echo $instance['message_label']; ?>" /><br/>
			<small><?php _e('The label for the message box', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'submit_btn_label' ); ?>"><?php _e('Submit button text', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'submit_btn_label' ); ?>" type="text" name="<?php echo $this->get_field_name( 'submit_btn_label' ); ?>" value="<?php echo $instance['submit_btn_label']; ?>" /><br/>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'submit_thankyou_message' ); ?>"><?php _e('Thankyou message', TEMPLATE_DOMAIN); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'submit_thankyou_message' ); ?>" type="text" name="<?php echo $this->get_field_name( 'submit_thankyou_message' ); ?>" class="widefat" rows="4"><?php echo $instance['submit_thankyou_message']; ?></textarea><br/>
			<small><?php _e('This is what people see when they submit the form. You can print out the fields the user entered by including #name#, #email# or #message# in your thankyou message.', TEMPLATE_DOMAIN) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hidden_field' ); ?>"><?php _e('Hidden field', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'hidden_field' ); ?>" type="text" name="<?php echo $this->get_field_name( 'hidden_field' ); ?>" value="<?php echo $instance['hidden_field']; ?>" /><br/>
			<small><?php _e('Use the hidden field to organise your different contact forms - this helps you to identify which form the message was sent from. Note: the email you receive will also include a link to the page that the form is on.', TEMPLATE_DOMAIN) ?></small>
		</p>
<?php
	}
}
?>
