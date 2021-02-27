<?php
/**
 * Template to display the drawer sidebar template.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<?php if ( themesetup()->is_amp() ) { ?>
	<amp-sidebar id="drawer-sidebar-amp" layout="nodisplay" side="left" class="slideout-sidebar">
		<button class="drawer-sidebar-toggle" on="tap:drawer-sidebar-amp.toggle">
			<?php echo wp_kses( themesetup()->get_svg( 'ui', 'close', 20 ), themesetup()->sanitize_svgs() ); ?>
			<?php esc_html_e( 'Close', 'themesetup' ); ?>
		</button>
<?php } else { ?>
	<aside id="drawer-sidebar-js" class="drawer-sidebar drawer-sidebar-js">
		<button class="drawer-sidebar-toggle">
			<?php echo wp_kses( themesetup()->get_svg( 'ui', 'close', 20 ), themesetup()->sanitize_svgs() ); ?>
			<?php esc_html_e( 'Close', 'themesetup' ); ?>
		</button>
<?php } ?>

	<div class="drawer-sidebar-inner">

		<?php themesetup()->display_sidebar(); ?>

	</div>

<?php if ( themesetup()->is_amp() ) { ?>
	</amp-sidebar>
<?php } else { ?>
	</aside>
<?php } ?>
