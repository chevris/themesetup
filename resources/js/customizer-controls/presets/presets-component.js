/**
 * WordPress dependencies
 */
const { Tooltip } = wp.components;
const { Component } = wp.element;
const { __ } = wp.i18n;

/**
 * Internal dependencies
 */
import { maybeParseJson } from '../common/common';

/**
 * External dependencies
 */
import PropTypes from 'prop-types';


class PresetsComponent extends Component {
	constructor( props ) {
		super( props );

		this.state = {
			search: ''
		};

		this.replaceSettings = this.replaceSettings.bind( this );
	}

	getPresets() {
		const { presets } = this.props.control.params;

		return presets.filter( ( preset ) => {
			return preset.label.toLowerCase().includes( this.state.search.toLowerCase() );
		});
	}

	render() {
		const presets = this.getPresets();
		return (
			<div className="themesetup-presets">
				<div className="search">
					<input
						type="search"
						placeholder={ __( 'Search Presets…', 'themesetup' ) }
						onChange={ ( e ) => {
							this.setState({ search: e.target.value });
						} }
					/>
				</div>
				{ 0 < presets.length ? (
					presets.map( ( preset, index ) => {
						return (
							<Tooltip key={ index } text={ preset.label }>
								<button
									onClick={ ( e ) => {
										e.preventDefault();
										this.replaceSettings( preset.setup );
									} }
								>
									<img
										src={ preset.image }
										alt={ preset.label }
									/>
								</button>
							</Tooltip>
						);
					})
				) : (
					<p>{ __( 'No presets found', 'themesetup' ) }</p>
				) }
			</div>
		);
	}

	replaceSettings( setup ) {
		setup = maybeParseJson( setup );
		Object.keys( setup ).map( ( themeMod ) => {

			if ( ! wp.customize.control( themeMod ) ) {
				return false;
			}
			if (
				[ 'text', 'textarea', 'select' ].includes(
					wp.customize.control( themeMod ).params.type
				)
			) {
				wp.customize.control( themeMod ).setting.set( setup[ themeMod ]);
				return false;
			}

			document.dispatchEvent(
				new CustomEvent( 'themesetup-changed-customizer-value', {
					detail: {
						value: setup[ themeMod ] || '',
						id: themeMod
					}
				})
			);

		});
	}
}

PresetsComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default PresetsComponent;
