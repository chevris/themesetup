<?php
/**
 * Template to display the slide-out sidebar toggle.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<button class="slideout-sidebar-toggle"
	<?php if ( themesetup()->is_amp() ) { ?>
		on="tap:slideout-sidebar-amp.toggle"
	<?php } ?>
>
	<?php echo wp_kses( themesetup()->get_svg( 'ui', 'menu', 20 ), themesetup()->sanitize_svgs() ); ?>
	<span><?php esc_html_e( 'Sidebar', 'themesetup' ); ?></span>
</button>
