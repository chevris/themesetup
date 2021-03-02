/**
 * WordPress dependencies
 */
const { render } = wp.element;

/**
 * Internal dependencies
 */
import IconCheckboxComponent from './icon-checkbox-component.js';

export const IconCheckboxControl = wp.customize.ThemesetupControl.extend({
	renderContent: function renderContent() {
		const control = this;
		render(
			<IconCheckboxComponent control={ control } />,
			control.container[ 0 ]
		);
	}
});
