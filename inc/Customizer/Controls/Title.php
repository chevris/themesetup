<?php
/**
 * Themesetup\Customizer\Controls\Title class
 * Handles data passing from args to JS.
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Controls;

/**
 * Title class
 */
class Title extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'themesetup_title_control';

	/**
	 * Send to JS.
	 */
	public function to_json() {
		parent::to_json();
	}
}
