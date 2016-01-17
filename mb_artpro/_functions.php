<?php

//this has to be defined here as they are used on hook names
define('MBUDM_PT_ARTWORK', "mbudm_artwork"); // artwork post type
	
add_action( 'after_setup_theme', 'mb_artwork_theme_setup' ); 
function mb_artwork_theme_setup(){
	
	add_theme_support( 'post-thumbnails', array( 'post', MBUDM_PT_ARTWORK ) );
	
	if(function_exists('register_sidebar')){ 
		$sidebars = array(
			'artwork-tabs' => __('Artwork Tabs', TEMPLATE_DOMAIN),
			'artwork-footer' => __('Artwork Footer', TEMPLATE_DOMAIN)
		);
		
		mbudm_register_sidebars($sidebars);
	}
}

/* mbudm_post_types() - override parent theme method
- artwork custom post type
*/
function mbudm_post_types() {
	register_post_type( MBUDM_PT_ARTWORK,
		array(
			'labels' => array(
				'name' => __( 'Artworks' , TEMPLATE_DOMAIN ),
				'singular_name' => __( 'Artwork', TEMPLATE_DOMAIN ),
				'add_new_item' => __('Add New Artwork', TEMPLATE_DOMAIN ),
				'edit_item' => __('Edit Artwork', TEMPLATE_DOMAIN ),
				'new_item' => __('New Artwork', TEMPLATE_DOMAIN ),
				'view_item' => __('View Artwork', TEMPLATE_DOMAIN ),
				'search_items' => __('Search Artworks', TEMPLATE_DOMAIN ),
				'not_found' => __('No Artworks found', TEMPLATE_DOMAIN ),
				'not_found_in_trash' => __('No Artworks found in Trash', TEMPLATE_DOMAIN )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'artworks'),
			'supports' => array('title','thumbnail','comments','editor' ),
			'taxonomies' => array('category'),
			'register_meta_box_cb' => 'mbudm_add_artworks_metaboxes'
		)
	);
}
/*
Add Custom Post Types to RSS feed
- adds mbudm_dartork to RSS feed
- called from mbudm_theme_setup();
*/
function mbudm_add_cpts_to_rss_feed( $args ) {
  if ( isset( $args['feed'] ) && !isset( $args['post_type'] ) )
    $args['post_type'] = array('post', MBUDM_PT_ARTWORK);
  return $args;
}
// ADD CUSTOM POST TYPES TO RSS FEED //
add_filter( 'request', 'mbudm_add_cpts_to_rss_feed' );



/* Custom widgets
- all widgets are in /widgets
*/
add_action( 'widgets_init', 'mb_artpro_widgets_init' );
function mb_artpro_widgets_init(){

	
	require_once( get_stylesheet_directory() .'/widgets/mb_Artwork_List_Widget.php');
	require_once( get_stylesheet_directory() .'/widgets/mb_Featured_Artwork_Widget.php');
	
	//todo
	/*
	mb_Artwork_Comment_Widget (shows a thumb and a selected comment - artwork select, comment select (ajax))
	*/
	register_widget( 'mb_Artwork_List_Widget' );
	register_widget( 'mb_Featured_Artwork_Widget' );
}


/* THEME ADMIN */
add_action ('admin_menu', 'mb_artpro_admin');
function mb_artpro_admin() {
    add_submenu_page('edit.php?post_type='.MBUDM_PT_ARTWORK, 'Manage Artwork Order', 'Ordering', 'edit_posts', basename(__FILE__), 'mbudm_artwork_order');
}


