<?php
/**
 * Miscellaneous template tags and template utility functions
 * 
 * These functions are for use throughout the theme's various template files.
 * This file is loaded via the 'after_setup_theme' hook at priority '10'
 */

/**
 * Add a shim for wp_body_open()
 * Ref. https://core.trac.wordpress.org/ticket/46679
 */
if ( ! function_exists( 'wp_body_open' ) ) :
function wp_body_open() {
	do_action( 'wp_body_open' );
}
endif;

/**
 * Display the Top Announcement
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_topann' ) ):
function strute_topann() {
	foreach ( array(
		'image',
		'content_title',
		'content',
		'content_style'
	) as $id )
		$$id = hoot_get_mod( 'topann_' . $id );

	$display = !empty( $image ) || !empty( $content_title ) || !empty( $content );
	$is_cpreview = is_customize_preview();

	if ( $display ) {
		foreach ( array(
			'sticky',
			'imgasbg',
			'url',
			'url_target',
			'url_scope',
			'content_stretch',
			'content_nopad'
		) as $id )
			$$id = hoot_get_mod( 'topann_' . $id );

		$styleclass = '';
		$styleclass  .= ( $content_stretch == 'stretch' ) ? ' topann-stretch' : ' topann-grid';
		$styleclass  .= ( $content_stretch == 'stretch' && $content_nopad ) ? ' topann-nopad' : '';
		$styleclass  .= ( $image && $imgasbg ) ? ' topann-hasbg' : ' topann-nobg';
		$styleclass  .= ( $sticky ) ? ' topann-stick' : '';

		$inlinestyle = '';
		$inlinestyle .= ( $image && $imgasbg ) ? 'background-image:url(' . esc_url( $image ) . ');' : '';

		$urltarget = ( $url_target ) ? '_blank' : '_self';
		$url_scope = $url_scope == 'background' ? 'background' : 'content';
		$urlanchor = ( empty( $url ) ) ? '' : '
			<a href="' . esc_url( $url ) . '" ' . hoot_get_attr( 'topann-url', $url_scope, array(
				'classes' => "topann-{$url_scope}-url",
				'target' => $urltarget,
			) ) . '></a>';
	} elseif ( $is_cpreview ) {
		$image = $content_title = $content = $content_style = '';
		$styleclass = 'hootnoshow';
		$inlinestyle = '';
		$urltarget = $url_scope = $urlanchor = '';
	}

	if ( $display || $is_cpreview ) :
		?>
		<div <?php hoot_attr( 'topann', '', array(
			'id' => 'topann',
			'classes' => !empty( $styleclass ) ? $styleclass : false,
			'style' => !empty( $inlinestyle ) ? $inlinestyle : false,
			) ); ?> >
			<?php
				if ( $url_scope == 'background' ) echo wp_kses_post( $urlanchor );
				if ( $image && !$imgasbg ) {
					echo '<div class="topann-inlineimg"><div>';
						if ( $url_scope == 'content' ) echo wp_kses_post( $urlanchor );
						echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $content_title ) . '" />';
					echo '</div></div>';
				}
				if ( !empty( $content_title ) || !empty( $content ) ):
					echo '<div class="topann-contentbox textstyle-' . esc_attr( $content_style ) . '">';
						if ( $url_scope == 'content' ) echo wp_kses_post( $urlanchor );
						if ( !empty( $content_title ) ) {
							echo '<h5 class="topann-content-title">' . do_shortcode( wp_kses_post( $content_title ) ) . '</h5>';
						}
						if ( !empty( $content ) ) {
							echo '<div class="topann-content">' . do_shortcode( wp_kses_post( wpautop( $content ) ) ) . '</div>';
						}
					echo '</div>';
				endif;
			?>
		</div>
		<?php
	endif;
}
endif;

/**
 * Display the SiteHead
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_sitehead' ) ):
function strute_sitehead() {
	?>
	<header <?php hoot_attr( 'sitehead' ); ?>>

		<div <?php hoot_attr( 'sitehead-part', '', 'hgrid' ); ?>>
			<div <?php hoot_attr( 'sitehead-partinner', '', 'hootflex hootflex--nor hgrid-span-12' ); ?>>
				<?php
				// Display Branding
				strute_branding();

				// Display Side
				strute_sitehead_aside();

				// Display Menu
				strute_menu();

				// Display Extras
				strute_sitehead_extra_dtp();
				strute_sitehead_extra_mob();
				?>
			</div>
		</div>

	</header><!-- #header -->
	<?php
}
endif;

/**
 * Display the branding area
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_branding' ) ):
function strute_branding() {
	?>
	<div <?php hoot_attr( 'branding' ); ?>>
		<div id="site-logo" class="<?php
			echo 'site-logo-' . esc_attr( hoot_get_mod( 'logo' ) );
			if ( hoot_get_mod('logo_background_type') == 'accent' )
				echo ' accent-typo with-background';
			elseif ( hoot_get_mod('logo_background_type') == 'invert-accent' )
				echo ' invert-accent-typo with-background';
			elseif ( hoot_get_mod('logo_background_type') == 'background' )
				echo ' with-background';
			if ( hoot_get_mod( 'logo_border' ) == 'border' || hoot_get_mod( 'logo_border' ) == 'bordernopad' )
				echo ' logo-border';
			if ( hoot_get_mod( 'logo_border' ) == 'bordernopad' )
				echo ' nopadding';
			?>">
			<?php
			// Display the Image Logo or Site Title
			strute_logo();
			?>
		</div>
	</div><!-- #branding -->
	<?php
}
endif;

/**
 * Displays the logo
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_logo' ) ):
function strute_logo() {

	$display = '';
	$strute_logo = hoot_get_mod( 'logo' );

	if ( ( is_front_page() ) ) {
		$tag_h1 = 'h1';
		$tag_h2 = 'h2';
	} else {
		$tag_h1 = $tag_h2 = 'div';
	}

	if ( 'text' == $strute_logo || 'custom' == $strute_logo ) {
		$display .= strute_get_text_logo( $strute_logo, $tag_h1, $tag_h2 );
	} elseif ( 'mixed' == $strute_logo || 'mixedcustom' == $strute_logo ) {
		$display .= strute_get_mixed_logo( $strute_logo, $tag_h1, $tag_h2 );
	} elseif ( 'image' == $strute_logo ) {
		$display .= strute_get_image_logo( $strute_logo, $tag_h1, $tag_h2 );
	}

	echo wp_kses( apply_filters( 'strute_logo', $display, $strute_logo, $tag_h1, $tag_h2 ), hoot_data( 'hootallowedtags' ) );
}
endif;

/**
 * Return the text logo
 *
 * @since 1.0
 * @access public
 * @param string $strute_logo text|custom
 * @param string $tag_h1
 * @param string $tag_h2
 * @return void
 */
