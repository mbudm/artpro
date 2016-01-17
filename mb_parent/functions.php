<?php
/**
 * mb_parent (mbudm parent theme) functions and definitions
 *
 */

/* SETUP */

/*
Theme setup
 - this is the first thing to run. called from 'after_setup_theme'
 - define constants that are able to be defined at this point
 - register sidebars
 - add post thumbnail support
 - define image sizes
 - add custom post types to the rss feed
 */

 	define('THEMENAME','MBU Parent Theme'); //friendly punctuated version
	define('THEMESHORTNAME','mb_parent'); //short name
	define('THEMEPREFIX','mbp'); //short prefix
	$td  = end(explode("/", get_template_directory()));
	define('TEMPLATE_DOMAIN', $td); // theme dir name  - unfriendly lowercase stub type version

add_action( 'after_setup_theme', 'mbudm_theme_setup' );
function mbudm_theme_setup(){
	global $mbudm_image_sizes;

// show admin bar only for admins
	if (!current_user_can('manage_options')) {
		add_filter('show_admin_bar', '__return_false');
	}


	$defaults = array('header-text' => true);
	add_theme_support( 'custom-header',$defaults);
	add_theme_support( 'post-thumbnails' );

/* Disable the Admin Bar. */
//remove_action( 'init', 'wp_admin_bar_init' );


	//define( 'NO_HEADER_TEXT', true );

	load_theme_textdomain(TEMPLATE_DOMAIN);

	if(function_exists('register_sidebar')){
		$sidebars = array(
			'home-hero'=> __('Home Hero', TEMPLATE_DOMAIN),
			'home-secondary'=>__('Home Secondary', TEMPLATE_DOMAIN),
			'home-tertiary'=>__('Home Tertiary', TEMPLATE_DOMAIN),
			'index'=>__('Index', TEMPLATE_DOMAIN),
			'single-post'=>__('Single Post', TEMPLATE_DOMAIN),
			'page'=>__('Page', TEMPLATE_DOMAIN),
			'header-sbar'=>__('Header', TEMPLATE_DOMAIN),
			'footer-sbar'=>__('Footer', TEMPLATE_DOMAIN),
		);

		mbudm_register_sidebars($sidebars);
	}



	define('MBUDM_IMAGESIZE_PREFIX','image-');
	define('MBUDM_IMAGESIZE_BANNER',MBUDM_IMAGESIZE_PREFIX .'24-banner');
	define('MBUDM_IMAGESIZE_20',MBUDM_IMAGESIZE_PREFIX .'20');
	define('MBUDM_IMAGESIZE_18',MBUDM_IMAGESIZE_PREFIX .'18');
	define('MBUDM_IMAGESIZE_WIDE_18',MBUDM_IMAGESIZE_PREFIX .'wide-18');
	define('MBUDM_IMAGESIZE_16',MBUDM_IMAGESIZE_PREFIX .'16');
	define('MBUDM_IMAGESIZE_12',MBUDM_IMAGESIZE_PREFIX .'12');
	define('MBUDM_IMAGESIZE_8',MBUDM_IMAGESIZE_PREFIX .'8');
	define('MBUDM_IMAGESIZE_6',MBUDM_IMAGESIZE_PREFIX .'6');

	define('MBUDM_IMAGESIZE_THUMB_6',MBUDM_IMAGESIZE_PREFIX .'thumb-6');
	define('MBUDM_IMAGESIZE_WIDE_6',MBUDM_IMAGESIZE_PREFIX .'wide-6');
	define('MBUDM_IMAGESIZE_5',MBUDM_IMAGESIZE_PREFIX .'5');
	define('MBUDM_IMAGESIZE_4',MBUDM_IMAGESIZE_PREFIX .'4');
	define('MBUDM_IMAGESIZE_3',MBUDM_IMAGESIZE_PREFIX .'3');
	define('MBUDM_IMAGESIZE_2',MBUDM_IMAGESIZE_PREFIX .'2');
	define('MBUDM_IMAGESIZE_1',MBUDM_IMAGESIZE_PREFIX .'1');

	mbudm_add_image_size('Full width banner (950 x 400)', MBUDM_IMAGESIZE_BANNER, 950, 400 ); //banner
	mbudm_add_image_size('5/6 (790 x 790)', MBUDM_IMAGESIZE_20, 790, 790 );
	mbudm_add_image_size('3/4 (710 x 710)', MBUDM_IMAGESIZE_18, 710, 710 );
	mbudm_add_image_size('3/4 WIDE (950 x 710)', MBUDM_IMAGESIZE_WIDE_18, 950, 710 ); //a bit taller than a banner
	mbudm_add_image_size('2/3 (630 x 630)', MBUDM_IMAGESIZE_16, 630, 630 );
	mbudm_add_image_size('1/2 (470 x 470)', MBUDM_IMAGESIZE_12, 470, 470 );
	mbudm_add_image_size('1/3 (310 x 310)', MBUDM_IMAGESIZE_8, 310, 310 );
	mbudm_add_image_size('1/4 (230 x 230)', MBUDM_IMAGESIZE_6, 230, 230);
	mbudm_add_image_size('1/4 WIDE (950 x 230)', MBUDM_IMAGESIZE_WIDE_6, 950, 230);// for category/search result layout
	mbudm_add_image_size('1/4 Thumb (230 wide)', MBUDM_IMAGESIZE_THUMB_6, 230, 950);// for hp blog archive grid layout
	mbudm_add_image_size('1/5 (182 x 182)', MBUDM_IMAGESIZE_5, 182,182 ); // not 5 cols but close
	mbudm_add_image_size('1/6 (150 x 150)', MBUDM_IMAGESIZE_4, 150, 150 );
	mbudm_add_image_size('1/8 (110 x 110) - cropped', MBUDM_IMAGESIZE_3, 110, 110 , true ); // thumbnails
	mbudm_add_image_size('1/12 (70 x 70) - cropped', MBUDM_IMAGESIZE_2, 70, 70 , true ); // tiny thumbnails
	mbudm_add_image_size('1/24 (30 x 30) - cropped', MBUDM_IMAGESIZE_1, 30, 30 , true );  //only avatars really



	define('MBUDM_HERO_TILE','simple_tile');
	define('MBUDM_HERO_LARGE','simple_large');
	define('MBUDM_HERO_SINGLE','simple_single');
	define('MBUDM_HERO_SWIPER','swiper');
}
/* globals used for customize UI */
$mbudm_font_sizes = array(
	'0.6em' => 'Tinsy',
	'0.7em' => 'Very Small',
	'0.8em' => 'Small',
	'0.9em' => 'Regular',
	'1.0em' => 'Medium',
	'1.1em' => 'Large',
	'1.2em' => 'Very Large',
	'1.4em' => 'Extra Large',
	'1.8em' => 'XXL',
	'2.4em' => 'XXXL',
);
$mbudm_image_sizes = array();
function mbudm_add_image_size( $size_name, $size_slug, $size_w, $size_h , $scale_bool = false ){
	global $mbudm_image_sizes;
	$mbudm_image_sizes[$size_slug] = $size_name ;
	add_image_size($size_slug, $size_w, $size_h , $scale_bool  );
}

