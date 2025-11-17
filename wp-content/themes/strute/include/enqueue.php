<?php
/**
 * Enqueue scripts and styles for the theme.
 * This file is loaded via the 'after_setup_theme' hook at priority '10'
 */

/* Add custom scripts. */
add_action( 'wp_enqueue_scripts', 'strute_enqueue_scripts', 5 );

/* Set data for theme scripts localization. */
add_action( 'wp_enqueue_scripts', 'strute_localize_script', 5 );

/* Load theme script. */
add_action( 'wp_enqueue_scripts', 'strute_base_enqueue_scripts', 15 );

/* Add custom styles. Set priority to default 10 so that theme's main style is loaded after these styles @12, and can thus easily override any style without over-qualification. */
add_action( 'wp_enqueue_scripts', 'strute_enqueue_styles', 10 );

/* Dequeue font awesome */
add_action( 'wp_enqueue_scripts', 'strute_dequeue_fontawesome', 99 );

/**
 * Load scripts for the front end.
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_enqueue_scripts' ) ) :
function strute_enqueue_scripts() {

	/* Load the comment reply script on singular posts with open comments if threaded comments are supported. */
	if ( is_singular() && get_option( 'thread_comments' ) && comments_open() )
		wp_enqueue_script( 'comment-reply' );

	/* Load jquery */
	wp_enqueue_script( 'jquery' );

	/* Load Superfish and WP's hoverIntent */
	wp_enqueue_script( 'hoverIntent' );
	$script_uri = hoot_locate_script( 'js/jquery.superfish' );
	wp_enqueue_script( 'jquery-superfish', $script_uri, array( 'jquery', 'hoverIntent'), '1.7.5', true );

	/* Load fitvids */
	$script_uri = hoot_locate_script( 'js/jquery.fitvids' );
	wp_enqueue_script( 'jquery-fitvids', $script_uri, array( 'jquery' ), '1.1', true );

	/* Load Theia Sticky Sidebar */
	if ( ! hoot_get_mod( 'disable_sticky_sidebar' ) ) {
		$script_uri = hoot_locate_script( 'js/resizesensor' );
		wp_enqueue_script( 'resizesensor', $script_uri, array(), '1.7.0', true );
		$script_uri = hoot_locate_script( 'js/jquery.theia-sticky-sidebar' );
		wp_enqueue_script( 'jquery-theia-sticky-sidebar', $script_uri, array( 'jquery', 'resizesensor' ), '1.7.0', true );
	}

	/* Load Lenis */
	if ( hoot_get_mod( 'enable_anims' ) && hoot_getchecked( 'enabled_anims', 'sscroll' ) && ! is_customize_preview() ) {
		$script_uri = hoot_locate_script( 'js/lenis' );
		wp_enqueue_script( 'lenis', $script_uri, array(), '1.1.17', true );
	}

}
endif;

