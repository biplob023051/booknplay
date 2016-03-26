jQuery(document).ready(function( e ) {
	'use strict';

	/* Add prettyphoto to images
	================================================== */
	jQuery.fn.bindPrettyPhoto = function() {
		jQuery( 'a[data-rel^="prettyPhoto"]' ).prettyPhoto({
			hook: 'data-rel',	/* the attribute tag to use for prettyPhoto hooks. default: 'rel'. For HTML5, use "data-rel" or similar. */
			animation_speed: 'fast',
			slideshow: 3000,
			autoplay_slideshow: false,
			opacity: 0.80,
			show_title: false,
			theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			overlay_gallery: false,
			social_tools: false
		});
	};
	jQuery.fn.bindPrettyPhoto();


	/* Superfish menu
	================================================== */
	jQuery( 'ul.sf-menu' ).supersubs({ 
		minWidth: 18.4,	// minimum width of sub-menus in em units 
		maxWidth: 27,	// maximum width of sub-menus in em units 
		extraWidth: 1	// extra width can ensure lines don't sometimes turn over due to slight rounding differences and font-family 
	}).superfish({	// main navigation init
		delay: 200,	// one second delay on mouseout 
		animation: {
			opacity: 'show',
			height: 'show'
		},	// fade-in and slide-down animation 
		speed: 'normal',	// faster animation speed 
		autoArrows: false,	// generation of arrow mark-up (for submenu) 
		dropShadows: false	// drop shadows (for submenu)
	});


	/* Onepage menu effect
	================================================== */
	jQuery( '#main-nav > li > a' ).click(function(){
		var url = jQuery(this).attr( 'href' ).split( '#' )[1],
			section = jQuery( '#' + url ).offset().top;

		if ( url ) {
			if ( url === jQuery('section.row:first-child').attr( 'id' ) ) {
				jQuery( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );
			} else {
				jQuery( 'html, body' ).animate( { scrollTop: section - 80 }, 'slow' );
			}
		} else {
			window.location = jQuery(this).attr( 'href' );
		}
	});

	// add style to active menu item
	var menus = jQuery( '#main-nav > li > a' ),
		sections = menus.map(function () {
			var url = jQuery(this).attr( 'href' ).split( '#' )[1],
				section = jQuery( '#' + url );

			if ( url ) {
				if ( section.length ) { return section; }
			}
		});

	jQuery(window).scroll(function () {
		var id,
			current,
			customTop = jQuery(this).scrollTop() + jQuery( '#main-nav' ).outerHeight();

		current = sections.map(function () {
			if ( jQuery(this).offset().top < customTop ) {
				return this;
			}
		});
		current = current[ current.length - 1 ];
		id = ( current && current.length ) ? current.attr( 'id' ) : '';
		menus.filter( '[href$=#' + id + ']' ).parent().addClass( 'current-menu-item' ).siblings().removeClass( 'current-menu-item' );
	});


	/* Add tooltip
	================================================== */
	jQuery( 'a[data-rel="tipsy"]' ).tipsy({
		fade: true,
		gravity: 's',
		offset: 5
	});


	/* Create select responsive menu
	================================================== */
	// DOM ready
	jQuery(function() {

		// Create the dropdown base
		jQuery( '<div><select />' ).appendTo( '#menu-wrapper > div' );

		// Create default option "Go to..."
		jQuery( '<option />', {
			'selected': 'selected',
			'value': '',
			'text': 'Go to...'
		}).appendTo( '#menu-wrapper select' );

		// Populate dropdown with menu items
		jQuery( '#menu-wrapper a' ).each(function() {
			var el = jQuery(this);
	
			if ( jQuery( el ).parents( '.sub-menu .sub-menu .sub-menu' ).length >= 1 ) {
				jQuery( '<option />', {
					'value': el.attr( 'href' ),
					'text': '- - - ' + el.text()
				}).appendTo( '#menu-wrapper select' );
			}
			else if ( jQuery( el ).parents( '.sub-menu .sub-menu' ).length >= 1 ) {
				jQuery( '<option />', {
					'value': el.attr( 'href' ),
					'text': '- - ' + el.text()
				}).appendTo( '#menu-wrapper select' );
			}
			else if ( jQuery( el ).parents( '.sub-menu' ).length >= 1 ) {
				jQuery( '<option />', {
					'value': el.attr( 'href' ),
					'text': '- ' + el.text()
				}).appendTo( '#menu-wrapper select' );
			}
			else {
				jQuery( '<option />', {
					'value': el.attr( 'href' ),
					'text': el.text()
				}).appendTo( '#menu-wrapper select' );
			}

		});

		// To make dropdown actually work
		// To make more unobtrusive: http: //css-tricks.com/4064-unobtrusive-page-changer/
		jQuery( '#menu-wrapper select' ).change(function() {
			window.location = jQuery(this).find( 'option:selected' ).val();
		});
	});


	/* Google map toggle
	================================================== */
	jQuery( '.google-map .sign-wrapper' ).click(function() {
		if ( jQuery( '.google-map' ).hasClass( 'close' ) ) {
			jQuery( '.google-map' ).removeClass('close');
		} else {
			jQuery( '.google-map' ).addClass('close');
		}
	});


	/* Scroll top
	================================================== */
	jQuery(window).scroll(function() {
		if ( jQuery(this).scrollTop() > 300 ) {
			jQuery( '#backtotop' ).fadeIn();
		} else {
			jQuery( '#backtotop' ).fadeOut();
		}
	});

	jQuery( '#backtotop, .divider-gotop' ).click(function() {
		jQuery( 'body, html' ).stop( false, false ).animate( { scrollTop: 0 }, 800 );
		return false;
	});


	/* Accordion
	================================================== */
	jQuery( 'ul.pa-accordion li' ).each(function() {
		jQuery(this).children( '.accordion-content' ).css( 'height', function() { 
			return jQuery(this).innerHeight(); 
		});

		if ( jQuery(this).hasClass( 'active' ) ) {
			jQuery(this).find( '.accordion-head-sign' ).html( '&minus;' );
		} else {
			jQuery(this).find( '.accordion-head-sign' ).html( '&#43;' );
			jQuery(this).children( '.accordion-content' ).addClass( 'display-none' );
		}

		jQuery(this).children( '.accordion-head' ).bind( 'click', function() {
			jQuery(this).parent().addClass(function() {
				if ( jQuery(this).hasClass( 'active' ) ) {
					return '';
				}
				return 'active';
			});
			jQuery(this).siblings( '.accordion-content' ).slideDown();
			jQuery(this).parent().find( '.accordion-head-sign' ).html( '&minus;' );
			jQuery(this).parent().siblings( 'li' )
				.removeClass( 'active' )
				.children( '.accordion-content' )
					.slideUp()
				.end()
				.find( '.accordion-head-sign' )
					.html( '&#43;' );
		});
	});


	/* Toggle
	================================================== */
	jQuery( 'ul.pa-toggle li' ).each(function() {
		jQuery(this).children( '.toggle-content' ).css( 'height', function() { 
			return jQuery(this).innerHeight(); 
		});

		jQuery(this).children( '.toggle-content' ).css( 'display', 'none' );
		jQuery(this).find( '.toggle-head-sign' ).html( '&#43;' );

		jQuery(this).children( '.toggle-head' ).bind( 'click', function() {

			if ( jQuery(this).parent().hasClass( 'active' ) ) {
				jQuery(this).parent().removeClass( 'active' );
			} else {
				jQuery(this).parent().addClass( 'active' );
			}

			jQuery(this).find( '.toggle-head-sign' ).html(function() {
				if ( jQuery(this).parent().parent().hasClass( 'active' ) ) {
					return '&minus;';
				}
				return '&#43;';
			});
			jQuery(this).siblings( '.toggle-content' ).slideToggle();
		});
	});

	jQuery( 'ul.pa-toggle' ).find( '.toggle-content.active' ).siblings( '.toggle-head' ).trigger( 'click' );


	/* Tab
	================================================== */
	jQuery( 'ul.pa-tabs' ).each(function() {
		// get tabs
		var tab = jQuery(this).find( '> li > a' );
		tab.click(function( e ) {
			// get tab's location
			var contentLocation = jQuery(this).attr( 'href' );
			// Let go if not a hashed one
			if ( contentLocation.charAt(0) === '#' ) {
				e.preventDefault();
				// add class active
				tab.removeClass( 'active' );
				jQuery(this).addClass( 'active' );
				// show tab content & add active class
				jQuery( contentLocation ).fadeIn( 1000 ).addClass( 'active' ).siblings().hide().removeClass( 'active' );

			}
		});
	});


	/* Counter
	================================================== */
	jQuery(window).scroll(function() {
		jQuery( '.counter-number.once' ).each( function() {
			if ( jQuery(this).offset().top < jQuery(window).scrollTop() + jQuery(window).outerHeight() ) {
				jQuery(this).countTo({
					onComplete: function(value) {
						jQuery(this).removeClass('once');
					}
				});
			}
		});
	});


	/* Progress bar
	================================================== */
	jQuery(window).scroll(function() {
		jQuery( '.progress-bar-wrapper.once' ).each( function() {
			if ( jQuery(this).offset().top < jQuery(window).scrollTop() + jQuery(window).outerHeight() ) {
				jQuery(this).find( '.progress-bar-meter' )
					.css({
						width: 0,
						display: 'block'
					})
					.delay( 1000 )
					.animate({
						'width': jQuery(this).find( '.value' ).text()
					},function() {
						jQuery(this).parents('.progress-bar-wrapper.once').removeClass('once');
					});
			}
		});
	});


	/* Contact form
	================================================== */
	jQuery( '#contact-form-dumpit' ).submit(function() {
		jQuery( '#contact-form .error-message' ).remove();
		var formInput,
			hasError = false,
			emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
			responseField = jQuery( 'input#recaptcha_response_field' ).val(),
			challengeField = jQuery( 'input#recaptcha_challenge_field' ).val();

		jQuery( '#contact-form .requiredfield' ).each(function() {
			var fieldError,
				emailError,
				messageError;

			if ( jQuery.trim( jQuery(this).val() ) === '' ) {
				fieldError = true;
				messageError = jQuery(this).data( 'message' );
			}
			if ( jQuery(this).hasClass( 'email' ) && ! emailReg.test( jQuery.trim( jQuery(this).val() ) ) ) {
				emailError = true;
				messageError = jQuery(this).data( 'email' );
			}

			if ( fieldError == true || emailError == true ) {
				jQuery(this).parent()
					.append( '<span class="error-message">' + messageError + '</span>' )
					.find( '.error-message' )
						.width( jQuery(this).width() )
						.height( jQuery(this).height() )
						.hover(function() {
							jQuery(this).fadeOut( 300 );
						});
				jQuery(this).addClass( 'inputError' );
				hasError = true;
			}
		});

		if ( ! hasError ) {

			if ( jQuery( '#contact-recaptcha' ).hasClass( 'true' ) ) {
				jQuery.post( the_ajax_script.ajaxurl, {
					action: 'get_recaptcha',
					recaptcha_challenge_field: challengeField,
					recaptcha_response_field: responseField
				}, function( data ) {
					if ( data == 'true' ) {
						jQuery( '#contact-form #contact-submit' ).fadeOut( 'normal', function() {
							jQuery(this).parent().append( '<div class="wait"></div>' );
						});
						formInput = jQuery( '#contact-form' ).serialize();
						jQuery.post( jQuery( '#contact-form' ).attr( 'action' ), formInput, function() {
							jQuery( '#contact-form' ).slideUp( 'fast', function() {
								jQuery( '#contact-form .wait' ).slideUp( 'fast' );
								jQuery( '#contact-success-message' ).removeClass( 'display-none' );
							});
						});
					} else {
						jQuery( '.recaptcha-error' ).removeClass( 'display-none' ).effect( 'shake' );
					}
				});

			} else {

				jQuery( '#contact-form #contact-submit' ).fadeOut( 'normal', function() {
					jQuery(this).parent().append( '<div class="wait"></div>' );
				});
				formInput = jQuery( '#contact-form' ).serialize();
				jQuery.post( jQuery( '#contact-form' ).attr( 'action' ), formInput, function() {
					jQuery( '#contact-form' ).slideUp( 'fast', function() {
						jQuery( '#contact-form .wait' ).slideUp( 'fast' );
						jQuery( '#contact-success-message' ).removeClass( 'display-none' );
					});
				});
			}
		}

		return false;
	});

});


jQuery(window).load(function() {
	'use strict';

	/* Init parallax sections
	================================================== */
	var i,
		para = jQuery( '.parallax' );
	for ( i = 0; i < para.length; i++ ) {
		jQuery( para[ i ] ).parallax( '50%', '0.3' );
	}


	/* Init sliders
	================================================== */
	jQuery.fn.bindNivo = function() {
		jQuery( '.nivoSlider' ).nivoSlider({
			effect: 'fade',
			directionNav: true,
			directionNavHide: true,
			controlNav: false
		});
	};
	jQuery.fn.bindNivo();

});