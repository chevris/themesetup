/**
 * WordPress dependencies
 */
const { render } = wp.element;

/**
 * Internal dependencies
 */
import ToggleComponent from './toggle-component.js';

export const ToggleControl = wp.customize.ThemesetupControl.extend({
	renderContent: function renderContent() {
		const control = this;
		render(
			<ToggleComponent control={ control } />,
			control.container[ 0 ]
		);
	}
});
