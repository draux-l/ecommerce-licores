<?php
/**
 * Defines customizer options
 *
 * This file is loaded at 'after_setup_theme' hook with 10 priority.
 */

/**
 * Theme default colors and fonts
 *
 * @since 1.0
 * @access public
 * @param string $key return a specific key value, else the entire defaults array
 * @return array|string
 */
if ( !function_exists( 'strute_default_style' ) ) :
function strute_default_style( $key = false ){
	// Used by customizer-options.php, as well as customize-preview.js
	$defaults = apply_filters( 'strute_default_style', array(
		'accent_color'               => '#ff8811',
		'accent_font'                => '#ffffff',
		'button_color'               => '#ff8811',
		'button_font'                => '#ffffff',
		'module_bg_default'          => '#f0f0f0',
		'module_fontcolor_default'   => '#333333',
		'article_background_type'    => 'background-whensidebar',
		'article_background_color'   => '#f8f8f8',
		'box_background'             => '#ffffff',
		'site_background'            => '#010101',
		'sidebar_width_px'           => 350,
		'sidebar_width_pcnt'         => 25,
		'goto_top_offset'            => array( 'desktop' => 60, 'tablet' => 30, 'mobile' => 10 ),
		'widgetmargin'               => array( 'desktop' => 50, 'tablet' => 40, 'mobile' => 30 ),
		'topann_content_bg'          => '#f8f8f8',
		'site_title_icon_size'       => 50,
		'logo_image_width'           => array( 'desktop' => 350, 'tablet' => 300, 'mobile' => 150 ),
		'logo_custom_line_font'      => 45,
		'logo_fontface'              => 'fontpt',
		'logo_fontface_style'        => 'uppercase',
		'headings_fontface'          => 'fontns',
		'headings_fontface_style'    => 'standard',
		'subheadings_fontface'       => 'fontpt',
		'subheadings_fontface_style' => 'standardi',
		'body_fontface'              => 'fontns',
		'article_maxwidth'           => 800,
		'article_maxwidth_nosidebar' => 1400,
	) );

	if ( $key )
		return ( isset( $defaults[ $key ] ) ) ? $defaults[ $key ] : false;
	else
		return $defaults;
}
endif;

/**
 * Build the Customizer options (panels, sections, settings)
 *
 * Always remember to mention specific priority for non-static options like:
 *     - options being added based on a condition (eg: if woocommerce is active)
 *     - options which may get removed (eg: logo_size, headings_fontface)
 *     - options which may get rearranged (eg: logo_background_type, box_background_color)
 *     This will allow other options inserted with priority to be inserted at
 *     their intended place.
 *
 * @since 1.0
 * @access public
 * @return array
 */