if ( !function_exists( 'strute_get_text_logo' ) ):
function strute_get_text_logo( $strute_logo, $tag_h1 = 'div', $tag_h2 = 'div' ) {
	$display = '';
	$title_icon = hoot_sanitize_fa( hoot_get_mod( 'site_title_icon', NULL ) );

	$class = $id = 'site-logo-' . esc_attr( $strute_logo );
	$class .= ( $title_icon ) ? ' site-logo-with-icon' : '';
	$class .= ( 'text' == $strute_logo && !function_exists( 'hoot_lib_premium_core' ) ) ? ' site-logo-text-' . hoot_get_mod( 'logo_size' ) : '';

	// Start Logo
	$display .= '<div id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '">';

		// Site Title with Icon
		$display .= "<{$tag_h1} " . hoot_get_attr( 'site-title' ) . '>';
			$display .= '<a href="' . esc_url( home_url() ) . '" rel="home" itemprop="url">';
				$display .= ( $title_icon ) ? '<i class="' . $title_icon . '"></i>' : '';
				$title = ( 'custom' == $strute_logo ) ? strute_get_custom_text_logo() : '<span class="blogname">' . get_bloginfo( 'name' ) . '</span>';
				$display .= apply_filters( 'strute_site_title', $title );
			$display .= "</a>";
		$display .= "</{$tag_h1}>";

		// Site Description
		if ( hoot_get_mod( 'show_tagline' ) && $desc = get_bloginfo( 'description' ) ) {
			$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description' ) . '><span>';
				$display .= $desc;
			$display .= "</span></{$tag_h2}>";
		} elseif ( is_customize_preview() ) {
			$hootnoshow = ( hoot_get_mod( 'show_tagline' ) ) ? '' : 'hootnoshow';
			$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description', '', $hootnoshow ) . '>' . get_bloginfo( 'description' ) . "</{$tag_h2}>";
		}

	$display .= '</div>';

	return apply_filters( 'strute_get_text_logo', $display, $strute_logo, $tag_h1, $tag_h2 );
}
endif;

/**
 * Return the mixed logo
 *
 * @since 1.0
 * @access public
 * @param string $strute_logo mixed|mixedcustom
 * @param string $tag_h1
 * @param string $tag_h2
 * @return void
 */
if ( !function_exists( 'strute_get_mixed_logo' ) ):
function strute_get_mixed_logo( $strute_logo, $tag_h1 = 'div', $tag_h2 = 'div' ) {
	$display = '';
	$has_logo = has_custom_logo();

	$class = $id = 'site-logo-' . esc_attr( $strute_logo );
	$class .= ( !empty( $has_logo ) ) ? ' site-logo-with-image' : '';
	$class .= ( 'mixed' == $strute_logo && !function_exists( 'hoot_lib_premium_core' ) ) ? ' site-logo-text-' . hoot_get_mod( 'logo_size' ) : '';

	// Start Logo
	$display .= '<div id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '">';

		// Logo Image
		if ( $has_logo ) {
			$display .= '<div class="site-logo-mixed-image">';
				$display .= get_custom_logo();
			$display .= '</div>';
		}

		$display .= '<div class="site-logo-mixed-text">';

			// Site Title (No Icon)
			$display .= "<{$tag_h1} " . hoot_get_attr( 'site-title' ) . '>';
				$display .= '<a href="' . esc_url( home_url() ) . '" rel="home" itemprop="url">';
					$title = ( 'mixedcustom' == $strute_logo ) ? strute_get_custom_text_logo() : '<span class="blogname">' . get_bloginfo( 'name' ) . '</span>';
					$display .= apply_filters( 'strute_site_title', $title );
				$display .= "</a>";
			$display .= "</{$tag_h1}>";

			// Site Description
			if ( hoot_get_mod( 'show_tagline' ) && $desc = get_bloginfo( 'description' ) ) {
				$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description' ) . '>';
					$display .= $desc;
				$display .= "</{$tag_h2}>";
			} elseif ( is_customize_preview() ) {
				$hootnoshow = ( hoot_get_mod( 'show_tagline' ) ) ? '' : 'hootnoshow';
				$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description', '', $hootnoshow ) . '>' . get_bloginfo( 'description' ) . "</{$tag_h2}>";
			}

		$display .= '</div>';

	$display .= '</div>';

	return apply_filters( 'strute_get_mixed_logo', $display, $strute_logo, $tag_h1, $tag_h2 );
}
endif;

/**
 * Return the image logo
 *
 * @since 1.0
 * @access public
 * @param string $strute_logo
 * @param string $tag_h1
 * @param string $tag_h2
 * @return void
 */
if ( !function_exists( 'strute_get_image_logo' ) ):
function strute_get_image_logo( $strute_logo = 'image', $tag_h1 = 'div', $tag_h2 = 'div' ) {
	$display = '';
	$has_logo = has_custom_logo();

	if ( !empty( $has_logo ) ) {
		$display .= '<div id="site-logo-image" class="site-logo-image">';

			// Logo Image
			$display .= "<{$tag_h1} " . hoot_get_attr( 'site-title' ) . '>';
				$display .= get_custom_logo();
				$display .= '<div style="clip: rect(1px, 1px, 1px, 1px); clip-path: inset(50%); height: 1px; width: 1px; margin: -1px; overflow: hidden; position: absolute !important;">' . esc_html( get_bloginfo( 'name' ) ) . '</div>';
			$display .= "</{$tag_h1}>";

			// Site Description
			if ( hoot_get_mod( 'show_tagline' ) && $desc = get_bloginfo( 'description' ) ) {
				$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description' ) . '>';
					$display .= $desc;
				$display .= "</{$tag_h2}>";
			} elseif ( is_customize_preview() ) {
				$hootnoshow = ( hoot_get_mod( 'show_tagline' ) ) ? '' : 'hootnoshow';
				$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description', '', $hootnoshow ) . '>' . get_bloginfo( 'description' ) . "</{$tag_h2}>";
			}

		$display .= '</div>';
	}

	return apply_filters( 'strute_get_image_logo', $display, $strute_logo, $tag_h1, $tag_h2 );
}
endif;

