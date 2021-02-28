<?php
/**
 * Themesetup\Nav_Menus\Social_Nav_Menu class
 *
 * @package themesetup
 */

namespace Themesetup\Nav_Menus;

use function Themesetup\themesetup;
use WP_Post;

/**
 * Class for managing the social navigation menu.
 */
class Social_Nav_Menu {

	const SLUG      = 'header-social';
	const ICON_SIZE = 24;

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'walker_nav_menu_start_el', [ $this, 'filter_social_nav_menu_icons' ], 10, 4 );
	}

	/**
	 * Adjusts the social navigation menu to display SVG icons.
	 *
	 * @param string  $item_output The menu item output.
	 * @param WP_Post $item        Menu item object.
	 * @param int     $depth       Depth of the menu.
	 * @param object  $args        An object of wp_nav_menu() arguments.
	 * @return string Filtered $item_output.
	 */
	public function filter_social_nav_menu_icons( string $item_output, WP_Post $item, int $depth, $args ) {
		// Only for our social menu location.
		if ( empty( $args->theme_location ) || static::SLUG !== $args->theme_location ) {
			return $item_output;
		}

		// Change SVG icon inside social links menu if there is supported URL.
		$svg = themesetup()->get_social_link_svg( $item->url, static::ICON_SIZE );
		if ( empty( $svg ) ) {
			$svg = themesetup()->get_svg( 'ui', 'link', static::ICON_SIZE );
		}
		return str_replace( $args->link_after, '</span>' . $svg, $item_output );
	}

}