if ( !function_exists( 'strute_customizer_options' ) ) :
function strute_customizer_options() {

	// Stores all the settings to be added
	$settings = array();

	// Stores all the sections to be added
	$sections = array();

	// Stores all the panels to be added
	$panels = array();

	// Theme default colors and fonts
	extract( strute_default_style() );

	// Directory path for radioimage buttons
	$imagepath =  hoot_data()->incuri . 'admin/images/';

	// Logo Font Options for Lite version
	$logofont = apply_filters( 'strute_options_logofont', array(
					'heading'  => esc_html__( "Logo Font (set in 'Typography' section)", 'strute' ),
					'heading2' => esc_html__( "Heading Font (set in 'Typography' section)", 'strute' ),
					'standard' => esc_html__( "Standard Body Font", 'strute' ),
					) );
	$fontfaces = hoot_themefonts('options');

	/*** Add Options (Panels, Sections, Settings) ***/

	/** Section **/

	$section = 'links';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Demo Install / Support', 'strute' ),
		'priority'    => '2',
	);

	$lcontent = array();
	$lcontent['demo'] = '<a class="hoot-cust-link" href="' .
				 'https://demo.wphoot.com/strute/' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-eye"></i> ' .
				 esc_html__( "Demo", 'strute') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Demo the theme features and options with sample content.", 'strute') .
				 '</span></a>';
	$himplink = class_exists( 'HootImport' ) ? esc_url( admin_url( 'themes.php?page=hoot-import' ) ) : ( function_exists( 'strute_abouttag' ) ? esc_url( admin_url( 'themes.php?page=' . strute_abouttag( 'slug' ) . '-welcome&tab=plugins' ) ) : 'https://wphoot.com/support/strute/#docs-section-demo-content' );
	$lcontent['install'] = '<a class="hoot-cust-link" href="' .
				 esc_url( $himplink ) .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-upload"></i> ' .
				 esc_html__( "1 Click Demo Content Import", 'strute') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Install demo content to make your site look exactly like the Demo Site. Use it as a starting point instead of starting from scratch.", 'strute') .
				 '</span></a>';
	$lcontent['support'] = '<a class="hoot-cust-link" href="' .
				 ( function_exists( 'strute_abouttag' ) ? esc_url( admin_url( 'themes.php?page=' . strute_abouttag( 'slug' ) . '-welcome&tab=qstart' ) ) : 'https://wphoot.com/support/' ) .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="far fa-life-ring"></i> ' .
				 esc_html__( "Documentation / Support", 'strute') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Get theme related support for both free and premium users.", 'strute') .
				 '</span></a>';
	$lcontent['rateus'] = '<a class="hoot-cust-link" href="' .
				 'https://wordpress.org/support/theme/strute/reviews/#new-post' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-star"></i> ' .
				 esc_html__( "Rate Us", 'strute') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 /* Translators: five stars */
				 sprintf( esc_html__( 'If you are happy with the theme, please give us a %1$s rating on WordPress.org. Thanks in advance!', 'strute'), '<span style="color:#0073aa;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ) .
				 '</span></a>';

	$settings['linksection'] = array(
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '10', // Non static options must have a priority
		'content'     => implode( ' ', apply_filters( 'strute_customizer_option_linksection', $lcontent ) ),
	);

	/** Section **/

	$section = 'hootshead-su';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Setup', 'strute' ),
		'priority'    => '5',
	);

	$settings['su_settings'] = array(
		'section'     => $section,
		'type'        => 'content',
		'content'     => '',
		'priority'    => '1111',
	);

	/** Section **/

	/** Section **/

	$section = 'logo';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Logo', 'strute' ),
		'priority'    => '10',
	);

	$settings['logo_background_type'] = array(
		'label'       => esc_html__( 'Logo Background', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'priority'    => '20', // Non static options must have a priority
		'choices'     => array(
			'transparent'   => esc_html__( 'None', 'strute' ),
			'accent'        => esc_html__( 'Accent Background', 'strute' ),
			'invert-accent' => esc_html__( 'Invert Accent Background', 'strute' ), // Implemented for possible child themes;
		),
		'default'     => 'transparent',
		'transport' => 'postMessage',
	);
	if ( !apply_filters( 'logo_background_type_invert_accent', false ) ) unset( $settings['logo_background_type']['choices']['invert-accent'] );

	$settings['logo_border'] = array(
		'label'       => esc_html__( 'Logo Border', 'strute' ),
		'sublabel'    => esc_html__( 'Display a border around logo.', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'default'     => 'none',
		'priority'    => '30',
		'choices'     => array(
			'none'        => esc_html__( 'None', 'strute' ),
			'border'      => esc_html__( 'Border (With padding)', 'strute' ),
			'bordernopad' => esc_html__( 'Border (No padding)', 'strute' ),
		),
		'transport' => 'postMessage',
	);

	$settings['show_tagline'] = array(
		'label'           => esc_html__( 'Show Tagline', 'strute' ),
		/* Translators: 1 is the link start markup, 2 is link markup end */
		'sublabel'        => sprintf( esc_html__( 'Display %1$sSite Description%2$s as tagline below logo.', 'strute' ), '<a href="' . esc_url( admin_url('options-general.php') ) . '" data-cust-linksection="title_tagline" target="_blank">', '</a>' ),
		'section'         => $section,
		'type'            => 'bettertoggle',
		'default'         => 1,
		'priority'    => '40',
		'transport' => 'postMessage',
	);

	$settings['logo'] = array(
		'label'       => esc_html__( 'Site Logo', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'text'        => esc_html__( 'Default Text (Site Title)', 'strute' ),
			'custom'      => esc_html__( 'Custom Text', 'strute' ),
			'image'       => esc_html__( 'Image Logo', 'strute' ),
			'mixed'       => esc_html__( 'Image &amp; Default Text (Site Title)', 'strute' ),
			'mixedcustom' => esc_html__( 'Image &amp; Custom Text', 'strute' ),
		),
		'default'     => 'text',
		/* Translators: 1 is the link start markup, 2 is link markup end */
		'description' => sprintf( esc_html__( 'Use %1$sSite Title%2$s as default text logo', 'strute' ), '<a href="' . esc_url( admin_url('options-general.php') ) . '" data-cust-linksection="title_tagline" target="_blank">', '</a>' ),
		'priority'    => '50',
		'selective_refresh' => array( 'logo_partial', array(
			'selector'            => '#branding',
			'settings'            => array( 'logo', 'custom_logo' ),
			'primary_setting'     => 'logo', // Redundant as 'logo' is first ID in settings array
			'render_callback'     => 'strute_branding',
			'container_inclusive' => true,
			) ),

	);

	$settings['site_title_icon'] = array(
		'label'           => esc_html__( 'Site Title Icon (Optional)', 'strute' ),
		'section'         => $section,
		'type'            => 'icon',
		'description'     => esc_html__( 'Leave empty to hide icon.', 'strute' ),
		'priority'    => '60',
		'active_callback' => 'strute_callback_site_title_icon',
		'transport' => 'postMessage',
	);

	$settings['site_title_icon_size'] = array(
		'label'           => esc_html__( 'Site Title Icon Size', 'strute' ),
		'section'         => $section,
		'type'            => 'betterrange',
		'displaysuffix'   => 'px',
		'default'         => $site_title_icon_size,
		'showreset'       => $site_title_icon_size,
		'input_attrs'     => array(
			'min'  => 10,
			'max'  => 350,
			'step' => 1,
		),
		'priority'    => '70',
		'active_callback' => 'strute_callback_site_title_icon',
		'transport' => 'postMessage',
	);

	$settings['logo_image_width'] = array(
		'label'           => esc_html__( 'Maximum Image Width', 'strute' ),
		'section'         => $section,
		'type'            => 'betterrange',
		'mediaquery'      => true,
		'displaysuffix'   => 'px',
		'input_attrs' => array(
			'min'  => 50,
			'max'  => 1380,
			'step' => 10,
		),
		'priority'        => '80', // Keep it with custom_logo->priority logo
		'default'         => $logo_image_width,
		'showreset'       => $logo_image_width,
		/* Translators: Line break */
		'description'     => sprintf( esc_html__( '(in pixels)%1$sThe logo width may be automatically adjusted by the browser depending on title length and space available.', 'strute' ), '<hr>' ),
		'active_callback' => 'strute_callback_logo_image_width',
		'transport' => 'postMessage',
	);

	$logo_custom_line_options = array(
		'text' => array(
			'label'       => esc_html__( 'Line Text', 'strute' ),
			'type'        => 'text',
		),
		'font' => array(
			'label'       => esc_html__( 'Line Font', 'strute' ),
			'type'        => 'select',
			'choices'     => $logofont,
			'default'     => 'heading',
		),
		'size' => array(
			'label'       => esc_html__( 'Line Font Size', 'strute' ),
			'type'        => 'betterrange',
			'displaysuffix' => 'px',
			'default'     => $logo_custom_line_font,
			'input_attrs' => array(
				'min'  => 10,
				'max'  => 200,
				'step' => 1,
			),
		),
	);

	$settings['logo_custom'] = array(
		'label'           => esc_html__( 'Custom Logo Text', 'strute' ),
		'section'         => $section,
		'type'            => 'sortlist',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description'     => sprintf( esc_html__( 'Use &lt;b&gt; and &lt;em&gt; tags in "Line Text" fields below to emphasize different words. Example:%1$s%2$s&lt;em&gt; wpHoot &lt;/em&gt;%3$s%1$s%2$s&lt;b&gt; Strute &lt;/b&gt;%3$s', 'strute' ), '<hr>', '<code>', '</code>' ),
		'choices'         => array(
			'line1' => esc_html__( 'Line 1', 'strute' ),
			'line2' => esc_html__( 'Line 2', 'strute' ),
			'line3' => esc_html__( 'Line 3', 'strute' ),
			'line4' => esc_html__( 'Line 4', 'strute' ),
		),
		'default'     => array(
			'line1'  => array( 'text' => wp_kses_post( __( '<em>wpHoot</em>', 'strute' ) ), 'size' => 20, 'font' => 'standard' ),
			'line2'  => array( 'text' => wp_kses_post( __( 'Hoot <b>Strute</b>', 'strute' ) ), 'size' => 50 ),
			'line3'  => array( 'sortitem_hide' => 1, 'font' => 'standard' ),
			'line4'  => array( 'sortitem_hide' => 1, ),
		),
		'options'         => array(
			'line1' => $logo_custom_line_options,
			'line2' => $logo_custom_line_options,
			'line3' => $logo_custom_line_options,
			'line4' => $logo_custom_line_options,
		),
		'attributes'      => array(
			'hideable'   => true,
			'sortable'   => false,
		),
		'priority'    => '90',
		'active_callback' => 'strute_callback_logo_custom',
		'transport' => 'postMessage',
	);

	/** Section **/

	$section = 'layout';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Site Layout', 'strute' ),
		'priority'    => '15',
	);

	$settings['site_layout_headline'] = array(
		'label'       => esc_html__( 'Site Layout', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '100',
	);

	$settings['site_layout'] = array(
		'label'       => esc_html__( 'Site Layout - Boxed vs Stretched', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'boxed'   => esc_html__( 'Boxed layout', 'strute' ),
			'stretch' => esc_html__( 'Stretched layout (full width)', 'strute' ),
		),
		'default'     => 'stretch',
		'priority'    => '110',
		'transport' => 'postMessage',
	);

	$settings['sidebar_headline'] = array(
		'label'       => esc_html__( 'Sidebars', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '120',
	);

	$settings['disable_sticky_sidebar'] = array(
		'label'       => esc_html__( 'Enable Sticky Sidebar', 'strute' ),
		'section'     => $section,
		'type'        => 'bettertoggle',
		'inverttoggle'=> true,
		'description' => esc_html__( 'Check this to display a fixed Sidebar when the user scrolls down the page.', 'strute' ),
		'priority'    => '130',
	);

	$settings['sidebar_tabs'] = array(
		'section'     => $section,
		'type'        => 'tabs',
		'priority'    => '140',
		'options'     => array(
			'width' => array(
				'sidebar1_width' => array(
					'label'       => esc_html__( 'Primary Sidebar', 'strute' ),
					'type'        => 'select',
					'choices'     => array(
						'auto' => esc_html__( 'auto', 'strute'),
						'px'   => esc_html__( 'pixels', 'strute'),
						'pcnt' => esc_html__( '%age', 'strute'),
					),
					'default'     => 'auto',
					'transport' => 'postMessage',
				),
				'sidebar1_width_px' => array(
					'type'        => 'betterrange',
					'displaysuffix' => 'px',
					'input_attrs' => array(
						'min'  => 100,
						'max'  => 600,
						'step' => 5,
					),
					'default'     => $sidebar_width_px,
					'showreset'   => $sidebar_width_px,
					'transport' => 'postMessage',
					'active_callback' => 'strute_callback_sb1w_px',
				),
				'sidebar1_width_pcnt' => array(
					'type'        => 'betterrange',
					'displaysuffix' => '%',
					'input_attrs' => array(
						'min'  => 20,
						'max'  => 75,
						'step' => 1,
					),
					'default'     => $sidebar_width_pcnt,
					'showreset'   => $sidebar_width_pcnt,
					'transport' => 'postMessage',
					'active_callback' => 'strute_callback_sb1w_pcnt',
				),
				'sidebar2_width' => array(
					'label'       => esc_html__( 'Secondary Sidebar', 'strute' ),
					// 'description' => esc_html__( '(for 2 column layouts)', 'strute' ),
					'type'        => 'select',
					'choices'     => array(
						'auto' => esc_html__( 'auto', 'strute'),
						'px'   => esc_html__( 'pixels', 'strute'),
						'pcnt' => esc_html__( '%age', 'strute'),
					),
					'default'     => 'auto',
					'transport' => 'postMessage',
				),
				'sidebar2_width_px' => array(
					'type'        => 'betterrange',
					'displaysuffix' => 'px',
					'input_attrs' => array(
						'min'  => 100,
						'max'  => 600,
						'step' => 5,
					),
					'default'     => $sidebar_width_px,
					'showreset'   => $sidebar_width_px,
					'transport' => 'postMessage',
					'active_callback' => 'strute_callback_sb2w_px',
				),
				'sidebar2_width_pcnt' => array(
					'type'        => 'betterrange',
					'displaysuffix' => '%',
					'input_attrs' => array(
						'min'  => 20,
						'max'  => 75,
						'step' => 1,
					),
					'default'     => $sidebar_width_pcnt,
					'showreset'   => $sidebar_width_pcnt,
					'transport' => 'postMessage',
					'active_callback' => 'strute_callback_sb2w_pcnt',
				),
			),
			'layout' => array(
				'sblayoutpnote' => array(
					'type'        => 'note',
				),
				'sidebar' => array(
					'label'       => esc_html__( 'Sidebar Layout - Default Site wide', 'strute' ),
					'type'        => 'radioimage',
					'choices'     => array(
						'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
						'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
						'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
						'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
						'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
						'full-width'         => $imagepath . 'sidebar-full.png',
						'none'               => $imagepath . 'sidebar-none.png',
					),
					'default'     => 'narrow-right',
					'description' => esc_html__( 'Set the default sidebar width and position for your site.', 'strute' ),
				),
				'sidebar_fp' => array(
					'label'       => esc_html__( 'Sidebar Layout - Front Page', 'strute' ),
					'type'        => 'radioimage',
					'choices'     => array(
						'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
						'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
						'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
						'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
						'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
						'full-width'         => $imagepath . 'sidebar-full.png',
						'none'               => $imagepath . 'sidebar-none.png',
					),
					'default'     => ( ( 'page' == get_option('show_on_front' ) ) ? 'full-width' : 'narrow-right' ),
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					'description' => sprintf( esc_html__( 'This is sidebar for the "Homepage Content" Module in %1$sFrontpage Modules Settings%2$s', 'strute' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[section]=frontpage' ) ) . '" rel="focuslink" data-focustype="section" data-href="frontpage">', '</a>' ),
				),
				'sidebar_archives' => array(
					'label'       => esc_html__( 'Sidebar Layout - Blog/Archives', 'strute' ),
					'type'        => 'radioimage',
					'choices'     => array(
						'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
						'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
						'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
						'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
						'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
						'full-width'         => $imagepath . 'sidebar-full.png',
						'none'               => $imagepath . 'sidebar-none.png',
					),
					'default'     => 'narrow-right',
				),
				'sidebar_pages' => array(
					'label'       => esc_html__( 'Sidebar Layout - Pages', 'strute' ),
					'type'        => 'radioimage',
					'choices'     => array(
						'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
						'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
						'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
						'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
						'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
						'full-width'         => $imagepath . 'sidebar-full.png',
						'none'               => $imagepath . 'sidebar-none.png',
					),
					'default'     => 'narrow-right',
				),
				'sidebar_posts' => array(
					'label'       => esc_html__( 'Sidebar Layout - single Posts', 'strute' ),
					'type'        => 'radioimage',
					'choices'     => array(
						'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
						'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
						'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
						'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
						'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
						'full-width'         => $imagepath . 'sidebar-full.png',
						'none'               => $imagepath . 'sidebar-none.png',
					),
					'default'     => 'narrow-right',
				),
			),
		),
	);

	if ( current_theme_supports( 'woocommerce' ) ) :

		$settings['sidebar_tabs']['options']['layout']['sidebar_wooshop'] = array(
			'label'       => esc_html__( 'Sidebar Layout - Woocommerce Shop/Archives', 'strute' ),
			'type'        => 'radioimage',
			'choices'     => array(
				'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
				'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
				'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
				'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
				'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
				'full-width'         => $imagepath . 'sidebar-full.png',
				'none'               => $imagepath . 'sidebar-none.png',
			),
			'default'     => 'narrow-right',
			'description' => esc_html__( 'Set the default sidebar width and position for WooCommerce Shop and Archives pages like product categories etc.', 'strute' ),
		);

		$settings['sidebar_tabs']['options']['layout']['sidebar_wooproduct'] = array(
			'label'       => esc_html__( 'Sidebar Layout - Woocommerce Single Product Page', 'strute' ),
			'type'        => 'radioimage',
			'choices'     => array(
				'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
				'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
				'narrow-left-right'  => $imagepath . 'sidebar-narrow-left-right.png',
				'narrow-left-left'   => $imagepath . 'sidebar-narrow-left-left.png',
				'narrow-right-right' => $imagepath . 'sidebar-narrow-right-right.png',
				'full-width'         => $imagepath . 'sidebar-full.png',
				'none'               => $imagepath . 'sidebar-none.png',
			),
			'default'     => 'narrow-right',
			'description' => esc_html__( 'Set the default sidebar width and position for WooCommerce product page', 'strute' ),
		);

	endif;

	/** Section **/

	$section = 'general';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'General', 'strute' ),
		'priority'    => '20',
	);

	$settings['gototop_headline'] = array(
		'label'       => esc_html__( 'Goto Top', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '150',
	);

	$settings['disable_goto_top'] = array(
		'label'       => esc_html__( "Enable 'Goto Top' Button", 'strute' ),
		'section'     => $section,
		'type'        => 'bettertoggle',
		'inverttoggle'=> true,
		'priority'    => '160',
		'description' => esc_html__( 'Check this to show "Top" button (bottom right of screen) when a user scrolls down the page.', 'strute' ),
		'transport' => 'postMessage',
	);
	$settings['goto_top_mobile'] = array(
		'description' => esc_html__( 'Show on mobile', 'strute' ),
		'section'     => $section,
		'type'        => 'bettertoggle',
		'default'     => 1,
		'priority'    => '170',
		'active_callback' => 'strute_callback_goto_top',
		'transport' => 'postMessage',
	);
	$settings['goto_top_icon'] = array(
		'description' => esc_html__( 'Icon:', 'strute' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'fa-angle-double-up fas'     => array( '<i class="fa-angle-double-up fas"></i>' ),
			'fa-chevron-up fas'          => array( '<i class="fa-chevron-up fas"></i>' ),
			'fa-arrow-up fas'            => array( '<i class="fa-arrow-up fas"></i>' ),
			'fa-caret-up fas'            => array( '<i class="fa-caret-up fas"></i>' ),
			'fa-level-up-alt fas'        => array( '<i class="fa-level-up-alt fas"></i>' ),
			'fa-sort-amount-up-alt fas'  => array( '<i class="fa-sort-amount-up-alt fas"></i>' ),
		),
		'default'     => 'fa-chevron-up fas',
		'priority'    => '180',
		'active_callback' => 'strute_callback_goto_top',
		'transport' => 'postMessage',
	);
	$gototopiconclass = get_theme_mod( 'goto_top_icon', $settings['goto_top_icon']['default'] );
	$settings['goto_top_icon_style'] = array(
		'description' => esc_html__( 'Style:', 'strute' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'style1'  => array( '<span class="gotostyle_s gotostyle1"><i class="' . $gototopiconclass . '"></i></span>' . esc_html__( 'Dark', 'strute' ) ),
			'style2'  => array( '<span class="gotostyle_s gotostyle2"><i class="' . $gototopiconclass . '"></i></span>' . esc_html__( 'Light', 'strute' ) ),
			'style3'  => array( '<span class="gotostyle_s gotostyle3"><i class="' . $gototopiconclass . '"></i></span>' . esc_html__( 'Accent Color', 'strute' ) ),
			'style4'  => array( '<span class="gotostyle_s gotostyle4"><i class="' . $gototopiconclass . '"></i></span>' . esc_html__( 'Invert Accent', 'strute' ) ),
			'style5'  => array( '<span class="gotostyle_c gotostyle5"><i class="' . $gototopiconclass . '"></i></span>' . esc_html__( 'Dark', 'strute' ) ),
			'style6'  => array( '<span class="gotostyle_c gotostyle6"><i class="' . $gototopiconclass . '"></i></span>' . esc_html__( 'Light', 'strute' ) ),
			'style7'  => array( '<span class="gotostyle_c gotostyle7"><i class="' . $gototopiconclass . '"></i></span>' . esc_html__( 'Accent Color', 'strute' ) ),
			'style8'  => array( '<span class="gotostyle_c gotostyle8"><i class="' . $gototopiconclass . '"></i></span>' . esc_html__( 'Invert Accent', 'strute' ) ),
		),
		'default'     => 'style7',
		'priority'    => '190',
		'active_callback' => 'strute_callback_goto_top',
		'transport' => 'postMessage',
	);
	$settings['goto_top_offset'] = array(
		'description' => esc_html__( 'Offset from bottom', 'strute' ),
		'section'     => $section,
		'type'        => 'betterrange',
		'mediaquery'  => true,
		'displaysuffix' => 'px',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 150,
			'step' => 5,
		),
		'default'     => $goto_top_offset,
		'showreset'   => $goto_top_offset,
		'priority'    => '200',
		'active_callback' => 'strute_callback_goto_top',
		'transport' => 'postMessage',
	);

	$settings['anim_headline'] = array(
		'label'       => esc_html__( 'Animations', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '210',
	);
	$animdescription = esc_html__( 'Switch off to disable all animations on the site. This includes effects on hover, effects when items enter/leave view etc.', 'strute' );
	$settings['enable_anims'] = array(
		'label'       => esc_html__( 'Enable Animations', 'strute' ),
		'section'     => $section,
		'type'        => 'bettertoggle',
		'default'     => 1,
		'priority'    => '220',
		'description' => $animdescription,
	);
	$animchoices = array(
		'h00' => array( esc_html__( 'General:', 'strute' ) ),
			'sscroll'     => esc_html__( 'Smooth Scroll on Desktop (Lenis)', 'strute' ),
			'stickyhead'  => esc_html__( 'Animate Sticky Header when it appears', 'strute' ),
			'aos'         => esc_html__( 'Animate elements when they come into view - Desktop', 'strute' ) . '<em>' . esc_html__( '* preview not available in Customizer', 'strute' ) . '</em>',
			'aosmob'      => esc_html__( 'Animate elements when they come into view - Mobile', 'strute' ) . '<em>' . esc_html__( '* preview not available in Customizer', 'strute' ) . '</em>',
			'imghov'      => esc_html__( 'Image Hover effect', 'strute' ),
		'h01' => array( esc_html__( 'Goto Top:', 'strute' ) ),
			'waygototop'  => esc_html__( 'Show Goto Top button after user has scrolled down a bit', 'strute' ),
			'animgototop' => esc_html__( 'Smooth scroll to top on button click', 'strute' ),
		'h02' => array( esc_html__( 'Posts/Pages/Archives:', 'strute' ) ),
			'pagehead'    => esc_html__( 'Animate Header Featured Image when user scrolls down', 'strute' ),
			'ajaxpaginate'=> esc_html__( 'Pagination links loads next set of posts while staying on page (These are page links on Blog, Archives and on Paginated Posts)', 'strute' ),
		'h03' => array( esc_html__( 'Posts:', 'strute' ) ),
			'prevnext'    => esc_html__( 'Briefly show previous/next posts (flyout types) when user reaches end of post.', 'strute' ),
		'h04' => array( esc_html__( 'Auto Scrolls **', 'strute' ) ),
			'scrollhash'  => esc_html__( 'Auto Scroll to #hash in the link url', 'strute' ),
			'scrollmain'  => esc_html__( 'Auto Scroll down to main content of page when a link is clicked', 'strute' ),
	);
	$animdefaults = 'sscroll, stickyhead, aos, aosmob, imghov, waygototop, animgototop, pagehead, ajaxpaginate, prevnext, scrollhash, scrollmain';
	$settings['enabled_anims'] = array(
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => $animchoices,
		'default'     => $animdefaults,
		'priority'    => '230',
		'active_callback' => 'strute_callback_enabled_anims',
	);

	$settings['autoscroll_scope'] = array(
		'label'       => esc_html__( '** Auto Scrolls', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'sitewide'   => esc_html__( 'Apply to links throughout the site', 'strute' ),
			'menu'       => esc_html__( 'Apply to links only in the menu', 'strute' ),
			'menu-posts' => esc_html__( 'Apply to links in menu, archives and post content', 'strute' ),
		),
		'description' => esc_html__( 'Auto scroll is executed when a user clicks on a link within the site to navigate to a part of the site.', 'strute' ),
		'default'     => 'sitewide',
		'priority'    => '240',
		'transport' => 'postMessage',
		'active_callback' => 'strute_callback_autoscroll',
	);

	$settings['miscsetup_headline'] = array(
		'label'       => esc_html__( 'Miscellaneous', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '250',
	);

	$settings['widgetmargin'] = array(
		'label'       => esc_html__( 'Widget Margin', 'strute' ),
		'section'     => $section,
		'type'        => 'betterrange',
		'mediaquery'  => true,
		'displaysuffix' => 'px',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 150,
			'step' => 1,
		),
		'default'     => $widgetmargin,
		'showreset'   => $widgetmargin,
		'description' => esc_html__( '(in pixels) Margin space above and below widgets.', 'strute' ),
		'priority'    => '260',
		'transport' => 'postMessage',
	);

	/** Panel **/

	/** Section **/

	$section = 'hootshead-sc';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Sections', 'strute' ),
		'priority'    => '25',
	);

	$settings['sc_settings'] = array(
		'section'     => $section,
		'type'        => 'content',
		'content'     => '',
		'priority'    => '1111',
	);

	/** Section **/

	$section = 'topannounce';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Top Announcement', 'strute' ),
		'priority'    => '30',
	);

	$settings['topann_descrip'] = array(
		'section'     => $section,
		'type'        => 'content',
		'content'     => esc_html__( 'To display this area, add widgets to "Below Header Left/Right" areas.
		Note: This area will be center aligned if only one of the Left/Right areas contains widgets.', 'strute' ),
		'content'     => esc_html__( 'This area is displayed at the top of the site above everything else. It can be used for displaying site-wide announcements.', 'strute' ),
		'class'       => 'hootnote',
		'priority'    => '270',
	);

	$settings['topann_sticky'] = array(
		'label'       => esc_html__( 'Stick to Top on scroll down', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'sublabel'     => sprintf( esc_html__( 'For the best user experience, we recommend keeping only one sticky element at the top - either the Announcement or the %1$sSticky Header%2$s.', 'strute' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[section]=stickysitehead' ) ) . '" rel="focuslink" data-focustype="section" data-href="stickysitehead">', '</a>' ),
		'section'     => $section,
		'type'        => 'bettertoggle',
		'default'     => 0,
		'priority'    => '280',
		'transport' => 'postMessage',
	);

	$settings['topann_image'] = array(
		'label'       => esc_html__( 'Image', 'strute' ),
		'section'     => $section,
		'type'        => 'image',
		'priority'    => '290',
		'transport' => 'postMessage', // to work with 'selective_refresh' added via 'topann_content'
	);

	$settings['topann_imgasbg'] = array(
		'section'     => $section,
		'type'        => 'checkbox',
		'description' => esc_html__( 'Set Image as background', 'strute' ),
		'default'     => 1,
		'priority'    => '300',
		'transport' => 'postMessage',
	);

	$settings['topann_url'] = array(
		'label'       => esc_html__( 'Link URL', 'strute' ),
		'section'     => $section,
		'type'        => 'url',
		'input_attrs' => array( 'placeholder' => 'https://' ),
		'priority'    => '310',
		'transport' => 'postMessage',
	);

	$settings['topann_url_target'] = array(
		'section'     => $section,
		'type'        => 'checkbox',
		'description' => esc_html__( 'Open link in new window?', 'strute' ),
		'priority'    => '320',
		'transport' => 'postMessage',
	);

	$settings['topann_url_scope'] = array(
		'label'       => esc_html__( 'Link URL Scope', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'background' => esc_html__( 'Entire Top Area', 'strute' ),
			'content'    => esc_html__( 'Only Content Box', 'strute' ),
		),
		'default'     => 'background',
		'priority'    => '330',
		'transport' => 'postMessage',
	);

	$settings['topann_content_stretch'] = array(
		'label'       => esc_html__( 'Content Box Size', 'strute' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'grid'    => $imagepath . 'topann-content-style-1.png',
			'stretch' => $imagepath . 'topann-content-style-2.png',
		),
		'description' => esc_html__( 'Boxed vs Stretched layout', 'strute' ) . '<hr>' . esc_html__( 'Stretched option can be useful if you are displaying an image HTML in the Content option below', 'strute' ),
		'default'     => 'grid',
		'priority'    => '340',
		'transport' => 'postMessage',
	);

	$settings['topann_content_nopad'] = array(
		'section'     => $section,
		'type'        => 'checkbox',
		// 'default'     => 1,
		'description' => esc_html__( 'Remove paddings / spaces at corners?', 'strute' ),
		'priority'    => '350',
		'active_callback' => 'strute_callback_topann_content_nopad',
		'transport' => 'postMessage',
	);

	$settings['topann_content_style'] = array(
		'label'       => esc_html__( 'Content Style', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'choices'     => array(
			'dark'            => esc_html__( 'Dark Font', 'strute' ),
			'light'           => esc_html__( 'Light Font', 'strute' ),
			'dark-on-light'   => esc_html__( 'Dark Font / Light Background', 'strute' ),
			'light-on-dark'   => esc_html__( 'Light Font / Dark Background', 'strute' ),
			'dark-on-custom'  => esc_html__( 'Dark Font / Custom Background', 'strute' ),
			'light-on-custom' => esc_html__( 'Light Font / Custom Background', 'strute' ),
		),
		'default'     => 'dark-on-light',
		'priority'    => '360',
		'transport' => 'postMessage',
	);

	$settings['topann_content_bg'] = array(
		'label'       => esc_html__( 'Custom Background', 'strute' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $topann_content_bg,
		'priority'    => '370',
		'active_callback' => 'strute_callback_topann_content_bg',
		'transport' => 'postMessage',
	);

	$settings['topann_content_title'] = array(
		'label'       => esc_html__( 'Content Title', 'strute' ),
		'section'     => $section,
		'type'        => 'text',
		'priority'    => '380',
		'transport' => 'postMessage', // to work with 'selective_refresh' added via 'topann_content'
	);

	$settings['topann_content'] = array(
		'label'       => esc_html__( 'Content Text', 'strute' ),
		'section'     => $section,
		'type'        => 'textarea',
		'priority'    => '390',
		'selective_refresh' => array( 'topann_content_partial', array(
			'selector'            => '#topann',
			'settings'            => array( 'topann_content', 'topann_content_title', 'topann_image' ),
			'render_callback'     => 'strute_topann',
			'container_inclusive' => true,
			) ),
	);

	$tac_li1 = class_exists( 'HootKit' ) ? '<li>' .
		'<strong style="display:block;margin:10px 0 0;">' . esc_html__( 'Insert Timer', 'strute' ) . '</strong> ' .
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		sprintf( esc_html__( 'You can add a HootKit timer to your content using shortcode. The values are the %1$send time%2$s', 'strute' ), '<span style="text-decoration:underline">', '</span>' ) .
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'<code>' . sprintf( esc_html__( '[HKtimer year="%1$s2029%2$s" month="%1$s12%2$s" day="%1$s31%2$s" hour="%1$s23%2$s" minute="%1$s59%2$s"]', 'strute' ), '<span style="text-decoration:underline">', '</span>' ) . '</code>' .
	'</li>' : '';
	$tac_li2 = '<li>' .
		'<strong style="display:block;margin:10px 0 0;">' . esc_html__( 'Use HTML tags to style your content:', 'strute' ) . '</strong>' .
		'<code style="font-weight:bold;font-size:1.2em">' . esc_html__( '<h5> Heading </h5>', 'strute' ) . '</code>' .
		'<code style="font-weight:bold">' . esc_html__( '<b> Bold </b>', 'strute' ) . '</code>' .
		'<code style="font-weight:bold">' . esc_html__( '<strong> Bold </strong>', 'strute' ) . '</code>' .
		'<code style="font-style:italic">' . esc_html__( '<em> Emphasize (italic) </em>', 'strute' ) . '</code>' .
		'<code style="color:#1e83bd">' . esc_html__( '<mark> Marked (highlighted) </mark>', 'strute' ) . '</code>' .
	'</li>';
	$tac_li3 = '<li>' .
		'<strong style="display:block;margin:10px 0 0;">' . esc_html__( 'Add image using img html:', 'strute' ) . '</strong>' .
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'<code>' . sprintf( esc_html__( '<img src=" %1$shttp://website.com/image.png%2$s ">', 'strute' ), '<span style="text-decoration:underline">', '</span>' ) . '</code>' .
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		sprintf( esc_html__( 'If you are adding a large image to Content (to display as a full width image banner), it can be useful to set %1$sContent Box Size%2$s option above to %1$sStretched%2$s', 'strute' ), '<strong>', '</strong>' ) .
	'</li>';

	$settings['topann_content_descrip'] = array(
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '400',
		'content'     => '<ul>' . $tac_li1 . $tac_li2 . $tac_li3 . '</ul>',
	);

	/** Section **/

	$section = 'topbar';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Top Bar', 'strute' ),
		'priority'    => '35',
	);

	$settings['topbar_descrip'] = array(
		'section'     => $section,
		'type'        => 'content',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'content'     => sprintf( esc_html__( 'To display this area, add widgets to %1$sTopbar Left/Right%2$s areas.%3$s%4$sNote: This area will be center aligned if only one of the Left/Right areas contains widgets.%5$s', 'strute' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[panel]=widgets' ) ) . '" rel="focuslink" data-focustype="panel" data-href="widgets">', '</a>', '<hr>', '<em>', '</em>' ),
		'class'       => 'hootnote',
		'priority'    => '410',
	);

	$settings['topbar_grid'] = array(
		'label'       => esc_html__( 'Stretch Topbar Content to cover full available width', 'strute' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'boxed'   => $imagepath . 'topbar-layout-boxed.png',
			'stretch' => $imagepath . 'topbar-layout-stretch.png',
		),
		'default'     => 'boxed',
		'priority'    => '420',
		'transport' => 'postMessage',
	);

	/** Section **/

	$section = 'sitehead';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Header Layout', 'strute' ),
		'priority'    => '40',
	);

	$settings['logo_side_headline'] = array(
		'label'       => esc_html__( 'Display Header Side Widget Area', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '430',
		'active_callback' => 'strute_callback_logo_side',
	);
	$settings['logo_side'] = array(
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'widget-area' => esc_html__( "Logo (left) + 'Header Side' widget area (right)", 'strute' ),
			'none'        => esc_html__( 'Logo (center)', 'strute' ),
		),
		'default'     => 'none',
		'priority'    => '440',
		'active_callback' => 'strute_callback_logo_side',
		'selective_refresh' => array( 'logo_side_partial', array(
			'selector'            => '#sitehead-aside',
			'settings'            => array( 'logo_side' ),
			'render_callback'     => 'strute_sitehead_aside',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // prevents full refresh on non applicable views
			) ),
	);

	$settings['sitehead_menu_headline'] = array(
		'label'       => esc_html__( 'Header Menu', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '450',
	);

	$settings['menu_location'] = array(
		'label'       => esc_html__( 'Menu Location', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'top'        => esc_html__( 'Above Logo', 'strute' ),
			'side'       => esc_html__( 'Header Side (Right of Logo)', 'strute' ),
			'bottom'     => esc_html__( 'Below Logo', 'strute' ),
			'none'       => esc_html__( 'Do not display menu', 'strute' ),
		),
		'default'     => 'side',
		'priority'    => '460',
		'transport' => 'postMessage',
	);

	$settings['fullwidth_menu_align'] = array(
		'label'       => esc_html__( 'Menu Area (alignment)', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'left'      => esc_html__( 'Left', 'strute' ),
			'right'     => esc_html__( 'Right', 'strute' ),
			'center'    => esc_html__( 'Center', 'strute' ),
		),
		'default'     => 'center',
		'priority'    => '470',
		'active_callback' => 'strute_callback_menu_align',
		'transport' => 'postMessage',
	);

	$settings['disable_table_menu'] = array(
		'label'       => esc_html__( 'Single Line Menu', 'strute' ),
		'section'     => $section,
		'type'        => 'bettertoggle',
		'default'     => 1,
		'description' => esc_html__( 'Enable this to shrink menu items to always fit in 1 single line. If there are too many menu items to fit in one line, disabling this will let them flow to a second line.', 'strute' ) . '<hr>' . "<img src='{$imagepath}menu-table.png'>",
		'priority'    => '480',
		'active_callback' => 'strute_callback_menu_misc',
		'transport' => 'postMessage',
	);

	$settings['mobile_menu_label'] = array(
		'label'       => esc_html__( 'Mobile Menu Label', 'strute' ),
		'section'     => $section,
		'type'        => 'text',
		'description' => esc_html__( 'Label appears next to the menu icon on mobile screens', 'strute' ),
		'default'     => esc_html__( 'Menu', 'strute' ),
		'priority'    => '490',
		'active_callback' => 'strute_callback_menu_misc',
		'transport' => 'postMessage',
	);

	$settings['mobile_submenu_click'] = array(
		'label'       => esc_html__( "[Mobile Menu] Submenu opens on 'Click'", 'strute' ),
		'section'     => $section,
		'type'        => 'bettertoggle',
		'default'     => 1,
		'description' => esc_html__( "Uncheck this option to make all Submenus appear in 'Open' state. By default, submenus open on clicking (i.e. single tap on mobile).", 'strute' ),
		'priority'    => '500',
		'active_callback' => 'strute_callback_menu_misc',
		'transport' => 'postMessage',
	);

	/** Section **/

	$section = 'stickysitehead';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Header Sticky', 'strute' ),
		'priority'    => '45',
	);

	$settings['sticky_accent'] = array(
		'label'       => esc_html__( 'Color Scheme', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'default' => esc_html__( 'Default', 'strute' ),
			'accent'  => esc_html__( 'Use Accent Color', 'strute' ),
		),
		'default'     => 'default',
		'priority'    => '505',
		'transport' => 'postMessage',
	);

	$settings['sticky_ops'] = array(
		'section'     => $section,
		'type'        => 'tabs',
		'headingtabs' => true,
		'disablejstoggle' => true,
		'priority'    => '510',
		'options'     => array(
			'desktop' => array(

				'sticky_sitehead_dtp' => array(
					'label'       => esc_html__( 'Enable Sticky Header on Desktop', 'strute' ),
					'description' => esc_html__( 'Check this to display a fixed Header at top when a user scrolls down the page.', 'strute' ),
					'type'        => 'bettertoggle',
					'default'     => 1,
					'transport' => 'postMessage',
				),
				'sticky_sitehead_dtp_layout' => array(
					'label'       => esc_html__( 'Display on Desktop', 'strute' ),
					'type'        => 'radio',
					'choices'     => array(
						'topbar'       => esc_html__( 'Topbar', 'strute' ),
						'logo'         => esc_html__( 'Logo', 'strute' ),
						'menu'         => esc_html__( 'Menu', 'strute' ),
						'text'         => esc_html__( 'Text', 'strute' ),
						'logomenu'     => esc_html__( 'Logo (left) + Menu (right)', 'strute' ),
						'logomenudiv'  => esc_html__( 'Logo (top) + Menu (bottom)', 'strute' ),
						'logotext'     => esc_html__( 'Logo (left) + Text (right)', 'strute' ),
						'logotextdiv'  => esc_html__( 'Logo (top) + Text (bottom)', 'strute' ),
						'logomenutext' => esc_html__( 'Logo (left) + Menu (right) + Text (bottom)', 'strute' ),
					),
					'default'     => 'logomenu',
					'active_callback' => 'strute_callback_sticky_dtp',
					'transport' => 'postMessage',
				),
				'sticky_sitehead_dtp_layout_descrip' => array(
					'type'        => 'content',
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					'content'    => sprintf( esc_html__( '%1$s%3$sMenu wil be displayed if "Do not display Menu" is not selected in Header section.%4$s%3$sTopbar will be displayed if you have widgets in Topbar widget areas.%4$s%2$s', 'strute' ), '<ul style="margin-top:-15px">', '</ul>', '<li>', '</li>' ),
				),
				'sticky_sitehead_dtp_logozoom' => array(
					'label'       => esc_html__( 'Logo Size (desktop sticky)', 'strute' ),
					'type'        => 'betterrange',
					'displaysuffix' => '%',
					'default'       => 65,
					'showreset'     => 65,
					'input_attrs'     => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					),
					'active_callback' => 'strute_callback_sticky_dtp_logo',
					'transport' => 'postMessage',
				),
				'sticky_sitehead_dtp_text' => array(
					'label'       => esc_html__( 'Custom Text (desktop sticky)', 'strute' ),
					'type'        => 'textarea',
					'active_callback' => 'strute_callback_sticky_dtp_text',
					'selective_refresh' => array( 'sticky_dtp_text_partial', array(
						'selector'            => '#sitehead-extradtp',
						'settings'            => array( 'sticky_sitehead_dtp_text' ),
						'render_callback'     => 'strute_sitehead_extra_dtp',
						'container_inclusive' => true,
						'fallback_refresh'    => true, // prevents full refresh on non applicable views
						) ),
				),

			),
			'mobile' => array(

				'sticky_sitehead_mob' => array(
					'label'       => esc_html__( 'Enable Sticky Header on Mobile', 'strute' ),
					'description' => esc_html__( 'Check this to display a fixed Header at top when a user scrolls down the page.', 'strute' ),
					'type'        => 'bettertoggle',
					'default'     => 1,
					'transport' => 'postMessage',
				),
				'sticky_sitehead_mob_layout' => array(
					'label'       => esc_html__( 'Display on Mobile', 'strute' ),
					'type'        => 'radio',
					'choices'     => array(
						'topbar'       => esc_html__( 'Topbar', 'strute' ),
						'logo'         => esc_html__( 'Logo', 'strute' ),
						'menu'         => esc_html__( 'Menu', 'strute' ),
						'menuleft'     => esc_html__( 'Menu (left)', 'strute' ), // XTRA
						'menuright'    => esc_html__( 'Menu (right)', 'strute' ), // XTRA
						'text'         => esc_html__( 'Text', 'strute' ),
						'logomenu'     => esc_html__( 'Logo (left) + Menu (right)', 'strute' ),
						'menulogo'     => esc_html__( 'Menu (left) + Logo (right)', 'strute' ),
						'logotext'     => esc_html__( 'Logo (left) + Text (right)', 'strute' ),
						'logotextdiv'  => esc_html__( 'Logo (top) + Text (bottom)', 'strute' ),
						'logomenutext' => esc_html__( 'Logo (left) + Menu (right) + Text (bottom)', 'strute' ),
					),
					'default'     => 'logomenu',
					'active_callback' => 'strute_callback_sticky_mob',
					'transport' => 'postMessage',
				),
				'sticky_sitehead_mob_layout_descrip' => array(
					'type'        => 'content',
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					'content'    => sprintf( esc_html__( '%1$s%3$sTopbar will be displayed if you have widgets in Topbar widget areas.%4$s%2$s', 'strute' ), '<ul style="margin-top:-15px">', '</ul>', '<li>', '</li>' ),
				),
				'sticky_sitehead_mob_logozoom' => array(
					'label'       => esc_html__( 'Logo Size (mobile sticky)', 'strute' ),
					'type'        => 'betterrange',
					'displaysuffix' => '%',
					'default'       => 50,
					'showreset'     => 50,
					'input_attrs'     => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 1,
					),
					'active_callback' => 'strute_callback_sticky_mob_logo',
					'transport' => 'postMessage',
				),
				'sticky_sitehead_mob_text' => array(
					'label'       => esc_html__( 'Custom Text (mobile sticky)', 'strute' ),
					'type'        => 'textarea',
					'active_callback' => 'strute_callback_sticky_mob_text',
					'selective_refresh' => array( 'sticky_mob_text_partial', array(
						'selector'            => '#sitehead-extramob',
						'settings'            => array( 'sticky_sitehead_mob_text' ),
						'render_callback'     => 'strute_sitehead_extra_mob',
						'container_inclusive' => true,
						'fallback_refresh'    => true, // prevents full refresh on non applicable views
						) ),
				),

			),
		),
	);

	/** Section **/

	$section = 'belowsitehead';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Header Below', 'strute' ),
		'priority'    => '50',
	);

	$settings['below_sitehead_descrip'] = array(
		'section'     => $section,
		'type'        => 'content',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'content'     => sprintf( esc_html__( 'To display this area, add widgets to %1$sBelow Header Left/Right%2$s areas.%3$s%4$sNote: This area will be center aligned if only one of the Left/Right areas contains widgets.%5$s', 'strute' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[panel]=widgets' ) ) . '" rel="focuslink" data-focustype="panel" data-href="widgets">', '</a>', '<hr>', '<em>', '</em>' ),
		'class'       => 'hootnote',
		'priority'    => '520',
	);

	$settings['below_sitehead_grid'] = array(
		'label'       => esc_html__( "Stretch 'Below Header' content to cover full available width", 'strute' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'boxed'   => $imagepath . 'fp-widgetarea-boxed.png',
			'stretch' => $imagepath . 'fp-widgetarea-stretch.png',
		),
		'default'     => 'boxed',
		'priority'    => '530',
		'transport' => 'postMessage',
	);

	/** Section **/

	$section = 'footer';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Footer', 'strute' ),
		'priority'    => '55',
	);

	$settings['subfooter_headline'] = array(
		'label'       => esc_html__( 'Sub Footer', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '540',
	);

	$settings['subfooter_descrip'] = array(
		'section'     => $section,
		'type'        => 'content',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'content'     => sprintf( esc_html__( 'To display this area, add widgets to %1$sSub Footer%2$s area.', 'strute' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[panel]=widgets' ) ) . '" rel="focuslink" data-focustype="panel" data-href="widgets">', '</a>' ),
		'class'       => 'hootnote',
		'priority'    => '550',
	);

	$settings['footer_headline'] = array(
		'label'       => esc_html__( 'Footer', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '560',
	);

	$settings['footer'] = array(
		'label'       => esc_html__( 'Footer Layout', 'strute' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'1-1' => $imagepath . '1-1.png',
			'2-1' => $imagepath . '2-1.png',
			'2-2' => $imagepath . '2-2.png',
			'2-3' => $imagepath . '2-3.png',
			'3-1' => $imagepath . '3-1.png',
			'3-2' => $imagepath . '3-2.png',
			'3-3' => $imagepath . '3-3.png',
			'3-4' => $imagepath . '3-4.png',
			'4-1' => $imagepath . '4-1.png',
		),
		'default'     => '4-1',
		'priority'    => '570',
		'transport' => 'postMessage',
	);

	$settings['footer_descrip'] = array(
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '580',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'content'     => sprintf( esc_html__( 'You must first save the changes you make here and refresh this screen for footer columns to appear in the Widgets panel (in customizer).%3$s Once you save the settings here, you can add content to footer columns using the %1$sWidgets Management screen%2$s.', 'strute' ), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>', '<hr>' ),
	);

	$settings['postfooter_headline'] = array(
		'label'       => esc_html__( 'Post Footer', 'strute' ),
		'section'     => $section,
		'type'        => 'headline',
		'priority'    => '590',
	);

	$settings['postfooter_text'] = array(
		'label'       => esc_html__( 'Post Footer Text', 'strute' ),
		'section'     => $section,
		'type'        => 'textarea',
		'default'     => esc_html__( '<!--default--> &copy; <!--year-->', 'strute'),
		'priority'    => '600',
		'selective_refresh' => array( 'postfooter_partial', array(
			'selector'            => '#post-footer',
			'settings'            => array( 'postfooter_text' ),
			'render_callback'     => 'strute_postfooter',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // prevents full refresh on non applicable views
			) ),
	);

	$settings['postfooter_descrip'] = array(
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '610',
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'content'     => sprintf( esc_html__( 'This can be useful for showing copyright info etc.
		%1$s
		%3$sUse the %5$s&lt;!--default--&gt;%6$s tag to show the default Info Text.%4$s
		%3$sUse the %5$s&lt;!--year--&gt;%6$s tag to insert the current year.%4$s
		%3$sAlways use %7$sHTML codes%8$s for symbols. For example, the HTML for &copy; is %5$s&amp;copy;%6$s
		%4$s
		%2$s
		
		', 'strute' ),
			'<ul>', '</ul>',
			'<li>', '</li>',
			'<mark>', '</mark>',
			'<a href="http://ascii.cl/htmlcodes.htm" target="_blank">', '</a>' ),
	);

	/** Section **/

	$section = 'hootshead-st';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Styling', 'strute' ),
		'priority'    => '60',
	);

	$settings['st_settings'] = array(
		'section'     => $section,
		'type'        => 'content',
		'content'     => '',
		'priority'    => '1111',
	);

	/** Section **/

	$section = 'colors';

	// Redundant as 'colors' section is added by WP. But we still add it for brevity
	$sections[ $section ] = array(
		'title'       => esc_html__( 'Colors / Backgrounds', 'strute' ),
		'priority'    => '65',
	);

	$settings['colorspnote'] = array(
		'section'     => $section,
		'type'        => 'note',
		'priority'    => '620',
	);

	$presets_arr = array(
		'default'    => array( 'accent_color' => $accent_color, 'accent_font' => $accent_font ),
		'gold'       => array( 'accent_color' => '#db970f', 'accent_font' => '#ffffff' ),
		'green'      => array( 'accent_color' => '#7ea844', 'accent_font' => '#ffffff' ),
		'dgreen'     => array( 'accent_color' => '#458700', 'accent_font' => '#ffffff' ),
		'aqua'       => array( 'accent_color' => '#38b295', 'accent_font' => '#ffffff' ),
		'blue'       => array( 'accent_color' => '#0d99e9', 'accent_font' => '#ffffff' ),
		'rblue'      => array( 'accent_color' => '#107cdb', 'accent_font' => '#ffffff' ),
		'brown'      => array( 'accent_color' => '#614c23', 'accent_font' => '#ffffff' ),
		'black'      => array( 'accent_color' => '#222222', 'accent_font' => '#ffffff' ),
		'maroon'     => array( 'accent_color' => '#ad2929', 'accent_font' => '#ffffff' ),
		'red'        => array( 'accent_color' => '#db0000', 'accent_font' => '#ffffff' ),
	);
	$presets = '';
	foreach ( $presets_arr as $key => $value ) {
		$value['button_color'] = $value['accent_color'];
		$value['button_font'] = $value['accent_font'];
		$q1 = !empty( $value['accent_color'] ) ? $value['accent_color'] : '#ccc';
		$q2 = !empty( $value['accent_font'] ) ? $value['accent_font'] : $q1;
		$q3 = !empty( $value['some_color1'] ) ? $value['some_color1'] : $q2;
		$q4 = !empty( $value['some_color2'] ) ? $value['some_color2'] : $q1;
		$presets .= '<div class="hoot-qd" data-preset="' . esc_attr( json_encode( $value ) ) . '">' .
						'<div class="hoot-qd1" style="background:'.esc_attr($q1).';border-color:'.esc_attr($q1).'"></div>' .
						'<div class="hoot-qd2" style="background:'.esc_attr($q2).';border-color:'.esc_attr($q2).'"></div>' .
						'<div class="hoot-qd3" style="background:'.esc_attr($q3).';border-color:'.esc_attr($q3).'"></div>' .
						'<div class="hoot-qd4" style="background:'.esc_attr($q4).';border-color:'.esc_attr($q4).'"></div>' .
					'</div>';
	}
	$settings['style_presets'] = array(
		'label'       => esc_html__( 'Presets:', 'strute' ),
		'description' => esc_html__( 'Applying these presets will modify the color settings below.', 'strute' ),
		'section'     => $section,
		'type'        => 'content',
		'content'     => '<div class="hoot-style-presets">' . $presets . '</div>',
		'priority'    => '630',
	);

	$settings['accent_color'] = array(
		'label'       => esc_html__( 'Accent Color / Font', 'strute' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $accent_color,
		'priority'    => '640',
		'transport' => 'postMessage',
	);
	$settings['accent_font'] = array(
		'section'     => $section,
		'type'        => 'color',
		'default'     => $accent_font,
		'priority'    => '640',
		'transport' => 'postMessage',
	);

	$settings['button_color'] = array(
		'label'       => esc_html__( 'Button Color / Font', 'strute' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $button_color,
		'priority'    => '650',
		'transport' => 'postMessage',
	);
	$settings['button_font'] = array(
		'section'     => $section,
		'type'        => 'color',
		'default'     => $button_font,
		'priority'    => '650',
		'transport' => 'postMessage',
	);

	$settings['background'] = array(
		'label'       => esc_html__( 'Site Background', 'strute' ),
		'section'     => $section,
		'type'        => 'betterbackground',
		'priority'    => '660',
		'default'     => array(
			'color'      => $site_background,
		),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description' => sprintf( esc_html__( 'This background is more prominently visible when %1$sSite Layout%2$s option is set to %1$s"Boxed"%2$s in the %1$s"Setup &amp; Layout"%2$s section.', 'strute' ), '<strong>', '</strong>' ),
		'transport' => 'postMessage',
	);

	$settings['box_background_color'] = array(
		'label'       => esc_html__( 'Site Content Background', 'strute' ),
		'section'     => $section,
		'type'        => 'color',
		'priority'    => '670',
		'default'     => $box_background,
		'transport' => 'postMessage',
	);
	$settings['gridarticle_bg'] = array(
		'label'       => esc_html__( 'Post Background — on Blog/Archive pages', 'strute' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $article_background_color,
		'priority'    => '755',
		'transport' => 'postMessage',
	);

	$settings['article_background_type'] = array(
		'label'       => esc_html__( 'Article Background — on individual Post/Pages', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'transparent' => esc_html__( 'None', 'strute' ),
			'background'  => esc_html__( 'Background', 'strute' ),
			'background-whensidebar' => esc_html__( 'Background (only when page has a sidebar)', 'strute' ),
		),
		'default'     => $article_background_type,
		'description' => esc_html__( 'This background is applied behind main article text on a Post/Page', 'strute' ) . '<br /><img src="' . $imagepath . 'article-background.png">',
		'priority'    => '755',
		'transport' => 'postMessage',
	);
	$settings['article_background_color'] = array(
		'section'     => $section,
		'type'        => 'color',
		'default'     => $article_background_color,
		'priority'    => '755',
		'active_callback' => 'strute_callback_article_background',
		'transport' => 'postMessage',
	);
	$settings['article_background_pnote'] = array(
		'section'     => $section,
		'type'        => 'note',
		'priority'    => '755',
	);

	/** Section **/

	$section = 'typography';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Typography', 'strute' ),
		'priority'    => '70',
	);

	$settings['typopnote'] = array(
		'section'     => $section,
		'type'        => 'note',
		'priority'    => '680',
	);

	$settings['load_local_fonts'] = array(
		'label'       => esc_html__( 'Load webfonts locally', 'strute' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'default'     => 0,
		'description' => esc_html__( 'Enable this to load Google Fonts (if used) from your own site instead of Google servers. This is required for GDPR compliance for EU visitors.', 'strute' ),
		'priority'    => '690',
	);

	$settings['logo_size'] = array(
		'label'       => esc_html__( 'Logo Text Size', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '700', // Non static options must have a priority
		'choices'     => array(
			'tiny'   => esc_html__( 'Tiny', 'strute'),
			'small'  => esc_html__( 'Small', 'strute'),
			'medium' => esc_html__( 'Medium', 'strute'),
			'large'  => esc_html__( 'Large', 'strute'),
			'huge'   => esc_html__( 'Huge', 'strute'),
		),
		'default'     => 'large',
		'active_callback' => 'strute_callback_logo_size',
		'transport' => 'postMessage',
	);

	$settings['logo_fontface'] = array(
		'label'       => esc_html__( 'Logo Font (Free Version)', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '710', // Non static options must have a priority
		'choices'     => $fontfaces,
		'default'     => $logo_fontface,
	);

	$settings['logo_fontface_style'] = array(
		'label'       => esc_html__( 'Logo Font Style', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '720', // Non static options must have a priority
		'choices'     => array(
			'standard'   => esc_html__( 'Standard', 'strute'),
			'standardi'  => esc_html__( 'Standard Italics', 'strute'),
			'uppercase'  => esc_html__( 'Uppercase', 'strute'),
			'uppercasei' => esc_html__( 'Uppercase Italics', 'strute'),
		),
		'default'     => $logo_fontface_style,
		'transport' => 'postMessage',
	);

	$settings['headings_fontface'] = array(
		'label'       => esc_html__( 'Headings Font (Free Version)', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '730', // Non static options must have a priority
		'choices'     => $fontfaces,
		'default'     => $headings_fontface,
	);

	$settings['headings_fontface_style'] = array(
		'label'       => esc_html__( 'Heading Font Style', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '740', // Non static options must have a priority
		'choices'     => array(
			'standard'   => esc_html__( 'Standard', 'strute'),
			'standardi'  => esc_html__( 'Standard Italics', 'strute'),
			'uppercase'  => esc_html__( 'Uppercase', 'strute'),
			'uppercasei' => esc_html__( 'Uppercase Italics', 'strute'),
		),
		'default'     => $headings_fontface_style,
		'transport' => 'postMessage',
	);

	$settings['subheadings_fontface'] = array(
		'label'       => esc_html__( 'Sub Headings Font (Free Version)', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '750', // Non static options must have a priority
		'choices'     => $fontfaces,
		'default'     => $subheadings_fontface,
	);

	$settings['subheadings_fontface_style'] = array(
		'label'       => esc_html__( 'Sub Heading Font Style', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '760', // Non static options must have a priority
		'choices'     => array(
			'standard'   => esc_html__( 'Standard', 'strute'),
			'standardi'  => esc_html__( 'Standard Italics', 'strute'),
			'uppercase'  => esc_html__( 'Uppercase', 'strute'),
			'uppercasei' => esc_html__( 'Uppercase Italics', 'strute'),
		),
		'default'     => $subheadings_fontface_style,
		'transport' => 'postMessage',
	);

	$settings['body_fontface'] = array(
		'label'       => esc_html__( 'Body Font (Free Version)', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '770', // Non static options must have a priority
		'choices'     => $fontfaces,
		'default'     => $body_fontface,
	);

	/** Section **/

	$section = 'hootshead-sa';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Site Areas', 'strute' ),
		'priority'    => '75',
	);

	$settings['sa_settings'] = array(
		'section'     => $section,
		'type'        => 'content',
		'content'     => '',
		'priority'    => '1111',
	);

	/** Section **/

	/** Section **/

	$section = 'header_image';

	if ( ! class_exists( 'HootKit' ) ) :
	$hklink = function_exists( 'strute_abouttag' ) ? admin_url( 'themes.php?page=' . strute_abouttag( 'slug' ) . '-welcome&tab=plugins' ) : '';
	$settings['himghkitpnote'] = array(
		'section'     => $section,
		'type'        => 'pnote',
		'class'       => 'hootnote-highlight',
		/* Translators: Link Tags */
		'content'     => sprintf( esc_html__( 'To replace this image with a Slider, please %1$sInstall HootKit plugin%2$s to add a Slider/Cover Widget to Frontpage Modules', 'strute' ), '<a href="' . esc_url( $hklink ) .'" target="_blank">', '</a>' ),
		'priority'    => '1',
	);
	endif;

	$settings['header_image_bg'] = array(
		'label'       => esc_html__( 'Background Image', 'strute' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $site_background,
		'transport' => 'postMessage',
		'priority'    => '1',
	);

	$settings['header_image_ops'] = array(
		'section'     => $section,
		'type'        => 'tabs',
		'headingtabs' => true,
		'disablejstoggle' => true,
		'priority'    => '780',
		'options'     => array(
			'content' => array(
				'header_image_feature' => array(
					'label'       => esc_html__( 'Feature Image', 'strute' ),
					'section'     => $section,
					'type'        => 'image',
					'default'     => hoot_data()->template_uri . 'images/placeholder.png',
					'transport' => 'postMessage',
				),
				'header_image_title' => array(
					'label'       => esc_html__( 'Heading', 'strute' ),
					'section'     => $section,
					'type'        => 'textarea',
					'default'     => '<b>' . esc_html( strtoupper( get_bloginfo( 'name' ) ) ) . '</b>',
					'transport' => 'postMessage',
				),
				'header_image_subtitle' => array(
					'label'       => esc_html__( 'Sub Heading', 'strute' ),
					'section'     => $section,
					'type'        => 'textarea',
					'default'     => '<b>' . esc_html__( 'Welcome to our Site', 'strute' ) . '</b>',
					'transport' => 'postMessage',
				),
				'header_image_text' => array(
					'label'       => esc_html__( 'Text', 'strute' ),
					'section'     => $section,
					'type'        => 'textarea',
					'default'     => esc_html__( 'Modify these settings from Customizer - Homepage Image section', 'strute' ),
					'transport' => 'postMessage',
					'selective_refresh' => array( 'header_image_text_partial', array(
						'selector'            => '#fpimg-text',
						'settings'            => array( 'header_image_text' ),
						'render_callback'     => 'strute_header_image_text',
						'container_inclusive' => true,
						'fallback_refresh'    => false, // prevents full refresh on non applicable views
						) ),
				),
				'header_image_button' => array(
					'label'       => esc_html__( 'Button 1 Text', 'strute' ),
					'section'     => $section,
					'type'        => 'text',
					'default'     => esc_html__( 'Know More', 'strute' ),
					'transport' => 'postMessage',
				),
				'header_image_url' => array(
					'label'       => esc_html__( 'Button 1 URL', 'strute' ),
					'section'     => $section,
					'type'        => 'url',
					'default'     => esc_url( home_url() ),
					'input_attrs' => array(
						'placeholder' => esc_html__( 'https://', 'strute' ),
					),
					'transport' => 'postMessage',
				),
				'header_image_button2' => array(
					'label'       => esc_html__( 'Button 2 Text', 'strute' ),
					'section'     => $section,
					'type'        => 'text',
					'default'     => esc_html__( 'Get it Today', 'strute' ),
					'transport' => 'postMessage',
				),
				'header_image_url2' => array(
					'label'       => esc_html__( 'Button 2 URL', 'strute' ),
					'section'     => $section,
					'type'        => 'url',
					'default'     => esc_url( home_url() ),
					'input_attrs' => array(
						'placeholder' => esc_html__( 'https://', 'strute' ),
					),
					'transport' => 'postMessage',
				),
			),
			'style' => array(

				'header_image_headlinelay' => array(
					'label'       => esc_html__( 'Layout', 'strute' ),
					'section'     => $section,
					'type'        => 'content',
					'class'       => 'hootsectionheadline',
				),
				'header_image_layout' => array(
					'label'       => esc_html__( 'Layout (Text &amp; Feature Image)', 'strute' ),
					'section'     => $section,
					'type'        => 'radioimage',
					'choices' => array(
						// no content
						0    => $imagepath . 'headerimg-0.jpg',
						// content - centre, tc, bl, bc, br, ml, mr
						1    => $imagepath . 'headerimg-1.jpg',
						2    => $imagepath . 'headerimg-2.jpg',
						3    => $imagepath . 'headerimg-3.jpg',
						4    => $imagepath . 'headerimg-4.jpg',
						5    => $imagepath . 'headerimg-5.jpg',
						6    => $imagepath . 'headerimg-6.jpg',
						7    => $imagepath . 'headerimg-7.jpg',
						// img - centre , r(content-l), l(content-r)
						8    => $imagepath . 'headerimg-8.jpg',
						9    => $imagepath . 'headerimg-9.jpg',
						10   => $imagepath . 'headerimg-10.jpg',
					),
					'default'     => 6,
					'transport' => 'postMessage',
					'selective_refresh' => array( 'header_image_partial', array(
						'selector'            => '#frontpage-image',
						'settings'            => array(
							'header_image_layout',
							'header_image_feature', 'header_image_title', 'header_image_subtitle', 'header_image_text', 'header_image_button', 'header_image_button2',
						),
						'render_callback'     => 'strute_header_image',
						'container_inclusive' => true,
						'fallback_refresh'    => false, // prevents full refresh on non applicable views
						) ),
				),
				'header_image_minheight' => array(
					'label'       => esc_html__( 'Minimum Height', 'strute' ),
					'description' => esc_html__( 'Set this if your image is too thin to hold the content.', 'strute' ),
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => 'px',
					'default'         => 0,
					'input_attrs'     => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 10,
					),
					'active_callback' => 'strute_callback_header_image_minheight',
					'transport' => 'postMessage',
				),
				'header_image_conpad' => array(
					'label'       => esc_html__( 'Content Margin', 'strute' ),
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => 'px',
					'default'         => 15,
					'input_attrs'     => array(
						'min'  => 0,
						'max'  => 250,
						'step' => 1,
					),
					'transport' => 'postMessage',
				),
				'header_image_imgpad' => array(
					'label'       => esc_html__( 'Feature Image Margin', 'strute' ),
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => 'px',
					'default'         => 15,
					'input_attrs'     => array(
						'min'  => 0,
						'max'  => 250,
						'step' => 1,
					),
					'transport' => 'postMessage',
				),

				'header_image_headlinecon' => array(
					'label'       => esc_html__( 'Content', 'strute' ),
					'section'     => $section,
					'type'        => 'content',
					'class'       => 'hootsectionheadline',
				),
				'header_image_headsize' => array(
					'label'       => esc_html__( 'Heading Size / Color', 'strute' ),
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => 'px',
					'default'         => 20,
					'input_attrs'     => array(
						'min'  => 10,
						'max'  => 150,
						'step' => 1,
					),
					'transport' => 'postMessage',
				),
				'header_image_headcolor' => array(
					'section'         => $section,
					'type'            => 'color',
					'default'         => '#ffffff',
					'transport' => 'postMessage',
				),
				'header_image_subheadsize' => array(
					'label'       => esc_html__( 'Sub Heading Size / Color', 'strute' ),
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => 'px',
					'default'         => 48,
					'input_attrs'     => array(
						'min'  => 10,
						'max'  => 150,
						'step' => 1,
					),
					'transport' => 'postMessage',
				),
				'header_image_subheadcolor' => array(
					'section'         => $section,
					'type'            => 'color',
					'default'         => $accent_color,
					'transport' => 'postMessage',
				),
				'header_image_textsize' => array(
					'label'       => esc_html__( 'Text Size / Color', 'strute' ),
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => 'px',
					'default'         => 18,
					'input_attrs'     => array(
						'min'  => 10,
						'max'  => 150,
						'step' => 1,
					),
					'transport' => 'postMessage',
				),
				'header_image_textcolor' => array(
					'section'         => $section,
					'type'            => 'color',
					'default'         => '#ffffff',
					'transport' => 'postMessage',
				),
				'header_image_btnsize' => array(
					'label'       => esc_html__( 'Button Text Size', 'strute' ),
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => 'px',
					'default'         => 16,
					'input_attrs'     => array(
						'min'  => 10,
						'max'  => 150,
						'step' => 1,
					),
					'transport' => 'postMessage',
				),
				'header_image_btncolor' => array(
					'label'       => esc_html__( 'Button 1 Color / Background', 'strute' ),
					'description' => esc_html__( 'Leave empty for default', 'strute' ),
					'section'         => $section,
					'type'            => 'color',
					'transport' => 'postMessage',
				),
				'header_image_btnbg' => array(
					'section'         => $section,
					'type'            => 'color',
					'transport' => 'postMessage',
				),
				'header_image_btncolor2' => array(
					'label'       => esc_html__( 'Button 2 Color / Background', 'strute' ),
					'description' => esc_html__( 'Leave empty for default', 'strute' ),
					'section'         => $section,
					'type'            => 'color',
					'default'         => '#ffffff',
					'transport' => 'postMessage',
				),
				'header_image_btnbg2' => array(
					'section'         => $section,
					'type'            => 'color',
					'default'         => '#eea925',
					'transport' => 'postMessage',
				),

				'header_image_headlinebgs' => array(
					'label'       => esc_html__( 'Backgrounds', 'strute' ),
					'section'     => $section,
					'type'        => 'content',
					'class'       => 'hootsectionheadline',
				),
				'header_image_conbg' => array(
					'label'       => esc_html__( 'Content Background / Opacity', 'strute' ),
					'section'         => $section,
					'type'            => 'color',
					'default'         => '#000000',
					'transport' => 'postMessage',
				),
				'header_image_conbg_opacity' => array(
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => '%',
					'default'         => 0,
					'input_attrs'     => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'transport' => 'postMessage',
				),
				'header_image_overlay' => array(
					'label'       => esc_html__( 'Image Overlay / Opacity', 'strute' ),
					'section'         => $section,
					'type'            => 'color',
					'default'         => '#000000',
					'transport' => 'postMessage',
				),
				'header_image_overlay_opacity' => array(
					'section'         => $section,
					'type'            => 'betterrange',
					'displaysuffix'   => '%',
					'default'         => 0,
					'input_attrs'     => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'transport' => 'postMessage',
				),

			),
		),
	);

	/** Section **/

	$section = 'frontpage';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Frontpage Modules', 'strute' ),
		'priority'    => '80',
	);

	if ( ! class_exists( 'HootKit' ) ) :
	$hklink = function_exists( 'strute_abouttag' ) ? admin_url( 'themes.php?page=' . strute_abouttag( 'slug' ) . '-welcome&tab=plugins' ) : '';
	$settings['fpmodshkitpnote'] = array(
		'section'     => $section,
		'type'        => 'pnote',
		'class'       => 'hootnote-highlight',
		/* Translators: Link Tags */
		'content'     => sprintf( esc_html__( 'To get the most out of this theme, please %1$sInstall HootKit plugin%2$s to add various Widgets and Sliders to Frontpage Modules', 'strute' ), '<a href="' . esc_url( $hklink ) .'" target="_blank">', '</a>' ),
		'priority'    => '1',
	);
	endif;

	$settings['frontpage_sections_enable'] = array(
		'label'       => esc_html__( "Enable Frontpage 'Widget Areas'", 'strute' ),
		'section'     => $section,
		'type'        => 'bettertoggle',
		'default'     => 1,
		'priority'    => '830',
		'transport' => 'postMessage',
	);

	$settings['frontpage_default_sections'] = array(
		'label'       => esc_html__( 'Default Frontpage', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'sublabel'    => sprintf( esc_html__( '%1$s%3$s%5$sHomepage Image:%6$s This shows the image you have set in %7$sHomepage Image%8$s section%4$s%3$s%5$sHomepage Content:%6$s This module shows the content you have set in %9$sHomepage Content%10$s section%11$s It shows your %5$sBlog Posts%6$s if you selected \'Your Latest Posts\'%12$s%11$s It shows the %5$sPage Content%6$s if you selected \'A static page\'%12$s%4$s%2$s', 'strute' ), '<ul>', '</ul>', '<li>', '</li>', '<strong>', '</strong>', '<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=header_image' ) ) . '" rel="focuslink" data-focustype="control" data-href="header_image">', '</a>', '<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=show_on_front' ) ) . '" rel="focuslink" data-focustype="control" data-href="show_on_front">', '</a>', '<p style="margin:5px 0 0">&#10148;', '</p>' ),
		'section'     => $section,
		'type'        => 'sortlist',
		'choices'     => array(
			'image'       => esc_html__( 'Homepage Image', 'strute' ),
			'content'     => esc_html__( 'Homepage Content', 'strute' ),
		),
		'options'     => array(),
		'attributes'  => array(
			'flypanel'      => false,
			'hideable'      => false,
			'sortable'      => true,
		),
		'priority'    => '840',
		'active_callback' => 'strute_callback_frontpage_default_sections',
	);

	$settings['frontpage_sections'] = array(
		'label'       => esc_html__( 'Frontpage Widget Areas', 'strute' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'sublabel'    => sprintf( esc_html__( '%1$s%3$sSort different sections of the Frontpage in the order you want them to appear.%4$s%3$sYou can add content to widget areas from the %5$sWidgets Management screen%6$s.%4$s%3$sYou can disable areas by clicking the "eye" icon. (This will hide them on the Widgets screen as well)%4$s%2$s', 'strute' ), '<ul>', '</ul>', '<li>', '</li>', '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
		'section'     => $section,
		'type'        => 'sortlist',
		'choices'     => array(
			'image'       => esc_html__( 'Homepage Image', 'strute' ),
			'area_a'      => esc_html__( 'Widget Area A', 'strute' ),
			'area_b'      => esc_html__( 'Widget Area B', 'strute' ),
			'area_c'      => esc_html__( 'Widget Area C', 'strute' ),
			'area_d'      => esc_html__( 'Widget Area D', 'strute' ),
			'content'     => esc_html__( 'Homepage Content', 'strute' ),
			'area_e'      => esc_html__( 'Widget Area E', 'strute' ),
			'area_f'      => esc_html__( 'Widget Area F', 'strute' ),
			'area_g'      => esc_html__( 'Widget Area G', 'strute' ),
			'area_h'      => esc_html__( 'Widget Area H', 'strute' ),
			'area_i'      => esc_html__( 'Widget Area I', 'strute' ),
			'area_j'      => esc_html__( 'Widget Area J', 'strute' ),
			'area_k'      => esc_html__( 'Widget Area K', 'strute' ),
			'area_l'      => esc_html__( 'Widget Area L', 'strute' ),
		),
		'default'     => array(
			'area_g'  => array( 'sortitem_hide' => 1, ),
			'area_h'  => array( 'sortitem_hide' => 1, ),
			'area_i'  => array( 'sortitem_hide' => 1, ),
			'area_j'  => array( 'sortitem_hide' => 1, ),
			'area_k'  => array( 'sortitem_hide' => 1, ),
			'area_l'  => array( 'sortitem_hide' => 1, ),
		),
		'options'     => array(),
		'attributes'  => array(
			'flypanel'      => true,
			'hideable'      => true,
			'sortable'      => true,
		),
		'priority'    => '850',
		'active_callback' => 'strute_callback_frontpage_sections',
		'transport' => 'postMessage',
	);

	$frontpagemodule_ops = apply_filters( 'strute_frontpage_widgetarea_sectionbg_index', array(
		'area_a'      => esc_html__( 'Widget Area A', 'strute' ),
		'area_b'      => esc_html__( 'Widget Area B', 'strute' ),
		'area_c'      => esc_html__( 'Widget Area C', 'strute' ),
		'area_d'      => esc_html__( 'Widget Area D', 'strute' ),
		'area_e'      => esc_html__( 'Widget Area E', 'strute' ),
		'area_f'      => esc_html__( 'Widget Area F', 'strute' ),
		'area_g'      => esc_html__( 'Widget Area G', 'strute' ),
		'area_h'      => esc_html__( 'Widget Area H', 'strute' ),
		'area_i'      => esc_html__( 'Widget Area I', 'strute' ),
		'area_j'      => esc_html__( 'Widget Area J', 'strute' ),
		'area_k'      => esc_html__( 'Widget Area K', 'strute' ),
		'area_l'      => esc_html__( 'Widget Area L', 'strute' ),
		'content'     => esc_html__( 'Homepage Content', 'strute' ),
		'image'       => esc_html__( 'Homepage Image', 'strute' ),
		) );

	foreach ( $frontpagemodule_ops as $fpgmodid => $fpgmodname ) {

		$settings["frontpage_sectionbg_{$fpgmodid}"] = array(
			'label'       => '',
			'section'     => $section,
			'type'        => 'group',
			'startwrap'   => 'fp-section-bg-button',
			'button'      => esc_html__( 'Module Background', 'strute' ),
			'options'     => array(
				'descrip' => array(
					'label'       => '',
					'type'        => 'content',
					'content'     => '<span class="hoot-module-bg-title">' . $fpgmodname . '</span>',
				),
			),
			'priority'    => '860',
		);

		if ( $fpgmodid === 'image' ) {
			$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['descrip'] = array(
				'type'        => 'content',
				/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
				'content'     => '<span class="hoot-module-bg-title">' . esc_html__( 'Homepage Image', 'strute' ) . '</span>' . sprintf( esc_html__( '%1$sThis module shows the image you have set in %3$sHomepage Image%4$s section%2$s', 'strute' ),
					'<p>', '</p>',
					'<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=header_image' ) ) . '" rel="focuslink" data-focustype="control" data-href="header_image" class="hoot-flypanel-close">', '</a>'
				),
			);
			continue;
		}
		if ( $fpgmodid === 'content' ) {
			$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['descrip'] = array(
				'type'        => 'content',
				/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
				'content'     => '<span class="hoot-module-bg-title">' . esc_html__( 'Homepage Content', 'strute' ) . '</span>' . sprintf( esc_html__( '%1$sThis module shows the content you have set in %3$sHomepage Content%4$s section%2$s%9$s%11$sIt shows your %7$sBlog Posts%8$s if you selected %3$sYour Latest Posts%4$s%12$s%11$sIt shows the %7$sPage Content%8$s if you selected %3$sA static page%4$s%12$s%10$s', 'strute' ),
					'<p>', '</p>',
					'<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=show_on_front' ) ) . '" rel="focuslink" data-focustype="control" data-href="show_on_front" class="hoot-flypanel-close">', '</a>',
					'<a href="' . esc_url( admin_url('options-reading.php') ) . '" target="_blank">', '</a>',
					'<strong>', '</strong>',
					'<ul>', '</ul>',
					'<li>', '</li>',
				),
			);
			$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['title'] = array(
				'label'       => esc_html__( 'Title (optional)', 'strute' ),
				'type'        => 'text',
				'transport' => 'postMessage',
			);
		} else {
			$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['columns'] = array(
				'label'   => esc_html__( 'Columns', 'strute' ),
				'type'    => 'select',
				'type'        => 'radioimage',
				'choices' => array(
					'100'         => $imagepath . '1-1.png',
					'50-50'       => $imagepath . '2-1.png',
					'33-66'       => $imagepath . '2-2.png',
					'66-33'       => $imagepath . '2-3.png',
					'25-75'       => $imagepath . '2-5.png',
					'75-25'       => $imagepath . '2-4.png',
					'33-33-33'    => $imagepath . '3-1.png',
					'25-25-50'    => $imagepath . '3-4.png',
					'25-50-25'    => $imagepath . '3-3.png',
					'50-25-25'    => $imagepath . '3-2.png',
					'25-25-25-25' => $imagepath . '4-1.png',
				),
				'default' => '100',
				'transport' => 'postMessage',
			);
		}

		$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['grid'] = array(
			'label'    => esc_html__( 'Layout', 'strute' ),
			'sublabel' => esc_html__( 'The fully stretched grid layout is especially useful for displaying full width slider widgets.', 'strute' ),
			'type'     => 'radioimage',
			'choices'     => array(
				'boxed'   => $imagepath . 'fp-widgetarea-boxed.png',
				'stretch' => $imagepath . 'fp-widgetarea-stretch.png',
			),
			'default'  => 'boxed',
			'transport' => 'postMessage',
		);

		$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['type'] = array(
			'label'   => esc_html__( 'Background Type', 'strute' ),
			'type'    => 'radio',
			'choices' => array(
				'none'        => esc_html__( 'None', 'strute' ),
				'color'       => esc_html__( 'Color', 'strute' ),
				'image'       => esc_html__( 'Image', 'strute' ),
			),
			'default' => 'none',
			'transport' => 'postMessage',
		);
		$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['color'] = array(
			'label'       => esc_html__( "Background Color (Select 'Color' above)", 'strute' ),
			'type'        => 'color',
			'default'     => $module_bg_default,
			'transport' => 'postMessage',
		);
		$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['image'] = array(
			'label'       => esc_html__( "Background Image (Select 'Image' above)", 'strute' ),
			'type'        => 'image',
			'transport' => 'postMessage',
		);
		$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['parallax'] = array(
			'label'   => esc_html__( 'Apply Parallax Effect to Background Image', 'strute' ),
			'type'    => 'checkbox',
			'default' => 1,
			'transport' => 'postMessage',
		);
		$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['font'] = array(
			'label'   => esc_html__( 'Font Color', 'strute' ),
			'type'    => 'radio',
			'choices' => array(
				'theme'       => esc_html__( 'Default Theme Color', 'strute' ),
				'color'       => esc_html__( 'Custom Font Color', 'strute' ),
				'force'       => esc_html__( 'Force Custom Font Color', 'strute' ),
			),
			'default' => 'theme',
			'transport' => 'postMessage',
		);
		$settings["frontpage_sectionbg_{$fpgmodid}"]['options']['fontcolor'] = array(
			'label'       => esc_html__( "Custom Font Color (select 'Custom Font Color' above)", 'strute' ),
			'type'        => 'color',
			'default'     => $module_fontcolor_default,
			'transport' => 'postMessage',
		);

	} // end foreach

	/** Section **/

	$section = 'archives';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Archives (Blog, Cats, Tags)', 'strute' ),
		'priority'    => '85',
	);

	$settings['archive_featured_image'] = array(
		'label'       => esc_html__( 'Archive Title Area Background', 'strute' ),
		'description' => esc_html__( 'Display featured image on archive pages like Blog page, Categories page, WooCommerce Shop page etc', 'strute' ),
		'section'     => $section,
		'type'        => 'select',
		'choices'     => array(
			'none'                => esc_html__( 'No Background', 'strute'),
			'staticheader-nocrop' => esc_html__( 'Featured Image (No Cropping)', 'strute'),
			'staticheader'        => esc_html__( 'Featured Image (Cropped)', 'strute'),
			'header'              => esc_html__( 'Featured Image (Parallax Effect)', 'strute'),
		),
		'default'     => 'staticheader',
		'priority'    => '870',
		'transport' => 'postMessage',
	);

	$settings['archivetypepnote'] = array(
		'label'       => esc_html__( 'Archive (Blog) Layout', 'strute' ),
		'section'     => $section,
		'type'        => 'note',
		/* Translators: strong tags */
		'priority'    => '880',
	);

	$settings['archive_type'] = array(
		'label'       => esc_html__( 'Archive (Blog) Layout', 'strute' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'big'          => $imagepath . 'archive-big.png',
			'block2'       => $imagepath . 'archive-block2.png',
			'block3'       => $imagepath . 'archive-block3.png',
			'mixed-block2' => $imagepath . 'archive-mixed-block2.png',
			'mixed-block3' => $imagepath . 'archive-mixed-block3.png',
		),
		'default'     => 'big',
		'priority'    => '890',
	);

	$settings['archive_post_content'] = array(
		'label'       => esc_html__( 'Post Items Content', 'strute' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'none' => esc_html__( 'None', 'strute' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'strute' ),
			'full-content' => esc_html__( 'Full Post Content', 'strute' ),
		),
		'default'     => 'excerpt',
		'description' => esc_html__( 'Content to display for each post in the list', 'strute' ),
		'priority'    => '900',
	);

	$settings['archive_post_meta'] = array(
		'label'       => esc_html__( 'Meta Information for Post List Items', 'strute' ),
		'sublabel'    => esc_html__( 'Check which meta information to display for each post item in the archive list.', 'strute' ),
		'section'     => $section,
		'type'        => 'sortlist',
		'choices'     => array(
			'author'   => esc_html__( 'Author', 'strute' ),
			'date'     => esc_html__( 'Date', 'strute' ),
			'cats'     => esc_html__( 'Categories', 'strute' ),
			'tags'     => esc_html__( 'Tags', 'strute' ),
			'comments' => esc_html__( 'No. of comments', 'strute' ),
		),
		'default'     => array(
			'tags' => array( 'sortitem_hide' => 1, ),
			'comments' => array( 'sortitem_hide' => 1, ),
		),
		'options'     => array(),
		'attributes'  => array(
			'hideable'      => true,
			'sortable'      => true,
			'ulclass'       => 'hoot-sortlist-multicheck',
		),
		'selective_refresh' => array( 'archive_post_meta_partial', array(
			'selector'            => '.blog .entry-byline, .home .entry-byline, .plural .entry-byline',
			'settings'            => array( 'archive_post_meta' ),
			'render_callback'     => 'strute_callback_archive_post_meta',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // prevents full refresh on non applicable views
			) ),
		'priority'    => '910',
	);

	$settings['excerpt_length'] = array(
		'label'       => esc_html__( 'Excerpt Length', 'strute' ),
		'section'     => $section,
		'type'        => 'betterrange',
		'description' => esc_html__( 'Number of words in excerpt.', 'strute' ),
		'input_attrs' => array(
			'min'  => 5,
			'max'  => 350,
			'step' => 1,
		),
		'default'     => 50,
		'showreset'   => 50,
		'priority'    => '920',
	);

	$settings['read_more'] = array(
		'label'       => esc_html__( "'Continue Reading' Text", 'strute' ),
		'section'     => $section,
		'type'        => 'text',
		'description' => esc_html__( "Replace the default 'Continue Reading' text. Leave empty if you dont want to change it.", 'strute' ),
		'input_attrs' => array(
			'placeholder' => esc_html__( 'default: Continue Reading', 'strute' ),
		),
		'default'     => esc_html__( 'Continue Reading', 'strute' ),
		'transport' => 'postMessage',
		'priority'    => '930',
	);

	/** Section **/

	$section = 'singular';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Single (Posts, Pages)', 'strute' ),
		'priority'    => '90',
	);

	$settings['page_header_full'] = array(
		'label'       => esc_html__( 'Stretch Title Area to Full Width at Top', 'strute' ),
		'sublabel'    => '<img src="' . $imagepath . 'page-header.png">',
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'default'    => esc_html__( 'Default (Archives, Blog, Woocommerce etc.)', 'strute' ),
			'posts'      => esc_html__( 'For All Posts', 'strute' ),
			'pages'      => esc_html__( 'For All Pages', 'strute' ),
			'no-sidebar' => esc_html__( 'Always override for full width pages (any page which has no sidebar)', 'strute' ),
		),
		'default'     => 'default, posts, pages, no-sidebar',
		'description' => esc_html__( 'This is the Page Header area containing Page/Post Title and Meta details like author, categories etc.', 'strute' ),
		'priority'    => '940',
	);

	$settings['singlemetapnote'] = array(
		'section'     => $section,
		'type'        => 'note',
		'priority'    => '950',
	);

	$settings['singular_post_page'] = array(
		'section'     => $section,
		'type'        => 'tabs',
		'priority'    => '960',
		'options'     => array(
			'posts' => array(

				'post_featured_image' => array(
					'label'       => esc_html__( 'Display Featured Image (Post)', 'strute' ),
					'type'        => 'select',
					'choices'     => array(
						'none'                => esc_html__( 'Do not display', 'strute'),
						'staticheader-nocrop' => esc_html__( 'Header Background (No Cropping)', 'strute'),
						'staticheader'        => esc_html__( 'Header Background (Cropped)', 'strute'),
						'header'              => esc_html__( 'Header Background (Parallax Effect)', 'strute'),
						'content'             => esc_html__( 'Beginning of content', 'strute'),
					),
					'default'     => 'content',
					'description' => esc_html__( 'Display featured image on a Post page.', 'strute' ),
					'transport' => 'postMessage',
				),

				'post_meta_location' => array(
					'label'       => esc_html__( 'Meta Information location', 'strute' ),
					'type'        => 'radio',
					'choices'     => array(
						'top'    => esc_html__( 'Top (below title)', 'strute' ),
						'bottom' => esc_html__( 'Bottom (after content)', 'strute' ),
					),
					'default'     => 'top',
				),

				'post_meta' => array(
					'label'       => esc_html__( 'Meta Information on Posts', 'strute' ),
					'sublabel'    => esc_html__( "Check which meta information to display on an individual 'Post' page", 'strute' ),
					'type'        => 'sortlist',
					'choices'     => array(
						'author'   => esc_html__( 'Author', 'strute' ),
						'date'     => esc_html__( 'Date', 'strute' ),
						'cats'     => esc_html__( 'Categories', 'strute' ),
						'tags'     => esc_html__( 'Tags', 'strute' ),
						'comments' => esc_html__( 'No. of comments', 'strute' )
					),
					'default'     => array(
					),
					'options'     => array(),
					'attributes'  => array(
						'hideable'      => true,
						'sortable'      => true,
						'ulclass'       => 'hoot-sortlist-multicheck',
					),
					'selective_refresh' => array( 'post_meta_partial', array(
						'selector'            => '.singular-post .entry-byline',
						'settings'            => array( 'post_meta' ),
						'render_callback'     => 'strute_callback_post_meta',
						'container_inclusive' => true,
						'fallback_refresh'    => false, // prevents full refresh on non applicable views
						) ),
				),
				'post_prev_next_links' => array(
					'label'       => esc_html__( 'Previous/Next Posts', 'strute' ),
					'sublabel'    => esc_html__( 'Display links to Prev/Next Post links at the end of post content.', 'strute' ),
					'type'        => 'radio',
					'choices'     => array(
						'none'        => esc_html__( 'Do not Display', 'strute' ),
						'text'        => esc_html__( 'Post Title', 'strute' ),
						'thumb'       => esc_html__( 'Post Title and Thumbnail', 'strute' ),
						'fixed-text'  => esc_html__( 'Flyout : Post Title', 'strute' ),
						'fixed-thumb' => esc_html__( 'Flyout : Post Title and Thumbnail', 'strute' ),
					),
					'default'     => 'fixed-thumb',
					'transport' => 'postMessage',
				),
				'post_prev_next_links_selective_refresh_holder' => array(
					'type'        => 'selective_refresh_holder',
					'selective_refresh' => array( 'post_prev_next_links_partial', array(
						'selector'            => '#loop-nav-wrap',
						'settings'            => array( 'post_prev_next_links_selective_refresh_holder' ),
						'render_callback'     => 'strute_post_prev_next_links',
						'container_inclusive' => true,
						'fallback_refresh'    => false, // prevents full refresh on non applicable views
						) ),
				),

			),
			'pages' => array(

				'post_featured_image_page' => array(
					'label'       => esc_html__( 'Display Featured Image (Page)', 'strute' ),
					'type'        => 'select',
					'choices'     => array(
						'none'                => esc_html__( 'Do not display', 'strute'),
						'staticheader-nocrop' => esc_html__( 'Header Background (No Cropping)', 'strute'),
						'staticheader'        => esc_html__( 'Header Background (Cropped)', 'strute'),
						'header'              => esc_html__( 'Header Background (Parallax Effect)', 'strute'),
						'content'             => esc_html__( 'Beginning of content', 'strute'),
					),
					'default'     => 'staticheader',
					'description' => esc_html__( "Display featured image on a 'Page' page.", 'strute' ),
					'transport' => 'postMessage',
				),

				'page_meta_location' => array(
					'label'       => esc_html__( 'Meta Information location', 'strute' ),
					'type'        => 'radio',
					'choices'     => array(
						'top'    => esc_html__( 'Top (below title)', 'strute' ),
						'bottom' => esc_html__( 'Bottom (after content)', 'strute' ),
					),
					'default'     => 'top',
				),

				'page_meta' => array(
					'label'       => esc_html__( 'Meta Information on Page', 'strute' ),
					'sublabel'    => esc_html__( "Check which meta information to display on an individual 'Page' page", 'strute' ),
					'type'        => 'sortlist',
					'choices'     => array(
						'author'   => esc_html__( 'Author', 'strute' ),
						'date'     => esc_html__( 'Date', 'strute' ),
						'comments' => esc_html__( 'No. of comments', 'strute' ),
					),
					'default'     => array(
					),
					'options'     => array(),
					'attributes'  => array(
						'hideable'      => true,
						'sortable'      => true,
						'ulclass'       => 'hoot-sortlist-multicheck',
					),
					'selective_refresh' => array( 'page_meta_partial', array(
						'selector'            => '.singular-page .entry-byline',
						'settings'            => array( 'page_meta' ),
						'render_callback'     => 'strute_callback_page_meta',
						'container_inclusive' => true,
						'fallback_refresh'    => false, // prevents full refresh on non applicable views
					) ),
				),

			),
		),
	);

	$settings['article_maxwidth_label'] = array(
		'label'           => esc_html__( 'Article Maximum Width', 'strute' ),
		'section'         => $section,
		'type'            => 'content',
		'description'     => esc_html__( 'Limit the content width within the article for better readability on larger screens.', 'strute' ),
		/* Translators: 1 is the link start markup, 2 is link markup end */
		'content'         => '<img src="' . $imagepath . 'article-maxwidth.png">' . '<br />' . sprintf( esc_html__( 'To add a background to the Article area, go to the %1$sColors Section%2$s.', 'strute' ), '<a href="#" data-cust-linksection="colors" target="_blank">', '</a>' ),
		'priority'    => '970',
	);
	$settings['article_maxwidth_pnote'] = array(
		'section'     => $section,
		'type'        => 'note',
		'priority'    => '980',
	);
	$settings['article_maxwidth'] = array(
		'section'         => $section,
		'type'            => 'betterrange',
		'description'     => esc_html__( 'When Post/Page has a Sidebar present', 'strute' ),
		'displaysuffix'   => 'px',
		'default'         => $article_maxwidth,
		'showreset'       => $article_maxwidth,
		'input_attrs'     => array(
			'min'  => 400,
			'max'  => 1400,
			'step' => 50,
		),
		'priority'    => '990',
		'transport' => 'postMessage',
	);
	$settings['article_maxwidth_nosidebar'] = array(
		'section'         => $section,
		'type'            => 'betterrange',
		'description'     => esc_html__( 'When Post/Page has no Sidebar Layout', 'strute' ),
		'displaysuffix'   => 'px',
		'default'         => $article_maxwidth_nosidebar,
		'showreset'       => $article_maxwidth_nosidebar,
		'input_attrs'     => array(
			'min'  => 400,
			'max'  => 1400,
			'step' => 50,
		),
		'priority'    => '1000',
		'transport' => 'postMessage',
	);

	/** Section **/

	/** Section **/

	$section = 'hootshead-ot';

	$sections[ $section ] = array(
		'title'       => esc_html__( 'Others', 'strute' ),
		'priority'    => '95',
	);

	$settings['ot_settings'] = array(
		'section'     => $section,
		'type'        => 'content',
		'content'     => '',
		'priority'    => '1111',
	);


	/*** Return Options Array ***/
	return apply_filters( 'strute_customizer_options', array(
		'settings' => $settings,
		'sections' => $sections,
		'panels'   => $panels,
	) );

}
endif;

/**
 * Add Options (settings, sections and panels) to Hoot_Customize class options object
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'strute_add_customizer_options' ) ) :
function strute_add_customizer_options() {

	$hoot_customize = Hoot_Customize::get_instance();

	// Add Options
	$options = strute_customizer_options();
	$hoot_customize->add_options( array(
		'settings' => $options['settings'],
		'sections' => $options['sections'],
		'panels' => $options['panels'],
		) );

}
endif;
add_action( 'init', 'strute_add_customizer_options', 0 ); // cannot hook into 'after_setup_theme' as this hook is already being executed (this file is loaded at after_setup_theme @priority 10) (hooking into same hook from within while hook is being executed leads to undesirable effects as $GLOBALS[$wp_filter]['after_setup_theme'] has already been ksorted)
// Hence, we hook into 'init' @priority 0, so that settings array gets populated before 'widgets_init' action ( which itself is hooked to 'init' at priority 1 ) for creating widget areas ( settings array is needed for creating defaults when user value has not been stored )

/**
 * Enqueue custom scripts to customizer screen
 *
 * @since 1.0
 * @return void
 */
function strute_customizer_enqueue_scripts() {
	// Enqueue Styles
	$style_uri = hoot_locate_style( hoot_data()->incuri . 'admin/css/customize' );
	wp_enqueue_style( 'strute-customize-styles', $style_uri, array(),  hoot_data()->hoot_version );
	// Enqueue Scripts
	$script_uri = hoot_locate_script( hoot_data()->incuri . 'admin/js/customize-controls' );
	wp_enqueue_script( 'strute-customize-controls', $script_uri, array( 'jquery', 'wp-color-picker', 'customize-controls', 'hoot-customize' ), hoot_data()->hoot_version, true );
}
// Load scripts at priority 12 so that Custom Controls / Hoot Customizer Interface (@11) have loaded their scripts
add_action( 'customize_controls_enqueue_scripts', 'strute_customizer_enqueue_scripts', 12 );