/**
 * Returns the custom text logo
 *
 * @since 1.0
 * @access public
 * @return string
 */
if ( !function_exists( 'strute_get_custom_text_logo' ) ):
function strute_get_custom_text_logo() {
	$title = '';
	$logo_custom = hoot_sortlist( hoot_get_mod( 'logo_custom' ) );

	if ( is_array( $logo_custom ) && !empty( $logo_custom ) ) {
		$lcount = 1;
		$title .= '<span class="customblogname">';
		foreach ( $logo_custom as $logo_custom_line ) {
			$line_class = 'site-title-line site-title-line' . $lcount;
			$line_class .= ( !empty( $logo_custom_line['font'] ) && $logo_custom_line['font'] == 'standard' ) ? ' bodyfont' : '';
			$line_class .= ( !empty( $logo_custom_line['font'] ) && $logo_custom_line['font'] == 'heading2' ) ? ' titlefont' : '';
			$line_class .= ( !empty( $logo_custom_line['accentbg'] ) ) ? ' accent-typo' : '';
			if ( is_customize_preview() ) {
				if ( empty( $logo_custom_line['text'] ) ) $logo_custom_line['text'] = __( 'Custom Line', 'strute' );
				if ( $logo_custom_line['sortitem_hide'] ) $line_class .= ' hootnoshow';
				$title .= '<span class="' . $line_class . '">' . wp_kses_decode_entities( $logo_custom_line['text'] ) . '</span>';
			} elseif ( !$logo_custom_line['sortitem_hide'] && !empty( $logo_custom_line['text'] ) ) {
				$title .= '<span class="' . $line_class . '">' . wp_kses_decode_entities( $logo_custom_line['text'] ) . '</span>';
			}

			$lcount++;
		}
		$title .= '</span>';

	}
	return apply_filters( 'strute_get_custom_text_logo', $title, $logo_custom );
}
endif;

/**
 * Display the primary menu area
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_sitehead_aside' ) ):
function strute_sitehead_aside() {
	$is_cpreview = is_customize_preview();

	$location = hoot_get_mod( 'menu_location' );
	if ( $location == 'side' && !$is_cpreview )
		return;

	$side = hoot_get_mod( 'logo_side' );
	if ( $side == 'none' && !$is_cpreview )
		return;

	?><div <?php hoot_attr( 'sitehead-aside' ); ?>><?php
		if ( $side == 'search' ) {
			get_search_form();
		} elseif ( $side == 'widget-area' ) {
			hoot_get_sidebar( 'sitehead' ); // Loads the template-parts/sidebar-sitehead.php template.
		}
	?></div><?php
}
endif;

/**
 * Display the secondary menu
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_menu' ) ):
function strute_menu() {
	$is_cpreview = is_customize_preview();

	$location = hoot_get_mod( 'menu_location' );
	if ( $location == 'none' && !$is_cpreview )
		return;

	?><div <?php hoot_attr( 'sitehead-menu' ); ?>><?php
		// Loads the template-parts/menu-primary.php template.
		hoot_get_menu( 'primary' );
		// Loads the template-parts/sidebar-menu-side.php template.
		hoot_get_sidebar( 'menu-side' ); // Loads the template-parts/sidebar-menu-side.php template.
	?></div><?php
}
endif;

/**
 * Display the sitehead extras
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_sitehead_extra_dtp' ) ):
function strute_sitehead_extra_dtp() {
	strute_sitehead_extras( 'dtp' );
}
endif;
if ( !function_exists( 'strute_sitehead_extra_mob' ) ):
function strute_sitehead_extra_mob() {
	strute_sitehead_extras( 'mob' );
}
endif;
if ( !function_exists( 'strute_sitehead_extras' ) ):
function strute_sitehead_extras( $key ) {
	if ( empty( $key ) )
		return;
	$text = '';
	if ( is_customize_preview() ) {
		$text = hoot_get_mod( "sticky_sitehead_{$key}_text" );
	} else {
		$enabled = hoot_get_mod( "sticky_sitehead_{$key}" );
		if ( $enabled ) {
			$layout = hoot_get_mod( "sticky_sitehead_{$key}_layout" );
			if ( in_array( $layout, array( 'text', 'logotext', 'logotextdiv', 'logomenutext' ) ) ) {
				$text = hoot_get_mod( "sticky_sitehead_{$key}_text" );
				$extras[ $key ] = $text;
			}
		}
	}
	if ( !empty( $text ) ) :
		?><div <?php hoot_attr( 'sitehead-extras', $key ); ?>><?php
			echo do_shortcode( wp_kses_post( wpautop( $text ) ) );
		?></div><?php
	endif;
}
endif;

/**
 * Get the top level menu items array
 *
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_nav_menu_toplevel_items' ) ):
function strute_nav_menu_toplevel_items( $theme_location = 'hoot-primary-menu' ) {
	static $location_items;
	if ( !isset( $location_items[$theme_location] ) && ($theme_locations = get_nav_menu_locations()) && isset( $theme_locations[$theme_location] ) ) {
		$menu_obj = get_term( $theme_locations[$theme_location], 'nav_menu' );
		if ( !empty( $menu_obj->term_id ) ) {
			$menu_items = wp_get_nav_menu_items($menu_obj->term_id);
			if ( $menu_items )
				foreach( $menu_items as $menu_item )
					if ( empty( $menu_item->menu_item_parent ) )
						$location_items[$theme_location][] = $menu_item;
		}
	}
	if ( !empty( $location_items[$theme_location] ) )
		return $location_items[$theme_location];
	else
		return array();
}
endif;

/**
 * Display Menu Nav Item Description
 *
 * @since 1.0
 * @param string   $title The menu item's title.
 * @param WP_Post  $item  The current menu item.
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return string
 */
