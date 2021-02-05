<?php
/**
 * The template for displaying 500 pages (internal server errors)
 *
 * @link https://github.com/xwp/pwa-wp#offline--500-error-handling
 *
 * @package themesetup
 */

namespace Themesetup;

get_header();

?>
<section id="primary" class="content-area">

	<?php do_action( 'themesetup_singular_content_header' ); ?>

	<div class="content-main">

		<main id="main" class="site-main" role="main">

			<section class="error">
				<header class="page-header">
					<h1 class="page-title">
						<?php esc_html_e( 'Oops! Something went wrong.', 'themesetup' ); ?>
					</h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<?php
					if ( function_exists( 'wp_service_worker_error_message_placeholder' ) ) {
						wp_service_worker_error_message_placeholder();
					}
					if ( function_exists( 'wp_service_worker_error_details_template' ) ) {
						wp_service_worker_error_details_template();
					}
					?>
				</div><!-- .page-content -->
			</section><!-- .error -->

		</main><!-- .site-main -->

	</div><!-- .content-main -->

</section><!-- .content-area -->

<?php
get_footer();
