/**
 * WordPress dependencies
 */
const { Component } = wp.element;
const { Dashicon, Button } = wp.components;
const { __ } = wp.i18n;

/**
 * External dependencies
 */
import PropTypes from 'prop-types';


class FocusButtonComponent extends Component {
	constructor( props ) {
		super( props );

		let defaultParams = {
			'section': ''
		};

		this.controlParams = this.props.control.params.input_attrs ? {
			...defaultParams,
			...this.props.control.params.input_attrs
		} : defaultParams;

		this.focusPanel = this.focusPanel.bind( this );
	}

	render() {
		return (
			<div className="themesetup-focus-button">
				<Button className="themesetup-focus-button-item" onClick={ () => this.focusPanel( this.controlParams.section ) } data-section={ this.controlParams.section }>
					{ ( this.props.control.params.label ? this.props.control.params.label : '' ) }
					<span
						className="themesetup-focus-button-item-icon"
					>
						<Dashicon icon="arrow-right-alt2"/>
					</span>
				</Button>
			</div>
		);
	}

	focusPanel( focusedSection ) {
		if ( undefined !== wp.customize.section( focusedSection ) ) {

			// wp.customize.section( focusedSection ).focus();
			wp.customize.section( focusedSection ).expanded.set( ! wp.customize.section( focusedSection ).expanded.get() ); // toggle section.
		}
	}

}

FocusButtonComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default FocusButtonComponent;
