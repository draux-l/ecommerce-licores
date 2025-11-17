<?php
/**
 * Functions for sending list of fonts available
 * 
 * Also add them to sanitization array (list of allowed options)
 */

/**
 * Build URL for loading Google Fonts
 * @credit http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 *
 * @since 1.0
 * @updated 2.9
 * @access public
 * @return void
 */
function strute_google_fonts_enqueue_url() {
	$fonts_url = '';
	$fonts = apply_filters( 'strute_google_fonts_preparearray', array() );
	$args = array();

	if ( !is_array( $fonts ) || empty( $fonts ) ) {
		$modsfont = array( hoot_get_mod( 'body_fontface' ), hoot_get_mod( 'logo_fontface' ), hoot_get_mod( 'headings_fontface' ), hoot_get_mod( 'subheadings_fontface' ) );
		$list = hoot_themefonts('enqueue');
		foreach ( $list as $nick => $value ) {
			if ( in_array( $nick, $modsfont ) ) {
				$fonts = array_merge( $fonts, $value );
			}
		}
	}
	$fonts = apply_filters( 'strute_google_fonts_array', $fonts );

	// Cant use 'add_query_arg()' directly as new google font api url will have multiple key 'family' when adding multiple fonts
	// Hence use 'add_query_arg' on each argument separately and then combine them.
	foreach ( $fonts as $key => $value ) {
		if ( is_array( $value ) && ( !empty( $value['normal'] ) || !empty( $value['italic'] ) ) && ( is_array( $value['normal'] ) || is_array( $value['italic'] ) ) ) {
			$arg = array( 'family' => $key . ':ital,wght@' );
			if ( !empty( $value['normal'] ) && is_array( $value['normal'] ) ) foreach ( $value['normal'] as $wght ) $arg['family'] .= "0,{$wght};";
			if ( !empty( $value['italic'] ) && is_array( $value['italic'] ) ) foreach ( $value['italic'] as $wght ) $arg['family'] .= "1,{$wght};";
			$arg['family'] = substr( $arg['family'], 0, -1 );
			$args[] = substr( add_query_arg( $arg, '' ), 1 );
		}
	}

	if ( !empty( $args ) ) {
		$fonts_url = 'https://fonts.googleapis.com/css2?' . implode( '&', $args ) . '&display=swap';
		if ( function_exists( 'hoot_wptt_get_webfont_url' ) ) {
			if ( hoot_get_mod( 'load_local_fonts' ) ) {
				$fonts_url = hoot_wptt_get_webfont_url( esc_url_raw( $fonts_url ) );
			} elseif( class_exists( 'Hoot_WPTT_WebFont_Loader' ) ) {
				$font_possible_cleanup = new Hoot_WPTT_WebFont_Loader( $fonts_url );
			}
		}
	}

	return $fonts_url;
}