if ( !function_exists( 'strute_menu_description' ) ):
function strute_menu_description( $title, $item, $args, $depth ) {
	$return = '';

	$title = '<span class="menu-title-text">' . $title . '</span>';
	if ( $depth == 0 ) {
		$hootmenu = ( isset( $item->ID ) ) ? get_post_meta( $item->ID, '_menu-item-hootmenu', true ) : array();
		if ( !empty( $hootmenu[ 'hoot_tag' ] ) ) {
			$style = '';
			$style .= ( !empty( $hootmenu[ 'hoot_tagbg' ] ) ) ? 'background:' . sanitize_hex_color( $hootmenu[ 'hoot_tagbg' ] ) . ';border-color:' . sanitize_hex_color( $hootmenu[ 'hoot_tagbg' ] ) . ';' : '';
			$style .= ( !empty( $hootmenu[ 'hoot_tagfont' ] ) ) ? 'color:' . sanitize_hex_color( $hootmenu[ 'hoot_tagfont' ] ) . ';' : '';
			$style = ( !empty( $style ) ) ? ' style="' . $style . '" ' : '';
			$title .= '<span class="menu-tag accent-typo"' . $style . '>' . esc_html( $hootmenu[ 'hoot_tag' ] ) . '</span>';
		}
	}
	$return .= '<span class="menu-title">' . $title . '</span>';
	if ( !empty( $item->description ) )
		$return .= '<span class="menu-description enforce-body-font">' . $item->description . '</span>';

	return $return;
}
endif;
add_filter( 'nav_menu_item_title', 'strute_menu_description', 5, 4 );

