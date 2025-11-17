<?php
/**
 * HTML attribute filters.
 * Filter schema ('library/attr-schema.php') for generic container's attributes based on specific theme options
 * Attributes for non-generic structural elements (mostly theme specific) are also loaded in this file.
 */

/* Modify Original Schema for Generic container's Option specific attributes */
add_filter( 'hoot_attr_body',     'strute_attr_body',     7    );
add_filter( 'hoot_attr_sitehead', 'strute_attr_sitehead', 7    );
add_filter( 'hoot_attr_menu',     'strute_attr_menu',     7, 2 );
add_filter( 'hoot_attr_content',  'strute_attr_content',  7    );
add_filter( 'hoot_attr_sidebar',  'strute_attr_sidebar',  7, 2 );
add_filter( 'hoot_attr_branding', 'strute_attr_branding', 7    );

/* New Theme Filters */
add_filter( 'hoot_attr_page-wrapper',      'strute_attr_page_wrapper',      7    );
add_filter( 'hoot_attr_topbar',            'strute_attr_topbar',            7    );
add_filter( 'hoot_attr_sitehead-part',     'strute_attr_sitehead_part',     7, 2 );
add_filter( 'hoot_attr_sitehead-menu',     'strute_attr_sitehead_menu',     7    );
add_filter( 'hoot_attr_sitehead-aside',    'strute_attr_sitehead_aside',    7    );
add_filter( 'hoot_attr_sitehead-extras',   'strute_attr_sitehead_extras',   7, 2 );
add_filter( 'hoot_attr_below-sitehead',    'strute_attr_below_sitehead',    7    );
add_filter( 'hoot_attr_main',              'strute_attr_main',              7    );
add_filter( 'hoot_attr_frontpage-grid',    'strute_attr_frontpage_grid',    7    );
add_filter( 'hoot_attr_frontpage-content', 'strute_attr_frontpage_content', 7    );
add_filter( 'hoot_attr_frontpage-area',    'strute_attr_frontpage_area',    7, 2 );
add_filter( 'hoot_attr_loop-meta-wrap',    'strute_attr_loop_meta_wrap',    7, 2 );
add_filter( 'hoot_attr_loop-meta',         'strute_attr_loop_meta',         7, 2 ); // This is a bit more generic (archive / singular etc ) than just for archives
add_filter( 'hoot_attr_loop-title',        'strute_attr_loop_title',        7, 2 ); // This is a bit more generic (archive / singular etc ) than just for archives
add_filter( 'hoot_attr_loop-description',  'strute_attr_loop_description',  7, 2 ); // This is a bit more generic (archive / singular etc ) than just for archives
add_filter( 'hoot_attr_content-wrap',      'strute_attr_content_wrap',      7, 2 );
add_filter( 'hoot_attr_archive-wrap',      'strute_attr_archive_wrap',      7, 2 );
add_filter( 'hoot_attr_sidebar-wrap',      'strute_attr_sidebar_wrap',      7, 2 );
add_filter( 'hoot_attr_page',              'strute_attr_post',              5, 2  );
add_filter( 'hoot_attr_post',              'strute_attr_post',              5, 2  );
add_filter( 'hoot_attr_sub-footer',        'strute_attr_sub_footer',        7    );
add_filter( 'hoot_attr_post-footer',       'strute_attr_post_footer',       7    );