function mbudm_register_sidebars($sidebars){
	foreach($sidebars as $sidebar_id => $sidebar_name ){
			//xtra widget class
			switch($sidebar_id){
				case '':
					$widget_class = 'grid_24 ';
				break;
				default:
					$widget_class = '';
				break;
			}
			register_sidebar(array(
				'name'=>$sidebar_name,
				'id'=>$sidebar_id,
				'before_widget' => '<div id="%1$s" class="'. $widget_class . 'widget %2$s"><div class="widget-inner">',
				'after_widget' => '</div></div>',
				'before_title' => '<h2>',
				'after_title' => '</h2>',
			));
		}
}

/* INIT */

/*
mbudm_init()
- all tasks that need to be triggered by the init hook
- create custom post type and
- define constants that were not able to be set up in mbudm_theme_setup();
- setup the default theme options array
- set up the wp menu in this theme
*/
add_action( 'init', 'mbudm_init' );
function mbudm_init(){

	mbudm_post_types();

	mbudm_define_constants();

	mbudm_setup_menus();
}

/* mbudm_post_types()
- child themes can add custom post types
*/
if ( ! function_exists( 'mbudm_post_types' ) ) {
	function mbudm_post_types() {
	}
}


/* define constants
- called from mbudm_init()
- these are constants that either could not be set up in theme_setup() - because the info, objects weren't ready or the constants are not needed before init.
*/
function mbudm_define_constants(){

	global $wpdb;
	$site_url = get_option('siteurl');
	$is_production  = strpos($site_url, 'localhost') === false ? true : false ;
	define('IS_PRODUCTION',$is_production  ); //
	$is_offline = isset($_GET['mb_offline']) ? true : false ;
	define('IS_OFFLINE',$is_offline ); //
	define('MBUDM_UMK_MAILING_LIST', "_mbudm_mailing_list"); // user meta key for mailing list
	define('MBUDM_MAILING_LIST_UNSUBSCRIBED', 0); // user meta value for unsubscribed
	define('MBUDM_MAILING_LIST_PENDING', 1); // user meta value for pending subscription
	define('MBUDM_MAILING_LIST_SUBSCRIBED', 2); // user meta value for subscribed subscription

}

/*
Menus
- two menus in this theme
*/
function mbudm_setup_menus(){
	//Add support for nav menus
	add_theme_support( 'nav-menus' );

	//Register the menu location
	if(function_exists('register_nav_menu')):
		register_nav_menu( 'primary_nav',  _x('Main','Nav Menu',TEMPLATE_DOMAIN)  );
		register_nav_menu( 'footer_nav',  _x('Footer','Nav Menu',TEMPLATE_DOMAIN) );
	endif;
	//add_filter('nav_menu_css_class', 'mbudm_add_page_type_to_menu', 10, 2 );
}


/* HOOKS AND FILTERS */

/*
Set custom image sizes for this theme to appear in the media interface
*/
function mbudm_image_sizes_choose($sizes) {
	$myimgsizes = array(
			MBUDM_IMAGESIZE_24 => __( "MBU Full width",TEMPLATE_DOMAIN ),
			MBUDM_IMAGESIZE_BANNER => __( "MBU Full width (banner)" ,TEMPLATE_DOMAIN),
			MBUDM_IMAGESIZE_18 => __( "MBU 3/4",TEMPLATE_DOMAIN ),
			MBUDM_IMAGESIZE_16 => __( "MBU 2/3",TEMPLATE_DOMAIN ),
			MBUDM_IMAGESIZE_12 => __( "MBU 1/2" ,TEMPLATE_DOMAIN),
			MBUDM_IMAGESIZE_8 => __( "MBU 1/3" ,TEMPLATE_DOMAIN),
			MBUDM_IMAGESIZE_6 => __( "MBU 1/4" ,TEMPLATE_DOMAIN),
			MBUDM_IMAGESIZE_WIDE_6 => __( "MBU 1/4 high" ,TEMPLATE_DOMAIN),
			MBUDM_IMAGESIZE_4 => __( "MBU 1/6" ,TEMPLATE_DOMAIN)
			);
	$newimgsizes = array_merge($sizes, $myimgsizes);
	return $newimgsizes;
}
add_filter('image_size_names_choose', 'mbudm_image_sizes_choose');




/* COMMENTS */

