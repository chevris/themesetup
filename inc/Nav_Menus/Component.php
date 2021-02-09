<?php
/**
 * Themesetup\Nav_Menus\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Nav_Menus;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;
use Themesetup\Nav_Menus\Walker_Vertical_Nav_Menu;
use function Themesetup\themesetup;

/**
 * Class for managing navigation menus.
 *
 * Exposes template tags:
 * * `themesetup()->is_primary_nav_menu_active()`
 * * `themesetup()->display_primary_nav_menu( array $args = [] )`
 * * `themesetup()->is_secondary_nav_menu_active()`
 * * `themesetup()->display_secondary_nav_menu( array $args = [] )`
 * * `themesetup()->is_social_nav_menu_active()`
 * * `themesetup()->display_social_nav_menu( array $args = [] )`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	const PRIMARY_NAV_MENU_SLUG   = 'primary';
	const SECONDARY_NAV_MENU_SLUG = 'secondary';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'nav_menus';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_register_nav_menus' ] );
		add_filter( 'nav_menu_item_args', [ $this, 'filter_nav_menu_dropdown_icon' ], 10, 3 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'filter_nav_menu_link_attributes_aria_dropdown' ], 10, 4 );

		$social_nav_menu = new Social_Nav_Menu();
		$social_nav_menu->initialize();
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
			'is_primary_nav_menu_active'   => [ $this, 'is_primary_nav_menu_active' ],
			'display_primary_nav_menu'     => [ $this, 'display_primary_nav_menu' ],
			'is_secondary_nav_menu_active' => [ $this, 'is_secondary_nav_menu_active' ],
			'display_secondary_nav_menu'   => [ $this, 'display_secondary_nav_menu' ],
			'is_social_nav_menu_active'    => [ $this, 'is_social_nav_menu_active' ],
			'display_social_nav_menu'      => [ $this, 'display_social_nav_menu' ],
		];
	}

	/**
	 * Registers the navigation menus.
	 */
	public function action_register_nav_menus() {
		register_nav_menus(
			[
				static::PRIMARY_NAV_MENU_SLUG   => esc_html_x( 'Primary', 'nav menu', 'themesetup' ),
				static::SECONDARY_NAV_MENU_SLUG => esc_html_x( 'Secondary', 'nav menu', 'themesetup' ),
				Social_Nav_Menu::SLUG           => esc_html_x( 'Social', 'nav menu', 'themesetup' ),
			]
		);
	}

	/**
	 * Add a sub-menu dropdown icon for wp_nav_menu with `show_toggles` arg.
	 *
	 * @param stdClass $args An array of arguments.
	 * @param string   $item Menu item.
	 * @param int      $depth Depth of the current menu item.
	 *
	 * @return stdClass $args An object of wp_nav_menu() arguments.
	 */
	public function filter_nav_menu_dropdown_icon( $args, $item, $depth ) {

		if ( isset( $args->show_toggles ) && $args->show_toggles ) {

			if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

				$icon = themesetup()->get_svg( 'ui', 'keyboard_arrow_down', 20 );

				$args->after = sprintf(
					'<button class="dropdown-toggle" tabindex="-1">%s</button>',
					$icon
				);
			} else {
				$args->after = '';
			}
		}

		return $args;
	}

	/**
	 * Add [aria-haspopup] and [aria-expanded] attributes to menu items that have children.
	 *
	 * @param array    $atts The HTML attributes applied to the menu item's `<a>` element.
	 * @param WP_Post  $item Menu item.
	 * @param stdClass $args An object of wp_nav_menu(…) arguments.
	 * @param int      $depth Depth of the current menu item.
	 *
	 * @return array Modified HTML attributes.
	 */
	public function filter_nav_menu_link_attributes_aria_dropdown( $atts, $item, $args, $depth ) {

		$item_has_children = in_array( 'menu-item-has-children', $item->classes, true );
		if ( $item_has_children ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		return $atts;
	}

	/**
	 * Checks whether the primary navigation menu is active.
	 *
	 * @return bool True if the primary navigation menu is active, false otherwise.
	 */
	public function is_primary_nav_menu_active(): bool {
		return (bool) has_nav_menu( static::PRIMARY_NAV_MENU_SLUG );
	}

	/**
	 * Displays the primary navigation menu.
	 *
	 * @param string $direction The menu direction.
	 * @param boolean $dropdown Whether it is dropdown menu
	 * @param array  $args Optional. Array of arguments. See `wp_nav_menu()` documentation for a list of supported
	 *                    arguments.
	 */
	public function display_primary_nav_menu( $direction = 'horizontal', $dropdown = true, array $args = [] ) {

		// No container for the ul by default, handled by templates.
		if ( ! isset( $args['container'] ) ) {
			$args['container'] = false;
		}

		if ( ! isset( $args['menu_class'] ) ) {
			if ( $dropdown ) {
				$args['menu_class'] = 'menu ' . $direction . '-menu is-dropdown';
			} else {
				$args['menu_class'] = 'menu ' . $direction . '-menu';
			}
		}

		if ( ! isset( $args['items_wrap'] ) ) {
			$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';
		}

		if ( 'vertical' === $direction ) {
			$args['walker']       = new Walker_Vertical_Nav_Menu();
			$args['show_toggles'] = false; // No needs to add toggles since it's handled by the walker class.
		} else {
			$args['show_toggles'] = true;
		}

		$args['theme_location'] = static::PRIMARY_NAV_MENU_SLUG;

		wp_nav_menu( $args );
	}

	/**
	 * Checks whether the secondary navigation menu is active.
	 *
	 * @return bool True if the secondary navigation menu is active, false otherwise.
	 */
	public function is_secondary_nav_menu_active(): bool {
		return (bool) has_nav_menu( static::SECONDARY_NAV_MENU_SLUG );
	}

	/**
	 * Displays the secondary navigation menu.
	 *
	 * @param string $direction The menu direction.
	 * @param boolean $dropdown Whether it is dropdown menu
	 * @param array  $args Optional. Array of arguments. See `wp_nav_menu()` documentation for a list of supported
	 *                    arguments.
	 */
	public function display_secondary_nav_menu( $direction = 'horizontal', $dropdown = true, array $args = [] ) {

		// No container for the ul by default, handled by templates.
		if ( ! isset( $args['container'] ) ) {
			$args['container'] = false;
		}

		if ( ! isset( $args['menu_class'] ) ) {
			if ( $dropdown ) {
				$args['menu_class'] = 'menu ' . $direction . '-menu is-dropdown';
			} else {
				$args['menu_class'] = 'menu ' . $direction . '-menu';
			}
		}

		if ( ! isset( $args['items_wrap'] ) ) {
			$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';
		}

		if ( 'vertical' === $direction ) {
			$args['walker']       = new Walker_Vertical_Nav_Menu();
			$args['show_toggles'] = false; // No needs to add toggles since it's handled by the walker class.
		} else {
			$args['show_toggles'] = true;
		}

		$args['theme_location'] = static::SECONDARY_NAV_MENU_SLUG;

		wp_nav_menu( $args );
	}

	/**
	 * Checks whether the social navigation menu is active.
	 *
	 * @return bool True if the social navigation menu is active, false otherwise.
	 */
	public function is_social_nav_menu_active(): bool {
		return (bool) has_nav_menu( Social_Nav_Menu::SLUG );
	}

	/**
	 * Displays the social navigation menu.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_nav_menu()` documentation for a list of supported
	 *                    arguments.
	 */
	public function display_social_nav_menu( array $args = [] ) {

		$social_label = get_theme_mod( 'themesetup_social_nav_menu_label', esc_html__( 'Follow us', 'themesetup' ) );
		// Defaults to 'Follow us' if empty.
		if ( '' === $social_label ) {
			$social_label = esc_html__( 'Follow us', 'themesetup' );
		}

		// No container for the ul by default, handled by templates.
		if ( ! isset( $args['container'] ) ) {
			$args['container'] = false;
		}

		if ( ! isset( $args['items_wrap'] ) ) {
			$args['items_wrap'] = '<ul id="%1$s" class="%2$s"><li class="social-menu-label">' . esc_html( $social_label ) . '</li>%3$s</ul>';
		}

		if ( ! isset( $args['menu_class'] ) ) {
			$args['menu_class'] = 'menu social-menu';
		}

		if ( ! isset( $args['link_before'] ) && ! isset( $args['link_after'] ) ) {
			$args['link_before'] = '<span class="screen-reader-text">';
			$args['link_after']  = '</span>' . themesetup()->get_svg( 'ui', 'link', Social_Nav_Menu::ICON_SIZE );
		}

		$args['theme_location'] = Social_Nav_Menu::SLUG;
		$args['depth']          = 1;

		wp_nav_menu( $args );
	}
}
