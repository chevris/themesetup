<?php
/**
 * Themesetup\Customizer\Controls\Radio_Image class
 * Handles data passing from args to JS.
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Controls;

/**
 * Radio_Image class
 */
class Radio_Image extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'themesetup_radio_image_control';
	/**
	 * Additional arguments passed to JS.
	 *
	 * @var array
	 */
	public $choices = [];

	/**
	 * Send to JS.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['choices'] = $this->choices;
	}
}
