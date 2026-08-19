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

	// Category filter tabs (Fleet homepage teaser) — client-side show/
	// hide by data-categories, no page reload. Cards with no categories
	// assigned always match "All" but never match a specific filter,
	// same as leaving a WP post uncategorized.
	document.querySelectorAll( '[data-rl-filter-group]' ).forEach( function ( group ) {
		var target = group.parentElement.querySelector( '[data-rl-filter-target]' );
		if ( ! target ) {
			return;
		}
		var buttons = group.querySelectorAll( '[data-filter]' );
		var cards = target.children;

		buttons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var filter = button.getAttribute( 'data-filter' );

				buttons.forEach( function ( b ) {
					b.classList.toggle( 'is-active', b === button );
				} );

				Array.prototype.forEach.call( cards, function ( card ) {
					var categories = ( card.getAttribute( 'data-categories' ) || '' ).split( ' ' );
					var matches = 'all' === filter || categories.indexOf( filter ) !== -1;
					card.style.display = matches ? '' : 'none';
				} );
			} );
		} );
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

	// Fleet detail page image gallery (single-fleet.php) — dot
	// navigation only, no autoplay (unlike the testimonial/hero
	// carousels above): a vehicle's photos aren't time-sensitive
	// content, so cycling them automatically would just be distracting.
	document.querySelectorAll( '[data-rl-fleet-gallery]' ).forEach( function ( gallery ) {
		var track = gallery.querySelector( '.rl-fleet-gallery__track' );
		var slides = gallery.querySelectorAll( '.rl-fleet-gallery__slide' );
		var dots = gallery.querySelectorAll( '.rl-fleet-gallery__dot' );

		function goTo( index ) {
			index = ( index + slides.length ) % slides.length;
			track.style.transform = 'translateX(-' + ( index * 100 ) + '%)';
			dots.forEach( function ( dot, di ) {
				dot.classList.toggle( 'is-active', di === index );
			} );
		}

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				goTo( parseInt( dot.getAttribute( 'data-index' ), 10 ) );
			} );
		} );
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
	// else is treated as a direct video file. `opts` lets the same
	// builder serve two different uses: the lightbox (sound on, native
	// controls) and the silent background preview below (muted, looping,
	// no controls) — see [data-rl-video-bg] further down.
	function buildVideoEmbed( url, opts ) {
		opts = opts || {};
		var muted = !! opts.muted;
		var loop = !! opts.loop;
		var showControls = false !== opts.controls;

		var youtubeMatch = url.match( /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]+)/ );
		if ( youtubeMatch ) {
			var videoId = youtubeMatch[ 1 ];
			var ytParams = [ 'autoplay=1', 'rel=0', 'playsinline=1' ];
			if ( muted ) { ytParams.push( 'mute=1' ); }
			if ( loop ) { ytParams.push( 'loop=1', 'playlist=' + videoId ); }
			if ( ! showControls ) { ytParams.push( 'controls=0' ); }
			var iframe = document.createElement( 'iframe' );
			iframe.src = 'https://www.youtube.com/embed/' + videoId + '?' + ytParams.join( '&' );
			iframe.setAttribute( 'allow', 'autoplay; encrypted-media; picture-in-picture' );
			iframe.setAttribute( 'allowfullscreen', '' );
			iframe.setAttribute( 'frameborder', '0' );
			return iframe;
		}

		var vimeoMatch = url.match( /vimeo\.com\/(\d+)/ );
		if ( vimeoMatch ) {
			var vimeoParams = [ 'autoplay=1' ];
			if ( muted ) { vimeoParams.push( 'muted=1' ); }
			if ( loop ) { vimeoParams.push( 'loop=1' ); }
			if ( ! showControls ) { vimeoParams.push( 'controls=0', 'background=1' ); }
			var vIframe = document.createElement( 'iframe' );
			vIframe.src = 'https://player.vimeo.com/video/' + vimeoMatch[ 1 ] + '?' + vimeoParams.join( '&' );
			vIframe.setAttribute( 'allow', 'autoplay; encrypted-media; picture-in-picture' );
			vIframe.setAttribute( 'allowfullscreen', '' );
			vIframe.setAttribute( 'frameborder', '0' );
			return vIframe;
		}

		var video = document.createElement( 'video' );
		video.src = url;
		video.autoplay = true;
		video.playsInline = true;
		video.controls = showControls;
		video.muted = muted;
		video.loop = loop;
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

	// Video Showcase background preview — once the card scrolls into
	// view, a muted/looping copy of the same video plays silently behind
	// the poster image (the button above still opens the full lightbox
	// with sound). Plain IntersectionObserver, not GSAP/ScrollTrigger —
	// this is core behavior, not a decorative animation, so it shouldn't
	// depend on those loading. Skipped under prefers-reduced-motion,
	// same as every other motion effect in this theme; the static poster
	// image underneath is a complete result on its own either way.
	var prefersReducedMotionForVideo = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	if ( ! prefersReducedMotionForVideo && 'IntersectionObserver' in window ) {
		var videoBgEls = document.querySelectorAll( '[data-rl-video-bg]' );
		if ( videoBgEls.length ) {
			var videoBgObserver = new IntersectionObserver( function ( entries, observer ) {
				entries.forEach( function ( entry ) {
					if ( ! entry.isIntersecting ) {
						return;
					}
					var container = entry.target;
					var url = container.getAttribute( 'data-video-url' );
					if ( url ) {
						var embed = buildVideoEmbed( url, { muted: true, loop: true, controls: false } );
						container.appendChild( embed );
						if ( 'VIDEO' === embed.tagName ) {
							// Autoplay is muted so browsers should allow it, but
							// dynamically-inserted <video> elements don't always
							// honor the autoplay attribute reliably — call
							// play() directly and swallow a rejected promise
							// (e.g. a browser blocking it anyway) rather than
							// letting it surface as an unhandled error; the
							// poster image is still showing either way.
							var playResult = embed.play();
							if ( playResult && 'function' === typeof playResult.catch ) {
								playResult.catch( function () {} );
							}
						}
					}
					observer.unobserve( container );
				} );
			}, { threshold: 0.4 } );

			videoBgEls.forEach( function ( el ) {
				videoBgObserver.observe( el );
			} );
		}
	}

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
