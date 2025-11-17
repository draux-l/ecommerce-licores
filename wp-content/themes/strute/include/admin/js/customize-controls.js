/**
 * Theme Customizer
 */


( function( api ) {

	// Extends our custom "hoot-theme" section.
	api.sectionConstructor['hoot-theme'] = api.Section.extend( {
		// No events for this type of section.
		attachEvents: function () {},
		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

	/*** JS equivalent for active_callback ***/

	api.bind('ready', function () {
		api.control('sidebar1_width', function (control) {
			control.setting.bind(function (value) {
				switch (value) {
					case 'auto':
						api.control( 'sidebar1_width_px', function( control ) {   control.deactivate(); } );
						api.control( 'sidebar1_width_pcnt', function( control ) { control.deactivate(); } );
						break;
					case 'px':
						api.control( 'sidebar1_width_px', function( control ) {   control.activate(); } );
						api.control( 'sidebar1_width_pcnt', function( control ) { control.deactivate(); } );
						break;
					case 'pcnt':
						api.control( 'sidebar1_width_px', function( control ) {   control.deactivate(); } );
						api.control( 'sidebar1_width_pcnt', function( control ) { control.activate(); } );
						break;
				}
			});
		});
		api.control('sidebar2_width', function (control) {
			control.setting.bind(function (value) {
				switch (value) {
					case 'auto':
						api.control( 'sidebar2_width_px', function( control ) {   control.deactivate(); } );
						api.control( 'sidebar2_width_pcnt', function( control ) { control.deactivate(); } );
						break;
					case 'px':
						api.control( 'sidebar2_width_px', function( control ) {   control.activate(); } );
						api.control( 'sidebar2_width_pcnt', function( control ) { control.deactivate(); } );
						break;
					case 'pcnt':
						api.control( 'sidebar2_width_px', function( control ) {   control.deactivate(); } );
						api.control( 'sidebar2_width_pcnt', function( control ) { control.activate(); } );
						break;
				}
			});
		});
		api.control('disable_goto_top', function (control) {
			control.setting.bind(function (value) {
				if ( value ) {
					api.control( 'goto_top_icon', function( control ) {       control.deactivate(); } );
					api.control( 'goto_top_mobile', function( control ) {     control.deactivate(); } );
					api.control( 'goto_top_icon_style', function( control ) { control.deactivate(); } );
					api.control( 'goto_top_offset', function( control ) {     control.deactivate(); } );
				} else {
					api.control( 'goto_top_icon', function( control ) {       control.activate(); } );
					api.control( 'goto_top_mobile', function( control ) {     control.activate(); } );
					api.control( 'goto_top_icon_style', function( control ) { control.activate(); } );
					api.control( 'goto_top_offset', function( control ) {     control.activate(); } );
				}
			});
		});
		api.control('goto_top_icon', function (control) {
			control.setting.bind(function (value) {
				var iconholders = jQuery('#customize-control-goto_top_icon_style .gotostyle_s i, #customize-control-goto_top_icon_style .gotostyle_c i');
				if ( value && iconholders.length )
					iconholders.removeClass().addClass(value);
			});
		});
		api.control('enable_anims', function (control) {
			control.setting.bind(function (value) {
				if ( value ) {
					api.control( 'enabled_anims', function( control ) {       control.activate(); } );
					var enabled = api('enabled_anims')();
					if ( typeof enabled === 'string' && ( enabled.indexOf('scrollhash') !== -1 || enabled.indexOf('scrollmain') !== -1 ) )
						api.control( 'autoscroll_scope', function( control ) {    control.activate(); } );
					else
						api.control( 'autoscroll_scope', function( control ) {    control.deactivate(); } );
				} else {
					api.control( 'enabled_anims', function( control ) {       control.deactivate(); } );
					api.control( 'autoscroll_scope', function( control ) {    control.deactivate(); } );
				}
			});
		});
		api.control('enabled_anims', function (control) {
			control.setting.bind(function (value) {
					var enabled = value;
					if ( typeof enabled === 'string' && ( enabled.indexOf('scrollhash') !== -1 || enabled.indexOf('scrollmain') !== -1 ) )
						api.control( 'autoscroll_scope', function( control ) {    control.activate(); } );
					else
						api.control( 'autoscroll_scope', function( control ) {    control.deactivate(); } );
			});
		});
		api.control('topann_content_style', function (control) {
			control.setting.bind(function (value) {
				switch (value) {
					case 'dark-on-custom':
					case 'light-on-custom':
						api.control( 'topann_content_bg', function( control ) {    control.activate(); } );
						break;
					default:
						api.control( 'topann_content_bg', function( control ) {    control.deactivate(); } );
						break;
				}
			});
		});
		api.control('topann_content_stretch', function (control) {
			control.setting.bind(function (value) {
				switch (value) {
					case 'grid':
						api.control( 'topann_content_nopad', function( control ) { control.deactivate(); } );
						break;
					case 'stretch':
						api.control( 'topann_content_nopad', function( control ) { control.activate(); } );
						break;
				}
			});
		});
		api.control('logo', function (control) {
			control.setting.bind(function (value) {
				switch (value) {
					case 'text':
						api.control( 'logo_size', function( control ) {            control.activate(); } );
						api.control( 'site_title_icon', function( control ) {      control.activate(); } );
						api.control( 'site_title_icon_size', function( control ) { control.activate(); } );
						api.control( 'custom_logo', function( control ) {          control.deactivate(); } );
						api.control( 'logo_image_width', function( control ) {     control.deactivate(); } );
						api.control( 'logo_custom', function( control ) {          control.deactivate(); } );
						break;
					case 'custom':
						api.control( 'logo_size', function( control ) {            control.deactivate(); } );
						api.control( 'site_title_icon', function( control ) {      control.activate(); } );
						api.control( 'site_title_icon_size', function( control ) { control.activate(); } );
						api.control( 'custom_logo', function( control ) {          control.deactivate(); } );
						api.control( 'logo_image_width', function( control ) {     control.deactivate(); } );
						api.control( 'logo_custom', function( control ) {          control.activate(); } );
						break;
					case 'image':
						api.control( 'logo_size', function( control ) {            control.deactivate(); } );
						api.control( 'site_title_icon', function( control ) {      control.deactivate(); } );
						api.control( 'site_title_icon_size', function( control ) { control.deactivate(); } );
						api.control( 'custom_logo', function( control ) {          control.activate(); } );
						api.control( 'logo_image_width', function( control ) {     control.activate(); } );
						api.control( 'logo_custom', function( control ) {          control.deactivate(); } );
						break;
					case 'mixed':
						api.control( 'logo_size', function( control ) {            control.activate(); } );
						api.control( 'site_title_icon', function( control ) {      control.deactivate(); } );
						api.control( 'site_title_icon_size', function( control ) { control.deactivate(); } );
						api.control( 'custom_logo', function( control ) {          control.activate(); } );
						api.control( 'logo_image_width', function( control ) {     control.activate(); } );
						api.control( 'logo_custom', function( control ) {          control.deactivate(); } );
						break;
					case 'mixedcustom':
						api.control( 'logo_size', function( control ) {            control.deactivate(); } );
						api.control( 'site_title_icon', function( control ) {      control.deactivate(); } );
						api.control( 'site_title_icon_size', function( control ) { control.deactivate(); } );
						api.control( 'custom_logo', function( control ) {          control.activate(); } );
						api.control( 'logo_image_width', function( control ) {     control.activate(); } );
						api.control( 'logo_custom', function( control ) {          control.activate(); } );
						break;
				}
			});
		});
		api.control('menu_location', function (control) {
			control.setting.bind(function (value) {
				switch (value) {
					case 'top': case 'bottom':
						api.control( 'logo_side_headline', function( control ) {   control.activate(); } );
						api.control( 'logo_side', function( control ) {            control.activate(); } );
						api.control( 'fullwidth_menu_align', function( control ) { control.activate(); } );
						api.control( 'disable_table_menu', function( control ) {   control.activate(); } );
						api.control( 'mobile_menu_label', function( control ) {    control.activate(); } );
						api.control( 'mobile_submenu_click', function( control ) { control.activate(); } );
						break;
					case 'none':
						api.control( 'logo_side_headline', function( control ) {   control.activate(); } );
						api.control( 'logo_side', function( control ) {            control.activate(); } );
						api.control( 'fullwidth_menu_align', function( control ) { control.deactivate(); } );
						api.control( 'disable_table_menu', function( control ) {   control.deactivate(); } );
						api.control( 'mobile_menu_label', function( control ) {    control.deactivate(); } );
						api.control( 'mobile_submenu_click', function( control ) { control.deactivate(); } );
						break;
					case 'side':
						api.control( 'logo_side_headline', function( control ) {   control.deactivate(); } );
						api.control( 'logo_side', function( control ) {            control.deactivate(); } );
						api.control( 'fullwidth_menu_align', function( control ) { control.deactivate(); } );
						api.control( 'disable_table_menu', function( control ) {   control.activate(); } );
						api.control( 'mobile_menu_label', function( control ) {    control.activate(); } );
						api.control( 'mobile_submenu_click', function( control ) { control.activate(); } );
						break;
				}
			});
		});

		var stickLogoOptions = ['logo', 'logomenu', 'logomenudiv', 'logotext', 'logotextdiv', 'logomenutext'];
		var stickTextOptions = ['text', 'logotext', 'logotextdiv', 'logomenutext'];
		api.control('sticky_sitehead_dtp', function (control) {
			control.setting.bind(function (value) {
				var displaycontrol = api('sticky_sitehead_dtp_layout')();
				if ( value ) {
					api.control( 'sticky_sitehead_dtp_layout', function( control ) {          control.activate(); } );
					if ( stickLogoOptions.includes(displaycontrol) ) {
					api.control( 'sticky_sitehead_dtp_logozoom', function( control ) {        control.activate(); } );
					}
					if ( stickTextOptions.includes(displaycontrol) ) {
					api.control( 'sticky_sitehead_dtp_text', function( control ) {            control.activate(); } );
					}
				} else {
					api.control( 'sticky_sitehead_dtp_layout', function( control ) {          control.deactivate(); } );
					api.control( 'sticky_sitehead_dtp_logozoom', function( control ) {        control.deactivate(); } );
					api.control( 'sticky_sitehead_dtp_text', function( control ) {            control.deactivate(); } );
				}
			});
		});
		api.control('sticky_sitehead_mob', function (control) {
			control.setting.bind(function (value) {
				var displaycontrol = api('sticky_sitehead_mob_layout')();
				if ( value ) {
					api.control( 'sticky_sitehead_mob_layout', function( control ) {          control.activate(); } );
					if ( stickLogoOptions.includes(displaycontrol) ) {
					api.control( 'sticky_sitehead_mob_logozoom', function( control ) {        control.activate(); } );
					}
					if ( stickTextOptions.includes(displaycontrol) ) {
					api.control( 'sticky_sitehead_mob_text', function( control ) {            control.activate(); } );
					}
				} else {
					api.control( 'sticky_sitehead_mob_layout', function( control ) {          control.deactivate(); } );
					api.control( 'sticky_sitehead_mob_logozoom', function( control ) {        control.deactivate(); } );
					api.control( 'sticky_sitehead_mob_text', function( control ) {            control.deactivate(); } );
				}
			});
		});
		api.control('sticky_sitehead_dtp_layout', function (control) {
			control.setting.bind(function (value) {
				if ( stickLogoOptions.includes(value) ) {
					api.control( 'sticky_sitehead_dtp_logozoom', function( control ) {        control.activate(); } );
				} else {
					api.control( 'sticky_sitehead_dtp_logozoom', function( control ) {        control.deactivate(); } );
				}
				if ( stickTextOptions.includes(value) ) {
					api.control( 'sticky_sitehead_dtp_text', function( control ) {            control.activate(); } );
				} else {
					api.control( 'sticky_sitehead_dtp_text', function( control ) {            control.deactivate(); } );
				}
			});
		});
		api.control('sticky_sitehead_mob_layout', function (control) {
			control.setting.bind(function (value) {
				if ( stickLogoOptions.includes(value) ) {
					api.control( 'sticky_sitehead_mob_logozoom', function( control ) {        control.activate(); } );
				} else {
					api.control( 'sticky_sitehead_mob_logozoom', function( control ) {        control.deactivate(); } );
				}
				if ( stickTextOptions.includes(value) ) {
					api.control( 'sticky_sitehead_mob_text', function( control ) {            control.activate(); } );
				} else {
					api.control( 'sticky_sitehead_mob_text', function( control ) {            control.deactivate(); } );
				}
			});
		});
		api.control('article_background_type', function (control) {
			control.setting.bind(function (value) {
				switch (value) {
					case 'background':
					case 'background-whensidebar':
						api.control( 'article_background_color', function( control ) {          control.activate(); } );
						break;
					default:
						api.control( 'article_background_color', function( control ) {          control.deactivate(); } );
						break;
				}
			});
		});
		api.control('header_image_layout', function (control) {
			control.setting.bind(function (value) {
				var ival = parseInt( value );
				if ( [ 1, 2, 3, 4, 5, 6, 7 ].includes(ival) ) {
					api.control( 'header_image_minheight', function( control ) {         control.activate();   } );
				} else {
					api.control( 'header_image_minheight', function( control ) {         control.deactivate(); } );
				}
			});
		});

		api.control('frontpage_sections_enable', function (control) {
			control.setting.bind(function (value) {
				if ( value ) {
					api.control( 'frontpage_sections', function( control ) {         control.activate();   } );
					api.control( 'frontpage_default_sections', function( control ) { control.deactivate(); } );
				} else {
					api.control( 'frontpage_sections', function( control ) {         control.deactivate(); } );
					api.control( 'frontpage_default_sections', function( control ) { control.activate();   } );
				}
			});
		});

		jQuery(document).ready(function($) {
			$('a[rel="focuslink"]').click(function(e) {
				e.preventDefault();
				var id = $(this).data('href'),
					type = $(this).data('focustype');
				if(api[type].has(id)) {
					api[type].instance(id).focus();
				}
			});

			// Color Presets
			$('.hoot-style-presets > div').on( 'click', function( event ) {
				var preset = $(this).data('preset');
				if ( typeof preset !== 'object' ) {
					try { preset = JSON.parse(preset); } catch(e) {}
				}
				if ( typeof preset === 'object' && preset !== null ) {
					for (var key in preset) { if (preset.hasOwnProperty(key)) {
						api( key ).set( preset[key] );
					} }
				}
			});

			// Desktop/Mobile Sticky Header
			var $footer_devices = $( '#customize-footer-actions .devices button' ),
				$overlaybody = $( '.wp-full-overlay' ),
				$hoottabdesktop = $( '.hoot-tabs-heading .hoot-tab-control[data-tab="desktop"]' ),
				$hoottabmobile = $( '.hoot-tabs-heading .hoot-tab-control[data-tab="mobile"]' );
			$footer_devices.on( 'click', function( event ) {
				var device = $(this).data('device');
				if ( device === 'desktop' )
					if ( ! $hoottabdesktop.is('.hootactive') ) { $hoottabdesktop.click(); }
				if ( device === 'tablet' || device === 'mobile' )
					if ( ! $hoottabmobile.is('.hootactive') ) { $hoottabmobile.click(); }
			});
			if ( $hoottabdesktop.length ) {
				$hoottabdesktop.on('click', function () {
					if ( ! $overlaybody.is('.preview-desktop') ) {
						$overlaybody.removeClass( 'preview-tablet preview-mobile' ).addClass( 'preview-desktop' );
						$footer_devices.removeClass( 'active' ).attr( 'aria-pressed', false );
						$footer_devices.filter( '.preview-desktop' ).addClass( 'active' ).attr( 'aria-pressed', true );
					}
				});
			}
			if ( $hoottabmobile.length ) {
				$hoottabmobile.on('click', function () {
					if ( ! $overlaybody.is('.preview-tablet, .preview-mobile') ) {
						$overlaybody.removeClass( 'preview-desktop' ).addClass( 'preview-tablet' );
						$footer_devices.removeClass( 'active' ).attr( 'aria-pressed', false );
						$footer_devices.filter( '.preview-tablet' ).addClass( 'active' ).attr( 'aria-pressed', true );
					}
				});
			}

			var areaIds = ['area_a', 'area_b', 'area_c', 'area_d', 'area_e', 'area_f', 'area_g', 'area_h', 'area_i', 'area_j', 'area_k', 'area_l', 'content', 'image'];
			function updateBgVisibility($input,areaId,initial=false) {
				var selectedValue = $input.val();
				var $parentli = $input.closest('li');
				var $colorli = $parentli.siblings("#customize-control-frontpage_sectionbg_" + areaId + "-color");
				var $imageli = $parentli.siblings("#customize-control-frontpage_sectionbg_" + areaId + "-image");
				var $parallaxli = $parentli.siblings("#customize-control-frontpage_sectionbg_" + areaId + "-parallax");
				if (selectedValue === "none") {
					if ( initial ) {
						$colorli.hide(); $imageli.hide(); $parallaxli.hide();
					} else {
						$colorli.slideUp('fast'); $imageli.slideUp('fast'); $parallaxli.slideUp('fast');
					}
				} else if (selectedValue === "color" || selectedValue === "highlight") {
					if ( initial ) {
						$colorli.show(); $imageli.hide(); $parallaxli.hide();
					} else {
						$colorli.slideDown('fast'); $imageli.slideUp('fast'); $parallaxli.slideUp('fast');
					}
				} else if (selectedValue === "image") {
					if ( initial ) {
						$colorli.hide(); $imageli.show(); $parallaxli.show();
					} else {
						$colorli.slideUp('fast'); $imageli.slideDown('fast'); $parallaxli.slideDown('fast');
					}
				}
			}
			function updateFontVisibility($input,areaId,initial=false) {
				var selectedValue = $input.val();
				var $parentli = $input.closest('li');
				var $colorli = $parentli.siblings("#customize-control-frontpage_sectionbg_" + areaId + "-fontcolor");
				if (selectedValue === "theme") {
					if ( initial ) {
						$colorli.hide();
					} else {
						$colorli.slideUp('fast');
					}
				} else {
					if ( initial ) {
						$colorli.show();
					} else {
						$colorli.slideDown('fast');
					}
				}
			}
			areaIds.forEach(function(areaId) {
				var $typeinput = $("#customize-control-frontpage_sectionbg_"+areaId+"-type input[type='radio']");
				if( $typeinput.length ) {
					$typeinput.filter(':checked').each(function() {
						updateBgVisibility($(this), areaId, true);
					});
					$typeinput.on('change', function() {
						updateBgVisibility($(this), areaId);
					});
				}
				var $typeinput = $("#customize-control-frontpage_sectionbg_"+areaId+"-font input[type='radio']");
				if( $typeinput.length ) {
					$typeinput.filter(':checked').each(function() {
						updateFontVisibility($(this), areaId, true);
					});
					$typeinput.on('change', function() {
						updateFontVisibility($(this), areaId);
					});
				}
			});

		});

	});

} )( wp.customize );