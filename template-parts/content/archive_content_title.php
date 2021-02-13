<?php
/**
 * Template part for displaying the archive content title template
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

?>

<section class="archive-content-title">

	<?php
	if ( is_home() && ! is_front_page() ) {
		?>
		<h1 class="content-title latest-post-title">
			<?php single_post_title(); ?>
		</h1>
		<?php
	} elseif ( is_search() ) {
		?>
		<h1 class="page-title search-title">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Search Results for: %s', 'themesetup' ),
				'<span>' . get_search_query() . '</span>'
			);
			?>
		</h1>
		<?php
	} elseif ( is_archive() || is_home() ) {
		the_archive_title( '<h1 class="page-title archive-title">', '</h1>' );
	}
	?>

</section>
