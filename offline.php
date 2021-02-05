<?php
/**
 * The template for displaying offline pages
 *
 * @link https://github.com/xwp/pwa-wp#offline--500-error-handling
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

// Prevent showing nav menus.
add_filter( 'has_nav_menu', '__return_false' );

get_header();

?>
	<section id="primary" class="content-area">

		<?php do_action( 'themesetup_singular_content_header' ); ?>

		<div class="content-main">

			<main id="main" class="site-main" role="main">

				<section class="error">
					<header class="page-header">
						<h1 class="page-title">
							<?php esc_html_e( 'Oops! It looks like you&#8217;re offline.', 'themesetup' ); ?>
						</h1>
					</header><!-- .page-header -->

					<div class="page-content">
						<?php
						if ( function_exists( 'wp_service_worker_error_message_placeholder' ) ) {
							wp_service_worker_error_message_placeholder();
						}
						?>
					</div><!-- .page-content -->
				</section><!-- .error -->

			</main><!-- .site-main -->

		</div><!-- .content-main -->

	</section><!-- .content-area -->
<?php
get_footer();
