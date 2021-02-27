<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #page div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package themesetup
 */

declare( strict_types=1 );

use function Themesetup\themesetup;

?>

	</div><!-- #content -->

	<?php
	do_action( 'themesetup_before_footer' );

	do_action( 'themesetup_footer' );

	do_action( 'themesetup_after_footer' );
	?>

</div><!-- #page -->

<?php do_action( 'themesetup_after_page' ); ?>

<?php wp_footer(); ?>

</body>
</html>
