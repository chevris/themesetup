<?php
/**
 * The template for displaying all single pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themesetup
 */

declare( strict_types=1 );

get_header();

do_action( 'themesetup_singular_content' );

get_footer();
