<?php
/**
 * Themesetup\Medias\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Medias;

use Themesetup\Component_Interface;
use WP_Post;

/**
 * Class for managing medias.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'medias';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_image_sizes' ] );

		// Post thumbnails.
		add_action( 'after_setup_theme', [ $this, 'action_add_post_thumbnail_support' ] );
		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'filter_post_thumbnail_sizes_attr' ], 10, 3 );

		// Header image.
		add_filter( 'get_header_image_tag', [ $this, 'filter_header_image_tag' ], 10, 3 );
	}

	/**
	 * Adds custom image sizes.
	 */
	public function action_add_image_sizes() {
		add_image_size( 'themesetup-featured-image', 1200, 9999 );
	}

	/**
	 * Adds support for post thumbnails on posts and pages.
	 */
	public function action_add_post_thumbnail_support() {
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1568, 9999 ); // Set 'post-thumbnail' default size, called when no size passed to the_post_thumbnail().
	}

	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for post thumbnails.
	 *
	 * @param array        $attr       Attributes for the image markup.
	 * @param WP_Post      $attachment Attachment post object.
	 * @param string|array $size       Registered image size or flat array of height and width dimensions.
	 * @return array The filtered attributes for the image markup.
	 */
	public function filter_post_thumbnail_sizes_attr( array $attr, WP_Post $attachment, $size ): array {

		if ( is_admin() ) {
			return $attr;
		}

		return $attr;

	}

	/**
	 * Filters the `sizes` value in the header image markup.
	 *
	 * @param string $html   The HTML image tag markup being filtered.
	 * @param object $header The custom header object returned by 'get_custom_header()'.
	 * @param array  $attr   Array of the attributes for the image tag.
	 * @return string The filtered header image HTML.
	 */
	public function filter_header_image_tag( string $html, $header, array $attr ): string {
		if ( isset( $attr['sizes'] ) ) {
			$html = str_replace( $attr['sizes'], '100vw', $html );
		}

		return $html;
	}

}
