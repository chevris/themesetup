/**
 * WordPress dependencies
 */
const { render } = wp.element;

/**
 * Internal dependencies
 */
import PresetsComponent from './presets-component.js';

export const PresetsControl = wp.customize.ThemesetupControl.extend({
	renderContent: function renderContent() {
		render(
			<PresetsComponent control={ this } />,
			this.container[ 0 ]
		);
	}
});
