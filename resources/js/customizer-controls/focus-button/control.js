/**
 * WordPress dependencies
 */
const { render } = wp.element;

/**
 * Internal dependencies
 */
import FocusButtonComponent from './focus-button-component.js';

export const FocusButtonControl = wp.customize.ThemesetupControl.extend({
	renderContent: function renderContent() {
		render(
			<FocusButtonComponent control={ this }/>,
			this.container[ 0 ]
		);
	}
});
