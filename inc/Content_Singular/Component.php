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
 * * `themesetup()->display_singular_entry_title_layout()`
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
		return [
			'display_singular_entry_title_layout' => [ $this, 'display_singular_entry_title_layout' ],
		];
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

	/**
	 * Display singular entry title.
	 */
	public function display_singular_entry_title_layout() {

		$layout = themesetup()->get_context( 'singular', 'title', 'layout' );

		if ( 'behind' === $layout ) {
			?>
			<div class="feat-img-behind">
				<?php themesetup()->post_thumbnail( 'themesetup-featured-image', 'cover' ); ?>
				<div class="entry-title-content">
					<?php get_template_part( 'template-parts/content/singular_entry_title_content', get_post_type() ); ?>
				</div>
			</div><!-- .feat-img-behind -->
			<?php
		} elseif ( 'above' === $layout ) {
			?>
			<div class="feat-img-above">
				<?php themesetup()->post_thumbnail( 'themesetup-featured-image', 'responsive' ); ?>
				<div class="entry-title-content">
					<?php get_template_part( 'template-parts/content/singular_entry_title_content', get_post_type() ); ?>
				</div>
			</div><!-- .feat-img-above -->
			<?php
		} elseif ( 'below' === $layout ) {
			?>
			<div class="feat-img-below">
				<div class="entry-title-content">
					<?php get_template_part( 'template-parts/content/singular_entry_title_content', get_post_type() ); ?>
				</div>
				<?php themesetup()->post_thumbnail( 'themesetup-featured-image', 'responsive' ); ?>
			</div><!-- .feat-img-below -->
			<?php
		} else {
			?>
			<div class="entry-title-content">
				<?php get_template_part( 'template-parts/content/singular_entry_title_content', get_post_type() ); ?>
			</div>
			<?php
		}
	}

}
