<?php
/**
 * Template part for displaying the header template
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

themesetup()->print_styles( 'themesetup-header' );

?>

<header id="masthead" class="site-header align-wrap">
	<?php themesetup()->display_header_layout(); ?>
</header>
