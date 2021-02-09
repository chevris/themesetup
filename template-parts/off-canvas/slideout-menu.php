<?php
/**
 * Template to display the slide-out menu.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<?php if ( themesetup()->is_amp() ) { ?>
	<amp-sidebar id="slideout-menu-amp" layout="nodisplay" side="left" class="slideout-menu">
		<button class="slideout-menu-toggle" on="tap:slideout-menu-amp.toggle">
			<?php echo wp_kses( themesetup()->get_svg( 'ui', 'close', 20 ), themesetup()->sanitize_svgs() ); ?>
			<?php esc_html_e( 'Close', 'themesetup' ); ?>
		</button>
<?php } else { ?>
	<aside id="slideout-menu-js" class="slideout-menu">
		<button class="slideout-menu-toggle">
			<?php echo wp_kses( themesetup()->get_svg( 'ui', 'close', 20 ), themesetup()->sanitize_svgs() ); ?>
			<?php esc_html_e( 'Close', 'themesetup' ); ?>
		</button>
<?php } ?>

	<div class="slideout-menu-inner">

		<?php if ( themesetup()->is_primary_nav_menu_active() ) : ?>
			<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'themesetup' ); ?>" role="navigation">
				<?php themesetup()->display_primary_nav_menu( 'vertical' ); ?>
			</nav>
		<?php endif; ?>

		<div style="height:2000px;"></div>

	</div>

<?php if ( themesetup()->is_amp() ) { ?>
	</amp-sidebar>
<?php } else { ?>
	</aside>
<?php } ?>