/**
 * Display custom-header
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_header_image' ) ):
function strute_header_image() {
	$is_cpreview = is_customize_preview();

	$image = get_header_image();
	$feature = hoot_get_mod( 'header_image_feature' );
	$title = hoot_get_mod( 'header_image_title' );
	$subtitle = hoot_get_mod( 'header_image_subtitle' );
	$text = hoot_get_mod( 'header_image_text' );
	$button1 = hoot_get_mod( 'header_image_button' );
	$url1 = hoot_get_mod( 'header_image_url' );
	$button2 = hoot_get_mod( 'header_image_button2' );
	$url2 = hoot_get_mod( 'header_image_url2' );
	$layout = hoot_get_mod( 'header_image_layout' );

	$url1 = $url1 ? $url1 : '#';
	$url2 = $url2 ? $url2 : '#';
	$layout = intval( $layout );
	$isfeaturelay = in_array( $layout, array( 8, 9, 10 ) );
	$isconlayout = in_array( $layout, array( 1, 2, 3, 4, 5, 6, 7, 9, 10 ) );

	$show = $image || ( $isfeaturelay && $feature ) || ( $isconlayout && ( $title || $subtitle || $text || $button1 || $button2 ) );
	$moduleclass = ! $show && $is_cpreview ? ' hootnoshow' : '';
	$moduleclass .= ' fpimg-' . $layout;
	$wrapclass = $isfeaturelay ? 'fpimg-featlay' : ( $isconlayout ? 'fpimg-conlay' : '' );
	$wrapclass .= $image ? ' fpimg-hasimg' : ' fpimg-noimg';
	$wrapclass .= $feature ? ' fpimg-hasfeat' : ' fpimg-nofeat';
	$wrapclass .= $title || $subtitle || $text || $button1 || $button2 ? ' fpimg-hascon' : ' fpimg-nocon';
	$wrapclass .= !$title ? ' fpimg-notitle' : '';
	$wrapclass .= !$subtitle ? ' fpimg-nosubtitle' : '';
	$wrapclass .= !$text ? ' fpimg-notext' : '';

	if ( $show || $is_cpreview ) : ?>

		<div id="frontpage-image" <?php hoot_attr( 'frontpage-area', 'image', 'frontpage-image frontpage-area frontpage-area-stretch' . $moduleclass ) ?>>

			<?php if ( $isfeaturelay ) : // fpimg as bg ?>
				<div class="fpimg-wrap fpimg-bg <?php echo hoot_sanitize_html_classes( $wrapclass ); ?>">
					<?php if ( $image ) : ?>
						<div class="fpimg-imgbox">
							<div class="fpimg" style="background-image:url('<?php echo esc_url( $image ); ?>');"></div>
						</div>
					<?php endif; ?>
					<?php strute_header_image_text_inner( $is_cpreview, $feature, $title, $subtitle, $text, $button1, $url1, $button2, $url2 ); ?>
				</div>

			<?php else: // fpimg as inline
				$minheight = hoot_get_mod( 'header_image_minheight' );
				if ( $minheight || $is_cpreview ) { $wrapclass .= ' fpimg-hasminheight'; }
				?>

				<div class="fpimg-wrap fpimg-inline <?php echo hoot_sanitize_html_classes( $wrapclass ); ?>">
					<?php if ( $image ) : ?>
						<div class="fpimg-imgbox">
							<img class="fpimg" src="<?php header_image(); ?>" width="<?php echo absint( get_custom_header()->width ); ?>" height="<?php echo absint( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
						</div>
					<?php endif; ?>
					<?php if ( $isconlayout ) { // Dont display if layout===0 i.e. no featureimg/content
						strute_header_image_text_inner( $is_cpreview, false, $title, $subtitle, $text, $button1, $url1, $button2, $url2 );
					} ?>
				</div>

			<?php endif; ?>

		</div><?php

		if ( $is_cpreview ) {
			echo '<script>jQuery(document).trigger("hootPgheadimgReinit");</script>';
		}

	endif;
}
endif;

if ( !function_exists( 'strute_header_image_text_inner' ) ):
function strute_header_image_text_inner( $is_cpreview, $feature, $title, $subtitle, $text, $button1, $url1, $button2, $url2 ) {
	$context = array( $is_cpreview, $feature, $title, $subtitle, $text, $button1, $url1, $button2, $url2 );
	?>
	<div class="fpimg-cfbox hgrid">
		<?php if ( $feature ) : ?>
			<div <?php hoot_attr( 'fpimg-feature', $context ) ?>>
				<img src="<?php echo esc_url( $feature ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
			</div>
		<?php endif; ?>
		<?php if ( $title || $subtitle || $text || $button1 || $button2 ) : ?>
			<div <?php hoot_attr( 'fpimg-cboxwrap', $context ) ?>><div class="fpimg-cbox">
				<?php
				if ( $title || $subtitle || $text ) :
					$sclass = hoot_get_mod( 'header_image_conbg_opacity' ) ? '' : 'fpimg-textbox-nobg';
					?><div class="fpimg-textbox <?php echo sanitize_html_class( $sclass ); ?>"><?php
						if ( $title ) :
							?><h1 class="fpimg-title"><?php echo do_shortcode( wp_kses_post( nl2br( $title ) ) ); ?></h1><?php
						endif;
						if ( $subtitle ) :
							?><div class="fpimg-subtitle hoot-subtitle"><?php echo do_shortcode( wp_kses_post( nl2br( $subtitle ) ) ); ?></div><?php
						endif;
						strute_header_image_text();
					?></div><?php
				endif;
				if ( $button1 || $button2 ) :
					?><div class="fpimg-btnbox"><?php
						foreach ( array(
							array( $button1, $url1 ),
							array( $button2, $url2 ),
						) as $key => $btn ) {
							if ( $btn[0] ) :
								$btnurl = $is_cpreview ? '' : $btn[1];
								?><a class="fpimg-button button <?php echo sanitize_html_class( 'fpimg-btn'.($key+1) ); ?>" href="<?php echo esc_url( $btnurl ); ?>"><?php echo esc_html( $btn[0] ); ?></a><?php
							endif;
						}
					?></div><?php
				endif;
				?>
			</div></div>
		<?php endif; ?>
	</div>
	<?php
}
endif;


if ( !function_exists( 'strute_header_image_text' ) ):
function strute_header_image_text() {
	$is_cpreview = is_customize_preview();

	$text = hoot_get_mod( 'header_image_text' );
	if ( $text || $is_cpreview ) :
		$sclass = $is_cpreview && ! $text ? ' hootnoshow' : '';
		?><div <?php hoot_attr( 'fpimg-text', '', array( 'id' => 'fpimg-text', 'classes' => $sclass ) ) ?>><?php echo do_shortcode( wp_kses_post( wpautop( $text ) ) ); ?></div><?php
	endif;
}
endif;

/**
 * Display title area content
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_add_custom_title_content' ) ):
function strute_add_custom_title_content( $location = 'pre', $context = '' ) {

	$pre_title_content_post = apply_filters( 'strute_pre_title_content_post', '', $location, $context );
	if ( ( $location == 'pre' && !$pre_title_content_post ) ||
		 ( $location == 'post' && $pre_title_content_post ) ) : 

		$pre_title_content = apply_filters( 'strute_pre_title_content', '', $location, $context );
		if ( !empty( $pre_title_content ) ) :

			$pre_title_content_stretch =  apply_filters( 'strute_pre_title_content_stretch', '', $location, $context ); ?>
			<div id="custom-content-title-area" class="<?php
				echo sanitize_html_class( $location . '-content-title-area' );
				echo ( ($pre_title_content_stretch) ? ' content-title-area-stretch' : ' content-title-area-grid' );
				?>">
				<div class="<?php echo ( ($pre_title_content_stretch) ? 'hgrid-stretch' : 'hgrid' ); ?>">
					<div class="hgrid-span-12">
						<?php echo wp_kses_post( do_shortcode( $pre_title_content ) ); ?>
					</div>
				</div>
			</div>
			<?php

		endif;

	endif;
}
endif;

/**
 * Display 404 content
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_display_404_content' ) ):
function strute_display_404_content() {
	echo esc_html( __( 'Apologies, but no entries were found.', 'strute' ) );
}
endif;
add_action( 'strute_404_content', 'strute_display_404_content', 5 );

/**
 * Utility function to map footer sidebars structure to CSS span architecture.
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_footer_structure' ) ):
function strute_footer_structure() {
	$footers = hoot_get_mod( 'footer' );
	$structure = array(
				'1-1' => array( 12, 12, 12, 12 ),
				'2-1' => array(  6,  6, 12, 12 ),
				'2-2' => array(  4,  8, 12, 12 ),
				'2-3' => array(  8,  4, 12, 12 ),
				'3-1' => array(  4,  4,  4, 12 ),
				'3-2' => array(  6,  3,  3, 12 ),
				'3-3' => array(  3,  6,  3, 12 ),
				'3-4' => array(  3,  3,  6, 12 ),
				'4-1' => array(  3,  3,  3,  3 ),
				);
	if ( isset( $structure[ $footers ] ) )
		return $structure[ $footers ];
	else
		return array( 12, 12, 12, 12 );
}
endif;

/**
 * Get footer column option.
 *
 * @since 1.0
 * @access public
 * @return int
 */
function strute_get_footer_columns() {
	$footers = hoot_get_mod( 'footer' );
	$columns = ( $footers ) ? intval( substr( $footers, 0, 1 ) ) : false;
	$columns = ( is_numeric( $columns ) && 0 < $columns ) ? $columns : false;
	return $columns;
}

/**
 * Utility function to map 2 column widths to CSS span architecture.
 *
 * @since 1.0
 * @access public
 * @return array
 */
