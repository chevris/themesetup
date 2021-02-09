<?php
/**
 * Themesetup\Content_Archive\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Content_Archive;

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
		return 'content_archive';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'themesetup_content_archive', [ $this, 'action_display_content_archive' ] );
		add_filter( 'themesetup_css_files', [ $this, 'filter_css_files_archive_loop' ] );
		add_action( 'themesetup_archive_loop', [ $this, 'action_display_archive_loop' ] );
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
	 * Display content archive template.
	 */
	public function action_display_content_archive() {
		get_template_part( 'template-parts/content/content-archive', get_post_type() );
	}

	/**
	 * Filters default CSS files.
	 *
	 * @param array $css_files Associative array of CSS files, as $handle => $data pairs.
	 * @return array Associative array of $handle => $data pairs.
	 */
	public function filter_css_files_archive_loop( $css_files ): array {

		// CSS files to add.
		$archive_loop_css_file = [
			'themesetup-archive-loop' => [
				'file'             => 'in-body/archive-loop.css',
				'preload_callback' => function () {
					return ! is_singular() && ! is_404();
				},
			],
		];

		// Enqueue and preload css files only if they exist.
		$css_files = array_merge( $archive_loop_css_file, $css_files );

		return $css_files;

	}

	/**
	 * Display archive loop.
	 */
	public function action_display_archive_loop() {

		$layout = themesetup()->get_context( 'archive_loop', 'layout' );

		$archive_loop_classes = [];
		$archive_loop_classes[] = $layout;
		$archive_loop_classes = implode( ' ', $archive_loop_classes );

		themesetup()->print_styles( 'themesetup-archive-loop' );
		?>
			<div id="archive-loop" class="archive-loop <?php echo esc_attr( $archive_loop_classes ); ?>">
			<?php
			// Start loop.
			$post_count = 0;
			while ( have_posts() ) {
				$post_count++;
				the_post();

				get_template_part( 'template-parts/content/archive-entry-classic', get_post_type() );

			}
			?>
			</div>
		<?php
	}

}
