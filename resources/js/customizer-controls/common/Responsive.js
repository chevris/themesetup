/**
 * WordPress dependencies
 */
const { Button, Tooltip, ButtonGroup } = wp.components;
const { useEffect } = wp.element;
const { __ } = wp.i18n;

/**
 * External dependencies
 */
import PropTypes from 'prop-types';

const ResponsiveControl = ({
	onChange,
	excluded,
	controlLabel,
	hideResponsive,
	children
}) => {
	const changeViewType = ( device ) => {
		wp.customize.previewedDevice( device );
		onChange( device );
	};

	useEffect( () => {
		document.addEventListener( 'themesetupChangedRepsonsivePreview', ( e ) => {
			changeViewType( e.detail );
		});
	}, []);

	const dispatchViewChange = ( device ) => {
		const event = new CustomEvent( 'themesetupChangedRepsonsivePreview', {
			detail: device
		});
		document.dispatchEvent( event );
	};

	const devices = {
		desktop: {
			tooltip: __( 'Desktop', 'themesetup' ),
			icon: 'desktop'
		},
		tablet: {
			tooltip: __( 'Tablet', 'themesetup' ),
			icon: 'tablet'
		},
		mobile: {
			tooltip: __( 'Mobile', 'themesetup' ),
			icon: 'smartphone'
		}
	};

	const deviceMap = {};

	Object.keys( devices ).map( ( key ) => {
		if ( excluded ) {
			if ( excluded.includes( key ) ) {
				return false;
			}
		}
		deviceMap[ key ] = devices[ key ];
	});

	const renderDeviceButton = ( device, index ) => {
		const { tooltip, icon } = deviceMap[ device ];

		return (
			<Tooltip text={ tooltip } key={ index }>
				<Button
					icon={ icon }
					className={ device }
					onClick={ () => {
						dispatchViewChange( device );
					} }
				/>
			</Tooltip>
		);
	};

	return (
		<>
			<div className="themesetup-responsive-control-bar">
				{ controlLabel && (
					<span className="customize-control-title">
						{ controlLabel }
					</span>
				) }
				{ ! hideResponsive && (
					<div className="floating-controls">
						<ButtonGroup>
							{ Object.keys( deviceMap ).map( ( device, index ) =>
								renderDeviceButton( device, index )
							) }
						</ButtonGroup>
					</div>
				) }
			</div>
			{ children && (
				<div className="themesetup-responsive-controls-content">
					{ children }
				</div>
			) }
		</>
	);
};

ResponsiveControl.propTypes = {
	onChange: PropTypes.func,
	controlLabel: PropTypes.string,
	hideResponsive: PropTypes.bool,
	children: PropTypes.any,
	excluded: PropTypes.array
};

export default ResponsiveControl;
