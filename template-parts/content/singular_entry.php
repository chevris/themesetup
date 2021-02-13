<?php
/**
 * Template part for displaying the entry content of a singular post or page.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content align-wrap">

		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'themesetup' ),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				get_the_title()
			)
		);

		wp_link_pages(
			[
				'before' => '<div class="page-links">' . __( 'Pages:', 'themesetup' ),
				'after'  => '</div>',
			]
		);
		?>

	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->

<?php
if ( themesetup()->has_comments() ) {
	comments_template();
}
