<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package themesetup
 */

declare( strict_types=1 );

get_header();

do_action( 'themesetup_singular_content' );

get_footer();
