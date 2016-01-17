<?php
/**
 * Mailing List Widget class.
 * @Parameters
 *  - signup_title
 *  - signup_message
 *  - signup_btn_label
 *  - signup_thankyou_message
 *  - unsubscribe_title
 *  - unsubscribe_message
 *  - unsubscribe_btn_label
 *  - unsubscribe_thankyou_message
 *  - email_label
 *  - name_label
 */
class mb_Mailing_List_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	 var $defaults;
	 
	function mb_Mailing_List_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_mb_mailing_list', 'description' => esc_html__("A widget for subscribing/unsubscribing to a mailing list.", TEMPLATE_DOMAIN) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 150, 'height' => 350, 'id_base' => 'mb-mailing-list-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mb-mailing-list-widget', esc_html__('MBU: Mailing List Forms', TEMPLATE_DOMAIN), $widget_ops, $control_ops );
		
		$this->defaults = array( 
		'signup_title' => 'Let us keep in touch', 
		'signup_message' => 'Join our mailing list and we\'ll keep in touch.',
		'signup_btn_label' => 'Join our mailing list',
		'signup_thankyou_message' => 'Thanks #name# for signing up to our mailing list. An email has been sent to #email# with a confirmation link.',
		'unsubscribe_title' => 'Mailing list',
		'unsubscribe_message' => 'On our mailing list? Thanks. You can unsubscribe at any time.',
		'unsubscribe_btn_label'  => 'Unsubscribe',
		'unsubscribe_thankyou_message' => 'We\'ve removed #email# from our mailing list.',
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
		$signup_title = apply_filters('widget_title', $instance['signup_title'] );
		$signup_message = $instance['signup_message'];
		$signup_btn_label = $instance['signup_btn_label'];
		$signup_thankyou_message = $instance['signup_thankyou_message'];
		$unsubscribe_title = $instance['unsubscribe_title'];
		$unsubscribe_message = $instance['unsubscribe_message'];
		$unsubscribe_thankyou_message = $instance['unsubscribe_thankyou_message'];
		$unsubscribe_btn_label = $instance['unsubscribe_btn_label'];
		$email_label = $instance['email_label'];
		$name_label = $instance['name_label'];
		
		/* Before widget (defined by themes). */
		echo  $before_widget ;
		
		
		/* handle any form submits */
		if(isset($_POST['mbudm_mailing_list_signup']) || isset($_POST['mbudm_mailing_list_unsubscribe']) || isset($_POST['mbudm_mailing_list_manage']) ){
			$ml_errors = array();
			$ml_alerts = array();
			
			// check email and username vars
			if(isset($_POST['useremail'])){
				// does user exist for this email?
				$post_email = strip_tags( $_POST['useremail'] );
				$post_email_valid = filter_var($post_email, FILTER_VALIDATE_EMAIL);
				if($post_email_valid){
					// is email subscribed?
					$email_user_id = email_exists($post_email);
					$is_email_signedup = get_user_meta($email_user_id, MBUDM_UMK_MAILING_LIST, true); 	
				}
			}
			if(isset($_POST['username'])){
				$post_name = strip_tags($_POST['username'] );
				$post_name_valid = !empty($post_name);
			}
			
			if(isset($_POST['mbudm_mailing_list_signup']) && !$is_email_signedup){
				if($post_email_valid){
					$message = "Thanks for signing up to the Linguaposta mailing list. To confirm your subscription click on this link:\r\n";
					// add user if doesn't exist
					if(!$email_user_id ){
						//create account and log them in
						$random_password = wp_generate_password( 12, false );
						
						$creds = array();
						$creds['user_login'] = $post_email;
						$creds['user_password'] = $random_password ;
						$creds['remember'] = true;
						$signup_user = wp_signon( $creds, false );
						if ( is_wp_error($signup_user) )
						   echo $signup_user->get_error_message();
						$signup_user_id = $signup_user->ID;
						$message .="To cancel your subscription just log in to our memeber area. Here are your new login details that we have created for you:\r\nUser name: ". $creds['user_login'] ."\r\n Password: ". $creds['user_password'] ."\r\n";
					}else{
						$signup_user_id = $email_user_id;
						$message .="You have an account with us already but since you were not logged in, we ask that you confirm your email address before we add you to out list.\r\n";
					}
					
					// set mailing list status for user to pending
					update_user_meta($signup_user_id, MBUDM_UMK_MAILING_LIST, MBUDM_MAILING_LIST_PENDING);
					
					// send confirmation email
					$subject = __("Confirm your Linguaposta mailing list subscription",TEMPLATE_DOMAIN);
					$headers = 'From: '. mbudm_get_option(THEMEPREFIX."_mail_name") .' <'. mbudm_get_option(THEMEPREFIX."_mail_address") .'>' . "\r\n";
					wp_mail($post_email, $subject, $message, $headers);
					
					/* replace any instances of #name# or #email# with the form values */
					$ml_alerts[] = str_replace(array('#name#','#email#'), array($post_name,$post_email), $signup_thankyou_message);
				}else{
					//invalid email
					$ml_errors[] = __("That email doesn't look valid",TEMPLATE_DOMAIN);
				}
			}
			
			if(isset($_POST['mbudm_mailing_list_unsubscribe'])){
				if($post_email_valid){ 
					if($is_email_signedup){
						// is email  subscribed? remove subscription
						update_user_meta($email_user_id, MBUDM_UMK_MAILING_LIST, MBUDM_MAILING_LIST_UNSUBSCRIBED );
						/* replace any instances of #name# or #email# with the form values */
						$ml_alerts[] = str_replace('#email#',$post_email, $unsubscribe_thankyou_message);
					}
				}else{
					//invalid email
					$ml_errors[] = __("That email doesn't look valid",TEMPLATE_DOMAIN);
				}
			}
		
		} // end  handlers for any form submits
		
		// is logged in and signed up to the mailing list?
		if ( is_user_logged_in() ){ 
			global $current_user;
			get_currentuserinfo();
			$is_signedup = get_user_meta($current_user->ID, MBUDM_UMK_MAILING_LIST, true);  
			
			if(isset($_POST['mbudm_mailing_list_manage'])){
				if($is_signedup == MBUDM_MAILING_LIST_SUBSCRIBED){
					// is user subscribed? remove subscription
					update_user_meta($current_user->ID, MBUDM_UMK_MAILING_LIST, MBUDM_MAILING_LIST_UNSUBSCRIBED, $is_signedup );
				}else{
					// user unsubscribed? add subscription
					update_user_meta($current_user->ID, MBUDM_UMK_MAILING_LIST, MBUDM_MAILING_LIST_SUBSCRIBED, $is_signedup );
				}
				
				$is_signedup = get_user_meta($current_user->ID, MBUDM_UMK_MAILING_LIST, true);   
			}	
			
			$user_manage_title = $is_signedup == MBUDM_MAILING_LIST_SUBSCRIBED ? $unsubscribe_title : $signup_title ;
			$user_manage_message = $is_signedup == MBUDM_MAILING_LIST_SUBSCRIBED ? $unsubscribe_message : $signup_message ;
			
			$btn_label = $is_signedup == MBUDM_MAILING_LIST_SUBSCRIBED ? $unsubscribe_btn_label : $signup_btn_label ;
		}
		
		//format error/alert strings
		$ml_alerts_msg = '';
		$ml_errors_msg = '';
		if(!empty($ml_alerts)){
			$ml_alerts_msg = '<div class="alerts" >';
			foreach($ml_alerts as $ml_alert){
				$ml_alerts_msg .= '<p>' . $ml_alert . '</p>';
			}
			$ml_alerts_msg .= '</div>';
		} 
		if(!empty($ml_errors)){
			$ml_errors_msg = '<div class="errors" >';
			foreach($ml_errors as $ml_error){
				$ml_errors_msg .= '<p>' . $ml_error . '</p>';
			}
			$ml_errors_msg .= '</div>';
		} 
		
		$submit_url = $_SERVER['REQUEST_URI'];
		
	
		/* print out forms */
		if ( is_user_logged_in() ){ 
			$submit_class = $is_signedup ? 'info-btn' : 'submit-btn' ;
		
		?>
		<h2><?php echo $user_manage_title ?></h2>
		<form action='<?php echo $submit_url ?>' METHOD='POST' id="mailinglist-usermanage">
				<p><?php echo $user_manage_message ?></p>
				<?php echo $ml_alerts_msg ?>
				<?php echo $ml_errors_msg ?>
				<ul>
					<li>
						<input type="submit" class="btn <?php echo $submit_class  ?>" value="<?php echo $btn_label ?>" />
						<input type="hidden" name="mbudm_mailing_list_manage" value="1"/>
					</li>
				</ul>
			</form>
		<?php
		}else{
		?>
			<h2><?php echo $signup_title ?></h2>
			<form action='<?php echo $submit_url ?>' METHOD='POST' id="mailinglist">
				<p><?php echo $signup_message ?></p>
				<?php echo $ml_alerts_msg ?>
				<?php echo $ml_errors_msg ?>
				<ul>
					<li>
						<label for="mailinglist-username" ><?php echo $name_label ?></label>
						<input type="text" id="mailinglist-username" name="username" value="" placeholder="<?php echo $name_label ?>" />
					</li>
					<li>
						<label for="mailinglist-useremail" ><?php echo $email_label ?></label>
						<input type="text" id="mailinglist-useremail" name="useremail" value="" placeholder = "<?php echo $email_label ?>" />				
					</li>
					<li>
						<input type="submit" class="btn submit-btn" value="<?php echo $signup_btn_label ?>" />
						<input type="hidden" name="mbudm_mailing_list_signup" value="1"/>
					</li>
				</ul>
			</form>
			<form action='<?php echo $submit_url ?>' METHOD='POST' id="mailinglist-unsubscribe" class="minimisable">
				<h3><?php echo $unsubscribe_title ?></h3>
				<p><?php echo $unsubscribe_message ?></p>
				<ul>
					<li>
						<label for="mailinglist-unsubscribe-useremail" ><?php echo $email_label ?></label>
						<input type="text" id="mailinglist-unsubscribe-useremail" name="useremail" value="" placeholder = "<?php echo $email_label ?>" />				
					</li>
					<li>
						<input type="submit" class="btn info-btn" value="<?php echo $unsubscribe_btn_label ?>" />
						<input type="hidden" name="mbudm_mailing_list_unsubscribe" value="1"/>
					</li>
				</ul>
			</form>
		<?php
		}
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for for text inputs only . */
		$instance['signup_title'] = strip_tags( $new_instance['signup_title'] );
		$instance['signup_message'] = strip_tags( $new_instance['signup_message'] );
		$instance['signup_btn_label'] = strip_tags( $new_instance['signup_btn_label'] );
		$instance['signup_thankyou_message'] = strip_tags( $new_instance['signup_thankyou_message'] );
		$instance['unsubscribe_title'] = strip_tags( $new_instance['unsubscribe_title'] );
		$instance['unsubscribe_message'] = strip_tags( $new_instance['unsubscribe_message'] );
		$instance['unsubscribe_btn_label'] = strip_tags( $new_instance['unsubscribe_btn_label'] );
		$instance['unsubscribe_thankyou_message'] = strip_tags( $new_instance['unsubscribe_thankyou_message'] );
		$instance['email_label'] = strip_tags( $new_instance['email_label'] );
		$instance['name_label'] = strip_tags( $new_instance['name_label'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<!-- Widget Title: MBU Mailing List -->
		<h3>Form labels</h3>
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
		<h3>Signup</h3>
		<p><?php _e('A form for the user to signup to the mailing list.', TEMPLATE_DOMAIN) ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'signup_title' ); ?>"><?php _e('Title', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'signup_title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'signup_title' ); ?>" value="<?php echo $instance['signup_title']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'signup_message' ); ?>"><?php _e('Message', TEMPLATE_DOMAIN); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'signup_message' ); ?>" type="text" name="<?php echo $this->get_field_name( 'signup_message' ); ?>" class="widefat" rows="4"><?php echo $instance['signup_message']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'signup_btn_label' ); ?>"><?php _e('Button label', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'signup_btn_label' ); ?>" type="text" name="<?php echo $this->get_field_name( 'signup_btn_label' ); ?>" value="<?php echo $instance['signup_btn_label']; ?>" />
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id( 'signup_thankyou_message' ); ?>"><?php _e('Thankyou message', TEMPLATE_DOMAIN); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'signup_thankyou_message' ); ?>" type="text" name="<?php echo $this->get_field_name( 'signup_thankyou_message' ); ?>" class="widefat" rows="4"><?php echo $instance['signup_thankyou_message']; ?></textarea>
			<small><?php _e('The message that appears after the form is submitted. You can print out both fields by adding in #name# and #email#.', TEMPLATE_DOMAIN) ?></small>
		</p>
		<h3>Unsubscribe</h3>
		<p><?php _e('A form for the user to unsubscribe from the mailing list.', TEMPLATE_DOMAIN) ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'unsubscribe_title' ); ?>"><?php _e('Title', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'unsubscribe_title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'unsubscribe_title' ); ?>" value="<?php echo $instance['unsubscribe_title']; ?>" class="widefat"  />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'unsubscribe_message' ); ?>"><?php _e('Message', TEMPLATE_DOMAIN); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'unsubscribe_message' ); ?>" type="text" name="<?php echo $this->get_field_name( 'unsubscribe_message' ); ?>" class="widefat" rows="4"><?php echo $instance['unsubscribe_message']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'unsubscribe_btn_label' ); ?>"><?php _e('Button label', TEMPLATE_DOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'unsubscribe_btn_label' ); ?>" type="text" name="<?php echo $this->get_field_name( 'unsubscribe_btn_label' ); ?>" value="<?php echo $instance['unsubscribe_btn_label']; ?>" />
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id( 'unsubscribe_thankyou_message' ); ?>"><?php _e('Thankyou message', TEMPLATE_DOMAIN); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'unsubscribe_thankyou_message' ); ?>" type="text" name="<?php echo $this->get_field_name( 'unsubscribe_thankyou_message' ); ?>" class="widefat" rows="4"><?php echo $instance['unsubscribe_thankyou_message']; ?></textarea>
			<small><?php _e('The message that appears after the form is submitted. You can print out the email field by adding in #email#.', TEMPLATE_DOMAIN) ?></small>
		</p>
<?php
	}
}
?>