/* THEME Customizations */
add_action('customize_register', 'mb_artwork_customize');
function mb_artwork_customize($wp_customize) {
  	$wp_customize->add_section( 'mbudm_artwork', array(
        'title'          => __('Artwork',TEMPLATE_DOMAIN),
        'priority'       => 55,
    ) );
    
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[artwork_category_size]', array(
        'default'        => MBUDM_IMAGESIZE_WIDE_6,
        'transport'=>'postMessage'
    ) );
	$wp_customize->add_control( TEMPLATE_DOMAIN.'_options[artwork_category_size]', array(
        'label'   => 'Category Artowrk Image Size',
		'section' => 'mbudm_artwork',
        'type'    => 'select',
        'choices' => $mbudm_image_sizes
    ) );
    
     $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[artwork_captions]', array(
        'default'  => '0',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[artwork_captions]', array(
        'label'   => __('Show captions below artwork thumbnails',TEMPLATE_DOMAIN),
        'section' => 'mbudm_artwork',
        'type'    => 'checkbox',
    ) );
}


/* Register custom $_GET vars */
function mbudm_register_query_vars($vars) {
	// add all get vars to the valid list of variables
	$vars[] = 'mb_aid'; //attachment id
	$vars[] = 'mb_action'; // artwork navigation action
	$vars[] = 'mb_cat'; // artwork navigation category
    return $vars;
}
add_filter('query_vars', 'mbudm_register_query_vars');


/* ARTWORKS */

/* 
add custom meta boxes to the admin interface for artwork cpt 
*/
function mbudm_add_artworks_metaboxes(){
	global $arr_artwork_details;
	
	$arr_artwork_details  = array (
             MBUDM_PT_ARTWORK."-year"  => array(
             "name" =>  MBUDM_PT_ARTWORK."-year", // custom field name i.e. the KEY
             "type" =>  "input", // type of custom field i.e. required form's element type  could be input/textarea/select etc ...
             "title"  => "Year", // title to be used for the key i.e. form's  label
             "description"  => "Year Artwork created", // field description (if any)
             "scope"  => array(MBUDM_PT_ARTWORK) // define the scope in posts/pages
             ), 
              MBUDM_PT_ARTWORK."-medium"  => array(
             "name" =>  MBUDM_PT_ARTWORK."-medium", // custom field name i.e. the KEY
             "type" =>  "input", // type of custom field i.e. required form's element type  could be input/textarea/select etc ...
             "title"  => "Medium", // title to be used for the key i.e. form's  label
             "description"  => "E.g Oil on canvas, bronze, mixed media ", // field description (if any)
             "scope"  => array(MBUDM_PT_ARTWORK) // define the scope in posts/pages
             ), 
              MBUDM_PT_ARTWORK."-height"  => array(
             "name" =>  MBUDM_PT_ARTWORK."-height", // custom field name i.e. the KEY
             "type" =>  "input", // type of custom field i.e. required form's element type  could be input/textarea/select etc ...
             "title"  => "Height", // title to be used for the key i.e. form's  label
             "description"  => "The height of the artwork. Include the unit, mm, cm, or inches.", // field description (if any)
             "scope"  => array(MBUDM_PT_ARTWORK) // define the scope in posts/pages
             ), 
              MBUDM_PT_ARTWORK."-width"  => array(
             "name" =>  MBUDM_PT_ARTWORK."-width", // custom field name i.e. the KEY
             "type" =>  "input", // type of custom field i.e. required form's element type  could be input/textarea/select etc ...
             "title"  => "Width", // title to be used for the key i.e. form's  label
             "description"  => "The width of the artwork. Include the unit, mm, cm, or inches.", // field description (if any)
             "scope"  => array(MBUDM_PT_ARTWORK) // define the scope in posts/pages
             ), 
              MBUDM_PT_ARTWORK."-depth"  => array(
             "name" =>  MBUDM_PT_ARTWORK."-depth", // custom field name i.e. the KEY
             "type" =>  "input", // type of custom field i.e. required form's element type  could be input/textarea/select etc ...
             "title"  => "Depth", // title to be used for the key i.e. form's  label
             "description"  => "The depth of the artwork. Include the unit, mm, cm, or inches.", // field description (if any)
             "scope"  => array(MBUDM_PT_ARTWORK) // define the scope in posts/pages
             ), 
               MBUDM_PT_ARTWORK."-freetext"  => array(
             "name" =>  MBUDM_PT_ARTWORK."-freetext", // custom field name i.e. the KEY
             "type" =>  "textarea", // type of custom field i.e. required form's element type  could be input/textarea/select etc ...
             "title"  => "Customised summary", // title to be used for the key i.e. form's  label
             "description"  => "If you want to write a custom artwork summary, enter it in here. This will stop any of the other summary items (including price and title) from showing. Basic HTML formatting tags are allowed here (eg &lt;strong&gt;bold&lt;/strong&gt; or &lt;em&gt;italic&lt;/em&gt; )", // field description (if any)
             "scope"  => array(MBUDM_PT_ARTWORK) // define the scope in posts/pages
             ), 
                 
 	);
	add_meta_box('mbudm_sticky_meta', __('Featured',TEMPLATE_DOMAIN), 'mbudm_sticky_meta', MBUDM_PT_ARTWORK,'side');
	add_meta_box('mbudm_artwork_price', __('Price',TEMPLATE_DOMAIN), 'mbudm_artwork_price', MBUDM_PT_ARTWORK, 'side', 'default');
	add_meta_box('mbudm_artwork_meta', __('Summary',TEMPLATE_DOMAIN), 'mbudm_artwork_meta', MBUDM_PT_ARTWORK, 'normal', 'default');
	remove_meta_box( 'postimagediv', MBUDM_PT_ARTWORK, 'side' );
}
/* Override custom fields interface with specific meta fields */
$arr_artwork_details = array();

