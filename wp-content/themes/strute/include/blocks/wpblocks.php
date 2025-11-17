<?php
/**
 * Blocks Setup
 * This file is loaded using 'after_setup_theme' hook at priority 10
 */

/* === WordPress Blocks === */


/** Add Gutenberg Wide Align support **/

add_theme_support( 'align-wide' );


/** Add slightly more opinionated styles for the front end **/

add_theme_support( 'wp-block-styles' );


/** Custom spacing option for blocks like cover and group **/

add_theme_support( 'custom-spacing' );


/** Add accent colors to Block Pallete - hook to init to have default vals for accent via hoot_get_mod **/

if ( apply_filters( 'strute_editor_color_palette', true ) )
	add_action( 'init', 'strute_wpblock_color_palette' );
function strute_wpblock_color_palette(){
	$defaults = array(
		'#000000' => array( 'black',                 __( 'Black', 'strute' ) ),
		'#abb8c3' => array( 'cyan-bluish-gray',      __( 'Cyan bluish gray', 'strute' ) ),
		'#ffffff' => array( 'white',                 __( 'White', 'strute' ) ),
		'#f78da7' => array( 'pale-pink',             __( 'Pale pink', 'strute' ) ),
		'#cf2e2e' => array( 'vivid-red',             __( 'Vivid red', 'strute' ) ),
		'#ff6900' => array( 'luminous-vivid-orange', __( 'Luminous vivid orange', 'strute' ) ),
		'#fcb900' => array( 'luminous-vivid-amber',  __( 'Luminous vivid amber', 'strute' ) ),
		'#7bdcb5' => array( 'light-green-cyan',      __( 'Light green cyan', 'strute' ) ),
		'#00d084' => array( 'vivid-green-cyan',      __( 'Vivid green cyan', 'strute' ) ),
		'#8ed1fc' => array( 'pale-cyan-blue',        __( 'Pale cyan blue', 'strute' ) ),
		'#0693e3' => array( 'vivid-cyan-blue',       __( 'Vivid cyan blue', 'strute' ) ),
		'#9b51e0' => array( 'vivid-purple',          __( 'Vivid purple', 'strute' ) ),
	);
	$load = false;
	$palette = array();
	$accent = hoot_get_mod( 'accent_color' );
		$load = true;
		$palette[] = array(
			'name' => __( 'Theme Accent Color', 'strute' ),
			'slug' => 'accent',
			'color' => $accent
		);
	$accentfont = hoot_get_mod( 'accent_font' );
		$load = true;
		$palette[] = array(
			'name' => __( 'Theme Accent Font Color', 'strute' ),
			'slug' => 'accent-font',
			'color' => $accentfont
		);
	if ( $load ) {
		foreach ( $defaults as $key => $value )
			if ( $key != $accent && $key != $accentfont )
				$palette[] = array(
					'name' => $value[1],
					'slug' => $value[0],
					'color' => $key
				);
		add_theme_support( 'editor-color-palette', $palette );
	}
}


/** Add Stylesheets **/

// Load after main stylesheet (and hootkit if available), but before child theme's stylesheet (and child hootkit)
add_action( 'wp_enqueue_scripts', 'strute_wpblock_assets', 16 );
function strute_wpblock_assets(){
	$style_uri = hoot_locate_style( 'include/blocks/wpblocks' );
	wp_enqueue_style( 'hoot-wpblocks', $style_uri, array(), hoot_data()->template_version );
}

// Set dynamic css handle to hoot-wpblocks
add_filter( 'hoot_style_builder_inline_style_handle', 'strute_dynamic_css_wpblock_handle', 4 );
function strute_dynamic_css_wpblock_handle(){ return 'hoot-wpblocks'; }
// Editor stylesheet (HBS loads @10)
add_action( 'enqueue_block_editor_assets', 'strute_wpblock_editor_assets', 12 );
function strute_wpblock_editor_assets(){
	// This is loaded in only Backend...
	$style_uri = hoot_locate_style( 'include/blocks/wpblocks-editor' );
	wp_enqueue_style( 'hoot-wpblocks-editor', $style_uri, array(), hoot_data()->template_version );
}
