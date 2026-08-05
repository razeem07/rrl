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
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var SelectControl = wp.components.SelectControl;
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
			} )
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
		return null;
	}

	registerPlugin( 'royal-limo-admin-panels', {
		render: RoyalLimoPanels,
	} );
} )( window.wp );
