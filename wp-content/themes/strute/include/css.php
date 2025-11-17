<?php
/**
 * Add dynamic css to frontend.
 *
 * This file is loaded at 'after_setup_theme' hook with 10 priority.
 */

/* Add CSS vars built from options to the dynamic CSS vars array */
add_action( 'hoot_dynamic_cssrules', 'strute_dynamic_cssvars', 2 );

/**
 * Map customizer option font style string to css properties
 * @param string
 * @return array
 */
function hoot_resolve_fontstyle( $option ) {
	// Reset to none/normal else scss may have default set as uppercase/italic
	return array(
		'text-transform' => 'uppercase' === $option || 'uppercasei' === $option ? 'uppercase' : 'none',
		'font-style'     => 'standardi' === $option || 'uppercasei' === $option ? 'italic'    : 'normal',
	);
}

/**
 * Frontpage custom font color css selector
 * @param bool
 * @param string
 * @return string
 */
function hoot_fp_customfontcolor_selector( $force = false, $selector = 'selector-place' ) {
	$sym = $force ? '#' : '.';
	return "{$sym}{$selector} *:not(.button):not(button):not(.button *):not(button *):not(.social-icons-icon):not(.social-icons-icon *), {$sym}{$selector} .more-link, {$sym}{$selector} .more-link a";
}

/**
 * Map customizer option font family string to css family
 * @param string
 * @return string
 */
function hoot_resolve_fontfamily( $option ) {
	$list = hoot_themefonts('optionsmap');
	if ( isset( $list[ $option ] ) && is_string( $list[ $option ] ) )
		return $list[ $option ];
	else
		return '';
}

/**
 * Map customizer option background to css properties
 * @param string
 * @return array
 */
function strute_resolve_background( $idtag ) {
	$bgarray = array(
		'background-color' => '',
		'background-image' => '',
		'background-repeat' => '',
		'background-position' => '',
		'background-attachment' => '',
		'background-size' => '',
	);
	global $hoot_style_builder;
	$bgvals = $hoot_style_builder->sanitize_background( $idtag, false );
	if ( is_array( $bgvals ) ) {
		foreach ( $bgarray as $key => $value ) {
			if ( !empty( $bgvals[ $key ] ) && is_array( $bgvals[ $key ] ) && !empty( $bgvals[ $key ]['value'] ) ) {
				$bgarray[ $key ] = $bgvals[ $key ]['value'];
			}
		}
	}
	return $bgarray;
}

/**
 * Custom CSS built from user theme options
 * For proper sanitization, always use functions from library/sanitization.php
 *
 * @since 1.0
 * @access public
 */
