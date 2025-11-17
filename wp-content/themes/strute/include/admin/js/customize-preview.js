/**
 * Theme Customizer enhancements for a better user experience.
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Basic Checks
	if( 'undefined' == typeof hootInlineStyles || ! $.isArray( hootInlineStyles ) )
		window.hootInlineStyles = [ '', '', {}, {} ];
	if ( !hootInlineStyles[0] || typeof hootInlineStyles[0] !== 'string' ) hootInlineStyles[0] = '';
	hootInlineStyles[1] = hootInlineStyles[1] ? true : false;
	if ( !hootInlineStyles[2] || typeof hootInlineStyles[2] !== 'object' ) hootInlineStyles[2] = {};
	if ( !hootInlineStyles[3] || typeof hootInlineStyles[3] !== 'object' ) hootInlineStyles[3] = {};

	// Make sure we have a hook
	var $csshandle_hook = hootInlineStyles[0] ? $( '#' + hootInlineStyles[0] + '-inline-css' ) : [];
	if ( ! $csshandle_hook.length ) {
		$csshandle_hook = $('<style id="hoot-customize-hookpreview" type="text/css"></style>').appendTo('head');
	}

	// Variables
	var hootpload = hootInlineStyles[1],
		settingMap = hootInlineStyles[2],
		defaultMap = hootInlineStyles[3];

	/*** Utility ***/
	function hootAddStyleTag( setting ) {
		if ( $( 'hoot-customize-' + setting ).length === 0 ) {
			$csshandle_hook.after( '<style id="hoot-customize-' + setting + '" type="text/css"></style>' );
		}
	}
	function hootUpdateCss( setting, value, defaultIfEmpty=true, defaultSuffix='' ) {
		var $target = $( '#hoot-customize-' + setting ),
			mapCssVar = settingMap[setting];
		if ( $target.length && mapCssVar ) {
			if ( typeof value !== 'object' ) {
				if ( value || value === 0 || value === '0' ) {
					$target.html( ':root{' + mapCssVar + ':' + value +'}' );
				} else {
					var mapDefault = defaultMap[setting];
					if ( !defaultIfEmpty ) $target.html(''); // clean up the style
					else if ( mapDefault ) $target.html( ':root{' + mapCssVar + ':' + mapDefault + defaultSuffix +'}' );
				}
			}
			else if ( typeof value.media === 'object' ) {
				var mapDefault = defaultMap[setting];
				var cssString = '';
				if ( typeof value.media.desktop === 'string' ) {
					cssString += ':root{' + mapCssVar + ':' + value.media.desktop +'}';
				} else if ( defaultIfEmpty ) {
					cssString += ':root{' + mapCssVar + ':' + mapDefault.desktop + defaultSuffix +'}';
				}
				if ( typeof value.media.tablet === 'string' ) {
					cssString += '@media only screen and (max-width: 969px){:root{' + mapCssVar + ':' + value.media.tablet +'}}';
				} else if ( defaultIfEmpty ) {
					cssString += '@media only screen and (max-width: 969px){:root{' + mapCssVar + ':' + mapDefault.tablet + defaultSuffix +'}}';
				}
				if ( typeof value.media.mobile === 'string' ) {
					cssString += '@media only screen and (max-width: 600px){:root{' + mapCssVar + ':' + value.media.mobile +'}}';
				} else if ( defaultIfEmpty ) {
					cssString += '@media only screen and (max-width: 600px){:root{' + mapCssVar + ':' + mapDefault.mobile + defaultSuffix +'}}';
				}
				if ( cssString ) {
					$target.html( cssString );
				}
			}
			else {
				var cssString = '';
				for ( var varid in value ) { if ( value.hasOwnProperty(varid) ) {
					var ivar = mapCssVar.replace( 'varid', varid );
					var ival = value[varid];
					if ( ival || ival === 0 || ival === '0' ) {
						cssString += ivar + ':' + ival + ';';
					}
				} }
				if ( cssString ) {
					$target.html( ':root{' + cssString + '}' );
				}
			}
		}
	}
	// @credit: https://css-tricks.com/snippets/javascript/lighten-darken-color/
	function hootcolor(col, amt) {
		var usePound = false;
		if (col[0] == "#") { col = col.slice(1); usePound = true; }
		var num = parseInt(col,16);
		var r = (num >> 16) + amt; if (r > 255) r = 255; else if  (r < 0) r = 0;
		var b = ((num >> 8) & 0x00FF) + amt; if (b > 255) b = 255; else if  (b < 0) b = 0;
		var g = (num & 0x0000FF) + amt; if (g > 255) g = 255; else if (g < 0) g = 0;
		return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
	}
	function hootHexToRgba(hex, opacity) {
		if (typeof hex !== "string") return false;
		hex = hex.replace(/^#/, "");
		if (!/^([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(hex)) return false;
		if (hex.length === 3) {
			hex = hex.split("").map(c => c + c).join("");
		}
		let r = parseInt(hex.substring(0, 2), 16);
		let g = parseInt(hex.substring(2, 4), 16);
		let b = parseInt(hex.substring(4, 6), 16);
		opacity = parseInt( opacity );
		if ( isNaN(opacity) || opacity < 0 || opacity > 100) return false;
		opacity /= 100;
		return `rgba(${r}, ${g}, ${b}, ${opacity})`;
	}
	function hootResolveFontstyle( newval ) {
		return {
			transform: newval === 'uppercase' || newval === 'uppercasei' ? 'uppercase' : 'none',
			style:     newval === 'standardi' || newval === 'uppercasei' ? 'italic' : 'normal',
		};
	}
	function hootResolveFpCols( columns ) {
		colArr = [];
		switch ( columns ) {
			case '100'         : colArr = [12];      break;
			case '50-50'       : colArr = [6,6];     break;
			case '33-66'       : colArr = [4,8];     break;
			case '66-33'       : colArr = [8,4];     break;
			case '25-75'       : colArr = [3,9];     break;
			case '75-25'       : colArr = [9,3];     break;
			case '33-33-33'    : colArr = [4,4,4];   break;
			case '25-25-50'    : colArr = [3,3,6];   break;
			case '25-50-25'    : colArr = [3,6,3];   break;
			case '50-25-25'    : colArr = [6,3,3];   break;
			case '25-25-25-25' : colArr = [3,3,3,3]; break;
			default            : colArr = [12];      break; // default: 100 if undefined
		}
		return colArr;
	}
	function hootUpdateBgPatt( cssTagId=false, patt=false ) {
		if ( cssTagId ) {
			hootUpdateCss( cssTagId, {
				bgimg: patt && patt !== 0 && patt !== '0' ? 'url(' + patt + ')' : 'none',
				bgrepeat: 'repeat',
				bgpos: '0 0',
				bgatch: 'scroll',
				bgsize: 'auto',
			} );
		}
	}
	function hootUpdateBgImg( cssTagId=false, settingId=false, prop=false, val=0 ) {
		if ( cssTagId && settingId && prop ) {
			var bgimg, bgrepeat, bgpos, bgatch, bgsize;
			var newvalArray = {
				bgimg: 'none',
				bgrepeat: 'repeat',
				bgpos: '0 0',
				bgatch: 'scroll',
				bgsize: 'auto',
			};

			if ( prop === 'bgimg' ) { bgimg = val; }
			else { wp.customize( settingId + '-image', function( setting ) { bgimg = setting.get(); }); }
			if ( bgimg ) newvalArray.bgimg = 'url(' + bgimg + ')';

			// Get other values: needed only if bgimg is set, else let them be default as it doesn't matter
			if ( bgimg && bgimg !== 0 && bgimg !== '0' ) {

				if ( prop === 'bgrepeat' ) { bgrepeat = val; }
				else { wp.customize( settingId + '-repeat', function( setting ) { bgrepeat = setting.get(); }); }
				if ( bgrepeat ) newvalArray.bgrepeat = bgrepeat;

				if ( prop === 'bgpos' ) { bgpos = val; }
				else { wp.customize( settingId + '-position', function( setting ) { bgpos = setting.get(); }); }
				if ( bgpos ) newvalArray.bgpos = bgpos;

				if ( prop === 'bgatch' ) { bgatch = val; }
				else { wp.customize( settingId + '-attachment', function( setting ) { bgatch = setting.get(); }); }
				if ( bgatch ) newvalArray.bgatch = bgatch;

				if ( prop === 'bgsize' ) { bgsize = val; }
				else { wp.customize( settingId + '-size', function( setting ) { bgsize = setting.get(); }); }
				if ( bgsize ) newvalArray.bgsize = bgsize;

			}
			hootUpdateCss( cssTagId, newvalArray );
		}
	}
	// Moved to singular approach of using [JSON.stringify( $optionsform.serializeArray() ) + PHP json_decode()]
	function hootDeserialize( serializedStr ) {
		try {
			var jsonObject = JSON.parse(serializedStr);
			if (typeof jsonObject === 'object' && jsonObject !== null) {
				return jsonObject;
			}
		} catch (e) {} // Not a JSON string, try with URLSearchParams
		return false;
	}
	function getMultilevelObjVal(obj, keys) {
		// If keys is a string, split it into an array
		if (typeof keys === 'string') {
			keys = keys.split('.');
		}
		return keys.reduce((acc, key) => {
			return acc && typeof acc === 'object' && acc !== null && acc[key] !== undefined
				? acc[key]
				: undefined;
		}, obj);
	}



	/*** Site title and description. ***/

	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '#site-logo-text #site-title a, #site-logo-mixed #site-title a' ).html( newval );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '#site-description' ).html( newval );
		} );
	} );

	/** Theme Settings **/

	wp.customize( 'site_layout', function( value ) {
		value.bind( function( newval ) {
			$( '#page-wrapper' ).removeClass('hgrid site-boxed site-stretch');
			if ( newval == 'boxed' )
				$( '#page-wrapper' ).addClass('hgrid site-boxed');
			else
				$( '#page-wrapper' ).addClass('site-stretch');
		} );
	} );

	function sidebar_width_px( setting, newval ) {
		var newvalint = parseInt(newval);
		newvalint = !isNaN(newvalint) && newvalint > 0 ? newvalint+'px' : false; // don't allow 0 or negative
		if ( !newvalint ) { // Redundancy Check as value should always exist in a range control
			// default for wide sidebar layouts
			$psb = $('#sidebar-primary');
			newvalint = ( $psb.length && ( $psb.is('.layout-wide-left') || $psb.is('.layout-wide-right') ) ) ? '33.33333%' : '25%';
		}
		hootUpdateCss( setting, newvalint, true, '' );
	}
	function sidebar_width_pcnt( setting, newval ) {
		var newvalint = parseInt(newval);
		newvalint = !isNaN(newvalint) && newvalint >= 0 && newvalint <= 100 ? newvalint+'%' : false; // don't allow 0 or negative
		if ( !newvalint ) { // Redundancy Check as value should always exist in a range control
			// default for wide sidebar layouts
			$psb = $('#sidebar-primary');
			newvalint = ( $psb.length && ( $psb.is('.layout-wide-left') || $psb.is('.layout-wide-right') ) ) ? '33.33333%' : '25%';
		}
		hootUpdateCss( setting, newvalint, true, '' );
	}
	wp.customize( 'sidebar1_width', function( value ) {
		hootAddStyleTag( 'sidebar1_width' );
		value.bind( function( newval ) {
			if ( newval === 'auto' ) {
				hootUpdateCss( 'sidebar1_width', false, false );
			} else if ( newval === 'px' ) {
				var sbval = '';
				wp.customize( 'sidebar1_width_px', function( setting ) { sbval = setting.get(); });
				sidebar_width_px( 'sidebar1_width', sbval );
			} else if ( newval === 'pcnt' ) {
				var sbval = '';
				wp.customize( 'sidebar1_width_pcnt', function( setting ) { sbval = setting.get(); });
				sidebar_width_pcnt( 'sidebar1_width', sbval );
			}
		} );
	} );
	wp.customize( 'sidebar1_width_px', function( value ) {
		value.bind( function( newval ) {
			var sbtype = '';
			wp.customize( 'sidebar1_width', function( setting ) { sbtype = setting.get(); });
			if ( sbtype == 'px' ) {
				sidebar_width_px( 'sidebar1_width', newval );
			}
		} );
	} );
	wp.customize( 'sidebar1_width_pcnt', function( value ) {
		value.bind( function( newval ) {
			var sbtype = '';
			wp.customize( 'sidebar1_width', function( setting ) { sbtype = setting.get(); });
			if ( sbtype == 'pcnt' ) {
				sidebar_width_pcnt( 'sidebar1_width', newval );
			}
		} );
	} );
	wp.customize( 'sidebar2_width', function( value ) {
		hootAddStyleTag( 'sidebar2_width' );
		value.bind( function( newval ) {
			if ( newval === 'auto' ) {
				hootUpdateCss( 'sidebar2_width', false, false );
			} else if ( newval === 'px' ) {
				var sbval = '';
				wp.customize( 'sidebar2_width_px', function( setting ) { sbval = setting.get(); });
				sidebar_width_px( 'sidebar2_width', sbval );
			} else if ( newval === 'pcnt' ) {
				var sbval = '';
				wp.customize( 'sidebar2_width_pcnt', function( setting ) { sbval = setting.get(); });
				sidebar_width_pcnt( 'sidebar2_width', sbval );
			}
		} );
	} );
	wp.customize( 'sidebar2_width_px', function( value ) {
		value.bind( function( newval ) {
			var sbtype = '';
			wp.customize( 'sidebar2_width', function( setting ) { sbtype = setting.get(); });
			if ( sbtype == 'px' ) {
				sidebar_width_px( 'sidebar2_width', newval );
			}
		} );
	} );
	wp.customize( 'sidebar2_width_pcnt', function( value ) {
		value.bind( function( newval ) {
			var sbtype = '';
			wp.customize( 'sidebar2_width', function( setting ) { sbtype = setting.get(); });
			if ( sbtype == 'pcnt' ) {
				sidebar_width_pcnt( 'sidebar2_width', newval );
			}
		} );
	} );

	wp.customize( 'disable_goto_top', function( value ) {
		value.bind( function( newval ) {
			if (newval) $('.fixed-goto-top').addClass('hootnoshow');
			else        $('.fixed-goto-top').removeClass('hootnoshow');
		} );
	} );
	wp.customize( 'goto_top_mobile', function( value ) {
		value.bind( function( newval ) {
			if (newval) $('.fixed-goto-top').removeClass('hidemobile');
			else        $('.fixed-goto-top').addClass('hidemobile');
		} );
	} );
	wp.customize( 'goto_top_icon', function( value ) {
		value.bind( function( newval ) {
			if (newval) {
				$('.fixed-goto-top i').removeClass().addClass(newval);
			}
		} );
	} );
	wp.customize( 'goto_top_icon_style', function( value ) {
		value.bind( function( newval ) {
			if (newval) {
				$('.fixed-goto-top').removeClass('goto-top-style1 goto-top-style2 goto-top-style3 goto-top-style4 goto-top-style5 goto-top-style6 goto-top-style7 goto-top-style8').addClass('goto-top-'+newval);
			}
		} );
	} );
	wp.customize( 'goto_top_offset', function( value ) {
		hootAddStyleTag( 'goto_top_offset' );
		value.bind( function( newval ) {
			try {
				var newvalObj = JSON.parse( newval );
				var mediaVal = { media: {} }
				for ( var device in newvalObj ) { if ( newvalObj.hasOwnProperty(device) ) {
					var newvalint = parseInt( newvalObj[device] );
					if ( !isNaN(newvalint) && newvalint >= 0 ) { // don't allow negative
						mediaVal.media[device] = newvalint+'px';
					}
				} }
				hootUpdateCss( 'goto_top_offset', mediaVal, true, 'px' );
			} catch (error) {
				console.error("Invalid JSON string:", error.message);
			}
		} );
	} );

	wp.customize( 'widgetmargin', function( value ) {
		hootAddStyleTag( 'widgetmargin' );
		hootAddStyleTag( 'halfwidgetmargin' );
		value.bind( function( newval ) {
			try {
				var newvalObj = JSON.parse( newval );
				var mediaVal = { media: {} }
				var mediaValSmall = { media: {} }
				for ( var device in newvalObj ) { if ( newvalObj.hasOwnProperty(device) ) {
					var newvalint = parseInt( newvalObj[device] );
					if ( !isNaN(newvalint) ) { // allow 0
						mediaVal.media[device] = newvalint+'px';
						var newvalintsmall = newvalint > 50 ? ( newvalint / 2 ) : 25; // lets not rely upon default
						mediaValSmall.media[device] = newvalintsmall+'px';
					}
				} }
				hootUpdateCss( 'widgetmargin', mediaVal, true, 'px' );
				hootUpdateCss( 'halfwidgetmargin', mediaValSmall, true, 'px' );
			} catch (error) {
				console.error("Invalid JSON string:", error.message);
			}
		} );
	} );

	wp.customize( 'topann_sticky', function( value ) {
		value.bind( function( newval ) {
			if (newval)
				$('#topann').addClass('topann-stick');
			else
				$('#topann').removeClass('topann-stick');
		} );
	} );
	wp.customize( 'topann_imageX', function( value ) {
		value.bind( function( newval ) {
			var imgasbg = 0;
			wp.customize( 'topann_imgasbg', function( setting ) { imgasbg = setting.get(); });
			if (newval) {
				$('#topann').removeClass('hootnoshow');
			} else {
				var title, content;
				wp.customize( 'topann_content_title', function( setting ) { title = setting.get(); });
				wp.customize( 'topann_content', function( setting ) { content = setting.get(); });
				if ( ! title && ! content ) { $('#topann').addClass('hootnoshow'); }
			}
			if (newval) {
				if ( imgasbg ) {
					$('#topann').css('background-image', 'url(' + newval + ')');
				} else {
					$('.topann-inlineimg img').removeClass('hootnoshow').attr('src', newval);
				}
			} else {
				if ( imgasbg ) {
					$('#topann').css('background-image', 'none');
				} else {
					$('.topann-inlineimg img').addClass('hootnoshow');
				}
			}
		} );
	} );
	wp.customize( 'topann_imgasbg', function( value ) {
		value.bind( function( newval ) {
			if ( ! $('#topann').hasClass( 'hootnoshow' ) ) {
				wp.customize.preview.send( 'refresh' );
				// Fallback to content's partial refresh to handle changes
				var partial = wp.customize.selectiveRefresh.partial('topann_content_partial');
				if ( partial ) { partial.refresh(); } else { wp.customize.preview.send( 'refresh' ); }
			}
		} );
	} );
	wp.customize( 'topann_content_stretch', function( value ) {
		value.bind( function( newval ) {
			if (newval === 'stretch') {
				$('#topann').removeClass('topann-grid').addClass('topann-stretch');
			} else {
				$('#topann').removeClass('topann-stretch').addClass('topann-grid');
			}
			var nopad = 0;
			wp.customize( 'topann_content_nopad', function( setting ) { nopad = setting.get(); });
			if (newval === 'stretch' && nopad) {
				$('#topann').addClass('topann-nopad');
			} else {
				$('#topann').removeClass('topann-nopad');
			}
		} );
	} );
	wp.customize( 'topann_content_nopad', function( value ) {
		value.bind( function( newval ) {
			var stretch;
			wp.customize( 'topann_content_stretch', function( setting ) { stretch = setting.get(); });
			if (newval && stretch === 'stretch') {
				$('#topann').addClass('topann-nopad');
			} else {
				$('#topann').removeClass('topann-nopad');
			}
		} );
	} );
	wp.customize( 'topann_content_style', function( value ) {
		value.bind( function( newval ) {
			if (typeof newval === 'string') {
				$('.topann-contentbox').removeClass('textstyle-dark textstyle-light textstyle-dark-on-light textstyle-light-on-dark textstyle-dark-on-custom textstyle-light-on-custom').addClass('textstyle-'+newval);
			}
		} );
	} );
	wp.customize( 'topann_content_bg', function( value ) {
		hootAddStyleTag( 'topann_content_bg' );
		value.bind( function( newval ) {
			hootUpdateCss( 'topann_content_bg', newval );
		} );
	} );

	wp.customize( 'logo_side', function( value ) {
		this.selectiveRefresh.bind("render-partials-response", function(response) {
			if ( typeof response.contents.logo_side_partial !== undefined ) {
				var location = '', logoside = '';
				wp.customize( 'menu_location', function( setting ) { location = setting.get(); });
				wp.customize( 'logo_side', function( setting ) { logoside = setting.get(); });
				logoside = logoside === 'widget-area' ? 'widget' : logoside;
				logoside = location === 'side' ? 'menu' : logoside;
				$("#header").removeClass('sitehead-side-widget sitehead-side-search sitehead-side-none sitehead-side-menu').addClass('sitehead-side-'+logoside);
			}
		});
	} );

	wp.customize( 'menu_location', function( value ) {
		value.bind( function( newval ) {
			if ( ['top','bottom','none','side'].includes(newval) ) {
				$( '#header' ).removeClass('sitehead-menu-top sitehead-menu-bottom sitehead-menu-side sitehead-menu-none').addClass('sitehead-menu-'+newval);
				// Additionally manage side as well
				var logoside;
				if ( newval === 'side' ) {
					logoside = 'menu';
				} else {
					wp.customize( 'logo_side', function( setting ) { logoside = setting.get(); });
					logoside = ['search','widget-area','none'].includes(logoside) ? logoside : 'none';
					logoside = logoside === 'widget-area' ? 'widget' : logoside;
				}
				$( '#header' ).removeClass('sitehead-side-widget sitehead-side-search sitehead-side-none sitehead-side-menu').addClass('sitehead-side-'+logoside);
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	wp.customize( 'fullwidth_menu_align', function( value ) {
		value.bind( function( newval ) {
			if ( ['left','right','center'].includes(newval) ) {
				$( '#header' ).removeClass('sitehead-menualign-left sitehead-menualign-right sitehead-menualign-center').addClass('sitehead-menualign-'+newval);
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	wp.customize( 'disable_table_menu', function( value ) {
		value.bind( function( newval ) {
			if (newval) $( '#header' ).removeClass('hoot-tablemenu');
			else        $( '#header' ).addClass('hoot-tablemenu');
		} );
	} );

	wp.customize( 'mobile_menu_label', function( value ) {
		$toggletext = $('.menu-toggle-text')
		value.bind( function( newval ) {
			if ( newval ) { $toggletext.text( newval ).removeClass('hootnoshow'); }
			else { $toggletext.text('').addClass('hootnoshow'); }
		} );
	} );

	wp.customize( 'mobile_submenu_click', function( value ) {
		value.bind( function( newval ) {
			var mobilesubmenuclass = (newval) ? 'mobilesubmenu-click' : 'mobilesubmenu-open';
			$( '#header' ).removeClass('mobilesubmenu-click mobilesubmenu-open').addClass(mobilesubmenuclass);
		} );
	} );

	wp.customize( 'topbar_grid', function( value ) {
		value.bind( function( newval ) {
			if (newval == 'stretch')
				$('#topbar > div').removeClass('hgrid').addClass('hgrid-stretch');
			else
				$('#topbar > div').removeClass('hgrid-stretch').addClass('hgrid');
		} );
	} );

	wp.customize( 'below_sitehead_grid', function( value ) {
		value.bind( function( newval ) {
			var mobilesubmenuclass = (newval == 'stretch') ? 'below-sitehead-stretch' : 'below-sitehead-boxed';
			$( '#below-sitehead' ).removeClass('below-sitehead-stretch below-sitehead-boxed').addClass(mobilesubmenuclass);
		} );
	} );

	wp.customize( 'logo_background_type', function( value ) {
		value.bind( function( newval ) {
			var addClasses = '';
			if ( newval == 'accent' ) {
				addClasses = 'accent-typo with-background';
			} else if ( newval == 'invert-accent' ) {
				addClasses = 'invert-accent-typo with-background';
			} else if ( newval == 'background' ) {
				if(hootpload){
					addClasses = 'with-background';
					var logobgcolor = '';
					wp.customize( 'logo_background', function( setting ) { logobgcolor = setting.get(); });
					if ( logobgcolor ) {
						hootUpdateCss( 'logo_background', logobgcolor );
					} else {
						wp.customize.preview.send( 'refresh' );
					}
				};
			} else {}
			$( '#site-logo' ).removeClass('accent-typo invert-accent-typo with-background').addClass(addClasses);
		} );
	} );
	if(hootpload){
	wp.customize( 'logo_background', function( value ) {
		hootAddStyleTag( 'logo_background' );
		value.bind( function( newval ) {
			hootUpdateCss( 'logo_background', newval );
		} );
	} );
	}

	wp.customize( 'logo_border', function( value ) {
		value.bind( function( newval ) {
			$( '#site-logo' ).removeClass('logo-border nopadding');
			if (newval == 'border' || newval == 'bordernopad')
				$( '#site-logo' ).addClass('logo-border');
			if (newval == 'bordernopad')
				$( '#site-logo' ).addClass('nopadding');
		} );
	} );

	if(!hootpload){
	wp.customize( 'logo_size', function( value ) {
		value.bind( function( newval ) {
			$( '#site-logo-text, #site-logo-mixed' ).removeClass('site-logo-text-tiny site-logo-text-small site-logo-text-medium site-logo-text-large site-logo-text-huge').addClass( 'site-logo-text-' + newval );
		} );
	} );
	}

	wp.customize( 'site_title_icon', function( value ) {
		value.bind( function( newval ) {
			if ( newval )
				$( '#site-logo-text, #site-logo-custom' ).addClass('site-logo-with-icon').find('i').remove().end().find('a').prepend('<i class="' + newval + '"></i>');
			else
				$( '#site-logo-text, #site-logo-custom' ).removeClass('site-logo-with-icon').find('i').remove();
		} );
	} );

	wp.customize( 'site_title_icon_size', function( value ) {
		hootAddStyleTag( 'site_title_icon_size' );
		value.bind( function( newval ) {
			var newvalint = parseInt(newval);
			newvalint = !isNaN(newvalint) && newvalint > 0 ? newvalint+'px' : false; // don't allow 0 or negative
			hootUpdateCss( 'site_title_icon_size', newvalint, true, 'px' );
		} );
	} );

	wp.customize( 'logo_image_width', function( value ) {
		hootAddStyleTag( 'logo_image_width' );
		value.bind( function( newval ) {
			try {
				var newvalObj = JSON.parse( newval );
				var mediaVal = { media: {} }
				for ( var device in newvalObj ) { if ( newvalObj.hasOwnProperty(device) ) {
					var newvalint = parseInt( newvalObj[device] );
					if ( !isNaN(newvalint) && newvalint > 0 ) { // don't allow 0 or negative
						mediaVal.media[device] = newvalint+'px';
					}
				} }
				hootUpdateCss( 'logo_image_width', mediaVal, true, 'px' );
			} catch (error) {
				console.error("Invalid JSON string:", error.message);
			}
		} );
	} );

	var lineids = $.isArray( settingMap['customlogolineids'] ) ? settingMap['customlogolineids'] : [ 'line1', 'line2', 'line3', 'line4' ];

	wp.customize( 'logo_custom', function( value ) {
		hootAddStyleTag( 'logo_custom_line_font' );
		value.bind( function( newval ) {
			var newval = hootDeserialize(newval);
			var lineselector = lineids.map(function(id) { return '.site-title-' + id; }).join(', ');
			var $lines = $('#site-title').find(lineselector);

			if ( !newval || $lines.length !== lineids.length ) {
				// Fallback to logo partial refresh to handle changes
				var partial = wp.customize.selectiveRefresh.partial('logo_partial');
				if ( partial ) { partial.refresh(); } else { wp.customize.preview.send( 'refresh' ); }
			} else {
				var linesizearray = {};
				var linesizedefault = defaultMap['logo_custom_line_font'];
				lineids.forEach(function(lineid, index) {
					var $line = $('.site-title-' + lineid);
					var text = getMultilevelObjVal( newval, ['logo_custom', lineid, 'text'] ) || '',
						hide = getMultilevelObjVal( newval, ['logo_custom', lineid, 'sortitem_hide'] ) || false,
						font = getMultilevelObjVal( newval, ['logo_custom', lineid, 'font'] ) || false,
						size = getMultilevelObjVal( newval, ['logo_custom', lineid, 'size'] ) || 0;
					$line.html( text );
					$line.removeClass('hootnoshow');
					if ( hide === 1 || hide === '1' || ! text )
						$line.addClass('hootnoshow');
					$line.removeClass('bodyfont titlefont');
					if ( font === 'standard' ) $line.addClass('bodyfont');
					if ( font === 'heading2' ) $line.addClass('titlefont');
					var linesize = parseInt( size ) || parseInt( linesizedefault ) || 24; // Dont allow 0
					linesizearray[lineid] = linesize + 'px';
				});
				hootUpdateCss( 'logo_custom_line_font', linesizearray );

			}
		} );
	} );

	wp.customize( 'show_tagline', function( value ) {
		value.bind( function( newval ) {
			if (newval)
				$( '#site-description' ).removeClass('hootnoshow');
			else
				$( '#site-description' ).addClass('hootnoshow');
		} );
	} );

	var sticky_sitehead_live = function( screen, layout, reset=false ) {
		var tag           = screen === 'mob' ? 'mob'            : 'dtp'           ;
		var stickytag     = screen === 'dtp' ? 'stickydtp'      : 'stickymob'     ;
		var dataStickytag = screen === 'dtp' ? 'data-stickydtp' : 'data-stickymob';
		var istag         = screen === 'dtp' ? 'isdtp'          : 'ismob'         ;
		var stickyLayouts = screen === 'dtp' ?
			'stickydtp-topbar stickydtp-logo stickydtp-menu stickydtp-text stickydtp-logomenu stickydtp-logomenudiv stickydtp-logotext stickydtp-logotextdiv stickydtp-logomenutext' :
			'stickymob-topbar stickymob-logo stickymob-menu stickymob-menuleft stickymob-menuright stickymob-text stickymob-logomenu stickymob-menulogo stickymob-logotext stickymob-logotextdiv stickymob-logomenutext';

		var $topbar = $( '#topbar' ),
			$header = $( '#header' );
		var $topbarContainer = $topbar.parent('.sticky-wrapper-topbar'),
			$headerContainer = $header.parent('.sticky-wrapper-header');
		if ( reset ) {
			$topbar.removeClass( stickytag ).removeAttr( dataStickytag );
			$header.removeClass( stickytag ).removeAttr( dataStickytag );
			if ( $topbarContainer.length ) { $topbarContainer.removeClass( istag ) }
			if ( $headerContainer.length ) { $headerContainer.removeClass( istag ) }
			$topbar.removeClass(stickyLayouts);
			$topbarContainer.height('');
			$header.removeClass(stickyLayouts);
			$headerContainer.height('');
			$('body').trigger( 'resetstickyderived' );
		} else {
			var isTopbar = layout === 'topbar';
			var isHeader = ! isTopbar;
			if ( isTopbar ) {
				$topbar.addClass( stickytag ).attr( dataStickytag, stickytag + '-' + layout );
				$header.removeClass( stickytag ).removeAttr(dataStickytag);
			} else {
				$header.addClass( stickytag ).attr( dataStickytag, stickytag + '-' + layout );
				$topbar.removeClass( stickytag ).removeAttr(dataStickytag);
			}
			// code from scroller.js
			if ( isTopbar ) {
				if ( $topbarContainer.length ) { $topbarContainer.addClass( istag ) }
				if ( $headerContainer.length ) { $headerContainer.removeClass( istag ) }
			} else {
				if ( $topbarContainer.length ) { $topbarContainer.removeClass( istag ) }
				if ( $headerContainer.length ) { $headerContainer.addClass( istag ) }
			}
			if ( $topbar.hasClass('hootstuck') && $topbarContainer.length ) {
				$header.removeClass(stickyLayouts);
				$headerContainer.height('');
				$topbar.removeClass(stickyLayouts).addClass( stickytag + '-' + layout );
				$topbarContainer.height( $('#topbar').outerHeight(true) );
				$('body').trigger( 'siteheadstucked' );
			}
			if ( $header.hasClass('hootstuck') && $headerContainer.length ) {
				$topbar.removeClass(stickyLayouts);
				$topbarContainer.height('');
				$header.removeClass(stickyLayouts).addClass( stickytag + '-' + layout );
				$headerContainer.height( $('#header').outerHeight(true) );
				$('body').trigger( 'siteheadstucked' );
			}
		}
	}

	wp.customize( 'sticky_accent', function( value ) {
		value.bind( function( newval ) {
			if ( newval === 'accent' ) {
				$( '#header' ).addClass('sticky-accent');
			} else {
				$( '#header' ).removeClass('sticky-accent');
			}
		} );
	} );
	wp.customize( 'sticky_sitehead_dtp', function( value ) {
		value.bind( function( newval ) {
			var layout;
			wp.customize( 'sticky_sitehead_dtp_layout', function( setting ) { layout = setting.get(); });
			var reset = layout ? ! newval : false;
			sticky_sitehead_live( 'dtp', layout, reset );
		} );
	} );
	wp.customize( 'sticky_sitehead_dtp_layout', function( value ) {
		value.bind( function( newval ) {
			var reset = !newval;
			sticky_sitehead_live( 'dtp', newval, reset );
		} );
	} );
	wp.customize( 'sticky_sitehead_dtp_logozoom', function( value ) {
		hootAddStyleTag( 'sticky_sitehead_dtp_logozoom' );
		value.bind( function( newval ) {
			var newvalint = parseInt(newval);
			newvalint = !isNaN(newvalint) && newvalint > 0 && newvalint <= 100 ? newvalint/100 : false; // dont allow 0
			hootUpdateCss( 'sticky_sitehead_dtp_logozoom', newvalint, false );
		} );
	} );
	// doesn't work with do_shortcode/wpautop in strute_sitehead_extras

	wp.customize( 'sticky_sitehead_mob', function( value ) {
		value.bind( function( newval ) {
			var layout;
			wp.customize( 'sticky_sitehead_mob_layout', function( setting ) { layout = setting.get(); });
			var reset = layout ? ! newval : false;
			sticky_sitehead_live( 'mob', layout, reset );
		} );
	} );
	wp.customize( 'sticky_sitehead_mob_layout', function( value ) {
		value.bind( function( newval ) {
			var reset = !newval;
			sticky_sitehead_live( 'mob', newval, reset );
		} );
	} );
	wp.customize( 'sticky_sitehead_mob_logozoom', function( value ) {
		hootAddStyleTag( 'sticky_sitehead_mob_logozoom' );
		value.bind( function( newval ) {
			var newvalint = parseInt(newval);
			newvalint = !isNaN(newvalint) && newvalint > 0 && newvalint <= 100 ? newvalint/100 : false; // dont allow 0
			hootUpdateCss( 'sticky_sitehead_mob_logozoom', newvalint, false );
		} );
	} );
	// doesn't work with do_shortcode/wpautop in strute_sitehead_extras

	wp.customize( 'background-color', function( value ) {
		hootAddStyleTag( 'background-color' );
		value.bind( function( newval ) {
			hootUpdateCss( 'background-color', newval );
		} );
	} );
	wp.customize( 'background-type', function( value ) {
		hootAddStyleTag( 'site_background_style' );
		value.bind( function( newval ) {
			if ( newval === 'predefined' ) {
				var patt;
				wp.customize( 'background-pattern', function( setting ) { patt = setting.get(); });
				hootUpdateBgPatt( 'site_background_style', patt );
			} else if ( newval === 'custom' ) {
				var bgimg;
				wp.customize( 'background-image', function( setting ) { bgimg = setting.get(); });
				hootUpdateBgImg( 'site_background_style', 'background', 'bgimg', bgimg );
			}
		} );
	} );
	wp.customize( 'background-pattern', function( value ) {
		value.bind( function( newval ) {
			hootUpdateBgPatt( 'site_background_style', newval );
		} );
	} );
	wp.customize( 'background-image', function( value ) {
		value.bind( function( newval ) {
			hootUpdateBgImg( 'site_background_style', 'background', 'bgimg', newval );
		} );
	} );
	wp.customize( 'background-repeat', function( value ) {
		value.bind( function( newval ) {
			hootUpdateBgImg( 'site_background_style', 'background', 'bgrepeat', newval );
		} );
	} );
	wp.customize( 'background-position', function( value ) {
		value.bind( function( newval ) {
			hootUpdateBgImg( 'site_background_style', 'background', 'bgpos', newval );
		} );
	} );
	wp.customize( 'background-attachment', function( value ) {
		value.bind( function( newval ) {
			hootUpdateBgImg( 'site_background_style', 'background', 'bgatch', newval );
		} );
	} );
	wp.customize( 'background-size', function( value ) {
		value.bind( function( newval ) {
			hootUpdateBgImg( 'site_background_style', 'background', 'bgsize', newval );
		} );
	} );

	wp.customize( 'box_background_color', function( value ) {
		hootAddStyleTag( 'box_background_color' );
		value.bind( function( newval ) {
			hootUpdateCss( 'box_background_color', newval );
		} );
	} );
	wp.customize( 'gridarticle_bg', function( value ) {
		hootAddStyleTag( 'gridarticle_bg' );
		value.bind( function( newval ) {
			hootUpdateCss( 'gridarticle_bg', newval );
		} );
	} );

	wp.customize( 'article_background_type', function( value ) {
		value.bind( function( newval ) {
			$article = $( '.singular .entry-content').parent('article.entry');
			$article.removeClass('article-bg article-bg-whensidebar');
			if (newval === 'background')
				$article.addClass('article-bg');
			else if (newval === 'background-whensidebar')
				$article.addClass('article-bg-whensidebar');
		} );
	} );
	wp.customize( 'article_background_color', function( value ) {
		hootAddStyleTag( 'article_background_color' );
		value.bind( function( newval ) {
			hootUpdateCss( 'article_background_color', newval );
		} );
	} );

	wp.customize( 'accent_color', function( value ) {
		hootAddStyleTag( 'accent_color' );
		if(!hootpload) {
			hootAddStyleTag( 'link_color' );
			hootAddStyleTag( 'link_hover_color' );
		}
		value.bind( function( newval ) {
			hootUpdateCss( 'accent_color', newval );
			if(!hootpload) {
				hootUpdateCss( 'link_color', newval );
				var newvaldark = hootcolor( newval, -25 );
				hootUpdateCss( 'link_hover_color', newvaldark );
			}
		} );
	} );
	wp.customize( 'accent_font', function( value ) {
		hootAddStyleTag( 'accent_font' );
		value.bind( function( newval ) {
			hootUpdateCss( 'accent_font', newval );
		} );
	} );

	wp.customize( 'button_color', function( value ) {
		hootAddStyleTag( 'button_color' );
		value.bind( function( newval ) {
			hootUpdateCss( 'button_color', newval );
		} );
	} );
	wp.customize( 'button_font', function( value ) {
		hootAddStyleTag( 'button_font' );
		value.bind( function( newval ) {
			hootUpdateCss( 'button_font', newval );
		} );
	} );

	if(!hootpload){
	wp.customize( 'logo_fontface_style', function( value ) {
		hootAddStyleTag( 'logo_fontface_style' );
		value.bind( function( newval ) {
			var newvalArray = hootResolveFontstyle( newval );
			hootUpdateCss( 'logo_fontface_style', newvalArray );
		} );
	} );
	}

	if(!hootpload){
	wp.customize( 'headings_fontface_style', function( value ) {
		hootAddStyleTag( 'headings_fontface_style' );
		value.bind( function( newval ) {
			var newvalArray = hootResolveFontstyle( newval );
			hootUpdateCss( 'headings_fontface_style', newvalArray );
		} );
	} );
	}

	if(!hootpload){
	wp.customize( 'subheadings_fontface_style', function( value ) {
		hootAddStyleTag( 'subheadings_fontface_style' );
		value.bind( function( newval ) {
			var newvalArray = hootResolveFontstyle( newval );
			hootUpdateCss( 'subheadings_fontface_style', newvalArray );
		} );
	} );
	}
	wp.customize( 'read_more', function( value ) {
		value.bind( function( newval ) {
			if ( newval ) { $( '.theme-more-link a' ).text( newval ); }
			// Let php determine default read more text - may be different in different contexts eg. theme vs hootkit widgets
			else { wp.customize.preview.send( 'refresh' ); }
		} );
	} );
	var cacheFeatImg = {};
	var featured_image = function( context, newval ) {
		var headeroptions = [ 'staticheader-nocrop', 'staticheader', 'header' ];
		if ( ! cacheFeatImg[context] || ! headeroptions.includes( cacheFeatImg[context] ) || ! headeroptions.includes( newval ) ) {
			wp.customize.preview.send( 'refresh' );
		} else {
			var $pgheadimgwrap = $('.pgheadimg-'+context);
			if ( $pgheadimgwrap.length ) {
				var $pgheadimg = $pgheadimgwrap.children('img.pgheadimg');
				var $pgheaddiv = $pgheadimgwrap.children('div.pgheadimg');
				$pgheadimgwrap.removeClass('pgheadimg-inline pgheadimg-bg')
				if ( newval === 'staticheader-nocrop' ) {
					$pgheadimgwrap.addClass('pgheadimg-inline');
					$pgheaddiv.addClass('hootnoshow');
					$pgheadimg.removeClass('hootnoshow');
				} else {
					$pgheadimgwrap.addClass('pgheadimg-bg');
					$pgheadimg.addClass('hootnoshow');
					$pgheaddiv.removeClass('hootnoshow').removeClass('bg-parallax bg-noparallax').addClass( newval === 'header' ? 'bg-parallax' : 'bg-noparallax' );
					if ( newval === 'header' ) {
						$pgheaddiv.css('transform',''); // important to remove any leftoer inline transform from hootPgheadimgInit in hoot.theme.js else parallax css becomes redundant
					}
				}
				$(document).trigger('hootPgheadimgReinit');
			}
		}
		cacheFeatImg[context] = newval;
	}
	wp.customize( 'archive_featured_image', function( value ) {
		cacheFeatImg.archive = value.get();
		value.bind( function( newval ) {
				featured_image( 'archive', newval );
		} );
	} );
	if(!hootpload){
	wp.customize( 'post_featured_image', function( value ) {
		cacheFeatImg.post = value.get();
		value.bind( function( newval ) {
				featured_image( 'post', newval );
		} );
	} );
	}
	if(!hootpload){
	wp.customize( 'post_featured_image_page', function( value ) {
		cacheFeatImg.page = value.get();
		value.bind( function( newval ) {
				featured_image( 'page', newval );
		} );
	} );
	}

	wp.customize( 'post_prev_next_links', function( value ) {
		var cachePrevNext = value.get();
		var fixedoptions = ['fixed-text','fixed-thumb'];
		value.bind( function( newval ) {
			if ( ! cachePrevNext || fixedoptions.includes( cachePrevNext ) || fixedoptions.includes( newval ) ) {
				wp.customize.preview.send( 'refresh' );
			} else {
				var partial = wp.customize.selectiveRefresh.partial('post_prev_next_links_partial');
				if ( partial ) { partial.refresh(); } else { wp.customize.preview.send( 'refresh' ); }
			}
			cachePrevNext = newval;
		} );
	} );
	if(!hootpload){
	wp.customize( 'article_maxwidth', function( value ) {
		hootAddStyleTag( 'article_maxwidth' );
		value.bind( function( newval ) {
			var newvalint = parseInt(newval);
			newvalint = !isNaN(newvalint) && newvalint > 0 ? newvalint+'px' : false; // don't allow 0 or negative
			hootUpdateCss( 'article_maxwidth', newvalint, true, 'px' );
		} );
	} );
	wp.customize( 'article_maxwidth_nosidebar', function( value ) {
		hootAddStyleTag( 'article_maxwidth_nosidebar' );
		value.bind( function( newval ) {
			var newvalint = parseInt(newval);
			newvalint = !isNaN(newvalint) && newvalint > 0 ? newvalint+'px' : false; // don't allow 0 or negative
			hootUpdateCss( 'article_maxwidth_nosidebar', newvalint, true, 'px' );
		} );
	} );
	}

	wp.customize( 'footer', function( value ) {
		value.bind( function( newval ) {
			var col = parseInt(newval.substr(0,1)),
				sty = parseInt(newval.substr(-1));
			if ( col && !isNaN( col ) && sty && !isNaN( sty ) ) {
				var fclasses = [12,12,12,12],
					fstyles = ['none','none','none','none'];
				switch (col) {
					case 1: fstyles[0] = 'block';
							break;
					case 2: if ( sty == 1 ) {      fclasses[0] = 6; fclasses[1] = 6; }
							else if ( sty == 2 ) { fclasses[0] = 4; fclasses[1] = 8; }
							else if ( sty == 3 ) { fclasses[0] = 8; fclasses[1] = 4; }
							fstyles[0] = fstyles[1] = 'block';
							break;
					case 3: if ( sty == 1 ) {      fclasses[0] = 4; fclasses[1] = 4; fclasses[2] = 4; }
							else if ( sty == 2 ) { fclasses[0] = 6; fclasses[1] = 3; fclasses[2] = 3; }
							else if ( sty == 3 ) { fclasses[0] = 3; fclasses[1] = 6; fclasses[2] = 3; }
							else if ( sty == 4 ) { fclasses[0] = 3; fclasses[1] = 3; fclasses[2] = 6; }
							fstyles[0] = fstyles[1] = fstyles[2] = 'block';
							break;
					case 4: fclasses[0] = fclasses[1] = fclasses[2] = fclasses[3] = 3;
							fstyles[0] = fstyles[1] = fstyles[2] = fstyles[3] = 'block';
							break;
				}
				$('.footer-column').removeClass('hgrid-span-12 hgrid-span-8 hgrid-span-6 hgrid-span-4 hgrid-span-3').removeAttr("style").each(function(index){
					$(this).addClass('hgrid-span-'+fclasses[index]).css('display',fstyles[index]);
				});
			}
		} );
	} );


	///////////////// Homepage Image

	$.each( [ 'minheight', 'conpad', 'imgpad', 'headsize', 'subheadsize', 'textsize', 'btnsize' ], function( index, setid ) {
		wp.customize( 'header_image_'+setid, function( value ) {
			hootAddStyleTag( 'header_image_'+setid );
			value.bind( function( newval ) {
				var newvalint = parseInt(newval);
				if ( setid === 'conpad' || setid === 'imgpad' ) {
					newvalint = !isNaN(newvalint) && newvalint >= 0 ? newvalint+'px' : false;
				} else {
					newvalint = !isNaN(newvalint) && newvalint > 0 ? newvalint+'px' : false; // don't allow 0 or negative
				}
				hootUpdateCss( 'header_image_'+setid, newvalint, true, 'px' );
			} );
		} );
	} );

	$.each( [ 'bg', 'headcolor', 'subheadcolor', 'textcolor', 'btncolor', 'btnbg', 'btncolor2', 'btnbg2' ], function( index, setid ) {
		wp.customize( 'header_image_'+setid, function( value ) {
			hootAddStyleTag( 'header_image_'+setid );
			value.bind( function( newval ) {
				if ( newval )
					hootUpdateCss( 'header_image_'+setid, newval );
				else
					wp.customize.preview.send( 'refresh' ); // removing css-var using hootUpdateCss will lead to css-var from last css.php come into play. whereas, in scss, we use a fallabck var for things like buttons (--hoot-buttoncolor/buttonfont) or headings(--hoot-headings-color,--hoot-subheadings-color) or text(--hoot-basefont-color). Prettu much everything except 'header_image_bg' // Hence we need to remove css-var added by hootUpdateCss AND css-var added by css.php (if any) since the last refresh/load
			} );
		} );
	} );

	$.each( [ 'conbg', 'overlay' ], function( index, setid ) {
		wp.customize( 'header_image_'+setid, function( value ) {
			hootAddStyleTag( 'header_image_'+setid );
			value.bind( function( newval ) {
				calcval = 'transparent';
				if ( newval ) {
					var op = 0;
					wp.customize( 'header_image_'+setid+'_opacity', function( setting ) { op = setting.get(); });
					calcval = hootHexToRgba( newval, op );
					calcval = calcval ? calcval : 'transparent';
				}
				hootUpdateCss( 'header_image_'+setid, calcval );
			} );
		} );
		wp.customize( 'header_image_'+setid+'_opacity', function( value ) {
			// hootAddStyleTag( 'header_image_'+setid );
			value.bind( function( newval ) {
				calcval = 'transparent';
				newval = parseInt( newval )
				if ( ! isNaN( newval ) ) {
					var color = 0;
					wp.customize( 'header_image_'+setid, function( setting ) { color = setting.get(); });
					calcval = hootHexToRgba( color, newval );
					calcval = calcval ? calcval : 'transparent';
				}
				if ( setid === 'conbg' ) {
					if ( !newval ) $('#frontpage-image .fpimg-textbox').addClass('fpimg-textbox-nobg');
					else $('#frontpage-image .fpimg-textbox').removeClass('fpimg-textbox-nobg');
				}
				hootUpdateCss( 'header_image_'+setid, calcval );
			} );
		} );
	} );


// 	////////////////////////////////////////////////////////////////////////////////////////////////////
// 	////////////////////////////////////////////////////////////////////////////////////////////////////
// 	////////////////////////////////////////////////////////////////////////////////////////////////////

	wp.customize( 'frontpage_sections_enable', function( value ) {
		value.bind( function( newval ) {
			wp.customize.preview.send( 'refresh' );
		} );
	} );

	var areaids = $.isArray( settingMap['fpareaids'] ) ? settingMap['fpareaids'] : [];

	wp.customize( 'frontpage_sections', function( value ) {
		// Latest value on load OR when customize preview refreshes from a non transport>postmessage setting
		// Hence this gets updated when we refresh below on seqChanged=true
		var origSeq = hootDeserialize( wp.customize.settings.values.frontpage_sections );
		var cacheSeq = origSeq && typeof origSeq['frontpage_sections'] === 'object'
						? Object.keys(origSeq['frontpage_sections'])
						: [];

		value.bind( function( newval ) {
			var newval = hootDeserialize(newval);
			var newSeq = newval && typeof newval['frontpage_sections'] === 'object'
						? Object.keys(newval['frontpage_sections'])
						: [];
			var seqChanged = cacheSeq.length !== newSeq.length;
			if ( !seqChanged ) {
				for (var i = 0; i < newSeq.length; i++) {
					if (newSeq[i] !== cacheSeq[i]) seqChanged = true;
				}
			}

			var areaselector = areaids.map(function(areaid) {
					var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
					return '#frontpage-' + areapageid;
				}).join(', ');
			var $areas = $('#main').find(areaselector);

			if ( !newval || $areas.length !== areaids.length || seqChanged ) {
				// Fallback to preview refresh to handle changes
				wp.customize.preview.send( 'refresh' );
			} else {
				areaids.forEach(function(areaid, index) {
					var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
					var $area = $('#frontpage-' + areapageid);
					var hide = getMultilevelObjVal( newval, ['frontpage_sections', areaid, 'sortitem_hide'] ) || false;
					$area.removeClass('hootnoshow');
					if ( areaid == 'image' ) {
						var image = false;
						wp.customize( 'header_image', function( setting ) { image = setting.get(); });
						if ( !image || image === 'remove-header' || hide === 1 || hide === '1' )
							$area.addClass('hootnoshow');
					} else if ( hide === 1 || hide === '1' ) {
						$area.addClass('hootnoshow');
					}
				});

			}
		} );
	} );

	// Homepage Content - Title
	wp.customize( 'frontpage_sectionbg_content-title', function( value ) {
		var $areatitle = $('#frontpage-page-content .frontpage-page-content-title');
		var $areahead = $areatitle.find('h3');
		value.bind( function( newval ) {
			if(newval) {
				$areatitle.removeClass('hootnoshow');
				$areahead.html(newval);
			} else {
				$areatitle.addClass('hootnoshow');
				$areahead.html('');
			}
		} );
	} );

	var fpfontselector = typeof settingMap['fpfontselector'] === 'string' ? settingMap['fpfontselector'] : false;

	$.each( areaids, function( index, areaid ) { if ( areaid !== 'image' ) {

		if ( areaid !== 'content' ) {
		wp.customize( 'frontpage_sectionbg_'+areaid+'-columns', function( value ) {
			var areapageid = areaid;
			var $area = $('#frontpage-' + areapageid);
			value.bind( function( newval ) {
				var colArr = hootResolveFpCols( newval );
				var $columns = $area.find('.frontpage-areacol');
				$columns.removeClass('hootnoshow hgrid-span-12 hgrid-span-9 hgrid-span-8 hgrid-span-6 hgrid-span-4 hgrid-span-3').each(function(index){
					if ( colArr[index] )
						$(this).addClass('hgrid-span-'+colArr[index]);
					else
						$(this).addClass('hootnoshow');
				});
				window.dispatchEvent(new Event('resize'));
				if ( $area.find('.lSSlideOuter').length > 0 ) {
					setTimeout(() => { wp.customize.preview.send( 'refresh' ) },500);
				}
			} );
		} );
		}

		wp.customize( 'frontpage_sectionbg_'+areaid+'-grid', function( value ) {
			var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
			var $area = $('#frontpage-' + areapageid);
			value.bind( function( newval ) {
				$area.removeClass('frontpage-area-stretch frontpage-area-boxed');
				if ( newval === 'stretch' ) {
					$area.addClass('frontpage-area-stretch');
				} else {
					$area.addClass('frontpage-area-boxed');
				}
			} );
		} );

		wp.customize( 'frontpage_sectionbg_'+areaid+'-type', function( value ) {
			var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
			value.bind( function( newval ) {
				var $area = $('#frontpage-'+areapageid),
					color = '',
					image = '',
					parallax = 0;
				$area.removeClass('bg-parallax bg-noparallax area-bgcolor')
					.removeClass('module-bg-image module-bg-color module-bg-none')
					.removeAttr("style");
				if ( newval == 'none' ) {
					$area.addClass('module-bg-none');
				} else if ( newval == 'color' ) {
					$area.addClass('module-bg-color area-bgcolor');
					wp.customize( 'frontpage_sectionbg_'+areaid+'-color', function( setting ) { color = setting.get(); });
					if ( color ) $area.css('background-color',color);
				}
				else if ( newval == 'image' ) {
					$area.addClass('module-bg-image')
					wp.customize( 'frontpage_sectionbg_'+areaid+'-image', function( setting ) { image = setting.get(); });
					if ( image ) $area.css('background-image','url('+image+')');
					wp.customize( 'frontpage_sectionbg_'+areaid+'-parallax', function( setting ) { parallax = setting.get(); });
					$area.addClass(parallax ? 'bg-parallax' : 'bg-noparallax');
				}
			} );
		} );

		wp.customize( 'frontpage_sectionbg_'+areaid+'-color', function( value ) {
			var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
			value.bind( function( newval ) {
				var type = '';
				wp.customize( 'frontpage_sectionbg_'+areaid+'-type', function( setting ) { type = setting.get(); });
				if ( type=='color' ) $('#frontpage-'+areapageid).css('background-color',newval);
			} );
		} );

		wp.customize( 'frontpage_sectionbg_'+areaid+'-image', function( value ) {
			var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
			value.bind( function( newval ) {
				var type = '',
					parallax = 0,
					$frontpagearea = $('#frontpage-' + areapageid);
				wp.customize( 'frontpage_sectionbg_'+areaid+'-parallax', function( setting ) { parallax = setting.get(); });
				wp.customize( 'frontpage_sectionbg_'+areaid+'-type', function( setting ) { type = setting.get(); });
				if (type === 'image')
					$frontpagearea
						.css('background-image', newval ? 'url(' + newval + ')' : 'none')
						.removeClass('bg-parallax bg-noparallax')
						.addClass(parallax ? 'bg-parallax' : 'bg-noparallax');
			} );
		} );

		wp.customize( 'frontpage_sectionbg_'+areaid+'-parallax', function( value ) {
			var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
			value.bind( function( newval ) {
				var type = 0,
					$frontpagearea = $('#frontpage-' + areapageid);
				wp.customize( 'frontpage_sectionbg_'+areaid+'-type', function( setting ) { type = setting.get(); });
				if ( type == 'image' )
					$frontpagearea
						.removeClass('bg-parallax bg-noparallax')
						.addClass(newval ? 'bg-parallax' : 'bg-noparallax');
			} );
		} );

		wp.customize( 'frontpage_sectionbg_'+areaid+'-font', function( value ) {
			var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
			var $fpareaStyle = $('#hoot-customize-frontpage-'+areapageid);
			value.bind( function( newval ) {
				if ( ! fpfontselector ) { wp.customize.preview.send( 'refresh' ); }
				var css = '';
				var fontcolor = '';
				wp.customize( 'frontpage_sectionbg_'+areaid+'-fontcolor', function( setting ) { fontcolor = setting.get(); });
				if ( fontcolor ) { switch (newval) {
					case 'color':
						css = fpfontselector.replace( '.varid', '.frontpage-'+areapageid ) + '{color:'+fontcolor+'}';
						break;
					case 'force':
						css = fpfontselector.replace( '.varid', '#frontpage-'+areapageid ) + '{color:'+fontcolor+'}';
						break;
				} }
				$fpareaStyle.html( css ); // css is empty if no fontcolor, or if font is set to 'theme'
			} );
		} );

		wp.customize( 'frontpage_sectionbg_'+areaid+'-fontcolor', function( value ) {
			var areapageid = ( areaid == 'content' ) ? 'page-content' : areaid;
			var $fpareaStyle = $('#hoot-customize-frontpage-'+areapageid);
			value.bind( function( newval ) {
				if ( ! fpfontselector ) { wp.customize.preview.send( 'refresh' ); }
				var css = '';
				var font = '';
				wp.customize( 'frontpage_sectionbg_'+areaid+'-font', function( setting ) { font = setting.get(); });
				if ( newval ) { switch (font) {
					case 'color':
						css = fpfontselector.replace( '.varid', '.frontpage-'+areapageid ) + '{color:'+newval+'}';
						break;
					case 'force':
						css = fpfontselector.replace( '.varid', '#frontpage-'+areapageid ) + '{color:'+newval+'}';
						break;
				} }
				$fpareaStyle.html( css ); // css is empty if no fontcolor, or if font is set to 'theme'
			} );
		} );

	} } );

} )( jQuery );