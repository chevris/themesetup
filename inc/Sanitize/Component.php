<?php
/**
 * Themesetup\Sanitize\Component class
 *
 * @package themesetup
 */

declare( strict_types=1 );

namespace Themesetup\Sanitize;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;

/**
 * Class for adding sanitize functions.
 *
 * Exposes template tags:
 * * `themesetup()->sanitize_responsive_range()`
 * * `themesetup()->sanitize_checkbox()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'sanitize';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `themesetup()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return [
			'sanitize_responsive_range' => [ $this, 'sanitize_responsive_range' ],
			'sanitize_checkbox' => [ $this, 'sanitize_checkbox' ],
		];
	}

	/**
	 * Function to sanitize responsive range inputs.
	 *
	 * @param string $input
	 * @return float|string|false
	 */
	public function sanitize_responsive_range( $input ) {
		if ( ! $this->is_json( $input ) ) {
			return floatval( $input );
		}
		$range_value            = json_decode( $input, true );
		$range_value['desktop'] = is_numeric( $range_value['desktop'] ) ? floatval( $range_value['desktop'] ) : '';
		$range_value['tablet']  = is_numeric( $range_value['tablet'] ) ? floatval( $range_value['tablet'] ) : '';
		$range_value['mobile']  = is_numeric( $range_value['mobile'] ) ? floatval( $range_value['mobile'] ) : '';
		return wp_json_encode( $range_value );
	}

	/**
	 * Sanitize checkbox output.
	 *
	 * @param boolean $value value to be sanitized.
	 * @return boolean
	 */
	public function sanitize_checkbox( $value ) {
		return isset( $value ) && true === (bool) $value;
	}

	/**
	 * Does the string is in json format
	 *
	 * @param string $string
	 * @return boolean
	 */
	public function is_json( $string ) {
		return is_string( $string ) && is_array( json_decode( $string, true ) );
	}

}
