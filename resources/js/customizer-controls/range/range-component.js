/**
 * WordPress dependencies
 */
const { RangeControl } = wp.components;
const { useState, useEffect } = wp.element;

/**
 * External dependencies
 */
import PropTypes from 'prop-types';

const RangeComponent = ({ control }) => {

	useEffect( () => {

		// Listen for new customizer values (like preset changes).
		document.addEventListener( 'themesetup-changed-customizer-value', ( e ) => {
			if ( ! e.detail ) {
				return false;
			};
			if ( e.detail.id !== control.id ) {
				return false;
			};
			updateValues( e.detail.value );
		});

	}, []);

	const [ value, setValue ] = useState( control.setting.get() );
	const defaults = { min: 0, max: 100, defaultVal: 15, step: 1 };
	const controlProps = {
		...defaults,
		...( control.params.input_attrs || {})
	};
	const { label } = control.params;
	const { defaultVal, min, max, step } = controlProps;

	const updateValues = ( newVal ) => {
		setValue( newVal );
		control.setting.set( newVal );
	};

	return (
		<div className="themesetup-white-background-control themesetup-range-control">
			<div className="themesetup-control-header">
				{ label && (
					<span className="customize-control-title">{ label }</span>
				) }
			</div>
			<div className="range-wrap">
				<RangeControl
					resetFallbackValue={
						0 === defaultVal ? 0 : defaultVal || ''
					}
					value={ 0 === parseInt( value ) ? 0 : value || '' }
					min={ 0 > min ? min : 0 }
					max={ max || 100 }
					step={ step || 1 }
					allowReset
					onChange={ updateValues }
				/>
			</div>
		</div>
	);
};

RangeComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default RangeComponent;
