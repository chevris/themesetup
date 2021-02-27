<?php
/**
 * Template to display the header drawer toggle template.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<button id="drawer-header-toggle-open" class="drawer-header-toggle"
	<?php if ( themesetup()->is_amp() ) { ?>
		on="tap:drawer-header-amp.toggle"
	<?php } ?>
>
	<?php echo wp_kses( themesetup()->get_svg( 'ui', 'menu', 20 ), themesetup()->sanitize_svgs() ); ?>
	<span><?php esc_html_e( 'Menu', 'themesetup' ); ?></span>
</button>
