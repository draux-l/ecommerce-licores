<?php
/**
 * This file contains functions and hooks for styling Hootkit plugin
 *   Hootkit is a free plugin released under GPL license and hosted on wordpress.org.
 *
 * This file is loaded at 'after_setup_theme' action @priority 10 ONLY IF hootkit plugin is active
 */

// Register HootKit
add_filter( 'hootkit_register', 'strute_register_hootkit', 5 );

// Set data for theme scripts localization.
add_action( 'wp_enqueue_scripts', 'strute_localize_hootkit', 5 );

// Hootkit plugin loads its styles at default @10 (we skip this using config 'theme_css')
// The theme's main style is loaded @12
// The child's main style is loaded @18
add_action( 'wp_enqueue_scripts', 'strute_enqueue_hootkit', 14 );
add_action( 'wp_enqueue_scripts', 'strute_enqueue_childhootkit', 20 );

// Set dynamic css handle to hootkit
// Set dynamic css handle to child hootkit inside `strute_dynamic_css_hootkit_handle` using `strute_dynamic_css_childhootkit_handle` 
add_filter( 'hoot_style_builder_inline_style_handle', 'strute_dynamic_css_hootkit_handle', 2 );

/**
 * Register Hootkit
 *
 * @since 1.0
 * @param array $config
 * @return string
 */
if ( !function_exists( 'strute_register_hootkit' ) ) :
function strute_register_hootkit( $config ) {
	// Array of configuration settings.
	$config = array(
		'nohoot'    => false,
		'theme_css' => true,
		'modules'   => array(
			'widget' => array(
				// Sliders
				'slider-image', 'slider-postimage',
				// Widgets
				'announce', 'content-blocks', 'content-posts-blocks', 'cta', 'icon', 'post-grid', 'post-list', 'social-icons', 'ticker', 'content-grid', 'profile', 'ticker-posts',
				// WC
				'content-products-blocks', 'product-list', 'products-ticker', 'products-search', 'products-carticon',
			),
			'misc' => array( 'shortcode-timer', 'fly-cart', 'classic-widgets' ),
		),
		'supports_version' => 'v2',
		'supports'  => array( 'cta-styles', 'content-blocks-style5', 'content-blocks-style6', 'slider-styles', 'grid-widget', 'list-widget', 'widget-subtitle', 'content-blocks-iconoptions', 'social-icons-altcolor', 'social-icons-align', 'slider-style3', 'slider-subtitles', ),
		'premium'   => array( 'carousel', 'postcarousel', 'postlistcarousel', 'productcarousel', 'productlistcarousel', 'contact-info', 'cover-image', 'number-blocks', 'vcards', 'buttons', 'icon-list', 'notice', 'toggle', 'tabs', ),
	);
	return $config;
}
endif;

/**
 * Enqueue Scripts and Styles
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_localize_hootkit' ) ) :
function strute_localize_hootkit() {
	$scriptdata = hoot_data( 'scriptdata' );
	if ( empty( $scriptdata ) )
		$scriptdata = array();
	$scriptdata['contentblockhover'] = 'enable'; // This needs to be explicitly enabled by supporting themes
	$scriptdata['contentblockhovertext'] = 'disable'; // Disabling needed for proper positioning of animation in latest themes (jquery animation is now redundant) (may be deleted later once all hootkit themes ported)
	hoot_set_data( 'scriptdata', $scriptdata );
}
endif;

/**
 * Enqueue Scripts and Styles
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_enqueue_hootkit' ) ) :
function strute_enqueue_hootkit() {

	$loadminified = ( defined( 'HOOT_DEBUG' ) ) ?
					( ( HOOT_DEBUG ) ? false : true ) :
					false;

	/* Load Hootkit Style */
	if ( $loadminified && file_exists( hoot_data()->template_dir . 'hootkit/hootkit.min.css' ) )
		$style_uri =  hoot_data()->template_uri . 'hootkit/hootkit.min.css';
	elseif ( file_exists( hoot_data()->template_dir . 'hootkit/hootkit.css' ) )
		$style_uri =  hoot_data()->template_uri . 'hootkit/hootkit.css';
	if ( !empty( $style_uri ) )
		wp_enqueue_style( 'strute-hootkit', $style_uri, array(), hoot_data()->template_version );

}
endif;
if ( !function_exists( 'strute_enqueue_childhootkit' ) ) :
function strute_enqueue_childhootkit() {
	if ( is_child_theme() ) :

	$loadminified = ( defined( 'HOOT_DEBUG' ) ) ?
					( ( HOOT_DEBUG ) ? false : true ) :
					false;

	/* Load Hootkit Style */
	if ( $loadminified && file_exists( hoot_data()->child_dir . 'hootkit/hootkit.min.css' ) )
		$style_uri =  hoot_data()->child_uri . 'hootkit/hootkit.min.css';
	elseif ( file_exists( hoot_data()->child_dir . 'hootkit/hootkit.css' ) )
		$style_uri =  hoot_data()->child_uri . 'hootkit/hootkit.css';
	if ( !empty( $style_uri ) ) {
		wp_enqueue_style( 'strute-child-hootkit', $style_uri, array(), hoot_data()->childtheme_version );
		add_filter( 'hoot_style_builder_inline_style_handle', 'strute_dynamic_css_childhootkit_handle', 10 );
	}

	endif;
}
endif;

