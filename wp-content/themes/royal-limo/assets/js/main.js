(function () {
	'use strict';

	// Sticky header shrink/blur state.
	var header = document.getElementById( 'site-header' );
	if ( header ) {
		var onScroll = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 40 );
		};
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	// Mobile nav toggle.
	var navToggle = document.getElementById( 'nav-toggle' );
	var primaryMenu = document.getElementById( 'primary-menu' );
	if ( navToggle && primaryMenu ) {
		navToggle.addEventListener( 'click', function () {
			var isOpen = primaryMenu.classList.toggle( 'is-open' );
			navToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );
	}

	// Dropdown submenus: click-to-toggle (needed for touch; desktop also
	// gets hover-to-open via CSS). Parent links with a real "#" placeholder
	// href toggle instead of navigating; real links behave normally.
	document.querySelectorAll( '.menu-item-has-children > a' ).forEach( function ( link ) {
		var parentItem = link.parentElement;
		link.addEventListener( 'click', function ( e ) {
			var href = link.getAttribute( 'href' ) || '';
			if ( '#' === href || '' === href ) {
				e.preventDefault();
			}
			var isOpen = parentItem.classList.toggle( 'is-open' );
			document.querySelectorAll( '.menu-item-has-children.is-open' ).forEach( function ( openItem ) {
				if ( openItem !== parentItem ) {
					openItem.classList.remove( 'is-open' );
				}
			} );
		} );
	} );

	document.addEventListener( 'click', function ( e ) {
		if ( ! e.target.closest( '.menu-item-has-children' ) ) {
			document.querySelectorAll( '.menu-item-has-children.is-open' ).forEach( function ( openItem ) {
				openItem.classList.remove( 'is-open' );
			} );
		}
	} );

	// Testimonial carousel (plain JS, no plugin).
	document.querySelectorAll( '[data-rl-carousel]' ).forEach( function ( carousel ) {
		var track = carousel.querySelector( '.rl-testimonial-track' );
		var slides = carousel.querySelectorAll( '.rl-testimonial-slide' );
		var dots = carousel.querySelectorAll( '.rl-testimonial-dot' );
		var index = 0;
		var timer;

		function goTo( i ) {
			index = ( i + slides.length ) % slides.length;
			track.style.transform = 'translateX(-' + ( index * 100 ) + '%)';
			dots.forEach( function ( dot, di ) {
				dot.classList.toggle( 'is-active', di === index );
			} );
		}

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				goTo( parseInt( dot.getAttribute( 'data-index' ), 10 ) );
				resetTimer();
			} );
		} );

		function resetTimer() {
			clearInterval( timer );
			timer = setInterval( function () {
				goTo( index + 1 );
			}, 6000 );
		}

		if ( slides.length > 1 ) {
			resetTimer();
		}
	} );

	// Hero banner carousel (plain JS core; animations.js optionally
	// enhances the text transition with GSAP via the dispatched event —
	// navigation works fully without it).
	document.querySelectorAll( '[data-rl-hero-carousel]' ).forEach( function ( carousel ) {
		var track = carousel.querySelector( '.rl-hero-track' );
		var slides = carousel.querySelectorAll( '.rl-hero-slide' );
		var prevBtn = carousel.querySelector( '[data-rl-hero-prev]' );
		var nextBtn = carousel.querySelector( '[data-rl-hero-next]' );
		var index = 0;
		var timer;

		function goTo( i, opts ) {
			index = ( i + slides.length ) % slides.length;
			track.style.transform = 'translateX(-' + ( index * 100 ) + '%)';

			var detail = { index: index, slide: slides[ index ] };
			carousel.dispatchEvent( new CustomEvent( 'rl:hero-slide-change', { detail: detail } ) );

			if ( ! opts || ! opts.silent ) {
				resetTimer();
			}
		}

		function resetTimer() {
			clearInterval( timer );
			if ( slides.length > 1 ) {
				timer = setInterval( function () {
					goTo( index + 1 );
				}, 6000 );
			}
		}

		if ( prevBtn ) {
			prevBtn.addEventListener( 'click', function () {
				goTo( index - 1 );
			} );
		}
		if ( nextBtn ) {
			nextBtn.addEventListener( 'click', function () {
				goTo( index + 1 );
			} );
		}

		// Deferred to the next tick: animations.js (loaded after this
		// file) needs to finish registering its 'rl:hero-slide-change'
		// listener first, or this initial dispatch fires before anyone
		// is listening and the first slide never gets its entrance.
		setTimeout( function () {
			goTo( 0 );
		}, 0 );
	} );

	// Service-areas route graphic: position the connecting line and car
	// icon to precisely match the real dot positions, rather than
	// guessing fixed percentages in CSS — robust to any stop count or
	// container width. Runs unconditionally (not GSAP-dependent) so the
	// line/car sit in the right place even if the entrance animation
	// (animations.js) never runs; that script just adds motion on top.
	document.querySelectorAll( '[data-rl-route]' ).forEach( function ( route ) {
		var line = route.querySelector( '[data-rl-route-line]' );
		var car = route.querySelector( '[data-rl-route-car]' );
		var dots = route.querySelectorAll( '.rl-route__dot' );
		if ( ! line || ! dots.length ) {
			return;
		}

		function positionRoute() {
			// Mobile switches to a vertical stacked layout via CSS
			// (.rl-route__line/.rl-route__car get display:none) — skip
			// the horizontal measurement entirely there.
			if ( window.innerWidth <= 768 ) {
				return;
			}

			var routeRect = route.getBoundingClientRect();
			var firstDot = dots[ 0 ].getBoundingClientRect();
			var lastDot = dots[ dots.length - 1 ].getBoundingClientRect();
			var centerY = ( firstDot.top + firstDot.height / 2 ) - routeRect.top;
			var startX = ( firstDot.left + firstDot.width / 2 ) - routeRect.left;
			var endX = ( lastDot.left + lastDot.width / 2 ) - routeRect.left;

			line.style.top = centerY + 'px';
			line.style.left = startX + 'px';
			line.style.width = ( endX - startX ) + 'px';

			if ( car ) {
				car.style.top = centerY + 'px';
				car.style.left = startX + 'px';
			}

			route.setAttribute( 'data-route-start-x', startX );
			route.setAttribute( 'data-route-end-x', endX );
		}

		positionRoute();
		window.addEventListener( 'resize', positionRoute );
	} );

	// Video Showcase lightbox — hand-rolled, no external lightbox library.
	// Detects YouTube/Vimeo URLs and builds the matching embed; anything
	// else is treated as a direct video file.
	function buildVideoEmbed( url ) {
		var youtubeMatch = url.match( /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]+)/ );
		if ( youtubeMatch ) {
			var iframe = document.createElement( 'iframe' );
			iframe.src = 'https://www.youtube.com/embed/' + youtubeMatch[ 1 ] + '?autoplay=1&rel=0';
			iframe.setAttribute( 'allow', 'autoplay; encrypted-media; picture-in-picture' );
			iframe.setAttribute( 'allowfullscreen', '' );
			iframe.setAttribute( 'frameborder', '0' );
			return iframe;
		}

		var vimeoMatch = url.match( /vimeo\.com\/(\d+)/ );
		if ( vimeoMatch ) {
			var vIframe = document.createElement( 'iframe' );
			vIframe.src = 'https://player.vimeo.com/video/' + vimeoMatch[ 1 ] + '?autoplay=1';
			vIframe.setAttribute( 'allow', 'autoplay; encrypted-media; picture-in-picture' );
			vIframe.setAttribute( 'allowfullscreen', '' );
			vIframe.setAttribute( 'frameborder', '0' );
			return vIframe;
		}

		var video = document.createElement( 'video' );
		video.src = url;
		video.controls = true;
		video.autoplay = true;
		video.playsInline = true;
		return video;
	}

	document.querySelectorAll( '[data-rl-video-play]' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			var url = button.getAttribute( 'data-video-url' );
			if ( ! url ) {
				return;
			}

			var modal = document.createElement( 'div' );
			modal.className = 'rl-video-modal';

			var backdrop = document.createElement( 'div' );
			backdrop.className = 'rl-video-modal__backdrop';

			var inner = document.createElement( 'div' );
			inner.className = 'rl-video-modal__inner';

			var closeBtn = document.createElement( 'button' );
			closeBtn.type = 'button';
			closeBtn.className = 'rl-video-modal__close';
			closeBtn.setAttribute( 'aria-label', 'Close video' );
			closeBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 6l12 12M18 6L6 18"/></svg>';

			var media = document.createElement( 'div' );
			media.className = 'rl-video-modal__media';
			media.appendChild( buildVideoEmbed( url ) );

			inner.appendChild( closeBtn );
			inner.appendChild( media );
			modal.appendChild( backdrop );
			modal.appendChild( inner );
			document.body.appendChild( modal );
			document.body.classList.add( 'rl-modal-open' );

			function closeModal() {
				modal.remove();
				document.body.classList.remove( 'rl-modal-open' );
				document.removeEventListener( 'keydown', onKeydown );
				button.focus();
			}
			function onKeydown( e ) {
				if ( 'Escape' === e.key ) {
					closeModal();
				}
			}

			backdrop.addEventListener( 'click', closeModal );
			closeBtn.addEventListener( 'click', closeModal );
			document.addEventListener( 'keydown', onKeydown );
			closeBtn.focus();
		} );
	} );

	// FAQ accordion — single-open (opening one closes any other), pure
	// CSS handles the height transition (grid-template-rows on
	// .rl-faq-item__answer); this just toggles .is-open/aria-expanded.
	document.querySelectorAll( '[data-rl-accordion]' ).forEach( function ( accordion ) {
		var items = accordion.querySelectorAll( '.rl-faq-item' );
		items.forEach( function ( item ) {
			var question = item.querySelector( '.rl-faq-item__question' );
			if ( ! question ) {
				return;
			}
			question.addEventListener( 'click', function () {
				var willOpen = ! item.classList.contains( 'is-open' );
				items.forEach( function ( other ) {
					other.classList.remove( 'is-open' );
					var otherQuestion = other.querySelector( '.rl-faq-item__question' );
					if ( otherQuestion ) {
						otherQuestion.setAttribute( 'aria-expanded', 'false' );
					}
				} );
				if ( willOpen ) {
					item.classList.add( 'is-open' );
					question.setAttribute( 'aria-expanded', 'true' );
				}
			} );
		} );
	} );
} )();
