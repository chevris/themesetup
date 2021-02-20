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
		add_filter( 'themesetup_css_files', [ $this, 'filter_css_files_archive' ] );
		add_action( 'themesetup_archive_content_title', [ $this, 'action_display_archive_content_title' ] );
		add_filter( 'get_the_archive_title', [ $this, 'filter_the_archive_content_title' ] );
		add_action( 'themesetup_archive_content', [ $this, 'action_display_archive_content' ] );
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
	 * Filters default CSS files.
	 *
	 * @param array $css_files Associative array of CSS files, as $handle => $data pairs.
	 * @return array Associative array of $handle => $data pairs.
	 */
	public function filter_css_files_archive( $css_files ): array {

		// CSS files to add.
		$archive_css_files = [
			'themesetup-archive-content-title' => [
				'file'             => 'in-body/archive-content-title.css',
				'preload_callback' => function () {
					return themesetup()->has_archive_content_title();
				},
			],
			'themesetup-archive-loop' => [
				'file'             => 'in-body/archive-loop.css',
				'preload_callback' => function () {
					return ! is_singular() && ! is_404();
				},
			],
		];

		// Enqueue and preload css files only if they exist.
		$css_files = array_merge( $archive_css_files, $css_files );

		return $css_files;

	}

	/**
	 * Display archive content title template.
	 */
	public function action_display_archive_content_title() {

		if ( themesetup()->has_archive_content_title() ) {
			get_template_part( 'template-parts/content/archive_content_title', get_post_type() );
		}
	}

	/**
	 * Filters the default archive titles.
	 * The 'time ago' filter need to be removed for date archives.
	 *
	 * @param string $title the name of the archive.
	 *
	 * @return string Archive title.
	 */
	public function filter_the_archive_content_title( $title ) {

		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

		if ( is_home() && is_front_page() ) {
			$title = get_bloginfo( 'name' );
		} elseif ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			/* translators: 1: Author Name */
			$title = sprintf( esc_html__( 'Author: %s', 'themesetup' ), get_the_author_meta( 'display_name' ) );
		} elseif ( is_day() ) {
			remove_filter( 'get_the_date', [ $this, 'filter_date_to_allow_time_ago_format' ], 10, 3 );
			/* translators: 1: Day */
			$title = sprintf( esc_html__( 'Day: %s', 'themesetup' ), get_the_date() );
			add_filter( 'get_the_date', [ $this, 'filter_date_to_allow_time_ago_format' ], 10, 3 );
		} elseif ( is_month() ) {
			remove_filter( 'get_the_date', [ $this, 'filter_date_to_allow_time_ago_format' ], 10, 3 );
			/* translators: 1: Month */
			$title = sprintf( esc_html__( 'Month: %s', 'themesetup' ), get_the_date( 'F Y' ) );
			add_filter( 'get_the_date', [ $this, 'filter_date_to_allow_time_ago_format' ], 10, 3 );
		} elseif ( is_year() ) {
			remove_filter( 'get_the_date', [ $this, 'filter_date_to_allow_time_ago_format' ], 10, 3 );
			/* translators: 1: Year */
			$title = sprintf( esc_html__( 'Year: %s', 'themesetup' ), get_the_date( 'Y' ) );
			add_filter( 'get_the_date', [ $this, 'filter_date_to_allow_time_ago_format' ], 10, 3 );
		} elseif ( is_tax( [ 'product_cat', 'product_tag' ] ) ) {
			$title = single_term_title( '', false );
		} elseif ( $term ) {
			$title = $term->name;
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} else {
			$title = esc_html__( 'Archives:', 'themesetup' );
		}

		return $title;

	}

	/**
	 * Display content archive template.
	 */
	public function action_display_archive_content() {
		get_template_part( 'template-parts/content/archive_content', get_post_type() );
	}

	/**
	 * Display archive loop.
	 */
	public function action_display_archive_loop() {

		$layout = themesetup()->get_context( 'archive', 'loop', 'layout' );

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

				get_template_part( 'template-parts/content/archive_entry_' . $layout, get_post_type() );

			}
			?>
			</div>
		<?php
	}

}
