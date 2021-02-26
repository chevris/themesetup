<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

if ( ! themesetup()->has_sidebar() ) {
	return;
}

themesetup()->print_styles( 'themesetup-widgets' );
?>

<aside id="secondary" class="site-sidebar widget-area">
	<?php themesetup()->display_sidebar(); ?>
</aside><!-- #secondary -->
