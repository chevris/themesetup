<?php
/**
 * Template to display the slide-out sidebar.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<?php if ( themesetup()->is_amp() ) { ?>
	<amp-sidebar id="slideout-sidebar-amp" layout="nodisplay" side="left" class="slideout-sidebar">
		<button class="slideout-sidebar-toggle" on="tap:slideout-sidebar-amp.toggle">
			<?php echo wp_kses( themesetup()->get_svg( 'ui', 'close', 20 ), themesetup()->sanitize_svgs() ); ?>
			<?php esc_html_e( 'Close', 'themesetup' ); ?>
		</button>
<?php } else { ?>
	<aside id="slideout-sidebar-js" class="slideout-sidebar">
		<button class="slideout-sidebar-toggle">
			<?php echo wp_kses( themesetup()->get_svg( 'ui', 'close', 20 ), themesetup()->sanitize_svgs() ); ?>
			<?php esc_html_e( 'Close', 'themesetup' ); ?>
		</button>
<?php } ?>

	<div class="slideout-sidebar-inner">

		<?php themesetup()->display_sidebar(); ?>

	</div>

<?php if ( themesetup()->is_amp() ) { ?>
	</amp-sidebar>
<?php } else { ?>
	</aside>
<?php } ?>
