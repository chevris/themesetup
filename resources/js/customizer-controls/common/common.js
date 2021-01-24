/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

export const maybeParseJson = ( input ) => {
	if ( 'string' !== typeof input ) {
		return input;
	}
	try {
		JSON.parse( input );
	} catch ( error ) {
		return input;
	}
	return JSON.parse( input );
};

/**
 * Simple object check.
 *
 * @param item
 * @return {boolean}
 */
export const isObject = ( item ) => {
	return item && 'object' === typeof item && ! Array.isArray( item );
};

/**
 * Deep merge objects.
 *
 * @param {...any} objects
 */
export const mergeDeep = ( ...objects ) => {
	const isObject = ( obj ) => obj && 'object' === typeof obj;
	return objects.reduce( ( prev, obj ) => {
		Object.keys( obj ).forEach( ( key ) => {
			const pVal = prev[ key ];
			const oVal = obj[ key ];
			if ( Array.isArray( pVal ) && Array.isArray( oVal ) ) {
				prev[ key ] = pVal.concat( ...oVal );
			} else if ( isObject( pVal ) && isObject( oVal ) ) {
				prev[ key ] = mergeDeep( pVal, oVal );
			} else {
				prev[ key ] = oVal;
			}
		});
		return prev;
	}, {});
};

export const getIntValAsResponsive = ( value ) => {
	value = maybeParseJson( value );
	if (
		'object' === typeof value &&
		Object.prototype.hasOwnProperty.call( value, 'desktop' ) &&
		Object.prototype.hasOwnProperty.call( value, 'tablet' ) &&
		Object.prototype.hasOwnProperty.call( value, 'mobile' )
	) {
		return value;
	}
	if ( 'number' === typeof value ) {
		value = {
			desktop: value,
			tablet: value,
			mobile: value
		};
		return value;
	}

	return value;
};

export const globalPaletteColors = [
	{
		'nv-primary-accent': __( 'Primary Accent', 'themesetup' ),
		'nv-secondary-accent': __( 'Secondary Accent', 'themesetup' ),
		'nv-site-bg': __( 'Site Background', 'themesetup' ),
		'nv-light-bg': __( 'Light Background', 'themesetup' ),
		'nv-dark-bg': __( 'Dark Background', 'themesetup' ),
		'nv-text-color': __( 'Text Color', 'themesetup' ),
		'nv-text-dark-bg': __( 'Text Dark Background', 'themesetup' ),
		'nv-c-1': __( 'Extra Color 1', 'themesetup' ),
		'nv-c-2': __( 'Extra Color 2', 'themesetup' )
	}
];
