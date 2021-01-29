<?php
/**
 * Themesetup\Customizer\Controls\Presets class
 * Handles data passing from args to JS.
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Controls;

/**
 * Presets class
 */
class Presets extends \WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'themesetup_presets_control';

	/**
	 * Additional arguments passed to JS.
	 *
	 * @var array
	 */
	public $presets = [];

	/**
	 * Send to JS.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['presets'] = $this->presets;
	}
}
