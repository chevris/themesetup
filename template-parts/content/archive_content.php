<?php
/**
 * Template part for displaying the archive content template
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

?>

<?php do_action( 'themesetup_archive_content_title' ); ?>

<section id="primary" class="content-area">

	<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) {

			do_action( 'themesetup_archive_loop' );

			// Previous/next page navigation.
			get_template_part( 'template-parts/content/pagination' );

		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>

	</main><!-- .site-main -->

</section><!-- .content-area -->

