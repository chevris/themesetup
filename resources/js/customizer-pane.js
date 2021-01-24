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
