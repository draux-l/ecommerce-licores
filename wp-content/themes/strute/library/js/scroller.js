jQuery(document).ready(function($) {
	"use strict";

	if( 'undefined' == typeof hootData )
		window.hootData = {};


	/*** Smooth Scroll ***/
	//credit: https://github.com/darkroomengineering/lenis
	var lenis;
	if( 'undefined' == typeof hootData.smoothScroll || 'enable' == hootData.smoothScroll ) {
		if (typeof Lenis === 'function') {
			lenis = new Lenis({ autoRaf: true });
		}
	}


	/*** Prev/Next Preview ***/

	if( 'undefined' == typeof hootData.loopnavPreview || 'enable' == hootData.loopnavPreview ) {
		var $loopnavfixed = $('#loop-nav-wrap.loop-nav-fixed');
		if ( $loopnavfixed.length ) {
			if ( typeof Waypoint === "function" ) {
				var $loopnavclonefixed = $('#loop-nav-wrap').clone().attr('id', 'loop-nav-wrapclone').removeClass('hootinview').insertAfter('#page-wrapper');
				var waypoints = $loopnavfixed.waypoint( function( direction ) {
					if ( direction=='down' && $loopnavclonefixed.length ) {
						$loopnavclonefixed.addClass('hootinview');
					}
				}, { offset: '50%' } );
			}
		}
	}


	/*** Animaton on scroll init ***/

	if( 'undefined' == typeof hootData.aos || 'enable' == hootData.aos ) {
		if (typeof AOS === 'object') {
			var disableAOS = false;
			if ( 'undefined' != typeof hootData.aosdisable ) {
				if ( hootData.aosdisable === 'mobile' )  disableAOS = window.innerWidth < 970;
				if ( hootData.aosdisable === 'desktop' ) disableAOS = window.innerWidth > 969;
			}
			var once = false;
			if ( 'undefined' !== typeof hootData.aosonce ) {
				once = hootData.aosonce === 'enable';
			}
			AOS.init({
				offset: 50,
				duration: 600,
				easing: 'ease-in',
				delay: 300,
				disable: disableAOS, // 'mobile' (phones and tablets), 'phone' or 'tablet'
				once: once
			});
			// remove delays on mobile devices - useful for staggered delays added to columns (or rows) of HK widgets
			function aosResetMobileDelays() {
				if ( window.innerWidth < 970 ) {
					$('[data-aos-delay]').removeAttr('data-aos-delay');
				}
			}
			aosResetMobileDelays();
			var resizeTimeout;
			jQuery(window).on('resize', function() {
				if (resizeTimeout) { clearTimeout(resizeTimeout); }
				resizeTimeout = setTimeout(function() { aosResetMobileDelays(); }, 1000);
			});
			$('body').on('ajaxPaginationLoaded', function() {
				AOS.refreshHard();
			} )
		}
	}


	/*** hootScroller ***/
	function checkAOSConditions($target) {
		var $elementWithAOS = $target.closest('[data-aos="fade-up"]'); // Check itself and ancestors
		if ( $elementWithAOS.length && ( !$elementWithAOS.hasClass('aos-animate') || $elementWithAOS.css('opacity') < 1 ) ) {
			// Get the computed style of the element
			var transformValue = $elementWithAOS.css('transform');
			if (transformValue && transformValue !== 'none') {
				// Extract the translate3d values
				var match = transformValue.match(/matrix.*\((.+)\)/);
				if (match) {
					var matrixValues = match[1].split(','); // Split the matrix values
					var translateY = parseFloat(matrixValues[5] || 0); // Extract vertical translate (Y-axis)
					return translateY;
				}
			}
		}
		return 0;
	}
	function normalizeUrl(url) {
		var a = document.createElement('a');
		a.href = url;
		return a.origin + a.pathname.replace(/\/$/, '');
	}
	function extractFragment(url) {
		var a = document.createElement('a');
		a.href = url;
		if (a.hash) { return a.hash.split('?')[0].substring(1); }
		return null;
	}
	function hootScroller(fragment, padding=false, speed=false) {
		if( ( 'undefined' == typeof hootData.scroller || 'enable' == hootData.scroller )
			&& fragment && typeof fragment === 'string'
		) {
			var scrollerSpeed   = hootData.scrollerSpeed   || 500; // integer || 'linear'
			var scrollerPadding = hootData.scrollerPadding || 50 ;
			speed = parseInt( speed ) >= 0 ? parseInt( speed ) : scrollerSpeed;
			padding = parseInt( padding ) >= 0 ? parseInt( padding ) : scrollerPadding;

			var target = fragment ? fragment.replace('#','') : null;
			var $target = target === 'body' ? $(target) : $('#' + target);
			if ( $target.length ) {
				function tryScroll(attemptsLeft) {
					return new Promise(function (resolve) {
					var targetInLayout = $target.is(':visible') && $target.parents().filter(function () {
											return $(this).css('display') === 'none';
										}).length === 0;
					if (!targetInLayout) {
						// $target not in layout flow, Give it one more try
						if (attemptsLeft > 0) {
							setTimeout(function () { tryScroll(attemptsLeft - 1).then(resolve); }, 500);
						} else {
							resolve(false);
						}
					} else {
						// Do the scroll
						var destin = $target.offset().top;
						// AOS Fix
						var translateY = checkAOSConditions($target);
						if ( parseInt(translateY) !== 0 ) {
							destin = destin - parseInt(translateY);
						}
						// Padding
						if ( parseInt(padding) ) {
							destin -= parseInt(padding);
						}
						// Speed
						if ( speed === 'linear' || ! Math.abs( speed ) ) {
							var distance = Math.abs( window.scrollY - destin );
							speed = distance > 1000 ? 1000 : parseInt(distance * 1.3);
							speed = speed < 100 ? 100 : speed;
						} else {
							speed = Math.abs( speed );
						}
						// Lenis Fix
						$('html').addClass('hootscrolling');
						if ( typeof lenis === 'object' ) lenis.stop();
						// Lets go
						$("html:not(:animated),body:not(:animated)").animate({ scrollTop: destin}, speed, function(){
							if ( typeof lenis === 'object' ) lenis.start();
							$('html').removeClass('hootscrolling');
						} );
						// All done
						resolve(true);
					}
					});
				}
				return tryScroll(1).then(function (result) {
					return result;
				});
			}
		}

		// Let browser go ahead with normal link behavior
		return false;
	};


	/*** Top Button ***/

	if( 'undefined' == typeof hootData.scrollTopButton || 'enable' == hootData.scrollTopButton ) {
		$('.fixed-goto-top').on( 'click', function(e) {
			var $this = $(this),
				href = $this.attr('href') || '',
				fragment = $this.data('scroll-to') || extractFragment(href);
			var scrolled = hootScroller(fragment, 48);
			if ( scrolled ) { e.preventDefault(); }
		} );
	}
	if( 'undefined' == typeof hootData.wayTopButton || 'enable' == hootData.wayTopButton ) {
		var $top_btn = $('.waypoints-goto-top');
		if ( $top_btn.length ) {
			if (typeof Waypoint === "function") {
				var waypoints = $('#page-wrapper').waypoint(function(direction) {
					if(direction=='down')
						$top_btn.addClass('topshow');
					if(direction=='up')
						$top_btn.removeClass('topshow');
					},{offset: '-80%'});
			} else {
				$top_btn.addClass('topshow');
			}
		}
	} else {
		$('.fixed-goto-top').addClass('topshow');
	}


	/*** Autoscroller ***/

	var autoctrl = {
		autoscroller : 'undefined' == typeof hootData.autoscroller || 'enable' == hootData.autoscroller,
		urlHashScroller : 'undefined' == typeof hootData.urlHashScroller || 'enable' == hootData.urlHashScroller,
		urlHashScrollerUglifyLink : hootData.urlHashScrollerPrettyLink !== 'enable',
		pageloadScrollID : hootData.pageloadScrollID && 'string' == typeof hootData.pageloadScrollID
		                    && hootData.pageloadScrollID !== 'disable' ? hootData.pageloadScrollID : false,
	}

	if( autoctrl.autoscroller ) {
		// Allow clicks only after 1 second. This prevents bug on pages like WC product where programatic click is registerd on '#tab-title-description' on page load
		setTimeout( () => {
			$('.autoscroller a, a.autoscroller').not('.skipscroll, .skipscroll a, .wc-tabs a').on('click', function (e) {
				var $this = $(this),
					href = $this.attr('href') || '',
					currentPage = normalizeUrl(window.location.href),
					targetPage = normalizeUrl(href),
					fragment = $this.data('scroll-to') || extractFragment(href);
				fragment = fragment ? fragment.replace('#','') : null;
				if ( targetPage === currentPage ) {
					if ( fragment ) {
						if ( fragment === 'respond' ) {
						} else if ( autoctrl.urlHashScroller ) {
							var scrolled = hootScroller(fragment);
							if ( scrolled ) { e.preventDefault(); }
						}
					}
					else if( autoctrl.pageloadScrollID ) {
						sessionStorage.setItem('scrollToFragment', 'defaulthootscroll');
					}
				}
				else if ( targetPage.startsWith(window.location.origin) ) {
					if ( fragment ) {
						if ( autoctrl.urlHashScroller ) {
							if ( href ) { // if this is <a> element with href (not a data-scroll-to)
								$this.attr('href', $this.attr('href').replace('#' + fragment, ''));
								sessionStorage.setItem('scrollToFragment', fragment);
							}
						}
					}
					else if( autoctrl.pageloadScrollID ) {
						sessionStorage.setItem('scrollToFragment', 'defaulthootscroll');
					}
				}
				else {}
			} );
		}, 1000);

		// Autoscroller Handler
		if( autoctrl.urlHashScroller || autoctrl.pageloadScrollID ) {
			var fragment = sessionStorage.getItem('scrollToFragment');
			var isDefaultScroll = false;
			if ( fragment === 'defaulthootscroll' ) {
				isDefaultScroll = true;
				fragment = window.scrollY <= 100 && ! $('body').hasClass('home') ? autoctrl.pageloadScrollID : false;
			}
			if (fragment) {
				var pad = isDefaultScroll ? 0 : false;
				var scrolled = hootScroller(fragment, pad);
				if ( ! isDefaultScroll && autoctrl.urlHashScrollerUglifyLink ) {
					// Push the fragment to the URL without causing the extra history entry
					window.history.replaceState(null, null, '#' + fragment);
				}

				// Remove the flag to avoid repeat scrolling on refresh
				sessionStorage.removeItem('scrollToFragment');
			}
		}
	}


	/*** Sticky sitehead ***/

	if( 'undefined' == typeof hootData.stickySitehead || 'enable' == hootData.stickySitehead ) {

		var isCPr = $('body').hasClass('is-customizer-preview');
		var $topbar = $('#topbar'),
			$header = $('#header');

		if ( typeof Waypoint === "function" && $topbar.length ) {
			var isDtpTopbar = $topbar.is('.stickydtp') ? ' isdtp' : '';
			var isMobTopbar = $topbar.is('.stickymob') ? ' ismob' : '';
			if ( isCPr || isDtpTopbar || isMobTopbar ) {
				$topbar.wrap( '<div class="sticky-wrapper-topbar' + isDtpTopbar + isMobTopbar + '" />' );
				var $tbarWrapper = $topbar.parent();
				var stickyTopbar = new Waypoint({
					element: $tbarWrapper[0],
					handler: function( dir ) {
						var dtpTbarStuckClass = $('#topbar').attr('data-stickydtp') || '';
						var mobTbarStuckClass = $('#topbar').attr('data-stickymob') || '';
						if ( dir === 'down' ) {
							$tbarWrapper.height( $('#topbar').outerHeight(true) );
							$topbar.addClass( dtpTbarStuckClass + ' hootstuck ' + mobTbarStuckClass );
							$('body').trigger( 'siteheadstucked' );
						} else {
							$tbarWrapper.height( '' );
							$topbar.removeClass( dtpTbarStuckClass + ' hootstuck ' + mobTbarStuckClass );
						}
					},
					offset: function() {
						var oset = parseInt( hootData.stickySiteheadOffset ) || $('#page-wrapper').offset().top;
						oset = parseInt( oset );
						oset = oset && oset > 50 ? oset : 50
						oset = -50 - oset;
						return oset;
					}
				});
			}
		}

		if ( typeof Waypoint === "function" && $header.length ) {
			var isDtpHeader = $header.is('.stickydtp') ? ' isdtp' : '';
			var isMobHeader = $header.is('.stickymob') ? ' ismob' : '';
			if ( isCPr || isDtpHeader || isMobHeader ) {
				$header.wrap( '<div class="sticky-wrapper-header' + isDtpHeader + isMobHeader + '" />' );
				var $headWrapper = $header.parent();
				var stickyHeader = new Waypoint({
					element: $headWrapper[0],
					handler: function( dir ) {
						var dtpHeadStuckClass = $('#header').attr('data-stickydtp') || '';
						var mobHeadStuckClass = $('#header').attr('data-stickymob') || '';
						if ( dir === 'down' ) {
							$headWrapper.height( $('#header').outerHeight(true) );
							$header.addClass( dtpHeadStuckClass + ' hootstuck ' + mobHeadStuckClass );
							$('body').trigger( 'siteheadstucked' );
						} else {
							$headWrapper.height( '' );
							$header.removeClass( dtpHeadStuckClass + ' hootstuck ' + mobHeadStuckClass );
						}
					},
					offset: function() {
						var oset = parseInt( hootData.stickySiteheadOffset ) || $('#main').offset().top;
						oset = parseInt( oset );
						oset = oset && oset > 300 ? oset : 300
						oset = -50 - oset;
						return oset;
					}
					});
			}

		}

	}

});