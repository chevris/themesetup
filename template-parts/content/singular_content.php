<?php
/**
 * Template part for displaying the content area for a singular post or page.
 *
 * @package themesetup
 */

?>

<?php
while ( have_posts() ) {
	the_post();
	?>

	<?php do_action( 'themesetup_singular_content_title' ); ?>

	<section id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<?php get_template_part( 'template-parts/content/singular_entry', get_post_type() ); ?>

		</main><!-- .site-main -->

	</section><!-- .content-area -->

	<?php
}
