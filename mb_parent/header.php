<?php
/**
 * The Header for the theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage ArtPro
 * @since ArtPro 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'artpro' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
<?php 

$mods = get_theme_mods();
			$theme_mods = isset($mods[TEMPLATE_DOMAIN.'_options']) ? $mods[TEMPLATE_DOMAIN.'_options'] : false ;
			$header_layout_class = $theme_mods ? $theme_mods['header_layout'] : 'header_layout_h' ;

				$header_image = get_header_image(); 
				$header_dimensions = get_custom_header();
				$header_style = $header_image ? ' style="height:'.$header_dimensions->height.'px" ' : '' ;

				$header_style = $header_layout_class == 'header_layout_v' ? '' : $header_style ;
				
?>
	<header id="branding" role="banner" class="container_24 c24slim <?php echo $header_layout_class ?>" <?php echo $header_style ?> >
		<?php 
			if($header_image){
				?>
				<a id="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo $header_image ?>" height="<?php echo  $header_dimensions->height ?>" width="<?php echo $header_dimensions->width; ?>" alt="" /></a>
				<?php } ?>
				
				<?php if(display_header_text()){ ?>
			
			<hgroup>
			
				<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span> <span id="site-description"><?php bloginfo( 'description' ); ?></span></h1>	
				
			</hgroup>
			<?php } ?>
			<nav id="access" role="navigation">
				<a id="menu-toggle" href="#">
					<span class="assistive-text"><?php _e( 'Toggle Menu', 'artpro' ) ?></span>
					<span class="bar">&nbsp;</span>
					<span class="bar">&nbsp;</span>
					<span class="bar">&nbsp;</span>
				</a>
				<h3 class="assistive-text"><?php _e( 'Main menu', 'artpro' ); ?></h3>
				<?php /* Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
				<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'artpro' ); ?>"><?php _e( 'Skip to primary content', 'artpro' ); ?></a></div>
				<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'artpro' ); ?>"><?php _e( 'Skip to secondary content', 'artpro' ); ?></a></div>
				<?php /* Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assigned to the primary location is the one used. If one isn't assigned, the menu with the lowest ID is used. */ ?>
				<div class="menu-wrap cfix">
				<?php wp_nav_menu( array( 'theme_location' => 'primary_nav' ) ); ?>
				</div>
			</nav><!-- #access -->
			<?php 
			if(is_active_sidebar('header-sbar' )  ) {
			get_sidebar('header-sbar'); 
			}
			?>
	</header><!-- #branding -->
	<div id="main">
