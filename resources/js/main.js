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
	 * Add listener to the menu overlays, so they can be closed on click.
	 *
	 * @note: Opened menus must have a class : [slug]-offcanvas-opened and
	 * the overlay id must be mask-[slug]
	 */
	document.addEventListener( 'click', function( e ) {
		if ( e.target && 'overlay-mask' === e.target.className ) {
			const maskId = e.target.id;
			const menu = maskId.split( '-' );

			document.body.classList.remove( menu[ 1 ] + '-offcanvas-opened' );
			document.body.classList.remove( 'scroll-disabled' );
			removeOverlay( maskId );
		}
	});

	initSlideoutMenu();
	initSlideoutSidebar();
	initDropdownVerticalMenu();
}

/**
 * Handles slide-out menu.
 */
function initSlideoutMenu() {

	const togglers = document.getElementsByClassName( 'slideout-menu-toggle' );
	const siteHeader = document.getElementById( 'masthead' );
	const slideoutMenu = document.getElementById( 'slideout-menu-js' );

	// No point if no toggler.
	if ( ! togglers.length ) {
		return;
	}

	const menuCloseButton = slideoutMenu.getElementsByClassName( 'slideout-menu-toggle' )[ 0 ];
	const headerToggleButton = siteHeader.getElementsByClassName( 'slideout-menu-toggle' )[ 0 ];

	/**
	 * Open / close mobile off-canvas menu.
	 *
	 * @note: The opening class must be [slug]-offcanvas-opened and
	 * the overlay id must be mask-[slug]
	 */
	for ( let i = 0; i < togglers.length; i++ ) {
		togglers[ i ].addEventListener(
			'click',
			function() {
				if ( document.body.classList.contains( 'slideoutmenu-offcanvas-opened' ) ) {
					closeMenu( 'slideoutmenu-offcanvas-opened', headerToggleButton, 'mask-slideoutmenu' );
				} else {
					openMenu( 'slideoutmenu-offcanvas-opened', menuCloseButton, 'mask-slideoutmenu' );
				}
			},
			false
		);
	}

}

/**
 * Handles slide-out sidebar.
 */
function initSlideoutSidebar() {

	const togglers = document.getElementsByClassName( 'slideout-sidebar-toggle' );
	const siteContent = document.getElementById( 'content' );
	const slideoutSidebar = document.getElementById( 'slideout-sidebar-js' );

	// No point if no toggler.
	if ( ! togglers.length ) {
		return;
	}

	const sidebarCloseButton = slideoutSidebar.getElementsByClassName( 'slideout-sidebar-toggle' )[ 0 ];
	const contentToggleButton = siteContent.getElementsByClassName( 'slideout-sidebar-toggle' )[ 0 ];

	/**
	 * Open / close mobile off-canvas menu.
	 *
	 * @note: The opening class must be [slug]-offcanvas-opened and
	 * the overlay id must be mask-[slug]
	 */
	for ( let i = 0; i < togglers.length; i++ ) {
		togglers[ i ].addEventListener(
			'click',
			function() {
				if ( document.body.classList.contains( 'slideoutsidebar-offcanvas-opened' ) ) {
					closeMenu( 'slideoutsidebar-offcanvas-opened', contentToggleButton, 'mask-slideoutsidebar' );
				} else {
					openMenu( 'slideoutsidebar-offcanvas-opened', sidebarCloseButton, 'mask-slideoutsidebar' );
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
 * @description Opens specifed off-canvas menu.
 * @param {string} openingClass  The class to add to the body to toggle menu visibility.
 * @param {object} focusOnOpen The button used to close the menu on which we focus.
 * @param {string} maskId     The ID to use for the overlay.
 */
function openMenu( openingClass, focusOnOpen, maskId ) {
	document.body.classList.add( openingClass );
	document.body.classList.add( 'scroll-disabled' );
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
	document.body.classList.remove( 'scroll-disabled' );
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
