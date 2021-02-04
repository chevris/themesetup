<?php
/**
 * Themesetup\Content_Singular\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Content_Singular;

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
		return 'content_singular';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'themesetup_content_singular', [ $this, 'action_display_content_singular' ] );
		add_action( 'themesetup_content_404', [ $this, 'action_display_content_404' ] );
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
	 * Display content singular template.
	 */
	public function action_display_content_singular() {
		get_template_part( 'template-parts/content/content-singular', get_post_type() );
	}

	/**
	 * Display content singular template.
	 */
	public function action_display_content_404() {

	}

}
