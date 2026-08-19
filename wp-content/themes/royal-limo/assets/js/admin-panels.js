( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.plugins || ! wp.data || ! wp.element || ! wp.components ) {
		return;
	}

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerPlugin = wp.plugins.registerPlugin;
	var PluginDocumentSettingPanel = ( wp.editor && wp.editor.PluginDocumentSettingPanel )
		|| ( wp.editPost && wp.editPost.PluginDocumentSettingPanel );

	// Resolved lazily (as functions, called at render time) rather than
	// cached once at script-load time — if wp.blockEditor hasn't fully
	// attached its exports at the exact instant this IIFE first runs
	// (a load-order/timing race, not something a fixed script
	// dependency list fully rules out in every environment), a
	// module-load-time `var MediaUpload = wp.blockEditor.MediaUpload`
	// would lock in `undefined` forever even after wp.blockEditor
	// finishes loading moments later. Resolving fresh on each render
	// avoids that.
	function getMediaUpload() {
		return ( wp.blockEditor && wp.blockEditor.MediaUpload ) || ( wp.editor && wp.editor.MediaUpload );
	}

	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var SelectControl = wp.components.SelectControl;
	var Button = wp.components.Button;
	var __ = wp.i18n.__;

	if ( ! PluginDocumentSettingPanel ) {
		return;
	}

	function useMeta() {
		var meta = useSelect( function ( select ) {
			var editor = select( 'core/editor' );
			return editor ? editor.getEditedPostAttribute( 'meta' ) : {};
		}, [] );
		var editPost = useDispatch( 'core/editor' ).editPost;

		function setMetaField( key, value ) {
			var next = {};
			next[ key ] = value;
			editPost( { meta: next } );
		}

		return [ meta || {}, setMetaField ];
	}

	function numberField( label, key, meta, setMetaField ) {
		return el( TextControl, {
			key: key,
			label: label,
			type: 'number',
			value: meta[ key ] === undefined || meta[ key ] === null ? '' : meta[ key ],
			onChange: function ( value ) {
				setMetaField( key, value === '' ? '' : Number( value ) );
			},
		} );
	}

	function FleetDetailsPanel() {
		var metaState = useMeta();
		var meta = metaState[0];
		var setMetaField = metaState[1];

		return el(
			PluginDocumentSettingPanel,
			{ name: 'royal-limo-fleet-details', title: __( 'Vehicle Details', 'royal-limo' ), icon: 'car' },
			numberField( __( 'Seating Capacity (seats)', 'royal-limo' ), '_fleet_seating_capacity', meta, setMetaField ),
			numberField( __( 'Luggage Capacity (bags)', 'royal-limo' ), '_fleet_luggage_capacity', meta, setMetaField ),
			numberField( __( 'Max Passengers (Pax)', 'royal-limo' ), '_fleet_pax', meta, setMetaField ),
			numberField( __( 'Full Day Price ($)', 'royal-limo' ), '_fleet_price_full_day', meta, setMetaField ),
			numberField( __( 'Half Day Price ($)', 'royal-limo' ), '_fleet_price_half_day', meta, setMetaField ),
			el( TextControl, {
				key: '_fleet_specs',
				label: __( 'Specs line (e.g. "Leather interior · Bar · WiFi")', 'royal-limo' ),
				value: meta._fleet_specs || '',
				onChange: function ( value ) {
					setMetaField( '_fleet_specs', value );
				},
			} ),
			el( 'hr' ),
			GalleryField( meta, setMetaField ),
			el( 'hr' ),
			el( 'p', { className: 'components-base-control__help' }, __( 'FAQs for this specific vehicle — add as many as you need.', 'royal-limo' ) ),
			FaqRepeater( meta, setMetaField, '_fleet_faqs' )
		);
	}

	function TestimonialRatingPanel() {
		var metaState = useMeta();
		var meta = metaState[0];
		var setMetaField = metaState[1];
		var rating = meta._testimonial_rating || 5;

		var options = [ 5, 4, 3, 2, 1 ].map( function ( n ) {
			return { label: '★'.repeat( n ) + '☆'.repeat( 5 - n ), value: n };
		} );

		return el(
			PluginDocumentSettingPanel,
			{ name: 'royal-limo-testimonial-rating', title: __( 'Rating', 'royal-limo' ), icon: 'star-filled' },
			el( SelectControl, {
				label: __( 'Star Rating', 'royal-limo' ),
				value: rating,
				options: options,
				onChange: function ( value ) {
					setMetaField( '_testimonial_rating', Number( value ) );
				},
			} ),
			el( 'p', { className: 'components-base-control__help' }, __( 'Title = customer name. Main editor content = the quote text.', 'royal-limo' ) )
		);
	}

	function textField( label, key, meta, setMetaField, type ) {
		return el( TextControl, {
			key: key,
			label: label,
			type: type || 'text',
			value: meta[ key ] || '',
			onChange: function ( value ) {
				setMetaField( key, value );
			},
		} );
	}

	function BannerContentPanel() {
		var metaState = useMeta();
		var meta = metaState[0];
		var setMetaField = metaState[1];

		return el(
			PluginDocumentSettingPanel,
			{ name: 'royal-limo-banner-content', title: __( 'Banner Content', 'royal-limo' ), icon: 'button' },
			el( TextControl, {
				label: __( 'Eyebrow Text', 'royal-limo' ),
				help: __( 'Small pill badge shown above the title (e.g. "Experience The Road In Style").', 'royal-limo' ),
				value: meta._banner_eyebrow || '',
				onChange: function ( value ) {
					setMetaField( '_banner_eyebrow', value );
				},
			} ),
			el( TextControl, {
				label: __( 'Hero Title', 'royal-limo' ),
				help: __( 'The big headline shown on this slide.', 'royal-limo' ),
				value: meta._banner_hero_title || '',
				onChange: function ( value ) {
					setMetaField( '_banner_hero_title', value );
				},
			} ),
			el( TextareaControl, {
				label: __( 'Hero Description', 'royal-limo' ),
				help: __( 'Short supporting line under the title. Keep it brief — one or two sentences.', 'royal-limo' ),
				value: meta._banner_hero_description || '',
				rows: 3,
				onChange: function ( value ) {
					setMetaField( '_banner_hero_description', value );
				},
			} ),
			el( 'hr' ),
			textField( __( 'CTA 1 Label', 'royal-limo' ), '_banner_cta1_label', meta, setMetaField ),
			textField( __( 'CTA 1 URL', 'royal-limo' ), '_banner_cta1_url', meta, setMetaField, 'url' ),
			textField( __( 'CTA 2 Label', 'royal-limo' ), '_banner_cta2_label', meta, setMetaField ),
			textField( __( 'CTA 2 URL', 'royal-limo' ), '_banner_cta2_url', meta, setMetaField, 'url' )
		);
	}

	function textareaField( label, key, meta, setMetaField, rows ) {
		return el( TextareaControl, {
			key: key,
			label: label,
			value: meta[ key ] || '',
			rows: rows || 2,
			onChange: function ( value ) {
				setMetaField( key, value );
			},
		} );
	}

	/**
	 * Banner image picker — separate from the featured image (used
	 * elsewhere for the in-content photo), so the page-header banner
	 * and the content photo can be two different pictures instead of
	 * the same one repeating down the page.
	 */
	function BannerImageField( meta, setMetaField ) {
		var MediaUpload = getMediaUpload();
		var imageUrl = meta._service_banner_image || '';

		if ( ! MediaUpload ) {
			return el( 'p', { className: 'components-base-control__help', style: { color: '#cc1818' } },
				__( 'Banner Image picker unavailable — your browser may have an outdated copy of the editor scripts. Try a hard refresh (Ctrl+Shift+R / Cmd+Shift+R).', 'royal-limo' )
			);
		}

		return el( 'div', { className: 'royal-limo-field', style: { marginBottom: '24px' } },
			el( 'p', { className: 'components-base-control__label' }, __( 'Banner Image', 'royal-limo' ) ),
			el( 'p', { className: 'components-base-control__help', style: { marginTop: 0 } }, __( 'Shown in the page-header banner at the top. Falls back to the featured image if left empty.', 'royal-limo' ) ),
			imageUrl ? el( 'img', { src: imageUrl, style: { maxWidth: '100%', display: 'block', marginBottom: '8px', borderRadius: '4px' } } ) : null,
			el( MediaUpload, {
				onSelect: function ( media ) {
					setMetaField( '_service_banner_image', media.url );
				},
				allowedTypes: [ 'image' ],
				render: function ( obj ) {
					return el( Fragment, {},
						el( Button, { variant: 'secondary', onClick: obj.open }, imageUrl ? __( 'Change Image', 'royal-limo' ) : __( 'Select Image', 'royal-limo' ) ),
						imageUrl ? el( Button, { variant: 'link', isDestructive: true, style: { marginLeft: '8px' }, onClick: function () {
							setMetaField( '_service_banner_image', '' );
						} }, __( 'Remove', 'royal-limo' ) ) : null
					);
				},
			} )
		);
	}

	/**
	 * Vehicle gallery — multi-image picker for the single vehicle
	 * page's carousel, separate from the featured image (still used
	 * for card thumbnails elsewhere). Empty gallery just means the
	 * carousel falls back to the featured image alone.
	 */
	function GalleryField( meta, setMetaField ) {
		var MediaUpload = getMediaUpload();
		var images = Array.isArray( meta._fleet_gallery ) ? meta._fleet_gallery : [];

		if ( ! MediaUpload ) {
			return el( 'p', { className: 'components-base-control__help', style: { color: '#cc1818' } },
				__( 'Gallery picker unavailable — your browser may have an outdated copy of the editor scripts. Try a hard refresh (Ctrl+Shift+R / Cmd+Shift+R).', 'royal-limo' )
			);
		}

		function removeImage( index ) {
			setMetaField( '_fleet_gallery', images.filter( function ( url, i ) { return i !== index; } ) );
		}

		return el( 'div', { className: 'royal-limo-field', style: { marginBottom: '24px' } },
			el( 'p', { className: 'components-base-control__label' }, __( 'Gallery Images', 'royal-limo' ) ),
			el( 'p', { className: 'components-base-control__help', style: { marginTop: 0 } }, __( 'Shown as a carousel on this vehicle\'s page. Falls back to the featured image alone if left empty.', 'royal-limo' ) ),
			images.length ? el( 'div', { style: { display: 'flex', flexWrap: 'wrap', gap: '8px', marginBottom: '8px' } },
				images.map( function ( url, index ) {
					return el( 'div', { key: index, style: { position: 'relative' } },
						el( 'img', { src: url, style: { width: '72px', height: '72px', objectFit: 'cover', borderRadius: '4px', display: 'block' } } ),
						el( Button, {
							variant: 'secondary',
							isDestructive: true,
							isSmall: true,
							style: { position: 'absolute', top: '2px', right: '2px', minWidth: '20px', height: '20px', padding: 0, lineHeight: '18px' },
							onClick: function () { removeImage( index ); },
						}, '×' )
					);
				} )
			) : null,
			el( MediaUpload, {
				onSelect: function ( media ) {
					var list = Array.isArray( media ) ? media : [ media ];
					var urls = list.map( function ( item ) { return item.url; } );
					setMetaField( '_fleet_gallery', images.concat( urls ) );
				},
				allowedTypes: [ 'image' ],
				multiple: true,
				render: function ( obj ) {
					return el( Button, { variant: 'secondary', onClick: obj.open }, __( 'Add Images', 'royal-limo' ) );
				},
			} )
		);
	}

	/**
	 * Unlimited FAQ repeater — an array-type meta field (e.g.
	 * _service_faqs / _fleet_faqs, passed as metaKey) instead of a
	 * fixed number of numbered slots, so a service or vehicle can have
	 * as many or as few FAQs as it actually needs.
	 */
	function FaqRepeater( meta, setMetaField, metaKey ) {
		var faqs = Array.isArray( meta[ metaKey ] ) ? meta[ metaKey ] : [];

		function updateFaq( index, field, value ) {
			var next = faqs.map( function ( faq, i ) {
				return i === index ? Object.assign( {}, faq, ( function () {
					var patch = {};
					patch[ field ] = value;
					return patch;
				} )() ) : faq;
			} );
			setMetaField( metaKey, next );
		}

		function removeFaq( index ) {
			setMetaField( metaKey, faqs.filter( function ( faq, i ) { return i !== index; } ) );
		}

		function addFaq() {
			setMetaField( metaKey, faqs.concat( [ { question: '', answer: '' } ] ) );
		}

		var rows = faqs.map( function ( faq, index ) {
			return el( 'div', { key: index, style: { marginBottom: '16px', paddingBottom: '16px', borderBottom: '1px solid #ddd' } },
				el( TextControl, {
					label: __( 'Question', 'royal-limo' ) + ' ' + ( index + 1 ),
					value: faq.question || '',
					onChange: function ( value ) { updateFaq( index, 'question', value ); },
				} ),
				el( TextareaControl, {
					label: __( 'Answer', 'royal-limo' ),
					value: faq.answer || '',
					rows: 2,
					onChange: function ( value ) { updateFaq( index, 'answer', value ); },
				} ),
				el( Button, { variant: 'secondary', isDestructive: true, isSmall: true, onClick: function () { removeFaq( index ); } }, __( 'Remove This FAQ', 'royal-limo' ) )
			);
		} );

		return el( Fragment, {},
			rows,
			el( Button, { variant: 'primary', onClick: addFaq }, __( '+ Add FAQ', 'royal-limo' ) )
		);
	}

	function ServiceDetailsPanel() {
		var metaState = useMeta();
		var meta = metaState[0];
		var setMetaField = metaState[1];

		return el(
			PluginDocumentSettingPanel,
			{ name: 'royal-limo-service-details', title: __( 'Service Detail Page', 'royal-limo' ), icon: 'star-filled' },
			BannerImageField( meta, setMetaField ),
			el( 'hr' ),
			el( 'p', { className: 'components-base-control__help' }, __( '"Why Choose This Service?" checklist — leave any blank to skip. Section heading defaults to "Why Choose [Service Name]?" if left blank.', 'royal-limo' ) ),
			textField( __( 'Section Heading (optional)', 'royal-limo' ), '_service_benefits_heading', meta, setMetaField ),
			textField( __( 'Benefit 1', 'royal-limo' ), '_service_benefit1', meta, setMetaField ),
			textField( __( 'Benefit 2', 'royal-limo' ), '_service_benefit2', meta, setMetaField ),
			textField( __( 'Benefit 3', 'royal-limo' ), '_service_benefit3', meta, setMetaField ),
			textField( __( 'Benefit 4', 'royal-limo' ), '_service_benefit4', meta, setMetaField ),
			el( 'hr' ),
			el( 'p', { className: 'components-base-control__help' }, __( 'Feature highlight cards — leave any blank to skip. Section heading defaults to "What\'s Included" if left blank.', 'royal-limo' ) ),
			textField( __( 'Section Heading (optional)', 'royal-limo' ), '_service_features_heading', meta, setMetaField ),
			textField( __( 'Feature 1 Title', 'royal-limo' ), '_service_feature1_title', meta, setMetaField ),
			textField( __( 'Feature 2 Title', 'royal-limo' ), '_service_feature2_title', meta, setMetaField ),
			textField( __( 'Feature 3 Title', 'royal-limo' ), '_service_feature3_title', meta, setMetaField ),
			el( 'hr' ),
			el( 'p', { className: 'components-base-control__help' }, __( '"Our Booking Process" steps for this service — leave all step titles blank to hide the section entirely. Eyebrow/heading default to "How It Works" / "[Service Name] Booking Process".', 'royal-limo' ) ),
			textField( __( 'Eyebrow (optional)', 'royal-limo' ), '_service_process_eyebrow', meta, setMetaField ),
			textField( __( 'Section Heading (optional)', 'royal-limo' ), '_service_process_heading', meta, setMetaField ),
			textField( __( 'Step 1 Title', 'royal-limo' ), '_service_process_step1_title', meta, setMetaField ),
			textareaField( __( 'Step 1 Description', 'royal-limo' ), '_service_process_step1_description', meta, setMetaField ),
			textField( __( 'Step 2 Title', 'royal-limo' ), '_service_process_step2_title', meta, setMetaField ),
			textareaField( __( 'Step 2 Description', 'royal-limo' ), '_service_process_step2_description', meta, setMetaField ),
			textField( __( 'Step 3 Title', 'royal-limo' ), '_service_process_step3_title', meta, setMetaField ),
			textareaField( __( 'Step 3 Description', 'royal-limo' ), '_service_process_step3_description', meta, setMetaField ),
			el( 'hr' ),
			el( 'p', { className: 'components-base-control__help' }, __( 'For rich text content shown before the FAQ section (headings, images, formatting), use Services → Additional Content in the left-hand admin menu.', 'royal-limo' ) ),
			el( 'hr' ),
			el( 'p', { className: 'components-base-control__help' }, __( 'FAQs for this specific service — add as many as you need. Eyebrow/heading default to "Good To Know" / "[Service Name] — Frequently Asked Questions".', 'royal-limo' ) ),
			textField( __( 'Eyebrow (optional)', 'royal-limo' ), '_service_faq_eyebrow', meta, setMetaField ),
			textField( __( 'Section Heading (optional)', 'royal-limo' ), '_service_faq_heading', meta, setMetaField ),
			FaqRepeater( meta, setMetaField, '_service_faqs' )
		);
	}

	function TeamMemberDetailsPanel() {
		var metaState = useMeta();
		var meta = metaState[0];
		var setMetaField = metaState[1];

		return el(
			PluginDocumentSettingPanel,
			{ name: 'royal-limo-team-member-details', title: __( 'Team Member Details', 'royal-limo' ), icon: 'admin-users' },
			textField( __( 'Role / Title', 'royal-limo' ), '_team_role', meta, setMetaField ),
			el( 'p', { className: 'components-base-control__help' }, __( 'Title = full name. Featured image = photo. Social links below are optional — leave blank to hide that icon.', 'royal-limo' ) ),
			el( 'hr' ),
			textField( __( 'Facebook URL', 'royal-limo' ), '_team_facebook', meta, setMetaField, 'url' ),
			textField( __( 'Twitter / X URL', 'royal-limo' ), '_team_twitter', meta, setMetaField, 'url' ),
			textField( __( 'LinkedIn URL', 'royal-limo' ), '_team_linkedin', meta, setMetaField, 'url' ),
			textField( __( 'Instagram URL', 'royal-limo' ), '_team_instagram', meta, setMetaField, 'url' )
		);
	}

	function RoyalLimoPanels() {
		var postType = useSelect( function ( select ) {
			var editor = select( 'core/editor' );
			return editor ? editor.getCurrentPostType() : null;
		}, [] );

		if ( 'fleet' === postType ) {
			return el( FleetDetailsPanel );
		}
		if ( 'testimonial' === postType ) {
			return el( TestimonialRatingPanel );
		}
		if ( 'banner' === postType ) {
			return el( BannerContentPanel );
		}
		if ( 'service' === postType ) {
			return el( ServiceDetailsPanel );
		}
		if ( 'team_member' === postType ) {
			return el( TeamMemberDetailsPanel );
		}
		return null;
	}

	registerPlugin( 'royal-limo-admin-panels', {
		render: RoyalLimoPanels,
	} );
} )( window.wp );
