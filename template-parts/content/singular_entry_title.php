<?php
/**
 * Template part for displaying the singular entry title template
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

themesetup()->print_styles( 'themesetup-singular-entry-title' );
?>

<section class="singular-entry-title">

	<?php themesetup()->display_singular_entry_title_layout(); ?>

</section>
