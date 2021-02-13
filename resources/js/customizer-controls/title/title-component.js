/**
 * WordPress dependencies
 */
const { Component } = wp.element;

/**
 * External dependencies
 */
import PropTypes from 'prop-types';

const TitleComponent = ({ control }) => {
	return (
		<div className="themesetup-title-control">
			{ control.params.label && (
				<span class="customize-control-title">
					{ control.params.label }
				</span>
			) }
			{ control.params.description && (
				<span class="customize-control-description">
					{ control.params.description }
				</span>
			) }
		</div>
	);
};

TitleComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default TitleComponent;
