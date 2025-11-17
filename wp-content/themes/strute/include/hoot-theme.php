<?php
/**
 * Hoot Theme files
 */

/* Load theme includes. Must keep priority 10 for theme constants to be available. */
add_action( 'after_setup_theme', 'strute_includes', 10 );

/**
 * Loads the theme files supported by themes and template-related functions/classes. Functionality 
 * in these files should not be expected within the theme setup function.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function strute_includes() {

	/* Load the Theme Specific HTML attributes */
	require_once( hoot_data()->incdir . 'attr.php' );
	require_once( hoot_data()->incdir . 'attr-aos.php' );
	/* Load enqueue functions */
	require_once( hoot_data()->incdir . 'enqueue.php' );
	/* Load the dynamic css functions. */
	require_once( hoot_data()->incdir . 'css.php' );
	/* Load template tags. */
	require_once( hoot_data()->incdir . 'template-helpers.php' );
	/* Set the fonts. */
	require_once( hoot_data()->incdir . 'admin/fonts.php' );
	/* Set image sizes. */
	require_once( hoot_data()->incdir . 'admin/media.php' );
	/* Set menus */
	require_once( hoot_data()->incdir . 'admin/menus.php' );
	/* Set sidebars */
	require_once( hoot_data()->incdir . 'admin/sidebars.php' );
	/* Helper Functions */
	require_once( hoot_data()->incdir . 'admin/functions.php' );
	/* Load Customizer Options */
	if ( apply_filters( 'strute_customize_load_trt', file_exists( hoot_data()->incdir . 'admin/trt-customize-pro/class-customize.php' ) ) )
		require_once( hoot_data()->incdir . 'admin/trt-customize-pro/class-customize.php' );
	require_once( hoot_data()->incdir . 'admin/customizer-options.php' );
	require_once( hoot_data()->incdir . 'admin/customizer-helpers.php' );
	/* Load the about page. */
	if ( apply_filters( 'strute_load_about', ( file_exists( hoot_data()->incdir . 'admin/about.php' ) && file_exists( hoot_data()->incdir . 'admin/notice.php' ) ) ) ) {
		require_once( hoot_data()->incdir . 'admin/about.php' );
		require_once( hoot_data()->incdir . 'admin/notice.php' );
	}
	/* Load the theme setup file */
	require_once( hoot_data()->incdir . 'theme-setup.php' );
	require_once( hoot_data()->incdir . 'blocks/wpblocks.php' );

}

/* Theme Setup complete */
do_action( 'strute_loaded' );