function mbudm_format_comment($comment, $args, $depth)  {

        $GLOBALS['comment'] = $comment; ?>
        <li <?php comment_class(empty( $args['has_children'] ) ? 'cfix' : 'parent cfix') ?>  id="comment-<?php comment_ID() ?>">
                <?php
                /*
                echo '<pre>';
                print_r($comment);
                echo '</pre>';
                */
                ?>
            <div class="comment-vcard grid_6 push_18">
            	<div class="inner">
                <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] , get_template_directory() .'/img/rodin_avatar.png' ); ?>
                	<h4><?php printf(__('%s'), get_comment_author_link()) ?></h4>
                	<time datetime="<?php echo get_comment_date(DATE_ISO8601); ?>" ><?php echo get_comment_date("d M Y"); ?><span><?php echo get_comment_time("g:ia"); ?></span></time>
                </div>
            </div>

            <div class="comment_body grid_18 pull_6" >
            	<div class="inner">
            		<div class="comment_text cfix">
            <?php if ($comment->comment_approved == '0') : ?>
                		<em><php _e('Your comment is awaiting moderation.') ?></em>
            <?php endif; ?>
            			<?php
					echo comment_reply_link(array_merge($args,array('reply_text' => 'Reply', 'add_below' => 'comment-', 'depth' => $depth, 'max_depth' => $args['max_depth']) ) );
					?><?php comment_text(); ?>
					</div>
            	</div>
            </div>
<?php
}

/* WIDGETS */

/* Custom widgets
- all widgets are in /widgets
*/
add_action( 'widgets_init', 'mbudm_widgets_init' );
function mbudm_widgets_init(){

	include ('widgets/mb_Contact_Form_Widget.php');
	include ('widgets/mb_Featured_Comment_Widget.php');
	include ('widgets/mb_Mailing_List_Widget.php');
	include ('widgets/mb_Recent_Comments_Widget.php');
	include ('widgets/mb_Recent_Posts_Widget.php');
	include ('widgets/mb_Page_Content_Widget.php');
	include ('widgets/mb_Page_Promo_Widget.php');
	include ('widgets/mb_Social_Media_Follow_Widget.php');
	include ('widgets/mb_Social_Media_Share_Widget.php');
	include ('widgets/mb_Text_Widget.php');


	register_widget( 'mb_Contact_Form_Widget' );
	register_widget( 'mb_Featured_Comment_Widget' );
	register_widget( 'mb_Mailing_List_Widget' );
	register_widget( 'mb_Page_Content_Widget' );
	register_widget( 'mb_Page_Promo_Widget' );
	register_widget( 'mb_Recent_Comments_Widget' );
	register_widget( 'mb_Recent_Posts_Widget' );
	register_widget( 'mb_Social_Media_Follow_Widget' );
	register_widget( 'mb_Social_Media_Share_Widget' );
	register_widget( 'mb_Text_Widget' );
}

/* blank widget message */
function mbudm_widget_blank_msg($sidebar_name){
	if (current_user_can( 'switch_themes' ) && !mbudm_get_option(THEMEPREFIX.'_hide_widget_blank_message') ) {
	?>
	<div class="widget-blank-msg">
		<h3><?php _e('Add Widgets', TEMPLATE_DOMAIN); ?></h3>
		<p><?php printf(_x('This is the "%1$s" widget area. Add %2$swidgets%3$s here if you like.','Variable 1 is the sidebar name and 2 and 3 tag a link to the admin widgets page', TEMPLATE_DOMAIN),$sidebar_name,'<a href="/wp-admin/widgets.php">','</a>'); ?></p><small><?php _e('Only administrators are shown this message, and it can be switched off in Theme Options. Regular users will not see anything if this sidebar contains no widgets.', TEMPLATE_DOMAIN); ?></small>
		</div>
	<?php
	}
}

/* Set widget grid size */
function mbudm_widget_form_extend( $instance, $widget ) {
	if( isset($widget->defaults['grid_set']) && $widget->defaults['grid_set'] == false ){
		return $instance;
	}
	if ( !isset($instance['grid']) ) $instance['grid'] = null;
	//only display for some widgets?
	//only exclude if widget opts out?
	//put it at the end?
	/*
	echo '<pre>';
	print_r($instance);
	print_r($widget);
	echo '</pre>';
	*/
	/* Allowed grid sizes */
	$grids = array( "no-grid"=>"auto","grid_4" => "1/6",
"grid_6" => "1/4","grid_8" => "1/3","grid_12" => "1/2","grid_16"=>"2/3","grid_18" => "3/4","grid_20" => "5/6");
	$row = "<p><label for='widget-{$widget->id_base}-{$widget->number}-grid'>Width:</label>";
	$row .= "<select name='widget-{$widget->id_base}[{$widget->number}][grid]' id='widget-{$widget->id_base}-{$widget->number}-grid' class='widefat'>";
	foreach($grids as $grid => $grid_label) {
	$selected = $instance['grid'] == $grid ? ' selected="selected" ' : '' ;
	$row .= "<option value='".$grid."'". $selected. ">".$grid_label."</option>";
	}
	$row .= "</select>";
	echo $row;
	return $instance;
}
add_filter('widget_form_callback', 'mbudm_widget_form_extend', 10, 2);
function mbudm_widget_update( $instance, $new_instance ) {
	$instance['grid'] = $new_instance['grid'];
	return $instance;
}
add_filter( 'widget_update_callback', 'mbudm_widget_update', 10, 2 );
function mbudm_dynamic_sidebar_params( $params ) {
	global $wp_registered_widgets;
	$widget_id    = $params[0]['widget_id'];
	$widget_obj    = $wp_registered_widgets[$widget_id];
	$widget_opt    = get_option($widget_obj['callback'][0]->option_name);
	$widget_num    = $widget_obj['params'][0]['number'];

	if ( isset($widget_opt[$widget_num]['grid']) && !empty($widget_opt[$widget_num]['grid']) )
	$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$widget_opt[$widget_num]['grid']} ", $params[0]['before_widget'], 1 );

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'mbudm_dynamic_sidebar_params' );

/* COMMENTS */