function  mbudm_artwork_meta() {
	global $post, $arr_artwork_details;
    echo'<input type="hidden"  name="'.$meta_box['name'].'_noncename"  id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce(  plugin_basename(__FILE__) ).'" />';
    foreach($arr_artwork_details as $meta_box) {               
		  echo'<div class="mb_form_field" ><label for="'.$meta_box['name'].'">'.$meta_box['title'].'</label>';
		  switch( $meta_box['type']){
			  case "input":
				  $meta_box_value =  get_post_meta($post->ID, $meta_box['name'], true);
				  if($meta_box_value ==  "")
							  $meta_box_value =  $meta_box['std'];
				  echo'<input  type="text" name="'.$meta_box['name'].'"  value="'.$meta_box_value.'"  />';			  
			  break;
			  case "textarea":				
				  $meta_box_value =  get_post_meta($post->ID, $meta_box['name'], true);	  
				  if($meta_box_value ==  "")
							  $meta_box_value =  $meta_box['std'];  
				  echo'<textarea name="'.$meta_box['name'].'"  >'.$meta_box_value.'</textarea>';			  
			  break;
			  case "select":
				  echo'<select  name="'.$meta_box['name'].'">';
				  foreach ($meta_box['options'] as  $option) {
							  echo'<option';
							  if (  get_post_meta($post->ID, $meta_box['name'], true) == $option ) { 
										  echo '  selected="selected"'; 
							  } elseif ( $option ==  $meta_box['std'] ) { 
										  echo '  selected="selected"'; 
							  } 
							  echo'>'.  $option .'</option>';	  
				  }			  
				  echo'</select>';
			  break;        
		  }                        
		  echo'<p>'.$meta_box['description'].'</p>';     
		  echo '</div>';
	}
}


/* 
Featured item meta box for Artworks
*/
function mbudm_sticky_meta() { 
	global $post; ?>
	<input id="mbudm-sticky" name="sticky" type="checkbox" value="sticky" <?php checked(is_sticky($post->ID)); ?> /> <label for="mbudm-sticky" class="selectit"><?php _e('Make this a featured item', TEMPLATE_DOMAIN) ?></label>
	<?php
}

