/**
 * WordPress dependencies
 */
const { render } = wp.element;

/**
 * Internal dependencies
 */
import RadioImageComponent from './radio-image-component.js';

export const RadioImageControl = wp.customize.ThemesetupControl.extend({
	renderContent: function renderContent() {
		const control = this;
		render(
			<RadioImageComponent control={ control } />,
			control.container[ 0 ]
		);
	}
});