/* format a value into currency */
function mbudm_format_value($val,$with_currency = false){
	$csymbol = mbudm_get_option(THEMEPREFIX."_currency_symbol");
	$cpos = mbudm_get_option(THEMEPREFIX."_currency_symbol_position");
	$cs = $cpos == "before" ? $csymbol : '' ;
	$ca = $cpos == "after" ? $csymbol : '' ;
	$dsep = mbudm_get_option(THEMEPREFIX."_decimal_separator");
	$tsep = mbudm_get_option(THEMEPREFIX."_thousand_separator");
	$cflag = $with_currency ? '<span class="currency-flag" >'.mbudm_get_option(THEMEPREFIX."_currency") .'</span>' : '' ;
	return $cs . number_format($val, 2, $dsep, $tsep) . $ca . $cflag ;
}


/* CONTENT PARTS */
function mbudm_posted_on(){
 echo '<small class="entry_posted_on" >Published '. get_the_time('m/j/y') . '</small>';
}



/* THEME ADMIN */
add_action ('admin_menu', 'mbudm_admin');
function mbudm_admin() {
    // add the Customize link to the admin menu
    add_theme_page( __('Customize',TEMPLATE_DOMAIN), __('Customize',TEMPLATE_DOMAIN), 'edit_theme_options', 'customize.php' );

}

add_action('customize_register', 'mbudm_customize');
function mbudm_customize($wp_customize) {
	global $mbudm_image_sizes;
	global $mbudm_font_sizes;
	/*
    $wp_customize->add_section( 'mbudm_colors', array(
        'title'          => __('Colors',TEMPLATE_DOMAIN),
        'priority'       => 35,
    ) );
 */
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[key_color]', array(
        'default'        => '#0066CC',
        'transport'=>'postMessage'
    ) );
     $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[key_color]', array(
        'label'   => __('Key color',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[key_color]'
    ) ) );

    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[secondary_color]', array(
        'default'        => '#003366',
        'transport'=>'postMessage'
    ) );
     $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[secondary_color]', array(
        'label'   => __('Secondary color',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[secondary_color]'
    ) ) );

    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_white]', array(
        'default'        => '#ffffff',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_white]', array(
        'label'   => __('Image border',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_white]',
    ) ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_background]', array(
        'default'        => '#f4f3f1',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_background]', array(
        'label'   => __('Page background',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_background]',
    ) ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_very_light]', array(
        'default'        => '#e4e2e0',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_very_light]', array(
        'label'   => __('Very light',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_very_light]',
    ) ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_light]', array(
        'default'        => '#d4d3d0',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_light]', array(
        'label'   => __('Light',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_light]',
    ) ) );
     $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_tint]', array(
        'default'        => '#c4c3c0',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_tint]', array(
        'label'   => __('Base Tint',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_tint]',
    ) ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_color]', array(
        'default'        => '#999999',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_color]', array(
        'label'   => __('Base Color',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_color]',
    ) ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_dark]', array(
        'default'        => '#666666',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_dark]', array(
        'label'   => __('Dark',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_dark]',
    ) ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_very_dark]', array(
        'default'        => '#333333',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_very_dark]', array(
        'label'   => __('Very Dark',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_very_dark]',
    ) ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[base_black]', array(
        'default'        => '#000000',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, TEMPLATE_DOMAIN.'_options[base_black]', array(
        'label'   => __('Black',TEMPLATE_DOMAIN),
        'section' => 'colors',
        'settings'   => TEMPLATE_DOMAIN.'_options[base_black]',
    ) ) );

    $wp_customize->add_section( 'mbudm_branding', array(
        'title'          => __('Branding (header)',TEMPLATE_DOMAIN),
        'priority'       => 35,
    ) );


    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[branding_font]', array(
        'default'  => 'Georgia',
        'transport'=>'postMessage'
    ) );

      $wp_customize->add_control( TEMPLATE_DOMAIN.'_options[branding_font]', array(
        'label'   => __('Brand font',TEMPLATE_DOMAIN),
        'section' => 'mbudm_branding',
        'type'    => 'text',
    ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[branding_font_type]', array(
        'default'  => '0',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[branding_font_type]', array(
        'label'   => __('Is the Brand font a Google Web Font?',TEMPLATE_DOMAIN),
        'section' => 'mbudm_branding',
        'type'    => 'checkbox',
    ) );

	$wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[branding_font_size]', array(
        'default'  => '1.4em',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[branding_font_size]', array(
        'label'   => __('Brand font size',TEMPLATE_DOMAIN),
        'section' => 'mbudm_branding',
        'type'    => 'select',
        'choices' => $mbudm_font_sizes
    ) );

    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[branding_font_spacing]', array(
        'default'  => '0em',
        'transport'=>'postMessage'
    ) );

      $wp_customize->add_control( TEMPLATE_DOMAIN.'_options[branding_font_spacing]', array(
        'label'   => __('Brand font letter spacing (e.g. 1px or 0.5em)',TEMPLATE_DOMAIN),
        'section' => 'mbudm_branding',
        'type'    => 'text',
    ) );


     $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[header_layout]', array(
        'default'        => 'header_layout_h',
        'transport'=>'postMessage'
    ) );
	$wp_customize->add_control( TEMPLATE_DOMAIN.'_options[header_layout]', array(
        'label'   => 'Header layout style',
		'section' => 'mbudm_branding',
        'type'    => 'select',
        'choices' => array(
				'header_layout_h'=>'Horizontal layout',
				'header_layout_v'=>'Vertical layout'
			)
    ) );

    $wp_customize->add_section( 'mbudm_heading_font', array(
        'title'          => __('Headings/Subheadings text style',TEMPLATE_DOMAIN),
        'priority'       => 40,
    ) );

    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[heading_font]', array(
        'default'        => 'Georgia',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( TEMPLATE_DOMAIN.'_options[heading_font]', array(
        'label'   => __('Heading font',TEMPLATE_DOMAIN),
        'section' => 'mbudm_heading_font',
        'type'    => 'text',
    ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[heading_font_type]', array(
        'default'  => '0',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[heading_font_type]', array(
        'label'   => __('Is the heading font a Google Web Font?',TEMPLATE_DOMAIN),
        'section' => 'mbudm_heading_font',
        'type'    => 'checkbox',
    ) );

	$wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[heading_font_size]', array(
        'default'  => '1.4em',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[heading_font_size]', array(
        'label'   => __('Heading font size',TEMPLATE_DOMAIN),
        'section' => 'mbudm_heading_font',
        'type'    => 'select',
        'choices' => $mbudm_font_sizes
    ) );

    $wp_customize->add_section( 'mbudm_body_font', array(
        'title'          => __('Body text style',TEMPLATE_DOMAIN),
        'priority'       => 45,
    ) );
     $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[body_font]', array(
        'default'  => 'Trebuchet',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( TEMPLATE_DOMAIN.'_options[body_font]', array(
        'label'   => __('Body font',TEMPLATE_DOMAIN),
        'section' => 'mbudm_body_font',
        'type'    => 'text',
    ) );

   $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[body_font_type]', array(
        'default'  => '0',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[body_font_type]', array(
        'label'   => __('Is the body font a Google Web Font?',TEMPLATE_DOMAIN),
        'section' => 'mbudm_body_font',
        'type'    => 'checkbox',
    ) );
	$wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[body_font_size]', array(
        'default'  => '1em',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[body_font_size]', array(
        'label'   => __('Body font size',TEMPLATE_DOMAIN),
        'section' => 'mbudm_body_font',
        'type'    => 'select',
        'choices' => $mbudm_font_sizes
    ) );


    $wp_customize->add_section( 'mbudm_nav', array(
        'title'          => __('Navigation text style',TEMPLATE_DOMAIN),
        'priority'       => 50,
    ) );
    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[nav_font]', array(
        'default'  => 'Trebuchet',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control( TEMPLATE_DOMAIN.'_options[nav_font]', array(
        'label'   => __('Navigation font',TEMPLATE_DOMAIN),
        'section' => 'mbudm_nav',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[nav_font_type]', array(
        'default'  => '0',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[nav_font_type]', array(
        'label'   => __('Is the nav font a Google Web Font?',TEMPLATE_DOMAIN),
        'section' => 'mbudm_nav',
        'type'    => 'checkbox',
    ) );
	$wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[nav_font_size]', array(
        'default'  => '1.2em',
        'transport'=>'postMessage'
    ) );
    $wp_customize->add_control(  TEMPLATE_DOMAIN.'_options[nav_font_size]', array(
        'label'   => __('Navigation font size',TEMPLATE_DOMAIN),
        'section' => 'mbudm_nav',
        'type'    => 'select',
        'choices' => $mbudm_font_sizes
    ) );

       $wp_customize->add_section( 'mbudm_footer', array(
        'title'          => __('Footer',TEMPLATE_DOMAIN),
        'priority'       => 200,
    ) );

     $wp_customize->add_setting( TEMPLATE_DOMAIN.'_options[copyright_notice]', array(
        'default'        => 'Copyright 2013',
        'transport'=>'postMessage'
    ) );
	$wp_customize->add_control( TEMPLATE_DOMAIN.'_options[copyright_notice]', array(
        'label'   => __('Copyright notice',TEMPLATE_DOMAIN),
        'section' => 'mbudm_footer',
        'type'    => 'text',
    ) );
}

