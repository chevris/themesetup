<?php
/**
 * Themesetup\Base_Support\Component class
 *
 * @package themesetup
 */

declare( strict_types=1 );

namespace Themesetup\Base_Support;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;

/**
 * Class for adding basic theme support, most of which is mandatory to be implemented by all themes.
 *
 * Exposes template tags:
 * * `themesetup()->get_version()`
 * * `themesetup()->get_asset_version( string $filepath )`
 * * `themesetup()->get_cpt_objects()`
 * * `themesetup()->get_excluded_cpt()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'base_support';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_essential_theme_support' ] );
		add_action( 'wp_head', [ $this, 'action_add_pingback_header' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes_add_hfeed' ] );
		add_filter( 'embed_defaults', [ $this, 'filter_embed_dimensions' ] );
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
			'get_version' => [ $this, 'get_version' ],
			'get_asset_version' => [ $this, 'get_asset_version' ],
			'get_cpt_objects' => [ $this, 'get_cpt_objects' ],
			'get_excluded_cpt' => [ $this, 'get_excluded_cpt' ],
		];
	}

	/**
	 * Adds theme support for essential features.
	 */
	public function action_essential_theme_support() {
		// Add default RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Ensure WordPress manages the document title.
		add_theme_support( 'title-tag' );

		// Ensure WordPress theme features render in HTML5 markup.
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			]
		);

		// Add support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
	}

	/**
	 * Adds a pingback url auto-discovery header for singularly identifiable articles.
	 */
	public function action_add_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}

	/**
	 * Adds a 'hfeed' class to the array of body classes for non-singular pages.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes_add_hfeed( array $classes ): array {
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Sets the embed width in pixels, based on the theme's design and stylesheet.
	 *
	 * @see https://github.com/wprig/wprig/issues/12#issuecomment-399256759
	 *
	 * @param array $dimensions An array of embed width and height values in pixels (in that order).
	 * @return array Filtered dimensions array.
	 */
	public function filter_embed_dimensions( array $dimensions ): array {
		$dimensions['width'] = 720;
		return $dimensions;
	}

	/**
	 * Gets the theme version.
	 *
	 * @return string Theme version number.
	 */
	public function get_version(): string {
		static $theme_version = null;

		if ( null === $theme_version ) {
			$theme_version = wp_get_theme( get_template() )->get( 'Version' );
		}

		return $theme_version;
	}

	/**
	 * Gets the version for a given asset.
	 *
	 * Returns filemtime when WP_DEBUG is true, otherwise the theme version.
	 *
	 * @param string $filepath Asset file path.
	 * @return string Asset version number.
	 */
	public function get_asset_version( string $filepath ): string {
		if ( WP_DEBUG ) {
			return (string) filemtime( $filepath );
		}

		return $this->get_version();
	}

	/**
	 * Gets the theme custom post types.
	 *
	 * @return array Theme CPT objects.
	 */
	public function get_cpt_objects(): array {
		static $cpt_objects = null;

		if ( null === $cpt_objects ) {
			$args = [
				'public' => true,
				'_builtin' => false,
			];
			$output = 'objects';
			$operator = 'and';
			$cpt_objects = get_post_types( $args, $output, $operator );
		}

		return $cpt_objects;
	}

	/**
	 * Get an array of post types to exclude.
	 *
	 * @return array Excluded post types.
	 */
	public static function get_excluded_cpt() {
		static $excluded_cpt = null;

		if ( null === $excluded_cpt ) {
			$excluded_cpt = [
				'elementor_library',
				'manage_cpt_template',
			];
			$excluded_cpt = apply_filters( 'themesetup_filter_excluded_cpt', $excluded_cpt );
		}

		return $excluded_cpt;
	}

}
