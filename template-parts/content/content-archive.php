<?php
/**
 * Template part for displaying the content archive template
 *
 * @package themesetup
 */

?>

<section id="primary" class="content-area">

	<?php do_action( 'themesetup_archive_content_header' ); ?>

	<div class="content-main">

		<main id="main" class="site-main" role="main">

			<?php
			if ( have_posts() ) {

				do_action( 'themesetup_archive_loop' );

			} else {
				get_template_part( 'template-parts/content/error' );
			}
			?>

		</main><!-- .site-main -->

	</div><!-- .main-content -->

</section><!-- .content-area -->

