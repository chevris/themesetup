<?php
/**
 * Themesetup\Customizer\Controls\Range class
 * Handles data passing from args to JS.
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Controls;

/**
 * Range class
 */
class Range extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'themesetup_range_control';

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
