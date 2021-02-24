<?php
/**
 * Template part for displaying classic entry in the archive loop.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

$article_classes = [ 'entry', 'entry-classic' ];
$article_classes = implode( ' ', $article_classes );
$show_full_content = false;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $article_classes ); ?>>

	<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

	<?php themesetup()->comments_count(); ?>

	<div class="entry-summary">

	</div>

	<?php
	// Can show the full content in classic layout, only for post entries.
	if ( 'post' === get_post_type() && true === $show_full_content ) {
		?>
		<div class="entry-content">
			<?php
			global $more;
			$more = 0;
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
		<?php
	} else {
		?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->

		<div class="read-more">
			<a href="<?php the_permalink(); ?>" class="read-more-link">
				<span class="read-more-text"><?php esc_html_e( 'Continue Reading', 'themesetup' ); ?></span>
				<span class="read-more-line"></span>
			</a>
		</div>
		<?php
	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->
