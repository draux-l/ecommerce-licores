<?php
/**
 * HTML attribute to enable aos on elements.
 * Note: We use an optimized version of AOS. It does not include all the default animations and delays/durations.
 */

/**
 * Add AOS for elements
 */
function strute_aos_init() {
	$enabled = apply_filters( 'strute_aos_enable_elements', array(
		// Theme
		'fparea'     => true,
		'loopmeta'   => true,
		'page'       => true,
		'post'       => true,
		// HootKit
		'announce'   => true,
		'contact'    => true,
		'blocks'     => true, // content-block + content-post-block + content-product-block
		'grid'       => true, // content-grid + post-grid
		'cta'        => true,
		'iconlist'   => true,
		'notice'     => true,
		'listunit'   => true, // post-list + product-list
		'profile'    => true,
		'social'     => true,
		'vcards'     => true,
	) );
	$enabled = is_array( $enabled ) ? $enabled : array();
	if ( !empty( $enabled['fparea'] ) )
		add_filter( 'hoot_attr_frontpage-area',        'strute_attr_aos_fparea',           10 );
	if ( !empty( $enabled['loopmeta'] ) )
		add_filter( 'hoot_attr_loop-meta',             'strute_attr_aos_loopmeta',         10, 2 );
	if ( !empty( $enabled['page'] ) )
		add_filter( 'hoot_attr_page',                  'strute_attr_aos_post',             10, 2 );
	if ( !empty( $enabled['post'] ) )
		add_filter( 'hoot_attr_post',                  'strute_attr_aos_post',             10, 2 );
	if ( !empty( $enabled['announce'] ) )
		add_filter( 'hoot_attr_announce-widget',       'strute_attr_aos_hkit_announce',    10 );
	if ( !empty( $enabled['contact'] ) )
		add_filter( 'hoot_attr_contact-info-block',    'strute_attr_aos_hkit_blocks',      10, 2 );
	if ( !empty( $enabled['blocks'] ) )
		add_filter( 'hoot_attr_content-block-column',  'strute_attr_aos_hkit_blocks',      10, 2 );
	if ( !empty( $enabled['grid'] ) )
		add_filter( 'hoot_attr_hk-gridunit',           'strute_attr_aos_hkit_blocksquick', 10, 2 );
	if ( !empty( $enabled['cta'] ) )
		add_filter( 'hoot_attr_cta-widget',            'strute_attr_aos_hkit_zoomin',      10 );
	if ( !empty( $enabled['iconlist'] ) )
		add_filter( 'hoot_attr_icon-list-li',          'strute_attr_aos_hkit_blocks',      10, 2 );
	if ( !empty( $enabled['notice'] ) )
		add_filter( 'hoot_attr_notice-widget-wrap',    'strute_attr_aos_hkit_zoomin',      10 );
	if ( !empty( $enabled['listunit'] ) )
		add_filter( 'hoot_attr_hk-listunit',           'strute_attr_aos_hkit_blocksquick', 10, 2 );
	if ( !empty( $enabled['profile'] ) )
		add_filter( 'hoot_attr_profile-image',         'strute_attr_aos_hkit_zoomin',      10 );
	if ( !empty( $enabled['social'] ) )
		add_filter( 'hoot_attr_social-icons-icon',     'strute_attr_aos_hkit_blocks',      10, 2 );
	if ( !empty( $enabled['vcards'] ) )
		add_filter( 'hoot_attr_vcard-column',          'strute_attr_aos_hkit_blocks',      10, 2 );
}
add_action( 'hoot_aos_init', 'strute_aos_init' );

/**
 * AOS data attributes
 * * Animations available: [$: used in this theme]
 *    fade        || fade-up $
 *    flip-left $ || flip-right
 *    slide-up
 *    zoom-in $
 */

if ( ! function_exists( 'strute_attr_aos_fparea' ) ) :
function strute_attr_aos_fparea( $attr ) {
	$attr['data-aos'] = 'fade';
	return $attr;
}
endif;

if ( ! function_exists( 'strute_attr_aos_loopmeta' ) ) :
function strute_attr_aos_loopmeta( $attr, $context ) {
	if ( $context === 'singular' ) {
		$attr['data-aos'] = 'fade-up';
	} elseif ( $context === 'archive' ) {
		$attr['data-aos'] = 'fade-up';
	}
	return $attr;
}
endif;

if ( ! function_exists( 'strute_attr_aos_post' ) ) :
function strute_attr_aos_post( $attr, $context ) {
	if ( $context === 'single' ) {
		$attr['data-aos'] = 'fade-up';
		$attr['data-aos-delay'] = 200;
	} else {
		$attr['data-aos'] = 'fade-up';
		if ( is_array( $context ) && !empty( $context['counter'] ) ) {
			$style = !empty( $context['style'] ) ? $context['style'] : hoot_get_mod( 'archive_type' );
			$count = intval( $context['counter'] );
			switch( $style ) {
				case 'block2':
					if ( $count % 2 == 0 ) $attr['data-aos-delay'] = 400;
					break;
				case 'block3':
					if ( $count % 3 == 2 ) $attr['data-aos-delay'] = 200;
					if ( $count % 3 == 0 ) $attr['data-aos-delay'] = 400;
					break;
				case 'mixed-block2':
					if ( $count !== 1 ) {
						if ( $count % 2 == 0 ) $attr['data-aos-delay'] = 200;
						if ( $count % 2 == 1 ) $attr['data-aos-delay'] = 400;
					}
					break;
				default:
					break;
			}
		}
	}
	return $attr;
}
endif;

if ( ! function_exists( 'strute_attr_aos_hkit_announce' ) ) :
function strute_attr_aos_hkit_announce( $attr ) {
	$attr['data-aos'] = 'flip-left';
	return $attr;
}
endif;

if ( ! function_exists( 'strute_attr_aos_hkit_blocks' ) ) :
function strute_attr_aos_hkit_blocks( $attr, $context, $quick=false ) {
	$attr['data-aos'] = 'zoom-in';
	if ( is_array( $context ) && !empty( $context['counter'] ) ) {
		$count = intval( $context['counter'] );
		if ( !empty( $context['column'] ) ) {
			$count = intval( $context['column'] );
		} elseif ( !empty( $context['row'] ) ) {
			$count = intval( $context['row'] );
		}
		if ( $count ) {
			if ( $count > 10 ) $count = 10;
			$factor = $quick ? 100 : 200;
			$attr['data-aos-delay'] = $count * $factor;
		}
	}
	return $attr;
}
endif;
if ( ! function_exists( 'strute_attr_aos_hkit_blocksquick' ) ) :
function strute_attr_aos_hkit_blocksquick( $attr, $context ) {
	return strute_attr_aos_hkit_blocks( $attr, $context, true );
}
endif;

if ( ! function_exists( 'strute_attr_aos_hkit_zoomin' ) ) :
function strute_attr_aos_hkit_zoomin( $attr ) {
	$attr['data-aos'] = 'zoom-in';
	return $attr;
}
endif;

