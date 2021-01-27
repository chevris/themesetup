<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themesetup
 */

declare( strict_types=1 );

use function Themesetup\themesetup;

get_header();

?>

<h1 class="test">hello world</h1>

<?php var_dump( themesetup()->get_defaults() ); ?>

<div class="partial-test">
	<?php
	$test = themesetup()->get_setting( 'custom_control_test_setting4' );
	$decoded_test = json_decode( $test, true );
	var_dump( $decoded_test );
	?>
</div>

<div class="partial-test-2">
	<?php
	$test2 = themesetup()->get_setting( 'custom_control_test_setting5' );
	var_dump( $test2 );
	?>
</div>

<div class="partial-example-1">
	<?php
	$example1 = themesetup()->get_setting( 'example_setting_1' );
	var_dump( $example1 );
	?>
</div>


<?php
get_footer();