function strute_dynamic_cssvars() {
	$cssvars = array();

	// GRID

	// SPACERS

	$widgetmargin = hoot_get_mod( 'widgetmargin' );
	$widgetmargin = is_string( $widgetmargin ) ? json_decode( $widgetmargin, true ) : array();
	if ( is_array( $widgetmargin ) ) {
		foreach ( $widgetmargin as $device => $value ) {
			$value = intval( $value );
			$cssvars['--hoot-widget-margin'][ $device ] = $value || $value == 0 ? $value . 'px' : '';
			$cssvars['--hoot-widget-halfmargin'][ $device ] = $value && $value > 50 ? $value / 2 . 'px' : '25px';
		}
	}

	// FORMS

	/** Typography **/

	// GENERAL

	// LOGO

	$logo_fontstyle = hoot_resolve_fontstyle( hoot_get_mod( 'logo_fontface_style' ) );
	$cssvars['--hoot-logo-family'] = hoot_resolve_fontfamily( hoot_get_mod( 'logo_fontface' ) );
	$cssvars['--hoot-logo-style'] = $logo_fontstyle[ 'font-style' ];
	$cssvars['--hoot-logo-transform'] = $logo_fontstyle[ 'text-transform' ];
	$logo_custom = hoot_sortlist( hoot_get_mod( 'logo_custom' ) );
	if ( is_array( $logo_custom ) && !empty( $logo_custom ) ) {
		$lcount = 1;
		foreach ( $logo_custom as $logo_custom_line ) {
			$size = !empty( $logo_custom_line[ 'size' ] ) ? intval( $logo_custom_line[ 'size' ] ) : false;
			$cssvars['--hoot-logo-line'.$lcount.'-size'] = $size ? $size . 'px' : '';
			$lcount++;
		}
	}

	// HEADINGS

	$headings_fontstyle = hoot_resolve_fontstyle( hoot_get_mod( 'headings_fontface_style' ) );
	$cssvars['--hoot-headings-family'] = hoot_resolve_fontfamily( hoot_get_mod( 'headings_fontface' ) );
	$cssvars['--hoot-sidebarhead-family'] = $cssvars['--hoot-headings-family'];
	$cssvars['--hoot-footerhead-family']  = $cssvars['--hoot-headings-family'];

	$cssvars['--hoot-headings-style']    = $headings_fontstyle[ 'font-style' ];
	$cssvars['--hoot-h1-style']          = $cssvars['--hoot-headings-style'];
	$cssvars['--hoot-h2-style']          = $cssvars['--hoot-headings-style'];
	$cssvars['--hoot-h3-style']          = $cssvars['--hoot-headings-style'];
	$cssvars['--hoot-h4-style']          = $cssvars['--hoot-headings-style'];
	$cssvars['--hoot-h5-style']          = $cssvars['--hoot-headings-style'];
	$cssvars['--hoot-h6-style']          = $cssvars['--hoot-headings-style'];
	$cssvars['--hoot-sidebarhead-style'] = $cssvars['--hoot-headings-style'];
	$cssvars['--hoot-footerhead-style']  = $cssvars['--hoot-headings-style'];

	$cssvars['--hoot-headings-transform']    = $headings_fontstyle[ 'text-transform' ];
	$cssvars['--hoot-h1-transform']          = $cssvars['--hoot-headings-transform'];
	$cssvars['--hoot-h2-transform']          = $cssvars['--hoot-headings-transform'];
	$cssvars['--hoot-h3-transform']          = $cssvars['--hoot-headings-transform'];
	$cssvars['--hoot-h4-transform']          = $cssvars['--hoot-headings-transform'];
	$cssvars['--hoot-h5-transform']          = $cssvars['--hoot-headings-transform'];
	$cssvars['--hoot-h6-transform']          = $cssvars['--hoot-headings-transform'];
	// $cssvars['--hoot-sidebarhead-transform'] = $cssvars['--hoot-headings-transform'];
	// $cssvars['--hoot-footerhead-transform']  = $cssvars['--hoot-headings-transform'];

	$subheadings_fontstyle = hoot_resolve_fontstyle( hoot_get_mod( 'subheadings_fontface_style' ) );
	$cssvars['--hoot-subheadings-family'] = hoot_resolve_fontfamily( hoot_get_mod( 'subheadings_fontface' ) );
	$cssvars['--hoot-subheadings-style'] = $subheadings_fontstyle[ 'font-style' ];
	$cssvars['--hoot-subheadings-transform'] = $subheadings_fontstyle[ 'text-transform' ];

	// LINK

	$cssvars['--hoot-linkcolor'] = hoot_get_mod( 'accent_color' );
	$cssvars['--hoot-linkhovercolor'] = hoot_color_darken( $cssvars['--hoot-linkcolor'], 25, 25 );

	// BODY

	$cssvars['--hoot-basefont-family'] = hoot_resolve_fontfamily( hoot_get_mod( 'body_fontface' ) );

	// TOPBAR

	// MENU

	// FOOTER

	/** Colors and backgrounds **/

	// GENERAL

	$cssvars['--hoot-accentcolor'] = hoot_get_mod( 'accent_color' );
	$cssvars['--hoot-accentfont'] = hoot_get_mod( 'accent_font' );
	$cssvars['--hoot-buttoncolor'] = hoot_get_mod( 'button_color' );
	$cssvars['--hoot-buttonfont'] = hoot_get_mod( 'button_font' );

	// BODY

	$body_background = strute_resolve_background( 'background' );
	$cssvars['--hoot-body-bg']               = $body_background['background-color'];
	$cssvars['--hoot-body-bgimg']            = $body_background['background-image'];
	$cssvars['--hoot-body-bgrepeat']         = $body_background['background-repeat'];
	$cssvars['--hoot-body-bgpos']            = $body_background['background-position'];
	$cssvars['--hoot-body-bgatch']           = $body_background['background-attachment'];
	$cssvars['--hoot-body-bgsize']           = $body_background['background-size'];

	// TOPBAR

	// CONTENT

	$cssvars['--hoot-box-bg'] = hoot_get_mod( 'box_background_color' );
	$cssvars['--hoot-gridarticle-bg'] = hoot_get_mod( 'gridarticle_bg' );
	$cssvars['--hoot-article-bg'] = hoot_get_mod( 'article_background_color' );

	// FOOTER

	/** Misc **/

	// MISC

	$goto_top_offset = hoot_get_mod( 'logo_goto_top_offsetimage_width' );
	$goto_top_offset = is_string( $goto_top_offset ) ? json_decode( $goto_top_offset, true ) : array();
	if ( is_array( $goto_top_offset ) ) {
		foreach ( $goto_top_offset as $device => $value ) {
			$value = intval( $value );
			$cssvars['--hoot-goto-offset'][ $device ] = $value || $value == 0 ? $value . 'px' : '';
		}
	}

	$logo_icon_size = intval( hoot_get_mod( 'site_title_icon_size' ) );
	$cssvars['--hoot-logo-iconsize'] = $logo_icon_size ? $logo_icon_size . 'px' : '';

	$logo_image_width = hoot_get_mod( 'logo_image_width' );
	$logo_image_width = is_string( $logo_image_width ) ? json_decode( $logo_image_width, true ) : array();
	if ( is_array( $logo_image_width ) ) {
		foreach ( $logo_image_width as $device => $value ) {
			$value = intval( $value );
			$cssvars['--hoot-logo-maximgwidth'][ $device ] = $value || $value == 0 ? $value . 'px' : '';
		}
	}

	$logo_stickyzoom = intval( hoot_get_mod( 'sticky_sitehead_dtp_logozoom' ) );
	if ( $logo_stickyzoom && $logo_stickyzoom > 0 && $logo_stickyzoom <= 100 ) {
		$cssvars['--hoot-sticky-dtplogozoom'] = $logo_stickyzoom / 100;
	}
	$logo_stickyzoom = intval( hoot_get_mod( 'sticky_sitehead_mob_logozoom' ) );
	if ( $logo_stickyzoom && $logo_stickyzoom > 0 && $logo_stickyzoom <= 100 ) {
		$cssvars['--hoot-sticky-moblogozoom'] = $logo_stickyzoom / 100;
	}

	// TOP ANNOUNCEMENT BAR
		$topannbg = hoot_get_mod( 'topann_content_bg' );
		$cssvars['--hoot-textstyle-topannbg'] = $topannbg ? $topannbg : strute_default_style('topann_content_bg');

	// SIDEBAR WIDTHS

	$sbwidth = hoot_get_mod( 'sidebar1_width' );
	if ( $sbwidth === 'px' ) {
		$sbval = intval( hoot_get_mod( 'sidebar1_width_px' ) );
		if ( $sbval && $sbval > 0 ) {
			$cssvars['--hoot-sidebar1-width'] = $sbval . 'px';
		}
	} elseif ( $sbwidth === 'pcnt' ) {
		$sbval = intval( hoot_get_mod( 'sidebar1_width_pcnt' ) );
		if ( $sbval && $sbval >= 0 && $sbval <= 100 ) {
			$cssvars['--hoot-sidebar1-width'] = $sbval . '%';
		}
	}
	$sbwidth = hoot_get_mod( 'sidebar2_width' );
	if ( $sbwidth === 'px' ) {
		$sbval = intval( hoot_get_mod( 'sidebar2_width_px' ) );
		if ( $sbval && $sbval > 0 ) {
			$cssvars['--hoot-sidebar2-width'] = $sbval . 'px';
		}
	} elseif ( $sbwidth === 'pcnt' ) {
		$sbval = intval( hoot_get_mod( 'sidebar2_width_pcnt' ) );
		if ( $sbval && $sbval >= 0 && $sbval <= 100 ) {
			$cssvars['--hoot-sidebar2-width'] = $sbval . '%';
		}
	}

	// SINGLE ARTICLE

	$article_maxwidth = intval( hoot_get_mod( 'article_maxwidth' ) );
	$cssvars['--hoot-article-width'] = $article_maxwidth ? $article_maxwidth . 'px' : '';
	$article_maxwidth = intval( hoot_get_mod( 'article_maxwidth_nosidebar' ) );
	$cssvars['--hoot-article-width-nosb'] = $article_maxwidth ? $article_maxwidth . 'px' : '';

	// FRONTPAGE IMAGE
	foreach ( array(
		'minheight',
		'conpad',
		'imgpad',
		'headsize',
		'subheadsize',
		'textsize',
		'btnsize',
	) as $key ) {
		$val = hoot_get_mod( "header_image_{$key}" );
		$val = intval( $val );
		if ( $val || $val === 0 ) {
			$cssvars[ "--hoot-fimg-{$key}" ] = $val . 'px';
		}
	}
	foreach ( array(
		'bg',
		'headcolor',
		'subheadcolor',
		'textcolor',
		'btncolor',
		'btnbg',
		'btncolor2',
		'btnbg2',
	) as $key ) {
		$val = hoot_get_mod( "header_image_{$key}" );
		if ( $val ) {
			$cssvars[ "--hoot-fimg-{$key}" ] = $val;
		}
	}
	foreach ( array(
		'conbg',
		'overlay',
	) as $key ) {
		$val = hoot_get_mod( "header_image_{$key}" );
		$alpha = hoot_get_mod( "header_image_{$key}_opacity" );
		$bg = hoot_hex_to_rgba( $val, $alpha );
		if ( $bg ) {
			$cssvars[ "--hoot-fimg-{$key}" ] = $bg;
		}
	}

	/**
	 * Misc Stuff not covered by css vars
	 */

	/** Frontpage **/
	if ( !is_customize_preview() ) {
		$sections = hoot_sortlist( hoot_get_mod( 'frontpage_sections' ) );
		if ( is_array( $sections ) && !empty( $sections ) ) { foreach ( $sections as $key => $section ) {
			$id = ( $key == 'content' ) ? 'frontpage-page-content' : sanitize_html_class( 'frontpage-' . $key );
			$type = hoot_get_mod( "frontpage_sectionbg_{$key}-font" );
			switch ($type) {
				case 'color': $selector = hoot_fp_customfontcolor_selector( false, $id ); break;
				case 'force': $selector = hoot_fp_customfontcolor_selector( true, $id ); break;
				default: $selector = ''; break;
			}
			if ( $selector ) {
				hoot_add_css_rule( array(
							'selector'  => $selector,
							'property'  => 'color',
							'value'     => hoot_get_mod( "frontpage_sectionbg_{$key}-fontcolor" ),
						) );
			}
		} }
	}

	/**
	 * NOTE: add_cssvars checks if $cssvars is an array.
	 * NOTE: var is added ONLY if its value is not empty.
	 */
	$cssvars = apply_filters( 'strute_dynamic_cssvars', $cssvars );
	hoot_add_cssvars( $cssvars );

}
