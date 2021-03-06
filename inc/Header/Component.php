<?php
/**
 * Themesetup\Header\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Header;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;
use function Themesetup\themesetup;

/**
 * Class for managing archive content.
 *
 * Exposes template tags:
 * * `themesetup()->display_header_layout()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'header';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'themesetup_css_files', [ $this, 'filter_css_files_header' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes' ] );
		add_action( 'themesetup_header', [ $this, 'action_display_header' ] );
		add_action( 'themesetup_before_page', [ $this, 'action_display_drawer_header' ] );
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
			'display_header_layout' => [ $this, 'display_header_layout' ],
		];
	}

	/**
	 * Filters default CSS files.
	 *
	 * @param array $css_files Associative array of CSS files, as $handle => $data pairs.
	 * @return array Associative array of $handle => $data pairs.
	 */
	public function filter_css_files_header( $css_files ): array {

		// CSS files to add.
		$header_css_file = [
			'themesetup-header' => [
				'file'             => 'in-body/header.css',
				'preload_callback' => function () {
					return themesetup()->has_header();
				},
			],
		];

		// Enqueue and preload css files only if they exist.
		$css_files = array_merge( $header_css_file, $css_files );

		return $css_files;

	}

	/**
	 * Adds custom classes to the array of body classes to indicate which header is displayed.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ): array {

		if ( ! themesetup()->has_header() ) {
			$classes[] = 'no-header';
		} else {
			$classes[] = themesetup()->get_context( 'header', 'layout' );
		}

		return $classes;
	}

	/**
	 * Display content singular template.
	 */
	public function action_display_header() {

		if ( themesetup()->has_header() ) {
			get_template_part( 'template-parts/header/header', get_post_type() );
		}

	}

	/**
	 * Display the header drawer template.
	 */
	public function action_display_drawer_header() {

		if ( themesetup()->has_header() && themesetup()->has_header_drawer() ) {
			get_template_part( 'template-parts/header/drawer' );
		}

	}

	/**
	 * Display header layout.
	 */
	public function display_header_layout() {

		$layout = themesetup()->get_context( 'header', 'layout' );

		get_template_part( 'template-parts/header/header_' . $layout );

	}

}
