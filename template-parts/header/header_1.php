<?php
/**
 * Header-1
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<div class="header-1">

	<?php get_template_part( 'template-parts/header/branding' ); ?>

	<?php
	if ( themesetup()->is_header_primary_nav_menu_active() ) {
		get_template_part( 'template-parts/header/primary-nav' );
	}
	?>

	<?php
	if ( themesetup()->is_header_social_nav_menu_active() ) {
		get_template_part( 'template-parts/header/social-nav' );
	}
	?>

	<?php
	if ( themesetup()->has_header_drawer() ) {
		get_template_part( 'template-parts/header/drawer-toggle-open' );
	}
	?>

</div><!-- .header-1 -->
