jQuery(document).ready(function($) {
	"use strict";

	$('.hoot-abouttheme-top').on('click',function(e){
		var $target = $( $(this).attr('href') );
		if ( $target.length ) {
			e.preventDefault();
			var destin = $target.offset().top - 50;
			$("html:not(:animated),body:not(:animated)").animate({ scrollTop: destin}, 500 );
		}
	});

	$('#hoot-abouttabs .hootnav-tab').on('click',function(e){
		e.preventDefault();
		var targetid = $(this).data('tabid'),
			$navtabs = $('#hoot-abouttabs .hootnav-tab'),
			$tabs = $('#hoot-abouttabs .hoot-tabblock'),
			$target = $('#hoot-'+targetid);
		if ( $target.length ) {
			$navtabs.removeClass('nav-tab-active');
			$navtabs.filter('[data-tabid="'+targetid+'"]').addClass('nav-tab-active');
			$tabs.removeClass('hootactive');
			$target.addClass('hootactive');
			// Update the URL with the new tab parameter
			var newUrl = new URL(window.location.href);
			newUrl.searchParams.set('tab', targetid);
			history.replaceState(null, null, newUrl.toString());
			var $refererInput = $('.hoot-tabblock.hootactive input[name="_wp_http_referer"]');
			if ($refererInput.length) {
				var refererUrl = new URL($refererInput.val(), window.location.origin);
				refererUrl.searchParams.set('tab', targetid);
				$refererInput.val(refererUrl.pathname + refererUrl.search);
			}
		}
	});

});