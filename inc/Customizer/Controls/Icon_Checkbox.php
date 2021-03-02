<?php
/**
 * Themesetup\Customizer\Controls\Icon_Checkbox class
 * Handles data passing from args to JS.
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Controls;

/**
 * Icon_Checkbox class
 */
class Icon_Checkbox extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'themesetup_icon_checkbox_control';
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
		$this->json['input_attrs'] = $this->input_attrs;
	}
}