/**
 * Modify <body> element attributes
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_body( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' altthemedividers';
	$attr['class'] .= ' has-fullwidth';
	$anims = hoot_get_mod( 'enable_anims' );
	if ( $anims ) {
		$attr['class'] .= hoot_getchecked( 'enabled_anims', 'stickyhead' ) ? ' hootanim-sh'  : '';
		$attr['class'] .= hoot_getchecked( 'enabled_anims', 'pagehead' )   ? ' hootanim-pgh' : '';
		$imghov = apply_filters( 'hoot_anims_imghov_classes', 'hootanim-img hootanim-img1 hootanim-img2 hootanim-img3 hootanim-img4 hootanim-img5 hootanim-img6' );
		$imghov = is_string( $imghov ) && !empty( $imghov ) ? esc_attr( $imghov ) : '';
		$attr['class'] .= hoot_getchecked( 'enabled_anims', 'imghov' )     ? " {$imghov}" : '';
	}
	return $attr;
}

/**
 * Modify <header> element attributes
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_sitehead( $attr ) {

	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$location = hoot_get_mod( 'menu_location' );
	$side = hoot_get_mod( 'logo_side' );
	if ( $side == 'widget-area' ) { $side = 'widget'; }
	if ( $location == 'side' )    { $side = 'menu'  ; }

	// sitehead Side
	$attr['class'] .= ' sitehead-side-' . $side;

	// sitehead Menu
	$attr['class'] .= ' sitehead-menu-' . $location;
	$attr['class'] .= ' sitehead-menualign-' . hoot_get_mod( 'fullwidth_menu_align' );
	$mobile_submenu_click = hoot_get_mod( 'mobile_submenu_click' );
	$attr['class'] .= ( $mobile_submenu_click ) ? ' mobilesubmenu-click' : ' mobilesubmenu-open';
	$attr['class'] .= ( hoot_get_mod( 'disable_table_menu' ) ) ? '' : ' hoot-tablemenu';

	// misc
	$attr['class'] .= ' js-search';

	return $attr;
}

/**
 * Nav menu attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_menu( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	return $attr;
}

/**
 * Modify Main content container of the page attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_content( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];

	$layout_class = strute_layout_class( 'content' );
	if ( !empty( $layout_class ) )
		$attr['class'] .= ' ' . $layout_class;

	return $attr;
}

/**
 * Modify Sidebar attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_sidebar( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	if ( !empty( $context ) && ( $context == 'primary' || $context == 'secondary' ) ) {
		$layout_class = strute_layout_class( "sidebar" );
		if ( !empty( $layout_class ) )
			$attr['class'] .= $layout_class;
	}

	return $attr;
}

/**
 * Branding attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_branding( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	return $attr;
}

/**
 * Page wrapper attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_page_wrapper( $attr ) {
	$attr['id'] = 'page-wrapper';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];

	// Set site layout class
	$site_layout = hoot_get_mod( 'site_layout' );
	$attr['class'] .= ( $site_layout == 'boxed' ) ? ' hgrid site-boxed' : ' site-stretch';
	$attr['class'] .= ' page-wrapper';

	// Set layout if not already set
	$layout = hoot_data( 'currentlayout' );
	if ( empty( $layout ) )
		strute_layout('');

	// Set sidebar layout class
	$currentlayout = hoot_data( 'currentlayout', 'layout' );
	if ( !empty( $currentlayout ) ) :
		$attr['class'] .= ' sitewrap-'. $currentlayout;
		switch( $currentlayout ) {
			case 'none' :
			case 'full' :
			case 'full-width' :
				$attr['class'] .= ' sidebars0';
				break;
			case 'narrow-right' :
			case 'wide-right' :
			case 'narrow-left' :
			case 'wide-left' :
				$attr['class'] .= ' sidebarsN sidebars1';
				break;
			case 'narrow-left-left' :
			case 'narrow-left-right' :
			case 'narrow-right-left' :
			case 'narrow-right-right' :
				$attr['class'] .= ' sidebarsN sidebars2';
				break;
		}
	endif;

	// Set plugin style classes
	$classes = apply_filters( 'strute_attr_page_wrapper_plugins', array( 'hoot-cf7-style', 'hoot-mapp-style', 'hoot-jetpack-style' ) );
	$attr['class'] .= ' ' . hoot_sanitize_html_classes( $classes );

	// Set sticky sidebar class
	if ( !hoot_get_mod( 'disable_sticky_sidebar' ) )
		$attr['class'] .= ' hoot-sticky-sidebar';

	// Set menu background class
	if ( function_exists( 'hoot_lib_premium_core' ) && hoot_get_mod( 'menu_background_type' ) == 'background' )
		$attr['class'] .= ' with-menubg';

	return $attr;
}

/**
 * Topbar attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_topbar( $attr ) {
	$attr['id'] = 'topbar';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' topbar';
	return $attr;
}

/**
 * Modify sitehead part attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_sitehead_part( $attr ) {
	$attr['id'] = 'sitehead-part';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' sitehead-part';
	return $attr;
}

/**
 * sitehead Menu attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_sitehead_menu( $attr ) {
	$attr['id'] = 'sitehead-menu';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' sitehead-menu';
	return $attr;
}

/**
 * Sitehead Aside attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_sitehead_aside( $attr ) {
	$attr['id'] = 'sitehead-aside';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' sitehead-aside';
	return $attr;
}

/**
 * Sitehead Extras attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_sitehead_extras( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	if ( !empty( $context ) && ( $context === 'dtp' || $context === 'mob' ) ) {
		$attr['id'] = 'sitehead-extra' . $context;
		$attr['class'] .= ' sitehead-extra' . $context;
	}
	$attr['class'] .= ' sitehead-extra';
	return $attr;
}

/**
 * Below sitehead attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_below_sitehead( $attr ) {
	$attr['id'] = 'below-sitehead';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' below-sitehead';
	return $attr;
}

/**
 * Main attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_main( $attr ) {
	$attr['id'] = 'main';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' main';
	return $attr;
}

/**
 * Main content container of the frontpage
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_frontpage_grid( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' hgrid-stretch frontpage-grid';

	return $attr;
}

/**
 * Main content container of the frontpage
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_frontpage_content( $attr ) {
	$attr['id'] = 'content-frontpage';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' content-frontpage';

	return $attr;
}

/**
 * Frontpage Area
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_frontpage_area( $attr, $context ) {

	$key = $context;
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$module_bg = hoot_get_mod( "frontpage_sectionbg_{$key}-type" );

	if ( $module_bg == 'image' ) {
		$module_bg_img = hoot_get_mod( "frontpage_sectionbg_{$key}-image" );
		if ( !empty( $module_bg_img ) ) {
			$module_bg_parallax = hoot_get_mod( "frontpage_sectionbg_{$key}-parallax" );
			$attr['class'] .= ( $module_bg_parallax ) ? ' bg-parallax' : ' bg-noparallax';
			$attr['style'] = 'background-image:url(' . esc_attr( $module_bg_img ) . ');';
		}
	} elseif ( $module_bg == 'color' ) {
		$module_bg_color = hoot_get_mod( "frontpage_sectionbg_{$key}-color" );
		if ( !empty( $module_bg_color ) ) {
			$attr['class'] .= ' area-bgcolor';
			$attr['style'] = 'background-color:' . sanitize_hex_color( $module_bg_color ) . ';';
		}
	}
	return $attr;
}

/**
 * Loop meta attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_loop_meta_wrap( $attr, $context ) {

	$attr['id'] = 'loop-meta-wrap';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' loop-meta-wrap';

	return $attr;
}

/**
 * Loop meta attributes.
 * hoot_attr_archive_header in v3.0.0 ; we use it for generic loop (archive / singular etc )
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_loop_meta( $attr, $context ) {

	$attr['id'] = 'loop-meta';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' loop-meta';
	if ( $context == 'archive' ) $attr['class'] .= ' archive-header';
	$attr['itemscope'] = 'itemscope';
	$attr['itemtype']  = 'https://schema.org/WebPageElement';
	return $attr;

}

/**
 * Loop title attributes.
 * hoot_attr_archive_title in v3.0.0 ; we use it for generic loop (archive / singular etc )
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_loop_title( $attr, $context ) {

	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' loop-title entry-title';
	if ( $context == 'archive' ) $attr['class'] .= ' archive-title';
	$attr['itemprop']  = 'headline';

	return $attr;
}

/**
 * Loop description attributes.
 * hoot_attr_archive_description in v3.0.0 ; we use it for generic loop (archive / singular etc
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_loop_description( $attr, $context ) {

	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' loop-description';
	if ( $context == 'archive' ) $attr['class'] .= ' archive-description';
	$attr['itemprop']  = 'text';

	return $attr;
}

/**
 * Content Wrap attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_content_wrap( $attr, $context ) {
	$attr['id'] = 'content-wrap';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' content-wrap';
	if ( !hoot_get_mod( 'disable_sticky_sidebar' ) )
		$attr['class'] .= ' theiaStickySidebar';
	return $attr;
}

/**
 * Archive Wrap attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_archive_wrap( $attr, $context ) {
	$attr['id'] = 'archive-wrap';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' archive-wrap';
	return $attr;
}

/**
 * Sidebar Wrap attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_sidebar_wrap( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' sidebar-wrap';
	if ( !hoot_get_mod( 'disable_sticky_sidebar' ) )
		$attr['class'] .= ' theiaStickySidebar';
	return $attr;
}

/**
 * Post/Page <article> attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @param string $context
 * @return array
 */
function strute_attr_post( $attr, $context ) {
	if ( $context === 'single' ) {
		$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
		$article_background_type = hoot_get_mod( 'article_background_type' );
		if ( $article_background_type === 'background' )
			$attr['class'] .= ' article-bg';
		elseif ( $article_background_type === 'background-whensidebar' )
			$attr['class'] .= ' article-bg-whensidebar';
	}
	return $attr;
}

/**
 * Subfooter attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_sub_footer( $attr ) {
	$attr['id'] = 'sub-footer';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' sub-footer';
	return $attr;
}

/**
 * Postfooter attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function strute_attr_post_footer( $attr ) {
	$attr['id'] = 'post-footer';
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['class'] .= ' post-footer';
	return $attr;
}