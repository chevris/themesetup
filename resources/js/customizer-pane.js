/**
 * Scripts for customizer controls panel.
 */

/**
 * Internal dependencies
 */
import {
	collapseSection,
	expandSection
} from './utils';

/**
 * Dispatch custom event to handle device preview changes.
 */
window.addEventListener( 'load', () => {
	let deviceFooterButtons = document.querySelector( '#customize-footer-actions .devices' );
	deviceFooterButtons.addEventListener( 'click', function( e ) {
		let customEvent = new CustomEvent( 'themesetupChangedRepsonsivePreview', {
			'detail': e.target.dataset.device
		});
		document.dispatchEvent( customEvent );
	});
});

/**
 * Handle custom expanded sections.
 */
jQuery( document ).ready( function() {

	wp.customize.section.each( function( section ) {

		// Get the pane element of each section.
		var pane = jQuery( '#sub-accordion-section-' + section.id ),
			sectionLi = jQuery( '#accordion-section-' + section.id );

		// Check if the section is expanded.
		if ( sectionLi.hasClass( 'control-section-themesetup_expanded_section' ) ) {

			// Move element.
			pane.appendTo( sectionLi );

		}

	});

});

/**
 * Handle custom nested panels.
 *
 * @see https://wordpress.stackexchange.com/a/256103/17078
 */
( () => {

	var _panelEmbed,
		_panelIsContextuallyActive,
		_panelAttachEvents;

	wp.customize.bind( 'pane-contents-reflowed', function() {

		var panels = [];

		// Reflow panels
		wp.customize.panel.each( function( panel ) {
			if ( 'themesetup_nested_panel' !== panel.params.type || 'undefined' === typeof panel.params.panel ) {
				return;
			}
			panels.push( panel );
		});

		panels.sort( wp.customize.utils.prioritySort ).reverse();

		jQuery.each( panels, function( i, panel ) {
			var parentContainer = jQuery( '#sub-accordion-panel-' + panel.params.panel );
			parentContainer.children( '.panel-meta' ).after( panel.headContainer );
		});

	});

	// Extend Panel.
	_panelEmbed = wp.customize.Panel.prototype.embed;
	_panelIsContextuallyActive = wp.customize.Panel.prototype.isContextuallyActive;
	_panelAttachEvents = wp.customize.Panel.prototype.attachEvents;

	wp.customize.Panel = wp.customize.Panel.extend({
		attachEvents: function() {
			var panel;

			if ( 'themesetup_nested_panel' !== this.params.type || _.isUndefined( this.params.panel ) ) {
				_panelAttachEvents.call( this );
				return;
			}

			_panelAttachEvents.call( this );

			panel = this;

			panel.expanded.bind( function( expanded ) {
				var parent = wp.customize.panel( panel.params.panel );

				if ( expanded ) {
					parent.contentContainer.addClass( 'current-panel-parent' );
				} else {
					parent.contentContainer.removeClass( 'current-panel-parent' );
				}
			});

			panel.container.find( '.customize-panel-back' ).off( 'click keydown' ).on( 'click keydown', function( event ) {
				if ( wp.customize.utils.isKeydownButNotEnterEvent( event ) ) {
					return;
				}
				event.preventDefault(); // Keep this AFTER the key filter above

				if ( panel.expanded() ) {
					wp.customize.panel( panel.params.panel ).expand();
				}
			});
		},

		embed: function() {

			var panel = this,
				parentContainer;

			if ( 'themesetup_nested_panel' !== this.params.type || _.isUndefined( this.params.panel ) ) {
				_panelEmbed.call( this );
				return;
			}

			_panelEmbed.call( this );

			parentContainer = jQuery( '#sub-accordion-panel-' + this.params.panel );

			parentContainer.append( panel.headContainer );
		},

		isContextuallyActive: function() {

			var panel = this,
				children,
				activeCount = 0;

			if ( 'themesetup_nested_panel' !== this.params.type ) {
				return _panelIsContextuallyActive.call( this );
			}

			children = this._children( 'panel', 'section' );

			wp.customize.panel.each( function( child ) {
				if ( ! child.params.panel ) {
					return;
				}

				if ( child.params.panel !== panel.id ) {
					return;
				}

				children.push( child );
			});

			children.sort( wp.customize.utils.prioritySort );

			_( children ).each( function( child ) {
				if ( child.active() && child.isContextuallyActive() ) {
					activeCount += 1;
				}
			});
			return ( 0 !== activeCount );
		}
	});

}) ( jQuery );

