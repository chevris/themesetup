<?php
/**
 * Template to display the header drawer template.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<?php if ( themesetup()->is_amp() ) { ?>
	<amp-sidebar id="drawer-header-amp" layout="nodisplay" side="left" class="drawer-header">
		<button class="drawer-header-toggle" on="tap:drawer-header-amp.toggle">
			<?php echo wp_kses( themesetup()->get_svg( 'ui', 'close', 20 ), themesetup()->sanitize_svgs() ); ?>
			<?php esc_html_e( 'Close', 'themesetup' ); ?>
		</button>
<?php } else { ?>
	<aside id="drawer-header-js" class="drawer-header drawer-header-js">
		<button class="drawer-header-toggle">
			<?php echo wp_kses( themesetup()->get_svg( 'ui', 'close', 20 ), themesetup()->sanitize_svgs() ); ?>
			<?php esc_html_e( 'Close', 'themesetup' ); ?>
		</button>
<?php } ?>

	<div class="drawer-header-inner">

		<?php if ( themesetup()->is_primary_nav_menu_active() ) : ?>
			<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'themesetup' ); ?>" role="navigation">
				<?php themesetup()->display_primary_nav_menu( 'vertical' ); ?>
			</nav>
		<?php endif; ?>

	</div>

<?php if ( themesetup()->is_amp() ) { ?>
	</amp-sidebar>
<?php } else { ?>
	</aside>
<?php } ?>
