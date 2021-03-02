/**
 * WordPress dependencies
 */
const { Component, Fragment } = wp.element;
const { ButtonGroup, Dashicon, Tooltip, Button } = wp.components;
const { __ } = wp.i18n;

/**
 * External dependencies
 */
import PropTypes from 'prop-types';

class IconCheckboxComponent extends Component {
	constructor() {
		super( ...arguments );

		this.updateValues = this.updateValues.bind( this );

		let value = this.props.control.setting.get();

		let defaultParams = {
			options: {
				desktop: {
					name: __( 'Desktop', 'themesetup' ),
					icon: 'desktop'
				},
				tablet: {
					name: __( 'Tablet', 'themesetup' ),
					icon: 'tablet'
				},
				mobile: {
					name: __( 'Mobile', 'themesetup' ),
					icon: 'smartphone'
				}
			}
		};

		this.controlParams = this.props.control.params.input_attrs ? {
			...defaultParams,
			...this.props.control.params.input_attrs
		} : defaultParams;

		let baseDefault = {
			'mobile': true,
			'tablet': true,
			'desktop': true
		};

		this.defaultValue = this.props.control.params.default ? this.props.control.params.default : baseDefault;

		value = value ? {
			...JSON.parse( JSON.stringify( this.defaultValue ) ),
			...value
		} : JSON.parse( JSON.stringify( this.defaultValue ) );

		this.state = {
			value: value
		};
	}

	render() {

		const controlLabel = (
			<Fragment>
				<Tooltip text={ __( 'Reset Values', 'themesetup' ) }>
					<Button
						className="reset themesetup-reset"
						disabled={ ( this.state.value == this.defaultValue ) }
						onClick={ () => {
							let value = this.defaultValue;
							this.setState({ value: this.defaultValue });
							this.updateValues( value );
						} }
					>
						<Dashicon icon='image-rotate' />
					</Button>
				</Tooltip>
				{ this.props.control.params.label &&
					this.props.control.params.label
				}
			</Fragment>
		);

		return (
			<div className="themesetup-control-field themesetup-radio-icon-control">
				<div className="themesetup-responsive-control-bar">
					<span className="customize-control-title">{ controlLabel }</span>
				</div>
				<ButtonGroup className="themesetup-radio-container-control">
					{ Object.keys( this.controlParams.options ).map( ( item ) => {
						return (
							<Fragment>
								<Tooltip text={ this.controlParams.options[ item ].name }>
									<Button
										isTertiary
										className={ ( true === this.state.value[ item ] ? 'active-radio ' : '' ) + item }
										onClick={ () => {
											let value = this.state.value;
											if ( value[ item ]) {
												value[ item ] = false;
											} else {
												value[ item ] = true;
											}
											this.setState({ value: value });
											this.updateValues( value );
										} }
									>
										{ this.controlParams.options[ item ].icon && (
											<span className="themesetup-radio-icon">
												{ <Dashicon icon={this.controlParams.options[ item ].icon}/> }
											</span>
										) }
										{ ! this.controlParams.options[ item ].icon && (
											<span className="themesetup-radio-name">
												{ this.controlParams.options[ item ].name }
											</span>
										) }
									</Button>
								</Tooltip>
							</Fragment>
						);
					})}
				</ButtonGroup>
			</div>
		);
	}

	updateValues( value ) {
		this.props.control.setting.set({
			...this.props.control.setting.get(),
			...value,
			flag: ! this.props.control.setting.get().flag
		});
	}
}

export default IconCheckboxComponent;
