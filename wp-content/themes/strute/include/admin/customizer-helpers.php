<?php
/**
 * Helpers for customizer options
 *
 * This file is loaded at 'after_setup_theme' hook with 10 priority.
 */

/**
 * Modify default WordPress Settings Sections and Panels
 *
 * @since 1.0
 * @param object $wp_customize
 * @return void
 */
function strute_modify_default_customizer_options( $wp_customize ) {

	/**
	 * Defaults: [type] => cropped_image
	 *           [width] => 150
	 *           [height] => 150
	 *           [flex_width] => 1
	 *           [flex_height] => 1
	 *           [button_labels] => array(...)
	 *           [label] => Logo
	 */
	$wp_customize->get_control( 'custom_logo' )->section = 'logo';
	$wp_customize->get_control( 'custom_logo' )->priority = 99; // Keep it with logo_image_width->priority
	$wp_customize->get_control( 'custom_logo' )->width = 300;
	$wp_customize->get_control( 'custom_logo' )->height = 180;
	$wp_customize->get_control( 'custom_logo' )->active_callback = 'strute_callback_logo_image';

	if ( function_exists( 'get_site_icon_url' ) ) {
		$wp_customize->get_control( 'site_icon' )->label = esc_html__( 'Site Icon (Favicon)', 'strute' );
	}

	$wp_customize->get_section( 'title_tagline' )->priority = 7;
	$wp_customize->get_section( 'static_front_page' )->priority = 77;
	$wp_customize->get_section( 'static_front_page' )->title = esc_html__( 'Homepage Content', 'strute' );
	if ( current_theme_supports( 'custom-header' ) ) {
		$wp_customize->get_section( 'header_image' )->priority = 78;
		$wp_customize->get_section( 'header_image' )->title = esc_html__( 'Homepage Image', 'strute' );
	}

}
add_action( 'customize_register', 'strute_modify_default_customizer_options', 100 );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since 1.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function strute_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'custom_logo' )->transport = 'postMessage';
}
add_action( 'customize_register', 'strute_customize_register' );

/**
 * Callback Functions for customizer settings
 */