/* MAILING LIST */
/*
Add the mailing list subscription status of each user to the users page
*/
add_filter( 'manage_users_columns', 'mbudm_add_mailing_list_column');
function mbudm_add_mailing_list_column( $columns){
    $columns['_mbudm_mailing_list'] = __('Mailing list',TEMPLATE_DOMAIN);
    return $columns;
}
add_filter('manage_users_custom_column',  'mbudm_add_mailing_list_column_value', 10, 3);
function mbudm_add_mailing_list_column_value( $value, $column_name, $user_id ){
	$user = get_userdata( $user_id );
	$value = $user->_mbudm_mailing_list;
	switch($value){
		case 2:
			$value = __('Subscribed',TEMPLATE_DOMAIN);
		break;
		case 1:
			$value = __('Pending',TEMPLATE_DOMAIN);
		break;
		default:
			$value =__('Not subscribed',TEMPLATE_DOMAIN);
		break;
	}
	return $value;
}
function mbudm_mailing_list_admin_page(){

	//must check that the user has the required capability
    if (!current_user_can('update_themes'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.',TEMPLATE_DOMAIN) );
    }
    ?>
    <div class="wrap">
    <h2><?php _e('Export Mailing list subscribers',TEMPLATE_DOMAIN); ?></h2>
    <p><?php _e('Export users who are subscribed or who are pending confirmation to subscribe to your mailing list as a .csv file.',TEMPLATE_DOMAIN); ?></p>
	<p><?php _e('You can edit the .csv file in excel or Google Docs and most eMarketing tools can import .csv files.',TEMPLATE_DOMAIN); ?></p>
	<form method='post' id='mb_export_mailing_list' >
		<?php
		// Noncename needed to verify where the data originated
		wp_nonce_field( 'mb_export_mailing_list', '_wpnonce_mb_export_mailing_list');
		?>
			<input type="hidden" name="mb_settings_form_id" value="mb_export_mailing_list" />
			<input type="hidden" name="mb_settings_form_name" value="<?php _e('Export mailing list as CSV',TEMPLATE_DOMAIN); ?>" />
		<input name="save" class="button-primary" type = 'submit' value ='<?php _e('Export as CSV',TEMPLATE_DOMAIN); ?>' />
	</form>
	</div>
	<?php
}
add_action( 'show_user_profile', 'mbudm_profile_mailing_list' );
add_action( 'edit_user_profile', 'mbudm_profile_mailing_list' );

/*
Export Mailing List to a CSV file
Modified from the following plugin:
Plugin Name: Export Users to CSV
Plugin URI: http://pubpoet.com/plugins/
*/
function mbudm_export_mailing_list() {
		if ( isset( $_POST['_wpnonce_mb_export_mailing_list'] ) ) {
			check_admin_referer( 'mb_export_mailing_list', '_wpnonce_mb_export_mailing_list' );

			global $wpdb;
			$args = array(
				'fields' => 'all_with_meta',
				'meta_key' => '_mbudm_mailing_list',
				'meta_value' => 0,
				'meta_compare' => '>',
				'role' => ''
			);
			$users = get_users( $args );

			$col_headings = array(
				'ID' =>__('User ID',TEMPLATE_DOMAIN),
				'user_login' =>__('Login',TEMPLATE_DOMAIN),
				'user_email' =>__('Email',TEMPLATE_DOMAIN),
				'first_name' =>__('First name',TEMPLATE_DOMAIN),
				'last_name' =>__('Last name',TEMPLATE_DOMAIN),
				'nickname' =>__('Nickname',TEMPLATE_DOMAIN),
				'_mbudm_mailing_list' =>__('Subscribed',TEMPLATE_DOMAIN),
				'user_registered' =>__('Date registered',TEMPLATE_DOMAIN)
			);

			if ( ! $users ) {
				$referer = add_query_arg( 'error', 'empty', wp_get_referer() );
				wp_redirect( $referer );
				exit;
			}

			$sitename = sanitize_key( get_bloginfo( 'name' ) );
			$filename = $sitename . '-mailinglist-' . date( 'Y-m-d' ) . '.csv';

			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

			echo implode( ',', array_values($col_headings) ) . "\n";

			foreach ( $users as $user ) {
				$data = array();
				foreach ( $col_headings as $field=>$nicename ) {
					$value = isset( $user->{$field} ) ? $user->{$field} : '';
					if($field == '_mbudm_mailing_list' ){
						switch($value){
							case 2:
								$value = __('Subscribed',TEMPLATE_DOMAIN);
							break;
							case 1:
								$value = __('Pending',TEMPLATE_DOMAIN);
							break;
							default:
								$value =__('Not subscribed',TEMPLATE_DOMAIN);
							break;
						}
					}
					$data[] = '"' . str_replace( '"', '""', $value ) . '"';
				}
				echo implode( ',', $data ) . "\n";
			}
			exit;
		}
}

/*
Add checkbox for mailing list signup on user profile page
*/
function mbudm_profile_mailing_list( $user ) { ?>
	<table class="form-table">
		<tr>
			<th><label for="mbudm_mailing_list"><?php printf(__('Mailing list subscription for %s.',TEMPLATE_DOMAIN),get_bloginfo() ); ?></label></th>

			<td>
			<?php
			$mailing_list_status = get_user_meta($user->ID, '_mbudm_mailing_list', true);
			$mailing_list_checked = $mailing_list_status ? 'checked' : '' ;

			?>
				<label for="mbudm_mailing_list"><input type="checkbox" name="_mbudm_mailing_list" id="_mbudm_mailing_list" <?php echo $mailing_list_checked ?> /> <?php printf(__('Subscribed to the %s mailing list.',TEMPLATE_DOMAIN),get_bloginfo() ); ?> </label>
			</td>
		</tr>

	</table>
<?php }

add_action( 'personal_options_update', 'mbudm_profile_save_usermeta' );
add_action( 'edit_user_profile_update', 'mbudm_profile_save_usermeta' );
/* save the custom user meta */
function mbudm_profile_save_usermeta( $user_id ) {
	$mailing_list_status = isset($_POST['_mbudm_mailing_list']) ? 2 : 0 ; // 1 is for pending confirmation, not settable in this form.
	update_user_meta( $user_id, '_mbudm_mailing_list', $mailing_list_status );
}


/*
SHORTCODES
*/
/* Embed a widget in a page via a shortcode

Usage: [widget_via_shortcode widget_name="your_Widget_Class_Name" widget_option_one="Your value for widget option one" ]

*/
function widget_via_shortcode_parser( $atts, $content = null ){
	global $wp_widget_factory;

    extract(shortcode_atts(array(
        'widget_name' => FALSE
    ), $atts));

    $widget_name = esc_html($widget_name);

    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($widget_name));

        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct", TEMPLATE_DOMAIN ) ,'<strong>'.$widget_name.'</strong>').'</p>';
        else:
            $widget_name = $wp_class;
        endif;
    endif;

    /* use the widget defaults, if they are accessible. Override any defaults with an equivalent attribute that was added to the shortcode */
    if(is_array($wp_widget_factory->widgets[$widget_name]->defaults) ){
    	$combined_atts = array_merge($wp_widget_factory->widgets[$widget_name]->defaults,$atts);
    }else{
    	$combined_atts = $atts;
    }

	ob_start();
	the_widget($widget_name, $combined_atts, null );
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}
add_shortcode( 'widget_via_shortcode', 'widget_via_shortcode_parser' );

