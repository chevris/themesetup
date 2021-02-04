/**
 * WordPress dependencies
 */
const { Component } = wp.element;
const { ToggleControl } = wp.components;

/**
 * External dependencies
 */
import PropTypes from 'prop-types';

class ToggleComponent extends Component {
	constructor( props ) {
		super( props );

		this.state = {
			value: props.control.setting.get()
		};

	}

	render() {
		return (
			<div className="themesetup-toggle-control-wrap">
				<ToggleControl
					className="themesetup-toggle-control"
					checked={ this.state.value }
					label={ this.props.control.params.label }
					onChange={ ( value ) => this.updateValue( value ) }
				/>
			</div>
		);
	}

	updateValue( newValue ) {
		this.setState({
			value: newValue
		});
		this.props.control.setting.set( newValue );
	}

	componentDidMount() {
		const { control } = this.props;

		// Listen for new customizer values (like preset changes).
		document.addEventListener( 'themesetup-changed-customizer-value', ( e ) => {
			if ( ! e.detail ) {
				return false;
			}
			if ( e.detail.id !== control.id ) {
				return false;
			}
			this.updateValue( e.detail.value );
		});
	}
}

ToggleComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default ToggleComponent;