function strute_callback_logo_size( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'mixed' ) ? true : false;
}
function strute_callback_site_title_icon( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'custom' ) ? true : false;
}
function strute_callback_logo_image( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'image' || $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}
function strute_callback_logo_image_width( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'image' || $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}
function strute_callback_logo_custom( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'custom' || $selector == 'mixedcustom' ) ? true : false;
}
function strute_callback_sb1w_px( $control ) {
	$selector = $control->manager->get_setting('sidebar1_width')->value();
	return ( $selector == 'px' ) ? true : false;
}
function strute_callback_sb1w_pcnt( $control ) {
	$selector = $control->manager->get_setting('sidebar1_width')->value();
	return ( $selector == 'pcnt' ) ? true : false;
}
function strute_callback_sb2w_px( $control ) {
	$selector = $control->manager->get_setting('sidebar2_width')->value();
	return ( $selector == 'px' ) ? true : false;
}
function strute_callback_sb2w_pcnt( $control ) {
	$selector = $control->manager->get_setting('sidebar2_width')->value();
	return ( $selector == 'pcnt' ) ? true : false;
}
function strute_callback_goto_top( $control ) {
	$selector = $control->manager->get_setting('disable_goto_top')->value();
	return ( !$selector ) ? true : false;
}
function strute_callback_enabled_anims( $control ) {
	$selector = $control->manager->get_setting('enable_anims')->value();
	return ( $selector ) ? true : false;
}
function strute_callback_autoscroll( $control ) {
	$selector = $control->manager->get_setting('enable_anims')->value();
	$enaled = $control->manager->get_setting('enabled_anims')->value();
	return ( $selector && ( strpos($enaled, 'scrollhash') !== false || strpos($enaled, 'scrollmain') !== false ) ) ? true : false;
}
function strute_callback_topann_content_nopad( $control ) {
	$selector = $control->manager->get_setting('topann_content_stretch')->value();
	return ( $selector == 'stretch' ) ? true : false;
}
function strute_callback_topann_content_bg( $control ) {
	$selector = $control->manager->get_setting('topann_content_style')->value();
	return ( in_array( $selector, array( 'dark-on-custom', 'light-on-custom' ) ) ) ? true : false;
}
function strute_callback_logo_side( $control ) {
	$selector = $control->manager->get_setting('menu_location')->value();
	return ( $selector == 'top' || $selector == 'bottom' || $selector == 'none' ) ? true : false;
}
function strute_callback_menu_align( $control ) {
	$selector = $control->manager->get_setting('menu_location')->value();
	return ( $selector == 'top' || $selector == 'bottom' ) ? true : false;
}
function strute_callback_menu_misc( $control ) {
	$selector = $control->manager->get_setting('menu_location')->value();
	return ( $selector !== 'none' ) ? true : false;
}
function strute_callback_sticky_dtp( $control ) {
	$selector = $control->manager->get_setting('sticky_sitehead_dtp')->value();
	return ( !$selector ) ? false : true;
}
function strute_callback_sticky_dtp_logo( $control ) {
	$mainselector = $control->manager->get_setting('sticky_sitehead_dtp')->value();
	$selector = $control->manager->get_setting('sticky_sitehead_dtp_layout')->value();
	if ( !$mainselector ) return false;
	return ( in_array( $selector, array( 'logo', 'logomenu', 'logomenudiv', 'logotext', 'logotextdiv', 'logomenutext' ) ) ) ? true : false;
}
function strute_callback_sticky_dtp_text( $control ) {
	$mainselector = $control->manager->get_setting('sticky_sitehead_dtp')->value();
	$selector = $control->manager->get_setting('sticky_sitehead_dtp_layout')->value();
	if ( !$mainselector ) return false;
	return ( in_array( $selector, array( 'text', 'logotext', 'logotextdiv', 'logomenutext' ) ) ) ? true : false;
}
function strute_callback_sticky_mob( $control ) {
	$selector = $control->manager->get_setting('sticky_sitehead_mob')->value();
	return ( !$selector ) ? false : true;
}
function strute_callback_sticky_mob_logo( $control ) {
	$mainselector = $control->manager->get_setting('sticky_sitehead_mob')->value();
	$selector = $control->manager->get_setting('sticky_sitehead_mob_layout')->value();
	if ( !$mainselector ) return false;
	return ( in_array( $selector, array( 'logo', 'logomenu', 'logomenudiv', 'logotext', 'logotextdiv', 'logomenutext' ) ) ) ? true : false;
}
function strute_callback_sticky_mob_text( $control ) {
	$mainselector = $control->manager->get_setting('sticky_sitehead_mob')->value();
	$selector = $control->manager->get_setting('sticky_sitehead_mob_layout')->value();
	if ( !$mainselector ) return false;
	return ( in_array( $selector, array( 'text', 'logotext', 'logotextdiv', 'logomenutext' ) ) ) ? true : false;
}
function strute_callback_article_background( $control ) {
	$selector = $control->manager->get_setting('article_background_type')->value();
	return ( in_array( $selector, array( 'background', 'background-whensidebar' ) ) ) ? true : false;
}
function strute_callback_header_image_minheight( $control ) {
	$selector = $control->manager->get_setting('header_image_layout')->value();
	$selector = intval( $selector );
	return ( in_array( $selector, array( 1, 2, 3, 4, 5, 6, 7 ) ) ) ? true : false;
}
function strute_callback_frontpage_sections( $control ) {
	$selector = $control->manager->get_setting('frontpage_sections_enable')->value();
	return ( $selector ) ? true : false;
}
function strute_callback_frontpage_default_sections( $control ) {
	$selector = $control->manager->get_setting('frontpage_sections_enable')->value();
	return ( !$selector ) ? true : false;
}

/**
 * Callback Functions for selective refresh
 */

function strute_callback_archive_post_meta(){
	$metarray = hoot_get_mod('archive_post_meta');
	hoot_display_meta_info( $metarray, 'customizer' ); // Bug: the_author_posts_link() does not work in selective refresh
}
function strute_callback_post_meta(){
	$metarray = hoot_get_mod('post_meta');
	hoot_display_meta_info( $metarray, 'customizer' ); // Bug: the_author_posts_link() does not work in selective refresh
}
function strute_callback_page_meta(){
	$metarray = hoot_get_mod('page_meta');
	hoot_display_meta_info( $metarray, 'customizer' ); // Bug: the_author_posts_link() does not work in selective refresh
}
function strute_postfooter(){
	get_template_part( 'template-parts/footer', 'postfooter' ); // Loads the template-parts/footer-postfooter.php template.
}

