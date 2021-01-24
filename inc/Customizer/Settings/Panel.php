<?php
/**
 * Themesetup\Customizer\Settings\Panel class
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Settings;

/**
 * Panel class
 */
class Panel {

	/**
	 * Panel ID.
	 *
	 * @var string the panel ID.
	 */
	public $id;

	/**
	 * Args for panel instance.
	 *
	 * @var array args passed into panel instance.
	 */
	public $args = [];

	/**
	 * Constructor.
	 *
	 * @param string $id   the panel id.
	 * @param array  $args the panel args.
	 */
	public function __construct( $id, $args ) {
		$this->id   = $id;
		$this->args = $args;
	}

}
