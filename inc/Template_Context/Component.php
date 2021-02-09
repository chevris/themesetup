<?php
/**
 * Themesetup\Template_Context\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Template_Context;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;

/**
 * Class for managing template context.
 *
 * Exposes template tags:
 * * `themesetup()->get_context()`
 * * `themesetup()->has_comments()`
 * * `themesetup()->has_header()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Holds the context.
	 *
	 * @var array|null $context
	 */
	public static $context = null;

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'template_context';
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
			'get_context' => [ $this, 'get_context' ],
			'has_header' => [ $this, 'has_header' ],
			'has_comments' => [ $this, 'has_comments' ],
		];
	}

	/**
	 * Retrieves context data.
	 *
	 * @param string $key Data key.
	 * @param string $first_lvl
	 * @param string $second_lvl
	 * @return mixed Context data.
	 */
	public function get_context( $key, $first_lvl = '', $second_lvl = '' ) {

		if ( is_null( self::$context ) ) {
			self::set_context();
		}

		if ( isset( self::$context[ $key ] ) && '' !== self::$context[ $key ] ) {
			$value = self::$context[ $key ];
		} else {
			return null;
		}

		if ( ! empty( $first_lvl ) ) {
			if ( isset( $value[ $first_lvl ] ) && '' !== self::$context[ $key ] ) {
				$value = $value[ $first_lvl ];
			} else {
				return null;
			}

			if ( ! empty( $second_lvl ) ) {
				if ( isset( $value[ $second_lvl ] ) && '' !== self::$context[ $key ] ) {
					$value = $value[ $second_lvl ];
				} else {
					return null;
				}
			}
		}

		return $value;
	}

	/**
	 * Determines whether header should be displayed.
	 *
	 * @return boolean True if header should be displayed.
	 */
	public function has_header() {

		if ( is_null( self::$context ) ) {
			self::set_context();
		}
		return (bool) self::$context['header']['enabled'];

	}

	/**
	 * Determines whether comments should be displayed.
	 *
	 * @return boolean True if comments should be displayed.
	 */
	public function has_comments() {

		if ( is_null( self::$context ) ) {
			self::set_context();
		}
		return (bool) self::$context['has_comments'];

	}

	/**
	 * Initializes the context data used for this particular page.
	 */
	private static function set_context() {

		$header = [
			'enabled' => true,
			'layout' => 'header-1',
		];
		$archive_loop = [
			'layout' => 'classic',
		];
		$has_comments = false;

		if ( ( is_singular() || is_front_page() ) && ! is_home() ) {
			if ( is_front_page() ) {
				$post_id = get_option( 'page_on_front' );
				$post_type  = 'page';
			} else {
				$post_id    = get_the_ID();
				$post_type  = get_post_type();
			}

			$has_comments = post_type_supports( $post_type, 'comments' ) && ( comments_open() || get_comments_number() );

		} elseif ( is_archive() || is_search() || is_home() ) {
			if ( is_home() && is_front_page() ) {
				$archive_type = 'post_archive';
			} elseif ( is_home() && ! is_front_page() ) {
				$archive_type    = 'post_archive';
				$post_id         = get_option( 'page_for_posts' );
			} elseif ( is_search() ) {
				$archive_type = 'search_archive';
			} elseif ( is_category() || is_tag() ) {
				$archive_type = 'post_archive';
			} else {
				$post_type  = get_post_type();
				$archive_type = $post_type . '_archive';
			}
		} elseif ( is_404() ) {
			$archive_type = '404';
		}

		$context_arr = [
			'header' => $header,
			'has_comments' => $has_comments,
			'archive_loop' => $archive_loop,
		];

		self::$context = apply_filters( 'themesetup_filter_template_context', $context_arr );

	}

}