/**
 * Dynamic css data set via postMessage script in customizer preview
 *
 * @since 1.0
 * @access public
 */
function strute_customize_dynamic_cssrules() {
	// Add in Customizer Only
	if ( is_customize_preview() ) {
		$defaults = strute_default_style();

		$settings = array(
			'customlogolineids' => array( 'line1', 'line2', 'line3', 'line4' ),
			'fpareaids' => array( 'area_a', 'area_b', 'area_c', 'area_d', 'area_e', 'area_f', 'area_g', 'area_h', 'area_i', 'area_j', 'area_k', 'area_l', 'content', 'image' ),
			'fpfontselector' => hoot_fp_customfontcolor_selector( false, 'varid' ),
		);

		// Settings mapped to css variables
		$settings['goto_top_offset'] = '--hoot-goto-offset';
		$settings['widgetmargin'] = '--hoot-widget-margin';
		$settings['sidebar1_width'] = '--hoot-sidebar1-width';
		$settings['sidebar2_width'] = '--hoot-sidebar2-width';
		$settings['halfwidgetmargin'] = '--hoot-widget-halfmargin';
		$defaults['halfwidgetmargin'] = array( 'desktop' => 25, 'tablet' => 25, 'mobile' => 25 ); // Non official settings need to have a default added here
		$settings['article_maxwidth']           = '--hoot-article-width';
		$settings['article_maxwidth_nosidebar'] = '--hoot-article-width-nosb';
		$settings['topann_content_bg'] = '--hoot-textstyle-topannbg';
		$settings['site_title_icon_size'] = '--hoot-logo-iconsize';
		$settings['logo_image_width'] = '--hoot-logo-maximgwidth';
		$settings['sticky_sitehead_dtp_logozoom'] = '--hoot-sticky-dtplogozoom';
		$settings['sticky_sitehead_mob_logozoom'] = '--hoot-sticky-moblogozoom';
		$settings['background-color'] = '--hoot-body-bg';
		$settings['site_background_style'] = '--hoot-body-varid';
		$settings['box_background_color'] = '--hoot-box-bg';
		$settings['gridarticle_bg'] = '--hoot-gridarticle-bg';
		$settings['article_background_color'] = '--hoot-article-bg';
		$settings['accent_color'] = '--hoot-accentcolor';
		$settings['link_color'] = '--hoot-linkcolor';
		$settings['link_hover_color'] = '--hoot-linkhovercolor';
		$settings['accent_font'] = '--hoot-accentfont';
		$settings['button_color'] = '--hoot-buttoncolor';
		$settings['button_font'] = '--hoot-buttonfont';
		$settings['logo_fontface_style'] = '--hoot-logo-varid';
		$settings['headings_fontface_style'] = '--hoot-headings-varid';
		$settings['subheadings_fontface_style'] = '--hoot-subheadings-varid';
		$settings['logo_custom_line_font'] = '--hoot-logo-varid-size';

		foreach ( array(
			'minheight', 'conpad', 'imgpad', 'headsize', 'subheadsize', 'textsize', 'btnsize',
			'bg', 'headcolor', 'subheadcolor', 'textcolor', 'btncolor', 'btnbg', 'btncolor2', 'btnbg2',
			'conbg', 'overlay',
		) as $key ) {
			$settings["header_image_{$key}"] = "--hoot-fimg-{$key}";
		}
		$handle = apply_filters( 'hoot_style_builder_inline_style_handle', 'hoot-style' );
		$hootpload = '';

		$hootInlineStyles = apply_filters( 'hoot_customize_dynamic_cssrules', array( $handle, $hootpload, $settings, $defaults ) );
		wp_localize_script( 'hoot-customize-preview', 'hootInlineStyles', $hootInlineStyles );
	}
}
add_action( 'wp_enqueue_scripts', 'strute_customize_dynamic_cssrules', 999 );
