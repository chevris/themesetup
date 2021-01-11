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
 * * `themesetup()->render_view( $view, $data )`
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
		add_filter( 'script_loader_tag', [ $this, 'filter_script_loader_tag' ], 10, 2 );
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
			'render_view' => [ $this, 'render_view' ],
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
	 * Adds async/defer attributes to enqueued / registered scripts.
	 *
	 * If #12009 lands in WordPress, this function can no-op since it would be handled in core.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12009
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string Script HTML string.
	 */
	public function filter_script_loader_tag( string $tag, string $handle ): string {

		foreach ( [ 'async', 'defer' ] as $attr ) {
			if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
				continue;
			}

			// Prevent adding attribute when already added in #12009.
			if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
				$tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
			}

			// Only allow async or defer, not both.
			break;
		}

		return $tag;
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
	 * Loads a view into a template..
	 *
	 * @param string $view The name of the view to be loaded from `views` folder, without extension.
	 * @param array  $data An associative array of datas used in the view.
	 */
	public function render_view( $view, $data = [] ) {

		// Bail if no view.
		if ( empty( $view ) ) {
			return;
		}

		// Variables passed are accessible in $data. Since the view is executed in its own function, it gets its own scope.
		$data = apply_filters( "themesetup_filter_view_data_{$view}", $data );

		// Get the view ( Searches in the STYLESHEETPATH before TEMPLATEPATH so that child themes can overload one file ).
		$path = trailingslashit( get_stylesheet_directory() ) . 'views/' . $view . '.php';
		if ( is_file( $path ) ) {
			include $path;
			return;
		}

		$path = trailingslashit( get_template_directory() ) . 'views/' . $view . '.php';
		if ( is_file( $path ) ) {
			include $path;
			return;
		}

	}

}
