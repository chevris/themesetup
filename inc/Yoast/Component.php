<?php
/**
 * Themesetup\Yoast\Component class
 *
 * @package themesetup
 */

declare( strict_types=1 );

namespace Themesetup\Yoast;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;
use function Themesetup\themesetup;

/**
 * Class for managing Yoast plugin integration.
 *
 * Exposes template tags:
 * * `themesetup()->yoast_author_social_links()`
 *
 * @link https://wordpress.org/plugins/wordpress-seo/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'yoast';
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
			'yoast_author_social_links' => [ $this, 'yoast_author_social_links' ],
		];
	}

	/**
	 * Display the Yoast list of social links for the current author.
	 *
	 * @param int|false $author_id Author ID.
	 * @param int       $size Social icon size.
	 * @param string    $before Small text before the list of social links.
	 */
	public function yoast_author_social_links( $author_id, $size = 24, $before = '' ) {

		// Available social profiles.
		$social_profiles = [
			'facebook',
			'twitter',
			'instagram',
			'linkedin',
			'myspace',
			'pinterest',
			'soundcloud',
			'tumblr',
			'youtube',
			'wikipedia',
		];

		$links = '';

		// Allowed HTML to sanitize links and add allowed SVG markup.
		$allowed_html = [
			'a'  => [
				'href'   => [],
				'title'  => [],
				'target' => [],
			],
			'li' => [
				'class' => [],
			],
		];
		$allowed_html = array_merge( themesetup()->sanitize_svgs(), $allowed_html );

		// Create the list of social links.
		foreach ( $social_profiles as $profile ) {
			if ( '' !== get_the_author_meta( $profile, $author_id ) ) {
				if ( 'twitter' === $profile ) {
					$links .= '<li class="twitter"><a href="https://twitter.com/' . esc_attr( get_the_author_meta( $profile, $author_id ) ) . '" target="_blank">' . themesetup()->get_svg( 'social', $profile, $size, $profile ) . '</a></li>';
				} else {
					$links .= '<li class="' . esc_attr( $profile ) . '"><a href="' . esc_url( get_the_author_meta( $profile, $author_id ) ) . '" target="_blank">' . themesetup()->get_svg( 'social', $profile, $size, $profile ) . '</a></li>';
				}
			}
		}

		if ( '' !== $links ) {

			// If a label is set in args, use it.
			if ( '' !== $before ) {
				printf(
					'<ul class="author-social-links menu"><li class="author-social-links-before">%1$s</li>%2$s</ul>',
					esc_html( $before ),
					wp_kses( $links, $allowed_html )
				);
			} else {
				echo '<ul class="author-social-links menu">' . wp_kses( $links, $allowed_html ) . '</ul>';
			}
		}

	}
}
