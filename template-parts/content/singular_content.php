<?php
/**
 * Template part for displaying the content area for a singular post or page.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

?>

<?php
while ( have_posts() ) {
	the_post();
	?>

	<?php do_action( 'themesetup_singular_entry_title' ); ?>

	<section id="primary" class="content-area">

		<?php
		if ( themesetup()->has_sidebar() && 'toggle' === themesetup()->get_sidebar_layout() ) {
			get_template_part( 'template-parts/off-canvas/slideout-sidebar-toggle' );
		}
		?>

		<main id="main" class="site-main" role="main">

			<?php get_template_part( 'template-parts/content/singular_entry', get_post_type() ); ?>

		</main><!-- .site-main -->

		<?php get_sidebar(); ?>

	</section><!-- .content-area -->

	<?php
}