/**
 * Set dynamic css handle to hootkit
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_dynamic_css_hootkit_handle' ) ) :
function strute_dynamic_css_hootkit_handle( $handle ) {
	return 'strute-hootkit';
}
endif;
if ( !function_exists( 'strute_dynamic_css_childhootkit_handle' ) ) :
function strute_dynamic_css_childhootkit_handle( $handle ) {
	return 'strute-child-hootkit';
}
endif;

/**
 * Modify Content Box default style
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function strute_content_blocks_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['boxes']['fields']['icon_style'] ) )
		$settings['form_options']['boxes']['fields']['icon_style']['std'] = 'none';
	return $settings;
}
add_filter( 'hootkit_content_blocks_widget_settings', 'strute_content_blocks_widget_settings', 5 );

/**
 * Modify Border Style
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function strute_hootkit_widget_settings_border( $settings ) {
	if ( isset( $settings['form_options']['border']['options'] ) )
		$settings['form_options']['border']['options'] = array(
			'line line'		=> __( 'Top - Line || Bottom - Line', 'strute' ),
			'line shadow'	=> __( 'Top - Line || Bottom - Shadow', 'strute' ),
			'line none'		=> __( 'Top - Line || Bottom - None', 'strute' ),
			'shadow line'	=> __( 'Top - Shadow || Bottom - Line', 'strute' ),
			'shadow shadow'	=> __( 'Top - Shadow || Bottom - Shadow', 'strute' ),
			'shadow none'	=> __( 'Top - Shadow || Bottom - None', 'strute' ),
			'none line'		=> __( 'Top - None || Bottom - Line', 'strute' ),
			'none shadow'	=> __( 'Top - None || Bottom - Shadow', 'strute' ),
			'none none'		=> __( 'Top - None || Bottom - None', 'strute' ),
		);
	return $settings;
}
add_filter( 'hootkit_buttons_widget_settings', 'strute_hootkit_widget_settings_border', 5 );
add_filter( 'hootkit_content_blocks_widget_settings', 'strute_hootkit_widget_settings_border', 5 );
add_filter( 'hootkit_content_posts_blocks_widget_settings', 'strute_hootkit_widget_settings_border', 5 );
add_filter( 'hootkit_content_products_blocks_widget_settings', 'strute_hootkit_widget_settings_border', 5 );
add_filter( 'hootkit_cta_widget_settings', 'strute_hootkit_widget_settings_border', 5 );
add_filter( 'hootkit_number_blocks_widget_settings', 'strute_hootkit_widget_settings_border', 5 );
add_filter( 'hootkit_profile_widget_settings', 'strute_hootkit_widget_settings_border', 5 );
add_filter( 'hootkit_vcards_widget_settings', 'strute_hootkit_widget_settings_border', 5 );

/**
 * Modify Ticker default style
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function strute_ticker_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['background'] ) )
		$settings['form_options']['background']['std'] = '#f1f1f1';
	if ( isset( $settings['form_options']['fontcolor'] ) )
		$settings['form_options']['fontcolor']['std'] = '#666666';
	return $settings;
}
function strute_ticker_products_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['background'] ) )
		$settings['form_options']['background']['std'] = '#f1f1f1';
	if ( isset( $settings['form_options']['fontcolor'] ) )
		$settings['form_options']['fontcolor']['std'] = '#333333';
	return $settings;
}
add_filter( 'hootkit_ticker_widget_settings', 'strute_ticker_widget_settings', 5 );
add_filter( 'hootkit_ticker_posts_widget_settings', 'strute_ticker_widget_settings', 5 );
add_filter( 'hootkit_products_ticker_widget_settings', 'strute_ticker_products_widget_settings', 5 );

/**
 * Filter Ticker and Ticker Posts display Title markup
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function strute_hootkit_widget_title( $display, $title, $context, $icon = '' ) {
	$display = '<div class="ticker-title accent-typo">' . $icon . $title . '</div>';
	return $display;
}
add_filter( 'hootkit_widget_ticker_title', 'strute_hootkit_widget_title', 5, 4 );

/**
 * Set button styling (for user defined colors) in cover image widget
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
add_filter( 'hootkit_coverimage_inverthoverbuttons', '__return_true' );

/**
 * Set Read More button location for Content Block
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function strute_hootkit_content_block_styles_inboxlink( $styles ) {
	$styles = array( 'style4', 'style5', 'style6' );
	return $styles;
}
add_filter( 'hootkit_content_block_styles_inboxlink', 'strute_hootkit_content_block_styles_inboxlink', 5 );