<?php
/**
 * Template part for displaying the content when a 404 error has occurred
 *
 * @package themesetup
 */

?>

<section id="primary" class="content-area">

	<div class="content-main">

		<main id="main" class="site-main">

			<div class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'themesetup' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'themesetup' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->

	</div><!-- .content-main -->

</section><!-- #primary -->
