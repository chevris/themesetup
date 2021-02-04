<?php
/**
 * Template part for displaying the content area for a singular post or page.
 *
 * @package themesetup
 */

?>

<section id="primary" class="content-area">

<?php
while ( have_posts() ) {
	the_post();
	?>

	<?php do_action( 'themesetup_singular_content_header' ); ?>

	<div class="content-main">

		<main id="main" class="site-main" role="main">

			<?php get_template_part( 'template-parts/content/singular-entry', get_post_type() ); ?>

		</main><!-- .site-main -->

	</div><!-- .content-main -->

	<?php
}
?>

</section><!-- .content-area -->