/* Embed a section element via shortcode

Usage: [section class="col1" ][/section]

*/
function section_shortcode_parser( $atts, $content = null ){
	global $wp_widget_factory;
   // remove_filter( 'the_content', 'wpautop' );
    extract(shortcode_atts(array(
        'class' => ''
    ), $atts));

	ob_start();
	?>
	<section class="<?php echo $class ?> via-shortcode">
	<?php echo do_shortcode($content); ?>
	</section>
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	//add_filter( 'the_content', 'wpautop' , 12);
	return $output;
}
add_shortcode( 'section', 'section_shortcode_parser' );

/* Embed a clearing element via shortcode

Usage: [clear]

*/
function clear_shortcode_parser( $atts, $content = null ){
	global $wp_widget_factory;
	ob_start();
	?>
	<div class="clear"></div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}
add_shortcode( 'clear', 'clear_shortcode_parser' );




/* UTILITIES */
/* get the image by post id, formatted as figure block with an optional link*/
function mbudm_get_post_image($pid = null,$image_size = null,$link = false,$gridClass = ''){
	if($pid == null )
		$pid = get_the_ID();
	if($image_size == null )
		$image_size = MBUDM_IMAGESIZE_3;

	// find something to use as a description
	$p_title = get_the_title($pid );
	$p_ex = get_the_excerpt();
	$p_desc = $p_ex ? $p_ex : $p_title;

	if($link){
		$p_url		= get_permalink($pid );
		$url_tag_open = '<a href="'.$p_url.'" title="'.$p_title.'" >';
	}else{
		$url_tag_open  = null;
	}
	$imgdata = mbudm_get_post_image_tag_and_caption($pid,$image_size);

	$imgdata['caption'] = $imgdata['caption'] ? $imgdata['caption'] : $p_desc ;
	return mbudm_render_image($imgdata,$url_tag_open,$gridClass);
}
/* get the image by image id, formatted as figure block with */
function mbudm_get_image($img_id = null,$image_size = null,$post_link = false,$gridClass = ''){
	if($img_id == null )
		return;
	if($image_size == null )
		$image_size = MBUDM_IMAGESIZE_12;


	$imgdata = mbudm_get_image_tag_and_caption(get_the_ID(),$img_id,$image_size);

	$url_tag_open = $post_link;
	if($url_tag_open){
		//add a link to the post with the image id as a param
		$p_url	= add_querystring_var(get_permalink(), 'mb_aid', $img_id);
		$url_tag_open = '<a href="'.$p_url.'" title="'.$imgdata['caption'].'" >';
	}

	return mbudm_render_image($imgdata,$url_tag_open,$gridClass);
}
/* get the image tag and caption by post id */
function mbudm_get_post_image_tag_and_caption($pid,$image_size){
	$p_img_id = null;
	if ( has_post_thumbnail($pid) ){
		$p_img_id =  get_post_thumbnail_id($pid);
	}else{
		//attachments are stored in a custom way so as not to disturb native attachments
		$atts = get_post_meta( $pid, '_attachments', false );
		// decode and unserialize the data of the first attachment
		if($atts){
			$data = unserialize( base64_decode( $atts[0] ) );
			$p_img_id = $data['id'];
		}
	}

	return mbudm_get_image_tag_and_caption($pid, $p_img_id,$image_size);
}
/* get the image tag and caption by image id */
function mbudm_get_image_tag_and_caption($pid, $img_id, $image_size){
	if(isset($img_id)){
		$img_atts  = wp_get_attachment_image_src( $img_id,  $image_size, false);

		$img_tag		= '<img src="' . $img_atts[0] . '"  width="' . $img_atts[1] . '"  height="' . $img_atts[2] . '"  class="attachment-' . $image_size . '" />';

		$img_post = get_post($img_id);
		//var_dump($img_post);
		//$img_caption = $img_post->post_excerpt ? $img_post->post_excerpt : $img_post->post_title ;
		$img_caption = get_the_excerpt();
	}else{
		$img_caption = get_the_excerpt();
		$img_tag	= null;
	}
  $img_caption = empty($img_caption) ? get_the_title($pid) : $img_caption ;
	$imgdata = array('tag' => $img_tag, 'caption' => $img_caption);

	//echo('tag: '.$img_tag .'caption: '.$img_caption . ' ' . $imgdata['tag']);
	return $imgdata;
}