if ( !function_exists( 'strute_get_column_span' ) ):
function strute_get_column_span( $col_width ) {
	$return = array();
	switch( $col_width ):
		case '100':
			$return[0] = 'hgrid-span-12';
			break;
		case '50-50': default:
			$return[0] = 'hgrid-span-6';
			$return[1] = 'hgrid-span-6';
			break;
		case '33-66':
			$return[0] = 'hgrid-span-4';
			$return[1] = 'hgrid-span-8';
			break;
		case '66-33':
			$return[0] = 'hgrid-span-8';
			$return[1] = 'hgrid-span-4';
			break;
		case '25-75':
			$return[0] = 'hgrid-span-3';
			$return[1] = 'hgrid-span-9';
			break;
		case '75-25':
			$return[0] = 'hgrid-span-9';
			$return[1] = 'hgrid-span-3';
			break;
		case '33-33-33':
			$return[0] = 'hgrid-span-4';
			$return[1] = 'hgrid-span-4';
			$return[2] = 'hgrid-span-4';
			break;
		case '25-25-50':
			$return[0] = 'hgrid-span-3';
			$return[1] = 'hgrid-span-3';
			$return[2] = 'hgrid-span-6';
			break;
		case '25-50-25':
			$return[0] = 'hgrid-span-3';
			$return[1] = 'hgrid-span-6';
			$return[2] = 'hgrid-span-3';
			break;
		case '50-25-25':
			$return[0] = 'hgrid-span-6';
			$return[1] = 'hgrid-span-3';
			$return[2] = 'hgrid-span-3';
			break;
		case '25-25-25-25':
			$return[0] = 'hgrid-span-3';
			$return[1] = 'hgrid-span-3';
			$return[2] = 'hgrid-span-3';
			$return[3] = 'hgrid-span-3';
			break;
	endswitch;
	return $return;
}
endif;

/**
 * Wrapper function for strute_layout() to get the class names for current context.
 * Can only be used after 'posts_selection' action hook i.e. in 'wp' hook or later.
 *
 * @since 1.0
 * @access public
 * @param string $context content|primary-sidebar|sidebar|sidebar-primary
 * @return string
 */
if ( !function_exists( 'strute_layout_class' ) ):
function strute_layout_class( $context ) {
	return strute_layout( $context, 'class' );
}
endif;

/**
 * Utility function to return layout size or classes for the context.
 * Can only be used after 'posts_selection' action hook i.e. in 'wp' hook or later.
 *
 * @since 1.0
 * @access public
 * @param string $context content|primary-sidebar|sidebar|sidebar-primary
 * @param string $return  class|size return class name or just the span size integer
 * @return string
 */
if ( !function_exists( 'strute_layout' ) ):
function strute_layout( $context, $return = 'size' ) {

	// Set layout if not already set
	$layout = hoot_data( 'currentlayout' );
	if ( empty( $layout ) )
		strute_set_layout();

	// Get layout
	$layout = hoot_data( 'currentlayout' );
	$span_sidebar = $layout['sidebar'];
	$span_content = $layout['content'];
	$layout_class = ' layout-' . $layout['layout'];

	// Return Class or Span Size for the Content/Sidebar
	if ( $context == 'content' ) {

		if ( $return == 'class' ) {
			$extra_class = ( empty( $span_sidebar ) ) ? ' no-sidebar' : ' has-sidebar';
			return ' hgrid-span-' . $span_content . $extra_class . $layout_class . ' ';
		} elseif ( $return == 'size' ) {
			return intval( $span_content );
		}

	} elseif ( $context == 'sidebar' ||  $context == 'sidebar-primary' || $context == 'primary-sidebar' || $context == 'secondary-sidebar' || $context == 'sidebar-secondary' ) {

		if ( $return == 'class' ) {
			if ( !empty( $span_sidebar ) )
				return ' hgrid-span-' . $span_sidebar . $layout_class . ' ';
			else
				return '';
		} elseif ( $return == 'size' ) {
			return intval( $span_sidebar );
		}

	}

	return '';

}
endif;

/**
 * Utility function to calculate and set main (content+aside) layout according to the sidebar layout
 * set by user for the current view.
 * Can only be used after 'posts_selection' action hook i.e. in 'wp' hook or later.
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'strute_set_layout' ) ):
function strute_set_layout() {

	// Apply Sidebar Layout for front page
	if ( is_front_page() ) {
		$sidebar = hoot_get_mod( 'sidebar_fp' );
	}
	// Check for is_home after front_page to skip blog set as frontpage
	// Apply Sidebar layout for archives and blog
	elseif ( is_archive() || is_home() ) {
		$sidebar = hoot_get_mod( 'sidebar_archives' );
	}
	// Apply Sidebar Layout for Posts
	elseif ( is_singular( 'post' ) ) {
		$sidebar = hoot_get_mod( 'sidebar_posts' );
	}
	// Check for attachment before page (to handle images attached to a page - true for is_page and is_attachment)
	// Apply 'Full Width'
	elseif ( is_attachment() ) {
		$sidebar = 'none';
	}
	// Apply Sidebar Layout for Pages
	elseif ( is_page() ) {
		$sidebar = hoot_get_mod( 'sidebar_pages' );
	}
	// Apply No Sidebar Layout for 404
	elseif ( is_404() ) {
		$sidebar = 'none';
	}
	// Apply Sidebar Layout for Site
	else {
		$sidebar = hoot_get_mod( 'sidebar' );
	}

	// Allow for custom manipulation of the layout by child themes
	$sidebar = esc_attr( apply_filters( 'strute_layout', $sidebar ) );

	// Save the layout for current view
	strute_set_layout_span( $sidebar );

}
endif;

/**
 * Utility function to calculate and set main (content+aside) layout according to the sidebar layout
 * set by user for the current view.
 * Can only be used after 'posts_selection' action hook i.e. in 'wp' hook or later.
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'strute_set_layout_span' ) ):
function strute_set_layout_span( $sidebar ) {
	$spans = apply_filters( 'strute_layout_spans', array(
		'none' => array(
			'content' => 9,
			'sidebar' => 0,
		),
		'full' => array(
			'content' => 12,
			'sidebar' => 0,
		),
		'full-width' => array(
			'content' => 12,
			'sidebar' => 0,
		),
		'narrow-right' => array(
			'content' => 9,
			'sidebar' => 3,
		),
		'wide-right' => array(
			'content' => 8,
			'sidebar' => 4,
		),
		'narrow-left' => array(
			'content' => 9,
			'sidebar' => 3,
		),
		'wide-left' => array(
			'content' => 8,
			'sidebar' => 4,
		),
		'narrow-left-left' => array(
			'content' => 6,
			'sidebar' => 3,
		),
		'narrow-left-right' => array(
			'content' => 6,
			'sidebar' => 3,
		),
		'narrow-right-left' => array(
			'content' => 6,
			'sidebar' => 3,
		),
		'narrow-right-right' => array(
			'content' => 6,
			'sidebar' => 3,
		),
		'default' => array(
			'content' => 8,
			'sidebar' => 4,
		),
	) );

	/* Set the layout for current view */
	$currentlayout['layout'] = $sidebar;
	if ( isset( $spans[ $sidebar ] ) ) {
		$currentlayout['content'] = $spans[ $sidebar ]['content'];
		$currentlayout['sidebar'] = $spans[ $sidebar ]['sidebar'];
	} else {
		$currentlayout['content'] = $spans['default']['content'];
		$currentlayout['sidebar'] = $spans['default']['sidebar'];
	}
	hoot_set_data( 'currentlayout', $currentlayout );

}
endif;

