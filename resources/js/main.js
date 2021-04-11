/**
 * File main.js.
 */

if ( 'loading' === document.readyState ) {

	// The DOM has not yet been loaded.
	document.addEventListener( 'DOMContentLoaded', initScripts );
} else {

	// The DOM has already been loaded.
	initScripts();
}

/**
 * Initiate scripts when DOM loaded .
 */
function initScripts() {

	/**
	 * Add listener to the overlay masks, so they can be removed and close drawers.
	 *
	 * @note: Opened drawer must have a class : [slug]-drawer-opened and
	 * the overlay id must be mask-[slug]
	 */
	document.addEventListener( 'click', function( e ) {
		if ( e.target && 'overlay-mask' === e.target.className ) {
			const maskId = e.target.id;
			const drawer = maskId.split( '-' );

			document.body.classList.remove( drawer[ 1 ] + '-drawer-opened' );
			document.documentElement.classList.remove( 'scroll-disabled' );
			removeOverlay( maskId );
		}
	});

	initDynamicHeights();
	initDrawerHeader();
	initDrawerSidebar();
	initDropdownVerticalMenu();
}

/**
 * Handles dynamic heights.
 */
function initDynamicHeights() {

	resizeContentMinHeight();

	window.addEventListener( 'resize', function() {
		resizeContentMinHeight();
	});
}

/**
 * Handles header drawer.
 */
function initDrawerHeader() {

	const togglers = document.getElementsByClassName( 'drawer-header-toggle' );
	const siteHeader = document.getElementById( 'masthead' );
	const drawerHeader = document.getElementById( 'drawer-header-js' );

	// No point if no toggler.
	if ( ! togglers.length ) {
		return;
	}

	const drawerCloseButton = drawerHeader.getElementsByClassName( 'drawer-header-toggle' )[ 0 ];
	const headerToggleButton = siteHeader.getElementsByClassName( 'drawer-header-toggle' )[ 0 ];

	/**
	 * Open / close header drawer.
	 *
	 * @note: Opened drawer must have a class : [slug]-drawer-opened and
	 * the overlay id must be mask-[slug]
	 */
	for ( let i = 0; i < togglers.length; i++ ) {
		togglers[ i ].addEventListener(
			'click',
			function() {
				if ( document.body.classList.contains( 'header-drawer-opened' ) ) {
					closeMenu( 'header-drawer-opened', headerToggleButton, 'mask-header' );
				} else {
					openMenu( 'header-drawer-opened', drawerCloseButton, 'mask-header' );
				}
			},
			false
		);
	}

}

/**
 * Handles sidebar drawer.
 */
function initDrawerSidebar() {

	const togglers = document.getElementsByClassName( 'drawer-sidebar-toggle' );
	const siteContent = document.getElementById( 'content' );
	const drawerHeader = document.getElementById( 'drawer-sidebar-js' );

	// No point if no toggler.
	if ( ! togglers.length ) {
		return;
	}

	const drawerCloseButton = drawerHeader.getElementsByClassName( 'drawer-sidebar-toggle' )[ 0 ];
	const sidebarToggleButton = siteContent.getElementsByClassName( 'drawer-sidebar-toggle' )[ 0 ];

	/**
	 * Open / close sidebar drawer.
	 *
	 * @note: Opened drawer must have a class : [slug]-drawer-opened and
	 * the overlay id must be mask-[slug]
	 */
	for ( let i = 0; i < togglers.length; i++ ) {
		togglers[ i ].addEventListener(
			'click',
			function() {
				if ( document.body.classList.contains( 'sidebar-drawer-opened' ) ) {
					closeMenu( 'sidebar-drawer-opened', sidebarToggleButton, 'mask-sidebar' );
				} else {
					openMenu( 'sidebar-drawer-opened', drawerCloseButton, 'mask-sidebar' );
				}
			},
			false
		);
	}

}

/**
 * Handles dropdown vertical menus.
 */
function initDropdownVerticalMenu() {

	// Get all dropdown vertical menus.
	const allVerticalMenus = document.querySelectorAll( '.vertical-menu' );

	allVerticalMenus.forEach( ( menu ) => {

		// Get all dropdown buttons in each menu.
		const allDropdowns = menu.querySelectorAll( '.dropdown-toggle' );

		if ( ! allDropdowns.length ) {
			return;
		}

		allDropdowns.forEach( ( dropdown ) => {
			dropdown.addEventListener( 'click', ( e ) => {
				e.preventDefault();
				e.stopPropagation();
				dropdown.setAttribute( 'aria-expanded', 'false' === dropdown.getAttribute( 'aria-expanded' ) ? 'true' : 'false' );
				const section = dropdown.closest( 'section' );
				section.toggleAttribute( 'expanded' );
			});
		});
	});
}

/**
 * Helper functions
 */

/**
 * @description Add add a min-height to #content depending on the height of header and footer.
 */
function resizeContentMinHeight() {
	const siteContent = document.getElementById( 'content' );
	const siteHeader = document.getElementById( 'masthead' );
	const siteFooter = document.getElementById( 'colophon' );
	const adminBar = document.getElementById( 'wpadminbar' );

	const windowHeight = window.innerHeight;
	const headerHeight = siteHeader ? siteHeader.offsetHeight : 0;
	const headerMarginBottom = siteHeader ? parseInt( getComputedStyle( siteHeader ).marginBottom ) : 0;
	const footerHeight = siteFooter ? siteFooter.offsetHeight : 0;
	const footerMarginTop = siteFooter ? parseInt( getComputedStyle( siteFooter ).marginTop ) : 0;
	const adminBarHeight = adminBar ? adminBar.offsetHeight : 0;

	let contentMinHeight = windowHeight - headerHeight - headerMarginBottom - footerHeight - footerMarginTop - adminBarHeight;

	siteContent.style.minHeight = contentMinHeight + 'px';
}

/**
 * @description Opens specifed off-canvas menu.
 * @param {string} openingClass  The class to add to the body to toggle menu visibility.
 * @param {object} focusOnOpen The button used to close the menu on which we focus.
 * @param {string} maskId     The ID to use for the overlay.
 */
function openMenu( openingClass, focusOnOpen, maskId ) {
	document.body.classList.add( openingClass );
	document.documentElement.classList.add( 'scroll-disabled' );
	focusOnOpen.focus();
	createOverlay( maskId );
}

/**
 * @description Closes specifed slide-out menu.
 * @param {string} openingClass  The class to remove from the body to toggle menu visibility.
 * @param {object} focusOnClose The button used to open the menu on which we focus.
 * @param {string} maskId The ID to use for the overlay.
 */
function closeMenu( openingClass, focusOnClose, maskId ) {
	document.body.classList.remove( openingClass );
	document.documentElement.classList.remove( 'scroll-disabled' );
	focusOnClose.focus();
	removeOverlay( maskId );
}

/**
 * @description Creates semi-transparent overlay behind menus.
 * @param {string} maskId The ID to add to the div.
 */
function createOverlay( maskId ) {
	const mask = document.createElement( 'div' );
	mask.setAttribute( 'class', 'overlay-mask' );
	mask.setAttribute( 'id', maskId );
	document.body.appendChild( mask );
}

/**
 * @description Removes semi-transparent overlay behind menus.
 * @param {string} maskId The ID to use for the overlay.
 */
function removeOverlay( maskId ) {
	const mask = document.getElementById( maskId );
	mask.parentNode.removeChild( mask );
}
