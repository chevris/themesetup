<?php
/**
 * Themesetup\Slideout_Menu\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Slideout_Menu;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;

/**
 * Class for managing archive content.
 *
 * Exposes template tags:
 * * `themesetup()->the_template_tags_function()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'slideout_menu';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'themesetup_before_page', [ $this, 'action_display_slideout_menu' ] );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `themesetup()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return [];
	}

	/**
	 * Display the slide-out menu template.
	 */
	public function action_display_slideout_menu() {

		get_template_part( 'template-parts/off-canvas/slideout-menu' );

	}

}