/**
 * Filter default content size for calculating image thumbnail size
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'strute_thumbnail_size_contentwidth' ) ):
function strute_thumbnail_size_contentwidth() {
	return 'span-' . strute_layout( 'content' );
}
endif;
add_filter( 'hoot_thumbnail_size_contentwidth', 'strute_thumbnail_size_contentwidth' );

/**
 * Useful for live preview in customizer via transport=>postMessage
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_frontpagearea_fontstyle_customizer' ) ):
function strute_frontpagearea_fontstyle_customizer() {
	$sections = hoot_sortlist( hoot_get_mod( 'frontpage_sections' ) );
	if ( is_array( $sections ) && !empty( $sections ) ) { foreach ( $sections as $key => $section ) {
		$id = ( $key == 'content' ) ? 'frontpage-page-content' : sanitize_html_class( 'frontpage-' . $key );
		$type = hoot_get_mod( "frontpage_sectionbg_{$key}-font" );
		switch ($type) {
			case 'color': $selector = hoot_fp_customfontcolor_selector( false, $id ); break;
			case 'force': $selector = hoot_fp_customfontcolor_selector( true, $id ); break;
			default: $selector = ''; break;
		}
		$css = '';
		if ( $selector ) {
			$css = $selector . '{color:' . hoot_get_mod( "frontpage_sectionbg_{$key}-fontcolor" ) . ';}';
		}
		echo '<style id="hoot-customize-'.$id.'" type="text/css">'.$css.'</style>';
	} }
}
endif;
if ( is_customize_preview() ) add_action( 'wp_footer', 'strute_frontpagearea_fontstyle_customizer', 5 );

/**
 * Utility function to determine the location of page header
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'strute_titlearea_top' ) ):
function strute_titlearea_top() {

	/* Override For Full Width Pages (including 404 page) */
	if ( hoot_getchecked( 'page_header_full', 'no-sidebar' ) ) {
		$sidebar_size = strute_layout( 'primary-sidebar' );
		if ( empty( $sidebar_size ) )
			return apply_filters( 'strute_titlearea_top', true, 'no-sidebar' );
	}

	/* For Posts */
	if ( is_singular( 'post' ) ) {
		if ( hoot_getchecked( 'page_header_full', 'posts' ) )
			return apply_filters( 'strute_titlearea_top', true, 'posts' );
		else
			return apply_filters( 'strute_titlearea_top', false, 'posts' );
	}

	/* For Pages */
	if ( is_page() ) {
		if ( hoot_getchecked( 'page_header_full', 'pages' ) )
			return apply_filters( 'strute_titlearea_top', true, 'pages' );
		else
			return apply_filters( 'strute_titlearea_top', false, 'pages' );
	}

	/* Default */
	if ( hoot_getchecked( 'page_header_full', 'default' ) )
		return apply_filters( 'strute_titlearea_top', true, 'default' );
	else
		return apply_filters( 'strute_titlearea_top', false, 'default' );

}
endif;

/**
 * Utility function to get featured image display
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'strute_featured_image_location' ) ):
function strute_featured_image_location( $context, $returnarray = false ) {
	/* Get location */
	$location = '';
	if ( $context === 'post' ) {
		$location = hoot_get_mod( 'post_featured_image' );
	} elseif ( $context === 'page' ) {
		$location = hoot_get_mod( 'post_featured_image_page' );
	} elseif ( is_home() || is_archive() || is_search() ) {
		$context = 'archive';
		$location = hoot_get_mod( 'archive_featured_image' );
	} else {
		$context = 'page';
		$location = hoot_get_mod( 'post_featured_image_page' );
	}
	$location = apply_filters( 'strute_featured_image_location', $location, $context );
	return $returnarray ? array( $location, $context ) : $location;
}
endif;

