<?php
/**
 * Theme Setup
 * This file is loaded using 'after_setup_theme' hook at priority 10
 */


/* === WordPress === */


// Automatically add <title> to head.
add_theme_support( 'title-tag' );

// Adds core WordPress HTML5 support.
add_theme_support( 'html5', array( 'script', 'style', 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add theme support for WordPress Custom Logo
add_theme_support( 'custom-logo' );

// Add theme support for custom headers
add_theme_support( 'custom-header', array(
	'width' => 1600,
	'height' => 500,
	'flex-height' => true,
	'flex-width' => true,
	'default-image' => hoot_data()->template_uri . 'images/header.jpg',
	'header-text' => false,
) );

// Adds theme support for WordPress 'featured images'.
add_theme_support( 'post-thumbnails' );

// Automatically add feed links to <head>.
add_theme_support( 'automatic-feed-links' );


/* === WordPress Jetpack === */


add_theme_support( 'infinite-scroll', array(
	'type' => apply_filters( 'strute_jetpack_infinitescroll_type', '' ), // scroll or click - currently add support for both
	'container' => apply_filters( 'strute_jetpack_infinitescroll_container', 'content-wrap' ),
	'footer' => false,
	'wrapper' => true,
	'render' => apply_filters( 'strute_jetpack_infinitescroll_render', 'strute_jetpack_infinitescroll_render' ),
) );


/* === WooCommerce Plugin === */


// Woocommerce support and init load theme woo functions
if ( class_exists( 'WooCommerce' ) ) {
	add_theme_support( 'woocommerce' );
	if ( file_exists( hoot_data()->template_dir . 'woocommerce/functions.php' ) )
		include_once( hoot_data()->template_dir . 'woocommerce/functions.php' );
}


/** Hoot Import plugin **/

// theme config
if ( ! function_exists( 'strute_hootimport_theme_config' ) ) {
	function strute_hootimport_theme_config( $config ) {
		$child = hoot_data( 'childtheme_name' );
		$is_official_child = false;
		if ( $child ) {
			$checks = apply_filters( 'strute_hootimport_theme_config_childtheme_array', array() );
			foreach ( $checks as $check ) {
				if ( stripos( $child, $check ) !== false ) {
					$is_official_child = true;
					break;
				}
			}
		}
		return ( $is_official_child ) ? $config : array_merge( $config, array(
			'id' => 'strute', // *required // used for parent and unofficial child themes
			'menu_title' => __( 'Import Strute Demo', 'strute' ),
			'theme_name' => hoot_get_data( 'template_name' ),
			'theme_version' => hoot_get_data( 'template_version' ),
			'theme_img' => function_exists( 'strute_abouttag' ) ? (  strute_abouttag( 'fullshot' ) !==  strute_abouttag( 'shot' ) ?  strute_abouttag( 'fullshot' ) : '' ) : '',
		) );
	}
}
add_filter( 'hootimport_theme_config', 'strute_hootimport_theme_config', 5 );

// menu urls
if ( ! function_exists( 'strute_hootimport_menuurls' ) ) {
	function strute_hootimport_menuurls( $url ) {
		$homeurl = home_url( '/' );
		$check = rtrim( $url, '/' );
		if ( $check === $homeurl . 'author/jasin' || $check === $homeurl . 'author/jasins' || $check === $homeurl . 'author/wphoot' || $check === $homeurl . 'author/admin' ) {
			if ( function_exists( 'get_current_user_id' ) && function_exists( 'get_author_posts_url' ) ) {
				$authorid = get_current_user_id();
				if ( $authorid ) {
					$authorurl = get_author_posts_url( $authorid );
					$url = $authorurl ? $authorurl : $url;
				}
			}
		} elseif ( $check === $homeurl . 'i-dont-exist' || $check === $homeurl . '404' ) {
			$url = 'https://demo.wphoot.com/strute/i-dont-exist';
		}
		return $url;
	}
}
add_filter( 'hootimport_menu_item_customurl_home_replaced', 'strute_hootimport_menuurls', 5 );


/* === Hootkit Plugin === */


// Load theme's Hootkit functions if plugin is active
if ( class_exists( 'HootKit' ) && file_exists( hoot_data()->template_dir . 'hootkit/functions.php' ) )
	include_once( hoot_data()->template_dir . 'hootkit/functions.php' );


/* === Tribe The Events Calendar Plugin === */


// Load support if plugin active
if ( class_exists( 'Tribe__Events__Main' ) ) {

	// Hook into 'wp' to use conditional hooks
	add_action( 'wp', 'strute_tribeevent', 10 );

	// Add hooks based on view
	function strute_tribeevent() {
		if ( is_post_type_archive( 'tribe_events' ) || ( function_exists( 'tribe_is_events_home' ) && tribe_is_events_home() ) ) {
			add_action( 'strute_display_loop_meta', 'strute_tribeevent_loopmeta', 5 );
		}
		if ( is_singular( 'tribe_events' ) ) {
			add_action( 'strute_display_loop_meta', 'strute_tribeevent_loopmeta_single', 5 );
		}
	}

	// Modify theme options and displays
	function strute_tribeevent_loopmeta( $display ) { return false; }
	function strute_tribeevent_loopmeta_single( $display ) {
		the_post(); rewind_posts(); // Bug Fix
		return false;
	}

}


/* === AMP Plugin ===
 * @ref https://wordpress.org/plugins/amp/
 * @ref https://www.hostinger.in/tutorials/wordpress-amp/
 * @ref https://validator.ampproject.org/
 * @ref https://amp.dev/documentation/guides-and-tutorials/learn/validation-workflow/validation_errors/
 * @credit https://amp-wp.org/documentation/developing-wordpress-amp-sites/how-to-develop-with-the-amp-plugin/
 * @credit https://amp-wp.org/documentation/how-the-plugin-works/amp-plugin-serving-strategies/
*/
// Call 'is_amp_endpoint' after 'parse_query' hook
add_action( 'wp', 'strute_amp', 5 );
function strute_amp(){
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		add_action( 'wp_enqueue_scripts', 'strute_amp_remove_scripts', 999 );
		add_filter( 'hoot_attr_body', 'strute_amp_attr_body' );
		add_filter( 'theme_mod_mobile_submenu_click', 'strute_amp_emptymod' );
	}
}
function strute_amp_remove_scripts(){
	$dequeue = array_map( 'wp_dequeue_script', array(
		'comment-reply', 'jquery', 'hoverIntent', 'jquery-superfish', 'jquery-fitvids', 'resizesensor', 'jquery-theia-sticky-sidebar',
		'hoot-theme', 'hoot-theme-premium',
		'jquery-lightGallery', 'jquery-isotope',
		'jquery-waypoints', 'jquery-waypoints-sticky', 'hoot-scroller',
		'hootkit', 'jquery-lightSlider', 'jquery-circliful',
	) );
}
function strute_amp_attr_body( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? ' hootamp' : $attr['class'] . ' hootamp';
	return $attr;
}
function strute_amp_emptymod(){
	return 0;
}


/* === Breadcrumb NavXT Plugin === */


// Load support if plugin active
if ( class_exists( 'bcn_breadcrumb' ) ) {

	// Enclose pretext in span
	add_filter( 'bcn_widget_pretext', 'strute_bcn_pretext' );

	// Enclose pretext in span
	function strute_bcn_pretext( $pretext ) {
		if ( empty( $pretext ) ) return '';
		return '<span class="hoot-bcn-pretext">' . $pretext . '</span>';
	}

}


/* === Theme Hooks === */


/**
 * Handle content width for embeds and images.
 * This file is loaded using 'after_setup_theme' hook at priority 10
 */
$GLOBALS['content_width'] = apply_filters( 'strute_content_width', 1440 );

/**
 * Modify the '[...]' Read More Text
 *
 * @since 1.0
 * @return string
 */
function strute_readmoretext( $more ) {
	$read_more = esc_html( hoot_get_mod('read_more') );
	/* Translators: %s is the HTML &rarr; symbol */
	$read_more = ( empty( $read_more ) ) ? __( 'Continue Reading', 'strute' ) : $read_more;
	return $read_more;
}
add_filter( 'hoot_readmoretext', 'strute_readmoretext' );

/**
 * Modify the exceprt length.
 * Make sure to set the priority correctly such as 999, else the default WordPress filter on this function will run last and override settng here.
 *
 * @since 1.0
 * @return void
 */
function strute_custom_excerpt_length( $length ) {
	if ( is_admin() )
		return $length;

	$excerpt_length = intval( hoot_get_mod('excerpt_length') );
	if ( !empty( $excerpt_length ) )
		return $excerpt_length;
	return 50;
}
add_filter( 'excerpt_length', 'strute_custom_excerpt_length', 999 );
