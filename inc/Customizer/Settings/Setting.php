<?php
/**
 * Themesetup\Customizer\Settings\Setting class
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Settings;

/**
 * Setting class
 */
class Setting {

	/**
	 * Setting ID
	 *
	 * @var string the setting ID.
	 */
	public $id;

	/**
	 * Setting arguments.
	 *
	 * @var array args passed into settings.
	 */
	public $setting_args = [];

	/**
	 * Control arguments.
	 *
	 * @var array args passed into controls.
	 */
	public $control_args = [];

	/**
	 * Custom control if applies.
	 *
	 * @var null|string
	 */
	public $custom_control = null;

	/**
	 * The Partials array
	 *
	 * @var null|array
	 */
	public $partial = null;

	/**
	 * Constructor.
	 *
	 * @param string $id the setting id.
	 * @param array  $setting_args the add_setting array.
	 * @param array  $control_args the add_control array.
	 * @param string $custom_control [optional] this should be added if the control is a custom control.
	 * @param array  $partial [optional] this should be added if the control is a selective refresh control..
	 */
	public function __construct( $id, $setting_args, $control_args, $custom_control = null, $partial = null ) {
		$this->id             = $id;
		$this->setting_args   = $setting_args;
		$this->control_args   = $control_args;
		$this->custom_control = $custom_control;
		$this->partial        = $partial;
	}

}
