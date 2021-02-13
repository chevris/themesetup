/**
 * WordPress dependencies
 */
const { render } = wp.element;

/**
 * Internal dependencies
 */
import TitleComponent from './title-component.js';

export const TitleControl = wp.customize.ThemesetupControl.extend({
	renderContent: function renderContent() {
		const control = this;
		render(
			<TitleComponent control={ control } />,
			control.container[ 0 ]
		);
	}
});
