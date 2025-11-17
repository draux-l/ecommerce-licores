jQuery(document).ready(function($) {
	"use strict";

	if( 'undefined' == typeof hootData )
		window.hootData = {};

	/*** Superfish Navigation ***/

	if( 'undefined' == typeof hootData.superfish || 'enable' == hootData.superfish ) {
		if (typeof $.fn.superfish != 'undefined') {
			$('.sf-menu').superfish({
				delay: 500,						// delay on mouseout 
				animation: {height: 'show'},	// submenu open. Do not use 'toggle' #bug
				animationOut: {height:'hide'},	// submenu hide
				speed: 'fast',					// faster animation speed
				speedOut: 100,					// faster animation speed
				disableHI: false,				// true to disable hoverIntent detection
			});
		}
	}

	/*** Responsive Navigation ***/

	if( 'undefined' == typeof hootData.menuToggle || 'enable' == hootData.menuToggle ) {
		var $html = $('html'),
			$stickyhead = $('#header.stickymob'),
			$menuToggle = $('.menu-toggle'),
			$toggleCls = $menuToggle.siblings('.menu-toggleclose'),
			$menuItems = $menuToggle.siblings('.menu-items');
		if ( $('#wpadminbar').length ) $html.addClass('has-adminbar');
		$menuToggle.on('click', function (event) {
			event.preventDefault();
			$menuToggle.toggleClass( 'active' );
			if ( $menuItems.is('.fixedmenu-items.fixedmenu-left') ) {
				$html.toggleClass( 'fixedmenu-open' );
				if( $menuToggle.is('.active') ) {
					$menuItems.show().css( 'left', '-' + $menuItems.outerWidth() + 'px' ).animate( {left:0}, 300 );
					$toggleCls.show().css( 'left', '-' + $menuItems.outerWidth() + 'px' ).animate( {left:0}, 300 );
				} else {
					if ( $stickyhead.length && $stickyhead.is('.hootstuck') ) {
						$menuItems.hide();
						$toggleCls.hide();
					} else {
						$menuItems.animate( { left: '-' + $menuItems.outerWidth() + 'px' }, 300, function(){ $menuItems.hide(); } );
						$toggleCls.animate( { left: '-' + $menuItems.outerWidth() + 'px' }, 300, function(){ $toggleCls.hide(); } );
					}
				}
			} else {
				$menuItems.css( 'left', 'auto' ).slideToggle(); // add left:auto to override inline left from fixed menu in customizer screen: added for brevity only
			}
		});
		$('body').on('click', function (e) {
			if ( $html.hasClass('fixedmenu-open') && (
				// user clicked outside nav-menu OR close button
				!$(e.target).closest('.nav-menu').length || $(e.target).closest('.menu-toggleclose').length
			) ) {
				e.preventDefault(); // else url changes to url.com/# and browser scrolls to top for .menu-toggleclose
				$( '.menu-toggle.active' ).click();
			}
		});
	} else {
		$('.menu-toggle').hide();
	}

	/*** Mobile menu - Modal Focus ***/
	// @todo: fix for themes with dual menus BMUMBDNx
	// @todo: bugfix: when $lastItem does not have href, focus shifts from <a> with href to
	//                next element in document i.e. outside menu (doesnt even close it)
	function keepFocusInMenu(){
		var $menu = $('.menu-items'),
			$lastEl = $menu.find( 'input, a, button' ).last(),
			$firstEl = $menu.find( 'input, a, button' ).first(),
			$toggleCls = $('.menu-toggleclose');
		$lastEl.on( 'keydown', function( event ) {
			if ( window.matchMedia( '(max-width: 969px)' ).matches ) {
				var tabKey = event.key === 'Tab', shiftKey = event.shiftKey;
				if ( tabKey && !shiftKey ) {
					event.preventDefault();
					$toggleCls.focus();
				}
			}
		});
		$firstEl.on( 'keydown', function( event ) {
			if ( window.matchMedia( '(max-width: 969px)' ).matches ) {
				var tabKey = event.key === 'Tab', shiftKey = event.shiftKey;
				if ( tabKey && shiftKey ) {
					event.preventDefault();
					$toggleCls.focus();
				}
			}
		});
		$('.menu-toggle').on('keydown', function (event) {
			if ( window.matchMedia( '(max-width: 969px)' ).matches ) {
				var enterKey = event.key === 'Enter';
				if ( enterKey ) {
					event.preventDefault();
					$(this).click();
					$firstEl.focus();
				}
			}
		});
		$toggleCls.on( 'keydown', function( event ) {
			if ( window.matchMedia( '(max-width: 969px)' ).matches ) {
				var tabKey = event.key === 'Tab', shiftKey = event.shiftKey, enterKey = event.key === 'Enter';
				if ( tabKey ) {
					event.preventDefault();
					if ( shiftKey )
						$lastEl.focus();
					else
						$firstEl.focus();
				}
				if ( enterKey ) {
					event.preventDefault();
					$('.menu-toggle').focus().click();
				}
			}
		});
	}
	keepFocusInMenu();

	/*** JS Search ***/

	$('.js-search .searchbody i.fa-search, .js-search .js-search-placeholder').each(function(){
		var $self = $(this),
			searchbody = $self.closest('.searchbody');
		$self.on('click', function(){
			searchbody.toggleClass('hasexpand');
			if ( searchbody.is('.hasexpand' ) ) {
				searchbody.find('input.searchtext').focus();
			}
		});
	});
	$('.js-search .searchtext').each(function(){
		var $self = $(this),
			searchbody = $self.closest('.searchbody');
		$self.on({
			focus: function() { searchbody.addClass('hasexpand'); },
			blur: function() { searchbody.removeClass('hasexpand'); }
		});
	});

	/*** Responsive Videos : Target your .container, .wrapper, .post, etc. ***/

	if( 'undefined' == typeof hootData.fitVids || 'enable' == hootData.fitVids ) {
		if (jQuery.fn.fitVids)
			$("#content").fitVids();
	}

	/*** Theia Sticky Sidebar ***/

	if( 'undefined' == typeof hootData.stickySidebar || 'enable' == hootData.stickySidebar ) {
		if (jQuery.fn.theiaStickySidebar && $('.hoot-sticky-sidebar .main-content-grid > #content').length && $('.hoot-sticky-sidebar .main-content-grid > .sidebar').length) {

			var stickySidebarTop = 10;
			function getAdditionalMarginTop () { return stickySidebarTop; }

			if( 'undefined' != typeof hootData.stickySidebarTop ) {
				stickySidebarTop = hootData.stickySidebarTop;
			} else {
				// recalculate once we get height of sticky sitehead/topbar
				$('body').on('siteheadstucked', function() {
					// only if sticky is enabled for desktop [no need for mobile]
					var isTopbar = $('topbar').hasClass('stickydtp'),
						isHeader = $('header').hasClass('stickydtp');
					if ( isTopbar || isHeader ) {
						stickySidebarTop = isTopbar ? $('#topbar').outerHeight() : $('#header').outerHeight();
						stickySidebarTop += 20;
					}
				} )
				$('body').on('resetstickyderived', function() {
					stickySidebarTop = 10;
				} )
			}

			$( '#content, #sidebar-primary, #sidebar-secondary' ).theiaStickySidebar({
				additionalMarginTop: getAdditionalMarginTop, 
			});
		}
	}

	/*** Page Head Image ***/
	if( 'undefined' == typeof hootData.headimageTransition || 'enable' == hootData.headimageTransition ) {
		var ticking, isFP, isPG;
		var $pghead;
		var $pgheadimg;
		var $pgtext;
		function hootPgheadimgInit(){
			$pghead = $('.hootanim-pgh .pgheadimg-wrap');
			$pgheadimg = [];
			if ( $pghead.length ) {
				$pgheadimg = $pghead.children('.pgheadimg:not(.bg-parallax)');
				isPG = $pgheadimg.length;
			} else {
				$pghead = $('.hootanim-pgh #frontpage-image');
				if ( $pghead.length ) {
					$pgheadimg = $pghead.find('.fpimg');
					isFP = $pgheadimg.length;
				}
			}

			if ( isPG || isFP ) {
				var transitionscale = parseFloat( hootData.headimageTransitionScale );
					transitionscale = transitionscale && transitionscale >= 0 ? transitionscale : 75;
				$pgtext = isFP ? $pghead.find('.fpimg-cboxwrap, .fpimg-feature').not('.fpimg-noanimshrink') : [];

				function onScrollHandler() {
					var vpTop = window.scrollY;
					var vpHeight = window.innerHeight;
					var vpBot = vpTop + vpHeight;
					var elTop = $pghead.offset().top;
					var elHeight = $pghead.outerHeight();
					var elBot = elTop + elHeight;
					var inView = vpBot > elTop && vpTop < elBot;
					if ( inView ) {
						var pctDist;
						if ( elTop > vpHeight ) { // elTop is below viewport on load
							var elFromViewTop = elBot - vpTop;
							pctDist = elFromViewTop / (vpHeight + elHeight);
						} else { // elTop is within viewport on load
							pctDist = ( elBot - vpTop ) / elBot
						}
						if ( pctDist >= 0 && pctDist <= 1 ) {
							pctDist = 1 - pctDist;
							$pgheadimg.css({ "transform": 'scale(' + (1 + (pctDist * transitionscale * 0.01)) + ',' + (1 + (pctDist * transitionscale * 0.01)) + ')' });
							if ( isFP && $pgtext.length ) {
								var txtanim = true;
								// if elTop is below viewport on load, start anim after 70% of viewport crossed
								if ( elTop > vpHeight ) {
									txtanim = pctDist >= 0.70 ? true : false;
									pctDist = (pctDist - 0.70) / (1 - 0.70);
								} else {
								// if elTop is within viewport on load, start anim after 30% of distance (elBot-vpTop) crossed
									txtanim = pctDist >= 0.30 ? true : false;
									pctDist = (pctDist - 0.30) / (1 - 0.30);
								}
								if ( txtanim ) {
									$pgtext.css({ "transform": 'scale(' + (1 - (pctDist*0.6)) + ',' + (1 - (pctDist*0.6)) + ')', "opacity": 1 - pctDist });
								} else {
									$pgtext.css({ "transform": 'scale(1,1)', "opacity": 1 });
								}
							}
						}
					}
					ticking = false; // Mark as not ticking to allow the next request
				}
				// Remove any existing scroll event listeners (useful when hootPgheadimgReinit is triggered)
				jQuery(window).off('scroll.hootPgheadimg');
				// Add a namespaced scroll event listener
				jQuery(window).on('scroll.hootPgheadimg', function () {
					// Ensure that RAF is only called once per frame, even if the scroll event fires multiple times
					if (!ticking) {
						ticking = true;
						requestAnimationFrame(onScrollHandler);
					}
				});
				// Check on page load and resize
				onScrollHandler();
				var resizeTimeout;
				jQuery(window).on('resize', function() {
					if (resizeTimeout) { clearTimeout(resizeTimeout); }
					resizeTimeout = setTimeout(function() { onScrollHandler(); }, 500);
				});

			}
		}

		// Initialize on page load and event trigger
		hootPgheadimgInit();
		$(document).on('hootPgheadimgReinit', function() {
			hootPgheadimgInit();
		});
	}

	/*** AJAX archive pagination content load ***/
	if( 'undefined' == typeof hootData.ajaxPaginate || 'enable' == hootData.ajaxPaginate ) {
		var currentPath = window.location.pathname + window.location.search;
		$('.main-content-grid').on('click', '#content .pagination a', function (e) {
			e.preventDefault();
			var pageurl = $(this).attr('href');
			$(this).addClass('ajax-pagination-loading');
			$.ajax({
				type: 'GET',
				url: pageurl,
				success: function (data) {
					var $content = $('.main-content-grid #content');
					$('html, body').animate({
						scrollTop: $content.offset().top - ( window.innerHeight / 4 )
					});
					var $data = $(data);
					var content = $data.find('.main-content-grid #content').html();
					$content.fadeOut('fast', function() {
						$content.html(content).fadeIn( 400, function() {
							var $mosaic = $(".archive-mosaic").first().parent();
							if ( $mosaic.length && typeof $.fn.isotope != 'undefined' ) {
								$mosaic.isotope({
									itemSelector: '.archive-mosaic'
								});
							}
							$('body').trigger( 'ajaxPaginationLoaded' );
						} );
					} );
					history.pushState(null, null, pageurl);
					currentPath = window.location.pathname + window.location.search;
				}
			});
		});
		$('body.singular article.has-pages').on('click', '.post-nav-links a', function (e) {
			e.preventDefault();
			var pageurl = $(this).attr('href');
			$(this).addClass('ajax-pagination-loading');
			$.ajax({
				type: 'GET',
				url: pageurl,
				success: function (data) {
					var $content = $('article.has-pages .entry-content');
					$('html, body').animate({
						scrollTop: $content.offset().top - ( window.innerHeight / 4 )
					});
					var $data = $(data);
					var content = $data.find('article.has-pages .entry-content').html();
					$content.fadeOut('fast', function() { $content.html(content).fadeIn(); });
					history.pushState(null, null, pageurl);
					currentPath = window.location.pathname + window.location.search;
				}
			});
		});
		window.addEventListener('popstate', function () {
			var nextPath = window.location.pathname + window.location.search;
			if (currentPath !== nextPath) {
				currentPath = nextPath;
				location.reload();
			}
		});
	}

	/*** Animaton on scroll {dynamic mods} ***/
	if( 'undefined' == typeof hootData.aos || 'enable' == hootData.aos ) {
		if (typeof AOS === 'object') {
			var aosAnimations = ['fade', 'fade-up', 'flip-left', 'flip-right', 'slide-up', 'zoom-in'];
			// WC Archive pages
			if( 'undefined' == typeof hootData.aosWCshop || 'disable' !== hootData.aosWCshop ) {
				var aosanim = aosAnimations.includes(hootData.aosWCshop) ? hootData.aosWCshop : 'zoom-in';
				var count = 1;
				$('#archive-wrap ul.products > li').each(function( index ){
					$(this).attr('data-aos', aosanim);
					if ( count > 10 ) count = 10;
					$(this).attr('data-aos-delay', count * 200);
					count = $(this).is('.last') ? 1 : count + 1;
				});
			}
		}
	}

});