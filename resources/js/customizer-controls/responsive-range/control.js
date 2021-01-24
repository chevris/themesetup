/**
 * WordPress dependencies
 */
const { render } = wp.element;

/**
 * Internal dependencies
 */
import ResponsiveRangeComponent from './responsive-range-component.js';

export const ResponsiveRangeControl = wp.customize.ThemesetupControl.extend({
	renderContent: function renderContent() {
		const control = this;
		render(
			<ResponsiveRangeComponent control={ control } />,
			control.container[ 0 ]
		);
	}
});
