/**
 * Philip Walton implementation of "container queries"
 *
 * Use this javascript code to add classes to the .block-editor__typewriter block just following
 * the .editor-styles-wrapper block. Those classes are here to mimick front-end breakpoints.
 * As .editor-styles-wrapper container replaces front-end body and because of the 2 sidebars
 * (WordPress left menu and Gutenberg right settings sidebar), traditional media queries could
 * be very complicated to use. By using those classes for breakpoints it is much more easier
 * to simulate front-end sidebars in the back-end editor.
 *
 * Classes and breakpoints :
 *     .small-container class added beyond 1px width
 *     .medium-container class added beyond 992px width
 *     .large-container class added beyond 1170px width
 *
 * @see https://philipwalton.com/articles/responsive-components-a-solution-to-the-container-queries-problem/
 */

/*
window.addEventListener( 'DOMContentLoaded', e => {

	var ro;

	if ( 'ResizeObserver' in self ) {
		ro = new ResizeObserver( function( entries ) {
			entries.forEach( function( entry ) {

				// Those breakpoints are theme dependent
				var breakpoints = {
					'small-container': 1,
					'medium-container': 992,
					'large-container': 1170
				};
				Object.keys( breakpoints ).forEach( function( breakpoint ) {
					var minWidth = breakpoints[breakpoint];
					entry.contentRect.width >= minWidth ?
						entry.target.classList.add( breakpoint ) :
						entry.target.classList.remove( breakpoint );
				});
			});
		});
		ro.observe( document.querySelector( '.editor-styles-wrapper' ) );
	}
});
*/
