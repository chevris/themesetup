<?php
/**
 * Themesetup\Sidebar\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Sidebar;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;
use function Themesetup\themesetup;

/**
 * Class for managing archive content.
 *
 * Exposes template tags:
 * * `themesetup()->is_sidebar_1_active()`
 * * `themesetup()->is_sidebar_2_active()`
 * * `themesetup()->display_sidebar()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'sidebar';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'widgets_init', [ $this, 'action_register_sidebar_widget_areas' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes' ] );
		add_action( 'wp_footer', [ $this, 'action_display_slideout_sidebar' ] );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `themesetup()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return [
			'is_sidebar_1_active' => [ $this, 'is_sidebar_1_active' ],
			'is_sidebar_2_active' => [ $this, 'is_sidebar_2_active' ],
			'display_sidebar' => [ $this, 'display_sidebar' ],
		];
	}

	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebar_widget_areas() {

		$widget_areas = [
			'sidebar-1' => __( 'Sidebar 1', 'themesetup' ),
			'sidebar-2' => __( 'Sidebar 2', 'themesetup' ),
		];

		foreach ( $widget_areas as $id => $name ) {
			register_sidebar(
				apply_filters(
					'themesetup_filter_widget_area_args',
					[
						'name'          => $name,
						'id'            => $id,
						'description'   => esc_html__( 'Add widgets here.', 'themesetup' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					]
				)
			);
		}

	}

	/**
	 * Adds custom classes to indicate whether a sidebar is present and which sidebar layout it is.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ): array {

		if ( themesetup()->has_sidebar() ) {
				$classes[] = 'has-sidebar';
				$classes[] = 'has-sidebar--' . esc_attr( themesetup()->get_sidebar_layout() );
		}

		return $classes;
	}

	/**
	 * Display the slide-out sidebar template.
	 */
	public function action_display_slideout_sidebar() {

		if ( themesetup()->has_sidebar() && 'toggle' === themesetup()->get_sidebar_layout() ) {
			get_template_part( 'template-parts/off-canvas/slideout-sidebar' );
		}

	}

	/**
	 * Checks whether the sidebar 1 is active.
	 *
	 * @return bool True if the sidebar 1 is active, false otherwise.
	 */
	public function is_sidebar_1_active(): bool {
		return (bool) is_active_sidebar( 'sidebar-1' );
	}

	/**
	 * Checks whether the sidebar 2 is active.
	 *
	 * @return bool True if the sidebar 2 is active, false otherwise.
	 */
	public function is_sidebar_2_active(): bool {
		return (bool) is_active_sidebar( 'sidebar-2' );
	}

	/**
	 * Display the sidebar.
	 */
	public function display_sidebar() {

		$sidebar_id = themesetup()->get_context( 'sidebar', 'id' );
		dynamic_sidebar( $sidebar_id );
	}

}
