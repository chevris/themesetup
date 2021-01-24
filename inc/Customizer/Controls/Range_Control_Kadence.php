<?php
/**
 * Themesetup\Customizer\Controls\Range_Control class
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Controls;

/**
 * Range_Control class.
 */
class Range_Control extends \WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'themesetup_range_control';

	/**
	 * Additional arguments passed to JS.
	 *
	 * @var array
	 */
	public $default = [];

	/**
	 * Additional arguments passed to JS.
	 *
	 * @var array
	 */
	public $input_attrs = [];

	/**
	 * Send to JS.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['default']     = $this->default;
		$this->json['input_attrs'] = $this->input_attrs;
	}
	/**
	 * Empty Render Function to prevent errors.
	 */
	public function render_content() {
	}

}