function mbudm_render_image($imgdata,$url_tag_open,$gridClass){
	$imgClass = ( isset($imgdata) && isset($imgdata['tag']) ) ? 'has-img' : 'no-img' ;
	ob_start();
	?>
	<figure class="<?php echo $gridClass . ' ' . $imgClass ?> ">
		<div class="vframe"><!-- used for vertical centering -->
			<?php
			if($url_tag_open){
				echo $url_tag_open;
			}else{
				echo '<div>';
			}
			if (isset($imgdata) && isset($imgdata['tag'])){
				echo $imgdata['tag'];
			}else{
			?><span><?php _e('No Image Available',TEMPLATE_DOMAIN); ?></span><?php
			}
			if($url_tag_open){
				echo '</a>';
			}else{
				echo '</div>';
			}
			?>
		</div>
		<figcaption><?php echo $imgdata['caption'] ?></figcaption>
	</figure>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

function mb_featured_image($image_size = null) {
	global $post;

	if (has_post_thumbnail() ) {
	if($image_size == null )
		$image_size = MBUDM_IMAGESIZE_THUMB_6;

		//echo '<div class="post-thumb grid_6" >' . mbudm_get_post_image($post->ID,MBUDM_IMAGESIZE_THUMB_6,true) . '</div>';
		echo mbudm_get_post_image($post->ID,$image_size ,true);
	}
}


/* JS /CSS */

/*
enqueue scripts and CSS
- adds js and css files to the header/footer
*/
function mbudm_enqueue(){
	global $post;

	wp_deregister_script('jquery');
	/*wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', false, '1.7.1',true);
	 for working offline
	*/
	wp_register_script('jquery', get_template_directory_uri() . '/js/libs/jquery-1.8.3.js', false, '1.8.3',true);

	wp_enqueue_script('jquery');

	if( !is_admin()){

		mbudm_google_font_enqueue();

		wp_register_script('respond',get_template_directory_uri() . '/js/libs/respond.min.js',false,'1');
		wp_enqueue_script('respond');
		wp_register_script('modernizr',get_template_directory_uri() . '/js/libs/modernizr-2.0.6.min.js',false,'2.0.6');
		wp_enqueue_script('modernizr');
		wp_register_script('selectivizr',get_template_directory_uri() . '/js/libs/selectivizr-min.js',false,'1');
		wp_enqueue_script('selectivizr');
		wp_register_script('jquery-cycle', get_template_directory_uri() . '/js/libs/jquery.cycle.lite.1.0.min.js',array('jquery'),'1.0',true);
		wp_enqueue_script('jquery-cycle');
		wp_register_script('jquery-ui', get_template_directory_uri() . '/js/libs/jquery-ui-1.9.2.custom.min.js',array('jquery'),'1.9.2',true);
		wp_enqueue_script('jquery-ui');
		wp_register_script('jquery_simplemodal', get_template_directory_uri() . '/js/libs/jquery.simplemodal.1.4.1.min.js',array('jquery'),false,true);
		wp_enqueue_script('jquery_simplemodal');
		wp_register_script('jquery_qtip', get_template_directory_uri() . '/js/libs/jquery.qtip.js',array('jquery'),false,true);
		wp_enqueue_script('jquery_qtip');
		/*
		wp_register_script('jquery_minimiser', get_template_directory_uri() . '/js/libs/jquery.minimiser.js',array('jquery'),false,true);
		wp_enqueue_script('jquery_minimiser');
		wp_register_script('jqueryScrollTo', get_template_directory_uri() . '/js/libs/jquery.scrollTo-1.4.2-min.js',array('jquery'),false,true);
		wp_enqueue_script('jqueryScrollTo');*/
		$req_common = array(
		'jquery',
		'jquery_simplemodal',
		'jquery_qtip');
		wp_register_script('mb_plugins',get_template_directory_uri() . '/js/plugins.js',$req_common,'1',true);
		wp_enqueue_script('mb_plugins');
		$req_common[] = 'mb_plugins';
		wp_register_script('mb_common',get_template_directory_uri() . '/js/scripts.js',$req_common,'1',true);
		wp_enqueue_script('mb_common');

		if ( is_singular() && comments_open() && get_option('thread_comments') )
  			wp_enqueue_script( 'comment-reply' );
	}
}
add_action('wp_enqueue_scripts', 'mbudm_enqueue');

if (!function_exists('mbudm_google_font_enqueue')){
	function mbudm_google_font_enqueue(){
		$mods = get_theme_mods();

		if( isset($mods[TEMPLATE_DOMAIN.'_options']) ){
			$artpro_mods = $mods[TEMPLATE_DOMAIN.'_options'];

			$fonts = array();
			$fonts[] = isset($artpro_mods['branding_font']) && $artpro_mods['branding_font_type'] ? $artpro_mods['branding_font'] :'';
			$fonts[] = isset($artpro_mods['heading_font']) && $artpro_mods['heading_font_type'] ? $artpro_mods['heading_font'] :'';
			$fonts[] = isset($artpro_mods['body_font']) && $artpro_mods['body_font_type'] ? $artpro_mods['body_font'] :'';
			$fonts[] = isset($artpro_mods['nav_font']) && $artpro_mods['nav_font_type'] ? $artpro_mods['nav_font'] :'';
			$fontStr = '';
			$unique_fonts = array_unique($fonts);
			foreach($unique_fonts as $font){
				if($font){
					$fontStr .= strlen($fontStr) > 0 ? '|' : '' ;
					$fontStr .= $font .':400,b';
				}
			}

			if(strlen($fontStr) > 0 && !IS_OFFLINE){
				$webfonts = 'http://fonts.googleapis.com/css?family='.$fontStr ;
				wp_register_style('mb_fonts', $webfonts);
				wp_enqueue_style('mb_fonts');
			}
		}
	}
}
/*
Admin enqueue
- scripts and css used only in admin screens
*/
function mbudm_admin_enqueue(){
	if( is_admin()){
		// admin
		wp_register_script('jquery-ui', get_template_directory_uri() . '/js/libs/jquery-ui-1.9.2.custom.min.js',array('jquery'),'1.9.2',true);
		wp_enqueue_script('jquery-ui');

		wp_register_script('mb_admin',
				get_template_directory_uri() . '/js/adminscripts.js',
					   array('jquery-ui'),
					   '1' );
		wp_enqueue_script('mb_admin');

		 wp_register_style('mb_adminStyles', get_template_directory_uri() .'/css/admin.css' );
         wp_enqueue_style('mb_adminStyles');
	}
}
add_action('admin_enqueue_scripts', 'mbudm_admin_enqueue');
/* Theme customizer */
function mbudm_customizer_live_preview()
{
	$url = '/wp-content/themes/artpro/js/customizer.js';
	wp_register_script('mbudm_customizer',$url, array( 'jquery','customize-preview' ),'',true);
	wp_enqueue_script( 'mbudm_customizer' );

}
add_action( 'customize_preview_init', 'mbudm_customizer_live_preview' );
function mbudm_customize_css()
{
    include(mbudm_get_template_file_path('css_overrides.php'));
}
add_action( 'wp_head', 'mbudm_customize_css');


/* error handler */
function myErrorHandler($errno, $errstr, $errfile, $errline) {
  if ( E_RECOVERABLE_ERROR===$errno ) {
    echo "'catched' catchable fatal error: " . $errstr ."\n";
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    // return true;
  }
  return false;
}
set_error_handler('myErrorHandler');

/*
return the path to the file in includes
*/
function mbudm_get_template_file_path($filename){
	//return ABSPATH . 'wp-content/themes/linguaposta/includes/' . $filename;
	return get_template_directory() .'/includes/' . $filename;
}
/*
find key in multi dimensional array
*/
function &array_find_element_by_key($key, &$form) {
  if (array_key_exists($key, $form)) {
    $ret =& $form[$key];
    return $ret;
  }
  foreach ($form as $k => $v) {
    if (is_array($v)) {
      $ret =& array_find_element_by_key($key, $form[$k]);
      if ($ret) {
        return $ret;
      }
    }
  }
  return FALSE;
}


/*
add a value to a query string
- note i only allows for one instance of each value
*/
function add_querystring_var($url, $key, $value, $convert = true) {
    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
    $url = substr($url, 0, -1);
    if (strpos($url, '?') === false) {
        $qstring =  ('?' . $key . '=' . $value);
    } else {
        $qstring =  ('&' . $key . '=' . $value);
    }
    $qstring = $convert ? convert_chars($qstring) : $qstring ;
    return $url . $qstring;
}
/* remove a query string value by key */
function remove_querystring_var($url, $key) {
    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
    $url = substr($url, 0, -1);
    return $url;
}


/* FIXES */

/* the_category triggers a html validation error */
add_filter( 'the_category', 'add_nofollow_cat' );
function add_nofollow_cat( $text ) {
	$text = str_replace('rel="category"', '', $text);
	return $text;
}