/**
 * Utility function to display featured image in loop meta header
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'strute_pagehead_img' ) ):
function strute_pagehead_img( $context, $display ) {

	/* Get location */
	$locarray = strute_featured_image_location( $context, true );
	$location = $locarray[0];
	$context  = $locarray[1];

	if ( ! in_array( $location, array( 'header', 'staticheader', 'staticheader-nocrop' ) ) || ! in_array( $context, array( 'post', 'page', 'archive' ) ) )
		return;

	/* Get the correct image */
	$view_id = $img_id = 0;
	$taxonomies = apply_filters( 'hoot_taxonomy_field_taxonomies', array('category','post_tag') );
	if ( is_singular() ) {
		$view_id = null;
	} elseif ( is_home() && !is_front_page() ) {
		$view_id = get_option( 'page_for_posts' );
	} elseif (
		( in_array( 'category', $taxonomies ) && is_category() ) ||
		( in_array( 'post_tag', $taxonomies ) && is_tag() ) ||
		is_tax( $taxonomies )
	) {
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		$img_id = hoot_term_image_id( $cat->term_id );
	} elseif ( current_theme_supports( 'woocommerce' ) ) {
		if ( is_shop() ) {
			$view_id = get_option( 'woocommerce_shop_page_id' );
		} elseif ( is_product_category() ) {
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$img_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
		}
	}
	$img_id = ( $view_id !== 0 && has_post_thumbnail( $view_id ) ) ? get_post_thumbnail_id( $view_id ) : $img_id;
	$img_id = apply_filters( 'strute_pagehead_img_id', $img_id, $context, $location, $view_id );
	$img_id = absint( $img_id );
	$img_src = $img_id ? wp_get_attachment_image_src( $img_id, apply_filters( "strute_{$context}_imgsize", 'full', 'header' ) ) : 0;

	/* Display/Set the image */
	if ( is_array( $img_src ) && !empty( $img_src[0] ) ) {
		$wrapclasses = 'pgheadimg-wrap pgheadimg-' . $context;
		$image = '';
		$imageinline = '';
		$imagebgclass = '';
		$imagebgstyle = '';
		if ( $location == 'staticheader-nocrop' ) {
			$wrapclasses .= ' pgheadimg-inline';
			$imageinline = $img_src[0];
		}
		elseif ( $location == 'staticheader' || $location == 'header' ) {
			$wrapclasses .= ' pgheadimg-bg';
			$imagebgstyle = 'background-image:url(' . esc_url( $img_src[0] ) . ')';
			$imagebgclass = $location == 'header' ? 'bg-parallax' : 'bg-noparallax';
		}
		$image = $imageinline
				? '<img ' . hoot_get_attr( 'pgheadimg', '', array( 'src' => esc_url( $imageinline ) ) ) . '>'
				: ( $imagebgstyle
				? '<div ' . hoot_get_attr( 'pgheadimg', '', array( 'classes' => $imagebgclass, 'style' => $imagebgstyle ) ) . '></div>'
				: '' );
		if ( is_customize_preview() ) {
			$image .= $imageinline
				? '<div ' . hoot_get_attr( 'pgheadimg', '', array( 'classes' => 'hootnoshow bg-parallax bg-noparallax', 'style' => 'background-image:url(' . esc_url( $img_src[0] ) . ')' ) ) . '></div>'
				: '<img ' . hoot_get_attr( 'pgheadimg', '', array( 'classes' => 'hootnoshow', 'src' => esc_url( $img_src[0] ) ) ) . '>';
		}
		if ( $display ) {
			echo '<div ' . hoot_get_attr( 'pgheadimg-wrap', '', array( 'class' => $wrapclasses ) ) . '>' . $image . '</div>';
		} else {
			hoot_set_data( 'pgheadimg', array( $wrapclasses, $image ) );
		}
	}

}
endif;

/**
 * Do not display gravatar image if none exists
 * (hook into 'get_avatar' filter)
 * @credit https://stackoverflow.com/questions/34007075/how-to-show-avatar-only-if-it-exists
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_ns_filter_avatar' ) ):
function strute_ns_filter_avatar( $avatar, $id_or_email, $size, $default, $alt, $args ) {
	$headers = @get_headers( $args['url'] );
	if( ! is_array( $headers ) || empty( $headers ) || ! preg_match( "|200|", $headers[0] ) ) return;
	return $avatar;
}
endif;

/**
 * Display the Prev/Next Post in loop-nav for single post
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_post_prev_next_links' ) ):
function strute_post_prev_next_links() {
	$styles = array( 'text', 'thumb', 'fixed-text', 'fixed-thumb' );
	$style = hoot_get_mod( 'post_prev_next_links' );

	if ( in_array( $style, $styles ) ) {
		$isfixed = $style === 'fixed-text' || $style === 'fixed-thumb';
		$isthumb = $style === 'thumb' || $style === 'fixed-thumb';

		$wrapclass = 'loop-nav';
		$wrapclass .= ( $isfixed ) ? ' loop-nav-fixed' : ' loop-nav-inline';
		$wrapclass .= ( $isthumb ) ? ' loop-nav-thumb' : ' loop-nav-text';
		?><div <?php hoot_attr( 'loop-nav', '', array( 'id' => 'loop-nav-wrap', 'classes' => $wrapclass ) ); ?>><?php
			$loop = array( 'prev', 'next' );
			foreach ( $loop as $key ) {
				$adjacent = $key === 'prev' ? get_previous_post() : get_next_post();
				$label = $key === 'prev' ? __( 'Previous Post:', 'strute' ) : __( 'Next Post:', 'strute' );
				if ( $adjacent ) {
					$posturl = get_permalink( $adjacent );
					echo '<div class="' . $key . ' loop-nav-unit">';
						if ( $isfixed ) echo '<div class="loop-nav-unitctrl fas"></div>';
						if ( $isfixed ) echo '<a class="loop-nav-unitlink" href="' . esc_url( $posturl ) . '"></a>';
						if ( $isthumb ) {
							$thumbnail = get_the_post_thumbnail($adjacent->ID, 'thumbnail');
							if ( $thumbnail ) {
								$bg = $isfixed ? get_the_post_thumbnail_url($adjacent->ID, 'medium') : false;
								echo '<div class="loop-nav-image"' . ( $bg ? ' style="background-image:url(' . esc_url( $bg ) . ')"' : '' ) . '><a href="' . esc_url( $posturl ) . '">' . $thumbnail . '</a></div>';
							}
						}
						echo '<div class="loop-nav-link">';
							echo '<span class="loop-nav-label">' . esc_html( $label ) . '</span><a href="' . esc_url( $posturl ) . '">' . esc_html( $adjacent->post_title ) . '</a>';
						echo '</div>';
					echo '</div>';
				}
			}
		?></div><!-- .loop-nav --><?php

	} elseif( is_customize_preview() ) {
		?><div id="loop-nav-wrap" class="loop-nav"></div><?php
	}

}
endif;

/**
 * Display function to render posts for Jetpack's infinite scroll module
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'strute_jetpack_infinitescroll_render' ) ):
function strute_jetpack_infinitescroll_render(){
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		wc_get_template_part( 'content', 'product' );
	} else {
		while ( have_posts() ) : the_post();
			// Loads the template-parts/content-{$post_type}.php template.
			hoot_get_content_template();
		endwhile;
	}
}
endif;