/**
 * Set data for theme scripts localization
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_localize_script' ) ) :
function strute_localize_script() {

	$scriptdata = hoot_data( 'scriptdata' );
	if ( empty( $scriptdata ) )
		$scriptdata = array();

	// Sticky Sidebar
	if ( hoot_get_mod( 'disable_sticky_sidebar' ) || ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) )
		$scriptdata['stickySidebar'] = 'disable';

	// Misc
	$anims = hoot_get_mod( 'enable_anims' );
	$scriptdata['scrollerSpeed'] = 'linear';
	$scriptdata['scrollerPadding'] = 100;
	$scriptdata['smoothScroll']     = $anims && hoot_getchecked( 'enabled_anims', 'sscroll'      ) ? 'enable' : 'disable';
	$aos    = hoot_getchecked( 'enabled_anims', 'aos'    );
	$aosmob = hoot_getchecked( 'enabled_anims', 'aosmob' );
	$scriptdata['aos']              = $aos || $aosmob                                              ? 'enable' : 'disable';
	$scriptdata['aosdisable'] = $aos && $aosmob ? false : ( $aos ? 'mobile' : 'desktop' );
	$scriptdata['aosonce'] = is_front_page() ? 'enable' : 'disable';
	$scriptdata['wayTopButton']     = $anims && hoot_getchecked( 'enabled_anims', 'waygototop'   ) ? 'enable' : 'disable';
	$scriptdata['scrollTopButton']  = $anims && hoot_getchecked( 'enabled_anims', 'animgototop'  ) ? 'enable' : 'disable';
	$scriptdata['ajaxPaginate']     = $anims && hoot_getchecked( 'enabled_anims', 'ajaxpaginate' ) ? 'enable' : 'disable';
	$scriptdata['loopnavPreview']   = $anims && hoot_getchecked( 'enabled_anims', 'prevnext'     ) ? 'enable' : 'disable';
	$scriptdata['urlHashScroller']  = $anims && hoot_getchecked( 'enabled_anims', 'scrollhash'   ) ? 'enable' : 'disable';
	$scriptdata['urlHashScrollerUglifyLink'] = 'enable';
	$scriptdata['pageloadScrollID'] = $anims && hoot_getchecked( 'enabled_anims', 'scrollmain'   ) ? 'main' : 'disable';

	hoot_set_data( 'scriptdata', $scriptdata );
}
endif;

/**
 * Load scripts for the front end.
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_base_enqueue_scripts' ) ) :
function strute_base_enqueue_scripts() {

	/* Load Theme Javascript */
	$script_uri = hoot_locate_script( 'js/hoot.theme' );
	wp_enqueue_script( 'hoot-theme', $script_uri, array( 'jquery' ), hoot_data()->template_version, true );

	/* Pass data to various Theme Scripts. We use jquery so that data is available to all (jquery dependant) theme loaded scripts */
	$scriptdata = hoot_data( 'scriptdata' );
	if ( !empty( $scriptdata ) && is_array( $scriptdata ) )
		wp_localize_script( 'hoverIntent', 'hootData', $scriptdata );

}
endif;

/**
 * Load stylesheets for the front end.
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_enqueue_styles' ) ) :
function strute_enqueue_styles() {

	/* Load Google Fonts if 'google-fonts' is active. */
	$style_uri = strute_google_fonts_enqueue_url();
	if ( $style_uri )
		wp_enqueue_style( 'strute-googlefont', $style_uri, array(), null );

	/* Load font awesome if 'font-awesome' is active. */
	if ( apply_filters( 'hoot_force_theme_fa', true, 'frontend' ) )
		wp_deregister_style( 'font-awesome' ); // Bug Fix for plugins using older font-awesome library
	$style_uri = hoot_locate_style( hoot_data()->liburi . 'fonticons/font-awesome' );
	wp_enqueue_style( 'font-awesome', $style_uri, false, '5.15.4' );
	add_action('wp_head', 'strute_preload_fonticon', 5);

}
endif;

/**
 * Preload webfont to help with Page Speed
 *
 * @since 1.0
 */
if ( !function_exists( 'strute_preload_fonticon' ) ) :
function strute_preload_fonticon() { ?>
<link rel="preload" href="<?php echo hoot_data()->liburi; ?>fonticons/webfonts/fa-solid-900.woff2" as="font" crossorigin="anonymous">
<link rel="preload" href="<?php echo hoot_data()->liburi; ?>fonticons/webfonts/fa-regular-400.woff2" as="font" crossorigin="anonymous">
<link rel="preload" href="<?php echo hoot_data()->liburi; ?>fonticons/webfonts/fa-brands-400.woff2" as="font" crossorigin="anonymous">
<?php }
endif;

/**
 * Dequeue font awesome from frontend if a similar handle exists (registered by another plugin)
 * but it is already enqueued using the theme
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_dequeue_fontawesome' ) ) :
function strute_dequeue_fontawesome() {
	if ( wp_style_is( 'fontawesome' ) )
		wp_dequeue_style( 'fontawesome' );
}
endif;