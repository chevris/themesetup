<?php
/**
 * Themesetup\Content_Singular\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Content_Singular;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;
use function Themesetup\themesetup;

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
		add_filter( 'themesetup_css_files', [ $this, 'filter_css_files_singular' ] );
		add_action( 'themesetup_singular_entry_title', [ $this, 'action_display_singular_entry_title' ] );
		add_action( 'themesetup_singular_content', [ $this, 'action_display_singular_content' ] );
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
	 * Filters default CSS files.
	 *
	 * @param array $css_files Associative array of CSS files, as $handle => $data pairs.
	 * @return array Associative array of $handle => $data pairs.
	 */
	public function filter_css_files_singular( $css_files ): array {

		// CSS files to add.
		$archive_css_files = [
			'themesetup-singular-entry-title' => [
				'file'             => 'in-body/singular-entry-title.css',
				'preload_callback' => function () {
					return themesetup()->has_singular_entry_title();
				},
			],
		];

		// Enqueue and preload css files only if they exist.
		$css_files = array_merge( $archive_css_files, $css_files );

		return $css_files;

	}

	/**
	 * Display singular entry title template.
	 */
	public function action_display_singular_entry_title() {

		if ( themesetup()->has_singular_entry_title() ) {
			get_template_part( 'template-parts/content/singular_entry_title', get_post_type() );
		}
	}

	/**
	 * Display content singular template.
	 */
	public function action_display_singular_content() {
		get_template_part( 'template-parts/content/singular_content', get_post_type() );
	}

}
