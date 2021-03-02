<?php
/**
 * Template to display the header social nav template.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<nav class="social-nav hide-label" aria-label="<?php esc_attr_e( 'Social Links Menu', 'themesetup' ); ?>">
	<?php themesetup()->display_header_social_nav_menu(); ?>
</nav>
