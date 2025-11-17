jQuery(document).ready(function($) {
	"use strict";

	/*** Internal Links ***/
	$('a[data-cust-linksection]').on('click', function (e) {
		var targetSectionId = $(this).data('cust-linksection');
		if (wp && wp.customize && wp.customize.section(targetSectionId)) {
			e.preventDefault();
			wp.customize.section(targetSectionId).expand();
		}
	} );


	/*** Sortlist Control ***/

	$('ul.hoot-control-sortlist').each(function(){

		/** Prepare Sortlist **/

		var $self = $(this),
			openstate = $self.data('openstate'),
			accordian = $self.data('accordian'),
			$listItems = $self.children('li'),
			$listItemHeads = $listItems.children('.hoot-sortlistitem-head'),
			$listItemVisibility = $listItemHeads.children('.sortlistitem-display'),
			$listItemFlypanel = $listItemHeads.children('.sortlistitem-flypanel'),
			$listItemExpand = $listItemHeads.children('.sortlistitem-expand'),
			$listItemOptions = $listItems.children('.hoot-sortlistitem-options');

		$listItemHeads.on('click', function(e){
			if ( $listItemExpand.length ) {
				if ( accordian == 'one' ) {
					$self.find('.sortlistitem-expand').removeClass('options-open');
					$self.find('.hoot-sortlistitem-options').slideUp('fast');
				}
				$(this).children('.sortlistitem-expand').toggleClass('options-open');
				$(this).siblings('.hoot-sortlistitem-options').slideToggle('fast');
			} else if ( $listItemFlypanel.length ) {
				$listItems.removeClass('hootactive');
				e.stopPropagation();
				var parentItem = $(this).closest('li.hoot-control-sortlistitem');
				var fpbutton = $('.hoot-control-id-frontpage_sectionbg_' + parentItem.data('choiceid') + ' .hoot-flypanel-button');
				if ( fpbutton.length ) {
					fpbutton.trigger('click');
					parentItem.addClass('hootactive');
				}
			}
		});
		$listItemFlypanel.on('click', function(e){
				$listItems.removeClass('hootactive');
				e.stopPropagation();
				var parentItem = $(this).closest('li.hoot-control-sortlistitem');
				var fpbutton = $('.hoot-control-id-frontpage_sectionbg_' + parentItem.data('choiceid') + ' .hoot-flypanel-button');
				if ( fpbutton.length ) {
					fpbutton.trigger('click');
					parentItem.addClass('hootactive');
				}
		});

		if ( openstate ) {
			if ( openstate != 'all' ) {
				$listItemOptions.hide();
				$listItems.filter('[data-choiceid="' + openstate + '"]').children('.hoot-sortlistitem-head').click();
			}
		} else $listItemOptions.hide();

		$listItemVisibility.on('click', function(e){
			e.stopPropagation();
			var $liContainer = $(this).closest('li.hoot-control-sortlistitem');
			$liContainer.toggleClass('deactivated');
			var hideValue = ( $liContainer.is('.deactivated') ) ? '1' : '0';
			$(this).siblings('input.hoot-control-sortlistitem-hide').val(hideValue).trigger('change');
		});

		/** Sortlist Control **/

		function arrayToNestedObject(serializedArray) {
			var nestedObject = {};
			serializedArray.forEach(({ name, value }) => {
				var keys = name.split(/\[|\]\[|\]/).filter(Boolean);
				var currentLevel = nestedObject; // reference assignment
				keys.forEach((key, index) => {
					if (index === keys.length - 1) {
						currentLevel[key] = value;
					} else {
						currentLevel[key] = currentLevel[key] || {};
						currentLevel = currentLevel[key]; // reference assignment
					}
				});
			});
			return nestedObject;
		}
		var $optionsform = $self.find('input:not([data-displayonly]), textarea, select'),
			$input = $self.siblings('input.hoot-customize-control-sortlist'),
			updateSortable = function(){
				$optionsform = $self.find('input:not([data-displayonly]), textarea, select'); // Get updated list item order
				// Moved to singular approach of using [JSON.stringify( $optionsform.serializeArray() ) + PHP json_decode()]
				var nestedObject  = {};
				// serializeArray does not create a multidimensional array. It simpy creates array with name/value pairs
				var serializedArray = $optionsform.serializeArray();
				// create mutlidimensional array from serializeArray
				var nestedObject = arrayToNestedObject(serializedArray);
				// store as json encoded string
				$input.val( JSON.stringify(nestedObject) ).trigger('change');
			};

		$optionsform.on('change', updateSortable);

		$self.find('.betterrange-box').each(function () {
			var $range  = $(this).find('.betterrange-range'),
				$number = $(this).find('.betterrange-number'),
				$reset  = $(this).find('.betterrange-reset'),
				$input  = $(this).children('input[type="hidden"]');
			$range.on('input change', function () {
				var val = $(this).val();
				$number.val(val);
				$input.val(val).trigger('change');
			});
			$number.on('input change', function () {
				var val = parseFloat( $(this).val() ),
					min = parseFloat( $range.attr('min') ),
					max = parseFloat( $range.attr('max') );
				if (isNaN(val)){ val = min; }
				if (val < min) { val = min; }
				if (val > max) { val = max; }
				$range.val(val);
				$input.val(val).trigger('change');
			});
			$number.on('blur', function () {
				$(this).val( $range.val() );
			});
			if ( $reset.length ) {
				var resetVal = $reset.data('resetval');
				$reset.on('click', function () {
					$range.val(resetVal);
					$number.val(resetVal);
					$input.val(resetVal).trigger('change');
				});
			}
		});

		if ( $self.is('.sortable') ) {
			$self.sortable({
				handle: ".sortlistitem-sort",
				placeholder: "hoot-control-sortlistitem-placeholder",
				update: function(event, ui) {
					updateSortable();
				},
				forcePlaceholderSize: true,
			});
		}

	});


	/*** Radioimage Control ***/

	$('.customize-control-radioimage, .hoot-sortlistitem-option-radioimage').each(function(){

		var $radios = $(this).find('input'),
			$labels = $(this).find('.hoot-customize-radioimage');

		$radios.on('change',function(){
			$labels.removeClass('radiocheck');
			$(this).parent('.hoot-customize-radioimage').addClass('radiocheck');
		});

	});


	/*** Bettertoggle Control ***/

	$('.customize-control-bettertoggle .bettertoggle, .customize-control-bettertoggle .bettertoggle-invert').click( function(e){
		$(this).siblings('input[type=checkbox]').click();
	});


	/*** Betterrange Control ***/
	/*** with Media Queries ***/

	var $footer_devices = $( '#customize-footer-actions .devices button' ),
		$overlaybody = $( '.wp-full-overlay' ),
		$hootmediaswitchers = $( '.hoot-mediaswitcher i' );
	$footer_devices.on( 'click', function( event ) {
		var device = $(this).data('device');
		event.preventDefault();
			// Customizer Preview
			$overlaybody.removeClass( 'preview-desktop preview-tablet preview-mobile' ).addClass( 'preview-' + device );
			$footer_devices.removeClass( 'active' ).attr( 'aria-pressed', false );
			$footer_devices.filter( '.preview-' + device ).addClass( 'active' ).attr( 'aria-pressed', true );
		$hootmediaswitchers.each( function() {
			var $control = $(this).closest('.customize-control-betterrange'),
				$mediaboxes = $control.find('.betterrange-mediabox');
			// Media Switches
			$(this).removeClass('hootactive');
			$(this).filter('[data-device="' + device + '"]').addClass('hootactive');
			// Controls
			$mediaboxes.hide();
			$mediaboxes.filter('[data-device="' + device + '"]').show();
		});
	});

	$('.customize-control-betterrange').each(function () {
		var $this = $(this),
			$mediaswitches = $this.find('.hoot-mediaswitcher i'),
			$box = $this.children('.betterrange-box');
		var $mediaboxes = $box.children('.betterrange-mediabox'),
			$ranges = $mediaboxes.children('.betterrange-range'),
			$input = $box.children('input[type="hidden"]');

		function updateInput() {
			var val = $ranges.length > 1 ? {} : '' ;
			$ranges.each(function () {
				var device = $(this).parent().data('device'),
					deviceval = $(this).val();
				if ( $ranges.length > 1 ) val[device] = deviceval;
				else val = deviceval;
			});
			if ( $ranges.length > 1 ) val = JSON.stringify(val);
			$input.val(val).trigger('change');
		}

		$mediaboxes.each(function () {
			var $range = $(this).children('.betterrange-range'),
				$number = $(this).children('.betterrange-number'),
				$reset = $(this).children('.betterrange-reset');
			$range.on('input change', function () {
				var val = $(this).val();
				$number.val(val);
				updateInput();
			});
			$number.on('input change', function () {
				var val = parseFloat( $(this).val() ),
					min = parseFloat( $range.attr('min') ),
					max = parseFloat( $range.attr('max') );
				if (isNaN(val)){ val = min; }
				if (val < min) { val = min; }
				if (val > max) { val = max; }
				$range.val(val);
				updateInput();
			});
			$number.on('blur', function () {
				$(this).val( $range.val() );
			});
			if ( $reset.length ) {
				var resetVal = $reset.data('resetval');
				$reset.on('click', function () {
					$range.val(resetVal);
					$number.val(resetVal);
					updateInput();
				});
			}
		});

		if ( $mediaswitches ) {
			$mediaswitches.on('click', function () {
				if ( ! $(this).is('.hootactive') ) {
					var device = $(this).data('device');
					// Media Switches
					$mediaswitches.removeClass('hootactive');
					$(this).addClass('hootactive');
					// Controls
					$mediaboxes.slideUp('fast');
					$mediaboxes.filter('[data-device="' + device + '"]').slideDown('fast');
					// Customizer Preview
					$overlaybody.removeClass( 'preview-desktop preview-tablet preview-mobile' ).addClass( 'preview-' + device );
					$footer_devices.removeClass( 'active' ).attr( 'aria-pressed', false );
					$footer_devices.filter( '.preview-' + device ).addClass( 'active' ).attr( 'aria-pressed', true );
				}
			});
		}

	} );


	/*** Betterbackground Control ***/

	$('.hoot-customize-control-betterbackgroundstart').each(function( index ){

		var $blocks = $(this).nextUntil( '.hoot-customize-control-betterbackgroundend', "li" ),
			$bbButtons = $blocks.filter('.hoot-customize-control-betterbackgroundbutton'),
			$buttons = $bbButtons.find('.hoot-betterbackground-button'),
			$typeInput = $bbButtons.find('input.hoot-customize-control-betterbackground'),
			$customs = $blocks.filter('.customize-control-color, .customize-control-image, .customize-control-select'),
			$predefineds = $blocks.filter('.customize-control-color, .hoot-customize-control-groupstart'),
			showBlocks = function(control){
				if ( control == 'predefined' ) {
					$customs.hide();
					$predefineds.show();
				}
				if ( control == 'custom' ) {
					$predefineds.hide();
					$customs.show();
				}
			};

		$blocks.addClass('hoot-customize-control-background-blocks');//.attr('data-controlbackground', id);

		// If we have both custom image and pattern options
		if ( $bbButtons.length ) {
			showBlocks( $typeInput.val() );

			$buttons.on('click',function(){
				var value = $(this).data('value');

				$buttons.removeClass('selected').addClass('deactive');
				$(this).removeClass('deactive').addClass('selected');

				$typeInput.val(value).trigger('change');

				showBlocks(value);
			});

		}

		/* Patterns */

		var $pattPreview = $blocks.find('.hoot-betterbackground-button-pattern'),
			$patterns = $blocks.find('.hoot-customize-radioimage');

		if ( $pattPreview.length ) {
			$pattPreview.html('').append( $patterns.filter('.radiocheck').children('img').clone() );

			$patterns.on('click',function(){
				$pattPreview.html('').append( $(this).children('img').clone() );
			});
		}

	});


	/*** Icon Control ***/

	if ( (typeof hoot_customize_data != 'undefined') && (typeof hoot_customize_data.iconslist != 'undefined') ) {

		/** Fly Icon **/

		var $body = $('body'),
			$flyicon = $('#hoot-flyicon-content');

		$body.on( "openflypanel", function() {
			var $flypanelbutton = $body.data('flypanelbutton');
			if( $flypanelbutton && $flypanelbutton.data('flypaneltype')=='icon' && $flypanelbutton.data('flypanel')=='open' ) {

				$flyicon.html( hoot_customize_data.iconslist ).data('controlgroup', $flypanelbutton);

				var $flyiconIcons = $flyicon.find('i'),
					$input = $flypanelbutton.siblings('input.hoot-customize-control-icon'),
					selected = $input.val(),
					$icondisplay = $flypanelbutton.children('i');

				$flypanelbutton.addClass('flygroup-open');

				if(selected)
					$flyicon.find('i.'+selected.replace(' ', '.')).addClass('selected');

				$flyiconIcons.click( function(event){
					var iconvalue = $(this).data('value');
					$flyiconIcons.removeClass('selected');
					$(this).addClass('selected');
					$input.val( iconvalue ).trigger('change');
					$icondisplay.removeClass().addClass(iconvalue );
					$('.hoot-flypanel-back').trigger('click');
				});

				$body.addClass('hoot-displaying-flyicon');
				$body.data('flypaneltype','icon');
			}
		});

		$body.on( "closeflypanel", function() {
			$body.removeClass('hoot-displaying-flyicon');
			var controlGroup = $flyicon.data('controlgroup');
			if (controlGroup)
				$(controlGroup).removeClass('flygroup-open');
			if($body.data('flypaneltype')=='icon') {
				$body.data('flypaneltype','');
			}
		});

		$('.hoot-customize-control-icon-remove').click( function(event){
			var input = $(this).siblings('input.hoot-customize-control-icon'),
				icondisplay = $(this).siblings('.hoot-customize-control-icon-picked').children('i');
			input.val('').trigger('change');
			icondisplay.removeClass();
			// $('.hoot-flypanel-back').trigger('click'); // redundant
		});

	}


	/*** Tabs Control ***/

	/** Prepare Tabs **/

	$( ".hoot-tabs-control" ).each( function( index ) {
		var $li = $(this).parent('li'),
			liID = $li.attr('id'),
			$tabs = $(this).children('.hoot-tab-control'),
			isHeading = $(this).is('.hoot-tabs-heading'),
			disablejstoggle = $(this).data('disablejstoggle') ? true : false,
			tcount = 1;

		if ( $li.length && $tabs.length ) {
			$tabs.each( function( index ) {
				var tslug = $(this).data('tab');
				var $tabstart = $( '#' + liID + '-' + tslug ),
					$tabend   = $( '#' + liID + '-' + tslug + '-end' );
				var $tabunits = $tabstart.nextUntil($tabend).add($tabstart).add($tabend);

				if ( ! isHeading ) $tabunits.addClass('hoot-tab-unit');
				else               $tabunits.addClass('hoot-tab-fullunit');
				$tabstart.addClass('hoot-tab-start');
				$tabend.addClass('hoot-tab-end');
				if ( tcount === 1 ) {
					$(this).addClass('hootactive');
				} else {
					if ( disablejstoggle ) {
						$tabunits.addClass('hootdeactive');
					} else {
						$tabunits.hide();
					}
				}
				$tabunits.attr('data-controlgroup', liID);

				$(this).on('click', function(){
					$tabs.removeClass('hootactive');
					$(this).addClass('hootactive');
					if ( disablejstoggle ) {
						$('li[data-controlgroup="' + liID + '"]').addClass('hootdeactive');
						$tabunits.removeClass('hootdeactive');
					} else {
						$('li[data-controlgroup="' + liID + '"]').slideUp();
						var $unitsToSlide = $tabunits; // Clone $tabunits to apply exclusions
								var $select1 = $tabunits.filter('#customize-control-sidebar1_width');
								if ($select1.length) {
									var s1val = $select1.find('select').val();
									if (s1val === 'auto') {
										$unitsToSlide = $unitsToSlide.not('#customize-control-sidebar1_width_px,#customize-control-sidebar1_width_pcnt');
									} else if (s1val === 'px') {
										$unitsToSlide = $unitsToSlide.not('#customize-control-sidebar1_width_pcnt');
									} else if (s1val === 'pcnt') {
										$unitsToSlide = $unitsToSlide.not('#customize-control-sidebar1_width_px');
									}
									var $select2 = $tabunits.filter('#customize-control-sidebar2_width');
									if ($select2.length) {
										var s2val = $select2.find('select').val();
										if (s2val === 'auto') {
											$unitsToSlide = $unitsToSlide.not('#customize-control-sidebar2_width_px,#customize-control-sidebar2_width_pcnt');
										} else if (s2val === 'px') {
											$unitsToSlide = $unitsToSlide.not('#customize-control-sidebar2_width_pcnt');
										} else if (s2val === 'pcnt') {
											$unitsToSlide = $unitsToSlide.not('#customize-control-sidebar2_width_px');
										}
									}
								}
						$unitsToSlide.slideDown();
					}
				} );
				tcount++;
			} );
		}
	} );


	/*** Group Control ***/

	/** Prepare Groups **/

	$( ".hoot-customize-control-groupstart" ).each( function( index ) {
		var id = $(this).attr('id'),
			moveBlocks = $(this).nextUntil( '.hoot-customize-control-groupend', "li" );
		moveBlocks.addClass('hoot-customize-control-group-blocks').attr('data-controlgroup', id);
	});


	/** Fly Groups **/

	var $body = $('body');

	$body.on( "openflypanel", function() {
		var $flypanelbutton = $body.data('flypanelbutton');
		if( $flypanelbutton && $flypanelbutton.data('flypaneltype')=='group' && $flypanelbutton.data('flypanel')=='open' ) {
			var $groupstart = $flypanelbutton.parent('.hoot-customize-control-groupstart');
			$groupstart.addClass('flygroup-open');
			var moveBlocks = $groupstart.nextUntil( '.hoot-customize-control-groupend', "li" );
			$('#hoot-flygroup-content ul').css('display','none').html('').append(moveBlocks).fadeIn();
			$body.addClass('hoot-displaying-flygroup');
			$body.data('flypaneltype','group');
		}
	});

	$body.on( "closeflypanel", function() {
		$body.removeClass('hoot-displaying-flygroup');
		if($body.data('flypaneltype')=='group') {
			var itemsToMove = $('#hoot-flygroup-content > ul > li');
			if ( itemsToMove.length ) {
				var controlgroup = $(itemsToMove[0]).data('controlgroup'); // all li's in flygroup have same controlgroup
				itemsToMove.insertBefore('#' + controlgroup + '-end');
				$('#' + controlgroup).removeClass('flygroup-open');
			}
			$body.data('flypaneltype','');
		}
	});


	/*** Multi Check Boxes ***/

	$('.customize-control-bettercheckbox .bettercheckbox-multi').each(function(){

		var $control = $(this),
			$multi = $control.find('input[type="checkbox"]'),
			$input = $control.find('input[type="hidden"]');

		$multi.on('change', function(){
			var multiValues = $multi.filter(':checked').map(function(){
				return this.value;
			}).get().join(',');
			$input.val(multiValues).trigger('change');
		});

	});


	/*** Fly Panels - generic ***/
	// This code doesnt 'do' anything. It just acts as framework for other flypanel types.

	var $body = $("body"),
		$flypanelButtons = $('.hoot-flypanel-button'),
		initFly = function() {
			$flypanelButtons.click( function(event){
				if( $body.data('flypanel')=='open' && $(this).data('flypanel')=='open' ) {
					closeFly();
				} else {
					closeFly();
					openFly($(this));
				}
				event.stopPropagation();
			});
			$('.hoot-flypanel-back, .hoot-flypanel-close').click( function(event){
				closeFly();
				event.stopPropagation();
			});
			$('.hoot-flypanel').click( function(event){
				event.stopPropagation();
			});
			$body.click( function(event){
				if ( ! $(event.target).closest('.media-modal').length )
					closeFly();
			});
			var $obstarget = $('#hoot-flygroup-content ul');
			if ($obstarget.length) {
				var obstarget = $obstarget[0];
				var grpobserver = new MutationObserver(function(mutations) {
					mutations.forEach(function(mutation) {
						if ($(obstarget).children().length === 0) {
							closeFly(true);
						}
					});
				});
				var config = {
					childList: true, // Observe direct child additions/removals
					subtree: false   // Do not observe deeper descendants
				};
				grpobserver.observe(obstarget, config);
			}
		},
		closeFly = function(force=false){
			if( $body.data('flypanel')=='open' ) {
				$body.data('flypanel','close');
				$body.data('flypanelbutton','');
				$flypanelButtons.data('flypanel','close');
				$body.trigger('closeflypanel');
			}
		},
		openFly = function($flypanelButton){
			$body.data('flypanel','open');
			$body.data('flypanelbutton',$flypanelButton);
			$flypanelButton.data('flypanel','open');
			$body.trigger('openflypanel');
		};

	initFly();


});