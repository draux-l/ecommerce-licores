<?php
/**
 * Helper Functions
 */

/**
 * Set Theme About Page Tags
 * @access public
 * @return mixed
 */
function strute_abouttag( $index = 'slug' ) {
	static $tags;
	if ( empty( $tags ) ) {
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
		$defaults = $is_official_child ? array() : array(
			'slug' => 'strute',
			'name' => __( 'Strute', 'strute' ),
			'label' => __( 'Strute Options', 'strute' ),
			'vers' => hoot_data( 'template_version' ),
			'shot' => ( file_exists( hoot_data()->template_dir . 'screenshot.jpg' ) ) ? hoot_data()->template_uri . 'screenshot.jpg' : (
						( file_exists( hoot_data()->template_dir . 'screenshot.png' ) ) ? hoot_data()->template_uri . 'screenshot.png' : ''
						),
			'fullshot' => ( file_exists( hoot_data()->incdir . 'admin/images/screenshot.jpg' ) ) ? hoot_data()->incuri . 'admin/images/screenshot.jpg' : (
				( file_exists( hoot_data()->incdir . 'admin/images/screenshot.png' ) ) ? hoot_data()->incuri . 'admin/images/screenshot.png' : ''
			),
			'urlhoot'    => 'https://wphoot.com/',
			'urldemo'    => 'https://demo.wphoot.com/strute/',
			'urltheme'   => 'https://wphoot.com/store/strute/',
			'urlcdn'     => 'https://cdn.wphoot.com/',
			'urlsupport' => 'https://wphoot.com/support/',
			'urldocs'    => 'https://wphoot.com/support/strute/',
		);
		$defaults = apply_filters( 'strute_abouttags', $defaults );
		if ( ! is_array( $defaults ) ) $defaults = array();

		$tags = array();
		foreach ( array( 'slug', 'vers' ) as $key ) {
			if ( !empty( $defaults[ $key ] ) ) $tags[ $key ] = sanitize_html_class( $defaults[ $key ] );
		}
		foreach ( array( 'name', 'label' ) as $key ) {
			if ( !empty( $defaults[ $key ] ) ) $tags[ $key ] = esc_html( $defaults[ $key ] );
		}
		foreach ( array( 'shot', 'fullshot', 'urlhoot', 'urldemo', 'urltheme', 'urlcdn', 'urlsupport', 'urldocs' ) as $key ) {
			if ( !empty( $defaults[ $key ] ) ) $tags[ $key ] = esc_url( $defaults[ $key ] );
		}
		if ( empty( $tags['fullshot'] ) ) {
			if ( !empty( $tags['shot'] ) ) {
				$tags['fullshot'] = $tags['shot'];
			}
		}
	}
	return ( ( isset( $tags[ $index ] ) ) ? $tags[ $index ] : '' );
}