/**
 * Handle collapsible sections.
 */
( ( $, api ) => {

	/**
	 * Extend wp.customize.Section as a collapsible section
	 */
	api.CollapsibleSection = api.Section.extend({

		defaultExpandedArguments: $.extend(
			{},
			api.Section.defaultExpandedArguments,
			{ allowMultiple: true }
		),

		/**
		 * wp.customize.CollapsibleSection
		 *
		 * Custom section which would act as a collapsible (accordion).
		 *
		 * @constructs wp.customize.CollapsibleSection
		 * @augments   wp.customize.Section
		 *
		 * @param {string} id - ID.
		 * @param {Object} options - Options.
		 * @return {void}
		 */
		initialize( id, options ) {
			const section = this;
			api.Section.prototype.initialize.call( section, id, options );

			// Move the section to it's parent panel.
			section.headContainer.append( $( '#sub-accordion-section-' + id ) );

			if ( section.panel && section.panel() ) {
				const panel = api.panel( section.panel() );

				if ( panel ) {
					panel.container.last().addClass( 'control-section-collapse-parent' );
				}
			}
		},

		/**
		 * Attach events.
		 *
		 * @return {void}
		 */
		attachEvents() {
			const section = this;
			api.Section.prototype.attachEvents.call( section );

			if ( section.panel() && api.panel( section.panel() ) ) {
				api
					.panel( section.panel() )
					.container.find( '.customize-panel-back' )
					.on( 'click keydown', event => {
						if ( api.utils.isKeydownButNotEnterEvent( event ) ) {
							return;
						}
						event.preventDefault(); // Keep this AFTER the key filter above.

						if ( section.expanded() ) {
							section.collapse({ delayed: true });
						}
					});
			}
		},

		/**
		 * Update UI to reflect expanded state
		 *
		 * @param {boolean}  expanded - Expanded state.
		 * @param {Object}   args - Args.
		 * @return {void}
		 */
		onChangeExpanded( expanded, args ) {
			const section = this;

			// Immediately call the complete callback if there were no changes.
			if ( args.unchanged ) {
				if ( args.completeCallback ) {
					args.completeCallback();
				}
				return;
			}

			const overlay = section.headContainer.closest( '.wp-full-overlay' );

			if ( expanded ) {
				if ( ! args.allowMultiple ) {
					api.section.each( otherSection => {
						if ( otherSection !== section ) {
							otherSection.collapse({ duration: args.duration });
						}
					});
				}

				section.contentContainer.addClass( 'open' );

				if ( false !== args.transition ) {
					expandSection( section.contentContainer.get( 0 ) );
				}

				overlay.addClass( 'section-collapse-open' );
				section.headContainer.addClass( 'expanded' );
			} else {
				setTimeout( () => {
					section.contentContainer.removeClass( 'open' );
				}, 200 );

				if ( args.delayed ) {
					setTimeout( () => {
						collapseSection( section.contentContainer.get( 0 ) );
					}, 400 );
				} else {
					collapseSection( section.contentContainer.get( 0 ) );
				}

				overlay.removeClass( 'section-collapse-open' );
				section.container.removeClass( 'busy' );
				section.headContainer.removeClass( 'expanded' );
			}
		}

	});

	/**
	 * Extends wp.customize.sectionConstructor with section constructor for collapsible section.
	 */
	$.extend( api.sectionConstructor, {
		collapse: api.CollapsibleSection
	});

}) ( jQuery, wp.customize );
