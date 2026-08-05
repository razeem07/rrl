(function () {
	'use strict';

	if ( typeof gsap === 'undefined' ) {
		return;
	}

	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	if ( prefersReducedMotion ) {
		return;
	}

	if ( typeof ScrollTrigger !== 'undefined' ) {
		gsap.registerPlugin( ScrollTrigger );
	}

	// Shared count-up animation for any [data-target]/[data-suffix] element.
	// Used both for scroll-triggered counters (below the fold) and for the
	// hero's trust stats, which are triggered directly by slide-change
	// instead (see note below on why ScrollTrigger doesn't fit those).
	function animateCounter( el, extraTweenProps ) {
		var target = parseFloat( el.getAttribute( 'data-target' ), 10 ) || 0;
		var suffix = el.getAttribute( 'data-suffix' ) || '';
		var counter = { value: 0 };

		var tweenProps = Object.assign( {
			value: target,
			duration: 1.6,
			ease: 'power2.out',
			onUpdate: function () {
				el.textContent = Math.round( counter.value ).toLocaleString() + suffix;
			},
		}, extraTweenProps || {} );

		gsap.to( counter, tweenProps );
	}

	// Hero carousel: animate each slide's text (and trust-stat counters)
	// in as it becomes active. Core navigation (main.js) works with zero
	// dependency on this — gsap.fromTo() sets its own starting state, so
	// nothing depends on this code running for the content to be visible.
	//
	// The stat counters here are intentionally NOT scroll-triggered like
	// the ones below the fold: they live inside a carousel track that's
	// positioned via CSS transform, and ScrollTrigger measures elements'
	// viewport position — which for off-screen slides would be wrong/
	// unreliable relative to page scroll. Triggering directly off the
	// carousel's own slide-change event sidesteps that entirely.
	// Cascade order for the hero entrance: each element starts ~0.12s
	// after the previous one instead of the whole copy block fading in as
	// a single flat unit. This staggered-reveal technique (not a fancier
	// easing curve) is what actually reads as "smooth"/premium — same
	// approach page-builder themes like Elementor use for entrance
	// animations, just done by hand here with GSAP's stagger option.
	var HERO_CASCADE_SELECTORS = [
		'.rl-pill-badge',
		'.rl-hero-slide__title',
		'.rl-hero-slide__description',
		'.rl-hero-slide__ctas',
		'.rl-hero-stats',
	];
	var HERO_CASCADE_STAGGER = 0.12;

	document.querySelectorAll( '[data-rl-hero-carousel]' ).forEach( function ( carousel ) {
		carousel.addEventListener( 'rl:hero-slide-change', function ( e ) {
			var slide = e.detail && e.detail.slide;
			if ( ! slide ) {
				return;
			}

			var cascadeEls = HERO_CASCADE_SELECTORS
				.map( function ( sel ) { return slide.querySelector( sel ); } )
				.filter( Boolean );

			if ( cascadeEls.length ) {
				gsap.fromTo( cascadeEls,
					{ opacity: 0, y: 28 },
					{ opacity: 1, y: 0, duration: 0.8, ease: 'power3.out', stagger: HERO_CASCADE_STAGGER }
				);
			}

			var statsIndex = HERO_CASCADE_SELECTORS.indexOf( '.rl-hero-stats' );
			var statsDelay = ( statsIndex * HERO_CASCADE_STAGGER ) + 0.15;
			slide.querySelectorAll( '[data-rl-hero-counter]' ).forEach( function ( el ) {
				animateCounter( el, { delay: statsDelay } );
			} );
		} );
	} );

	// Service-areas route: draw the connecting line in and slide the car
	// icon along it as the section scrolls into view. Positions (data-
	// route-start-x/end-x) come from main.js, which measures the real
	// dot centers — this only adds motion on top of layout that's
	// already correct without it (see the note in main.js).
	document.querySelectorAll( '[data-rl-route]' ).forEach( function ( route ) {
		var line = route.querySelector( '[data-rl-route-line]' );
		var car = route.querySelector( '[data-rl-route-car]' );
		// Mobile uses the vertical stacked layout (CSS hides line/car
		// there) — skip setting up this animation entirely.
		if ( ! line || window.innerWidth <= 768 ) {
			return;
		}

		var startX = parseFloat( route.getAttribute( 'data-route-start-x' ) ) || 0;
		var endX = parseFloat( route.getAttribute( 'data-route-end-x' ) ) || 0;

		// scrollTrigger passed as a plain config object (not a direct
		// ScrollTrigger.create() call) — consistent with the rest of this
		// file, and avoids referencing the bare ScrollTrigger global,
		// which would throw if that script failed to load while gsap
		// itself succeeded.
		//
		// toggleActions has 4 slots: onEnter/onLeave/onEnterBack/onLeaveBack.
		// "reverse" on BOTH onEnterBack and onLeaveBack is needed to cover
		// scrolling back up whether you'd fully scrolled past the section
		// first (re-entering from its end boundary) or only partially
		// entered it before turning back (leaving via its start boundary)
		// — either way the car should travel back right-to-left. start is
		// later (top 70%, was 85%) and duration longer (2s, was 1.1s) so
		// it's actually visible while you're looking at the section
		// instead of finishing before it's fully in view.
		var tl = gsap.timeline( {
			paused: true,
			defaults: { ease: 'power2.inOut', immediateRender: false },
			scrollTrigger: {
				trigger: route,
				start: 'top 70%',
				toggleActions: 'play none reverse reverse',
			},
		} );
		// immediateRender: false — without it, GSAP applies the "from"
		// state (scaleX: 0, i.e. an invisible zero-width line) the
		// instant this script runs, regardless of scroll position, so
		// the line/car would sit invisible/uninitialized until the user
		// actually scrolls this far — same bug class documented for the
		// video showcase cascade below (caught via the same debugging).
		tl.fromTo( line, { scaleX: 0 }, { scaleX: 1, duration: 2 }, 0.3 );
		if ( car ) {
			tl.fromTo( car, { x: 0 }, { x: endX - startX, duration: 2 }, 0.3 );
		}
	} );

	// Video Showcase: same staggered-cascade technique as the hero
	// (badge -> heading -> description -> checks -> video card, ~0.12s
	// apart) rather than the whole panel fading in as one flat block —
	// one-shot since this section only appears once (not per-carousel-
	// slide like the hero).
	//
	// immediateRender: false is required here — without it, GSAP applies
	// the "from" state (opacity: 0) the instant this script runs, at
	// page load, regardless of scroll position, leaving this content
	// invisible until the user happens to scroll this far. Confirmed via
	// direct opacity inspection before vs. after scrolling into view
	// (this bug — and the identical one in the route-graphic animation
	// above — was caught that way, not by assumption).
	document.querySelectorAll( '.rl-video-showcase' ).forEach( function ( section ) {
		var cascadeEls = Array.prototype.slice.call( section.querySelectorAll(
			'.rl-pill-badge, .rl-video-showcase__copy h2, .rl-video-showcase__copy p, .rl-video-showcase__checks, [data-rl-cascade].rl-video-card'
		) );
		if ( ! cascadeEls.length ) {
			return;
		}
		gsap.fromTo( cascadeEls,
			{ opacity: 0, y: 28 },
			{
				opacity: 1,
				y: 0,
				duration: 0.8,
				ease: 'power3.out',
				stagger: 0.12,
				immediateRender: false,
				scrollTrigger: {
					trigger: section,
					start: 'top 80%',
					once: true,
				},
			}
		);
	} );

	// Generic section reveals on scroll.
	document.querySelectorAll( '.rl-reveal' ).forEach( function ( el ) {
		gsap.to( el, {
			opacity: 1,
			y: 0,
			duration: 0.8,
			ease: 'power3.out',
			scrollTrigger: {
				trigger: el,
				start: 'top 85%',
			},
		} );
	} );

	// Staggered card grids (services, fleet).
	document.querySelectorAll( '.rl-grid' ).forEach( function ( grid ) {
		var cards = grid.querySelectorAll( '.rl-service-card, .rl-fleet-card' );
		if ( ! cards.length ) {
			return;
		}
		gsap.to( cards, {
			opacity: 1,
			y: 0,
			scale: 1,
			duration: 0.9,
			ease: 'power4.out',
			stagger: 0.16,
			scrollTrigger: {
				trigger: grid,
				start: 'top 85%',
			},
		} );
	} );

	// Animated stat counters (below-the-fold "Why Choose Us" section).
	document.querySelectorAll( '[data-rl-counter]' ).forEach( function ( el ) {
		animateCounter( el, {
			scrollTrigger: {
				trigger: el,
				start: 'top 90%',
				once: true,
			},
		} );
	} );
} )();
