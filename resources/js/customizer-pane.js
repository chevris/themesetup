/**
 * Scripts for customizer controls panel.
 */

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
