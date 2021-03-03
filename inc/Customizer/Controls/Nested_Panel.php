<?php
/**
 * Themesetup\Customizer\Controls\Nested_Panel class
 * Handles data passing from args to JS.
 *
 * @package themesetup
 * @see https://wordpress.stackexchange.com/a/256103/17078
 */

namespace Themesetup\Customizer\Controls;

/**
 * Nested_Panel class
 */
class Nested_Panel extends \WP_Customize_Panel {

	/**
	 * The parent panel.
	 *
	 * @var string
	 */
	public $panel;

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'themesetup_nested_panel';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array = wp_array_slice_assoc(
			(array) $this,
			[
				'id',
				'description',
				'priority',
				'type',
				'panel',
			]
		);

		$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content']        = $this->get_content();
		$array['active']         = $this->active();
		$array['instanceNumber'] = $this->instance_number;

		return $array;
	}
}