/* 
Artwork Price meta box for Artworks 
*/
function mbudm_artwork_price(){
    global $post;

	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="artworkmeta_noncename" id="artworkmeta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the price data if its already been entered
    $price = get_post_meta($post->ID, '_mbudm_price', true);
 	_e('The product price should be entered in plain decimal format - so enter 2000.00 if your product price is $2,000.00 or €2.000,00. The formatting of prices is handled in Theme Options -> Currency',TEMPLATE_DOMAIN);
    // Echo out the field
    echo '<input type="text" name="_mbudm_price" value="' . $price  . '" class="widefat" />';
 
}

/* 
Save the Metabox Data for Artworks
- handles all custom meta boxes for MBUDM_PT_ARTWORK custom post type
 */
function mbudm_save_item_meta($post_id, $post) {
    global $arr_artwork_details;
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if(!isset($_POST['artworkmeta_noncename'])){
    	return $post->ID;
    }
    if (!wp_verify_nonce( $_POST['artworkmeta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
 
    switch($_POST['post_type']) 
    {
        case MBUDM_PT_ARTWORK:
    		$pt_meta['_mbudm_price'] = $_POST['_mbudm_price'];
    		
    		foreach($arr_artwork_details as $meta_box) {
				if( isset($_POST[$meta_box['name']]) ){
					$pt_meta[$meta_box['name']] = $_POST[$meta_box['name']];
    			}
    		}    
    		
    		/* check that this artwork is in all category order arrays for categories 
    		that it belongs to. Also ensure it is not in any arrays for cats that 
    		it doesn't belong to (but it might have been before this revision */
    		$args=array(
			  'taxonomy' => 'category',
			  'orderby' => 'name',
			  'order' => 'ASC'
			  );
	
			$categories=get_categories($args);
			
			$post_categories = wp_get_post_categories( $post_id );

			foreach($categories as $category) { 
				$cat_meta = get_option( "taxonomy_" . $category->cat_ID  ); 
				$order_arr = explode(",",$cat_meta['cat_artwork_order']);
				if(in_array($category->cat_ID , $post_categories) ){
					if(!in_array($post_id,$order_arr)){
						// the post is in this category but not yet in the order array  so add it
						$order_arr[] = $post_id;
					}
				}else{
					if(($key = array_search($post_id,$order_arr)) !== false) {
					    // the the post is not in this category so remove it from the order array	
						unset($order_arr[$key]);
					}
				}
				$cat_meta['cat_artwork_order'] = implode(",",$order_arr);
				update_option( "taxonomy_" . $category->cat_ID,$cat_meta); 
			}
    		
    	break;
    }
    if( $pt_meta){
		// Add values of $products_meta as custom fields
		foreach ($pt_meta as $key => $value) { // Cycle through the $products_meta array!
			if( $post->post_type == 'revision' ) return; // Don't store custom data twice
			$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
			if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			} else { // If the custom field doesn't have a value
				add_post_meta($post->ID, $key, $value);
			}
			if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
		}
    }
}
add_action('save_post', 'mbudm_save_item_meta', 1, 2); // save custom fields for custom post types

/* get the meta data for the artwork */
function mbudm_get_artwork_meta($post_id,$grid_class){
    global $arr_artwork_details;
	$all_meta = get_post_custom($post_id );
	$artwork_metadata ='';
	
	$freetext_meta = $all_meta[MBUDM_PT_ARTWORK."-freetext"];
	$ftext_str = isset($freetext_meta) ? join($freetext_meta,',') : '' ;
	if(strlen($freetext_meta) > 0){
		// just show the custom artwork summary
		$artwork_metadata ='<aside id="artwork-meta" class="'. $grid_class .'  custom-artwork-meta">'. $ftext_str . '</aside>';
	}else{
	 
		$title =  get_the_title($post_id);
		$artwork_metadata =  '<aside id="artwork-meta" class="' . $grid_class .'"><dl><dt>Title</dt><dd>' . $title . '</dd></dl>'. $tdata;
		$price = mbudm_get_the_artwork_price($post_id);
		if(strlen($price)>0 && floatval($price) > 0){
			$artwork_metadata .='<dl><dt>Price</dt><dd><strong>' . $price .' </strong></dd></dl></aside>';
		}			
		foreach($arr_artwork_details as $meta_box) {
			if( isset($all_meta[$meta_box['name']]) ){
				$artwork_metadata.='<dl><dt>'.$meta_box['title']. '</dt><dd>' . join($all_meta[$meta_box['name']],',') . '</dd></dl>';
				
			}else{
			
			}
		}         
	}
	return $artwork_metadata;
}

/* get the price - formatted into currency*/
function mbudm_the_artwork_price($artwork_id = null,$with_currency = false){
	echo mbudm_get_the_artwork_price($artwork_id,$with_currency);
}

function mbudm_get_the_artwork_price($artwork_id = null,$with_currency = false){
	if($artwork_id == null )
		$artwork_id = get_the_ID();
	$raw_price = get_post_meta( $artwork_id, '_mbudm_price', true );
	if(!$raw_price){
		$raw_price = 0;
	}
	return mbudm_format_value($raw_price,$with_currency);
}

/* 
Render the featured artworks
*/
function mbudm_get_featured_artwork($display_mode){
	ob_start();
	require_once( get_stylesheet_directory() .'/includes/featured_artwork.php' ));
	$is_single = false;
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}

/*
Called from widget MBU Artwork list 
- sticky_mode
- category
- thumb_size
- num_artworks
*/
function mbudm_get_artwork_list($sticky_mode,$category,$thumb_size,$num_artworks=-1){
		$sticky_array = get_option( 'sticky_posts' );
		// get products as list
		switch($sticky_mode){
			case 'unfeatured':
				$args = array(
						'numberposts' => $num_artworks,
						'post__not_in' => $sticky_array,
						'post_type' => MBUDM_PT_ARTWORK,
						'orderby' => 'title',
						'order' => 'ASC'
				);
			break;
			case 'featured':
				$args = array(
						'numberposts' => $num_artworks,
						'post__in' => $sticky_array,
						'post_type' => MBUDM_PT_ARTWORK,
						'orderby' => 'title',
						'order' => 'ASC'
				);
			break;
			case 'all':
			default:
				$args = array(
						'numberposts' => $num_artworks,
						'post_type' => MBUDM_PT_ARTWORK,
						'orderby' => 'title',
						'order' => 'ASC'
				);
			break;
		}
		if( isset($category) ){
			$args['category'] = $category;
		}
		
		$query = get_posts( $args );
		if ( count( $query ) > 0 ) {
			$filepath = mbudm_get_template_file_path( 'artwork_list.php' );
			ob_start();
			require_once( get_stylesheet_directory() .'/'.$filepath);

			$is_single = false;
			$output = ob_get_contents();
			ob_end_clean();
			echo $output;
		}
}


function mbudm_get_previous_artwork($aid,$cid){	
	$order_arr = mbudm_get_artwork_order($cid);
	if($order_arr){
		$key = array_search($aid,$order_arr);
	
		$previous = $aid == reset($order_arr) ? end($order_arr) : $order_arr[$key - 1] ;
		
	}else{
		$previous_obj  = get_previous_post( true ); 
		$previous = $previous_obj->ID;
	}
	return $previous;
}
function mbudm_get_next_artwork($aid,$cid){
	$order_arr = mbudm_get_artwork_order($cid);
	if($order_arr){
		$key = array_search($aid,$order_arr);
		$next = $aid == end($order_arr) ? reset($order_arr) : $order_arr[$key + 1] ;
		//echo 'aid: '.$aid. ' key:'. $key. ' next:' . $next ;
	}else{
		//echo 'order_arr: '.$order_arr ;
		$next_obj  = get_next_post( true ); 
		$next = $next_obj->ID;
	}
	return $next;
}
function mbudm_get_artwork_order($cid){
	$order_arr = false;
	$cat_meta = get_option( "taxonomy_" . $cid  ); 
	if($cat_meta){
		$order_arr = explode(",",$cat_meta['cat_artwork_order']);
	}
	return $order_arr;
}

/* add a page to sort the artwork, and save this as category metadata */
function mbudm_artwork_order(){
	// verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if(isset($_POST['action']) && $_POST['action'] == "reorder_artwork" ){
		if(!wp_verify_nonce($_POST['_wpnonce'], 'reorder_artwork') ) {
			wp_die( __('You do not have sufficient permissions to access this page.',TEMPLATE_DOMAIN) );
		}else{
			//process submitted artwork order
			$args=array(
			  'taxonomy' => 'category',
			  'orderby' => 'name',
			  'order' => 'ASC'
			  );
	
			$categories=get_categories($args);
			
			foreach($categories as $category) { 
			
				if(isset($_POST['mb_artwork_order_cat_' . $category->cat_ID]) ){
					$cat_meta = get_option( "taxonomy_" . $category->cat_ID  ); 
					$cat_meta['cat_artwork_order'] = $_POST['mb_artwork_order_cat_' . $category->cat_ID];
					update_option( "taxonomy_" . $category->cat_ID,$cat_meta); 
				}
				
			}
		}
 	}
    // Is the user allowed to edit the post or page?
    if (!current_user_can('update_themes'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.',TEMPLATE_DOMAIN) );
    }
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.

	 ?>
	 <div class="wrap" id="artwork-order">
	 <h2><?php _e( 'Manage Artwork Order',TEMPLATE_DOMAIN ) ?></h2>
	 <?
	 $args=array(
		  'taxonomy' => 'category',
		  'orderby' => 'name',
		  'order' => 'ASC'
		  );

		$categories=get_categories($args);
		
		$catfields = '';
		
		foreach($categories as $category) { 
		
			$cat_meta = get_option( "taxonomy_" . $category->cat_ID  ); 
			
			$catfields .= '<input type="hidden" name="mb_artwork_order_cat_' . $category->cat_ID . '" value="'.$cat_meta['cat_artwork_order'].'" />';
			
			$saved_order = explode(",",$cat_meta['cat_artwork_order']);
			
			echo '<div>';
			echo '<h3>'. $category->name.'</h3>';
		   
			$alist_args = array('tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field' => 'id',
						'terms' => $category->cat_ID
					)
				),
				'post_type' => 'mbudm_artwork',
				'posts_per_page'=>-1,
				'orderby' => 'title',
				'order' => 'ASC'
			);
			// The Query
			$the_alist_query = new WP_Query( $alist_args );
			
			
	
			if($the_alist_query->post_count > 0){
				$msg = 	count($saved_order) . ' - ' . $the_alist_query->post_count . ' | ';
				$output = $msg . '<ul id="mb_artwork_order_cat_'.$category->cat_ID.'" >';
				
				//use saved order if it is the same count()
				if(count($saved_order) == $the_alist_query->post_count ){
				
					foreach($saved_order as $aid) { 
						$output .= '<li id="mb_artwork_'.$aid.'" >'. mbudm_get_post_image($aid, MBUDM_IMAGESIZE_2,false) . '</li>'; 
					}
				}else{
			
					// The Loop
					while ( $the_alist_query->have_posts() ) : $the_alist_query->the_post();
						
						$output .= '<li id="mb_artwork_'.get_the_id().'" >'. mbudm_get_post_image(get_the_id(), MBUDM_IMAGESIZE_2,false) . '</li>'; 
						
					endwhile;
					
					// Reset Post Data
					wp_reset_postdata();
				
				}
				
				$output .= '</ul>';
			}else{
				$output = '<p>There are no artworks in this category.</p>';
			}
			
			echo($output);
			echo '</div>';
		}
		?>
		<form name='reorder_artwork' id='reorder_artwork' method='post' action='' >
			<input type='hidden' name='action' value='reorder_artwork' />
			<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('reorder_artwork'); ?>' />
			<?php echo $catfields  ?>
	 <p class="submit">
		<input type="submit" class="button-primary" value="Update Artwork Order" />
	 </p>
	 </div>
	 <?php
}


?>
