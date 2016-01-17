<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
	_e( 'This post is password protected. Enter the password to view comments.',TEMPLATE_DOMAIN);
	return;
}

	$reqval = $req ? '<strong>' .__('(required)',TEMPLATE_DOMAIN) . '</strong>' : '' ;
?>

<?php if ( have_comments() ) : ?>
    <h2 id="comments" class="grid_24"><?php comments_number('No Comments', 'One Comment','% Comments');?></h2>
     
    <ul class="commentlist">
		<?php wp_list_comments('type=comment&avatar_size=40&callback=mbudm_format_comment'); ?>
	</ul>

<?php if ( !empty($comments_by_type['pings']) ) : ?>
    <h3 id="trackbacks" class="grid_24"><?php _e('Trackbacks and Pingbacks',TEMPLATE_DOMAIN); ?></h3>
	<ul class="commentlist">
		<?php wp_list_comments('type=pings'); ?>
	</ul>
<?php endif; ?>
     
    <div class="navigation" class="grid_24">
        <div class="alignleft"><?php previous_comments_link() ?></div>
        <div class="alignright"><?php next_comments_link() ?></div>
    </div>
    
<?php endif; ?>



<?php if ('open' == $post->comment_status) : ?>

     <div id="respond">   
        <h2 class="grid_24"><?php comment_form_title( 'Leave a Comment', 'Leave a Reply to %s' ); ?></h2>
    
        <?php if ( get_option('comment_registration') && !$user_ID ) {
        $url_open = '<a href="' . home_url() . '/wp-login.php?redirect_to=' .get_permalink() . '">';
        $url_close = '</a>';
        ?>
        <p><?php printf(_x('You must be %1$s logged in %2$s to post a comment.','Variables 1 and 2 are the opening and closing tags for a link to the login page',TEMPLATE_DOMAIN),$url_open,$url_close); ?></p>
    
        <?php }else{ ?>
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="cfix">
            <h3 class="grid_24"><?php _e('Add your comment',TEMPLATE_DOMAIN); ?></h3>
            <?php comment_id_fields(); ?>
            <?php
            
            $message_class = $user_ID ? 'message' : 'message grid_18 pull_6';
            $submit_class = $user_ID ? 'submit' : 'submit grid_24';
            ?>
            <?php if ( $user_ID ) : ?>
   			 <div class="member-comment grid_24">
            <?php else : ?>
              <div class="public-comment">
              	<div class="grid_6 push_18">
                <p>
                	<label for="author"><?php _e('Name',TEMPLATE_DOMAIN); ?> <?php echo $reqval ?></label>
                	<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" placeholder="<?php _e('Your name',TEMPLATE_DOMAIN); ?>" class="input" />
                </p>
    			<p>
    				<label for="email"><?php _e('Mail',TEMPLATE_DOMAIN); ?> <?php echo $reqval ?><small><?php _e('(will not be published)',TEMPLATE_DOMAIN); ?></small></label>
    				<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="50" placeholder="<?php _e('Your email (will not be published)',TEMPLATE_DOMAIN); ?>" class="input" />
                </p>
                <p>
                	<label for="url"><?php _e('Website',TEMPLATE_DOMAIN); ?></label>
                	<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="50" placeholder="<?php _e('Your website',TEMPLATE_DOMAIN); ?>" class="input"/>
                </p>
                </div>
            <?php endif; ?>
            
            <div class="<?php echo $message_class ?>">
            	<label for="comment"><?php _e('Comment',TEMPLATE_DOMAIN); ?>
            	<?php if ( $user_ID ) : ?>
            	<small>
            	<?php printf(__('Logged in as %s.',TEMPLATE_DOMAIN),'<a href="' .get_option('siteurl') .'/wp-admin/profile.php">' . $user_identity. '</a>'); ?>
                <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account',TEMPLATE_DOMAIN); ?>"><?php _e('Logout',TEMPLATE_DOMAIN); ?></a>
                </small>
                <?php endif; ?>
            	</label>
            	<textarea name="comment" id="comment" rows="7" tabindex="4" placeholder="<?php _e('Your comment',TEMPLATE_DOMAIN); ?>" ></textarea></div>
    
            <div class="<?php echo $submit_class ?>"><input class="submit-btn" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment',TEMPLATE_DOMAIN); ?>" />
        
            </div>
    		</div>
            <?php do_action('comment_form', $post->ID); ?>
    
        </form>
        
        <div id="cancel-comment-reply">
			<small><?php cancel_comment_reply_link() ?></small>
    	</div>

	</div>

<?php } ?>
<?php endif; ?>
