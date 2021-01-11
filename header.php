<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package themesetup
 */

declare( strict_types=1 );

use function Themesetup\themesetup;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php
	if ( ! themesetup()->is_amp() ) {
		?>
		<script>document.documentElement.classList.remove( 'no-js' );</script>
		<?php
	}
	?>

	<?php wp_head(); ?>
</head>

<body id="body" <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php do_action( 'before_page' ); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'themesetup' ); ?></a>

