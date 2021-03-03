<?php
/**
 * Header-1
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<div class="header-2">

	<?php get_template_part( 'template-parts/header/branding' ); ?>

	<?php
	if ( themesetup()->is_header_primary_nav_menu_active() ) {
		get_template_part( 'template-parts/header/primary-nav' );
	}
	?>

</div><!-- .header-2 -->
