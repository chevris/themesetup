<?php
/**
 * Backward-compatibility functions for when theme requirements are not met
 *
 * This file must be parseable by PHP 5.2.
 *
 * @package themesetup
 */

/**
 * Gets the message to warn the user about the theme requirements not being met.
 *
 * @global string $wp_version WordPress version.
 *
 * @return string Message to show to the user.
 */
function themesetup_get_insufficient_requirements_message(): string { // phpcs:ignore NeutronStandard.Globals.DisallowGlobalFunctions.GlobalFunctions
	global $wp_version;

	$insufficient_wp  = version_compare( $wp_version, THEMESETUP_MINIMUM_WP_VERSION, '<' );
	$insufficient_php = version_compare( phpversion(), THEMESETUP_MINIMUM_PHP_VERSION, '<' );

	if ( $insufficient_wp && $insufficient_php ) {
		/* translators: 1: required WP version number, 2: required PHP version number, 3: available WP version number, 4: available PHP version number */
		return sprintf( __( 'Themesetup requires at least WordPress version %1$s and PHP version %2$s. You are running versions %3$s and %3$s respectively. Please update and try again.', 'themesetup' ), THEMESETUP_MINIMUM_WP_VERSION, THEMESETUP_MINIMUM_PHP_VERSION, $wp_version, phpversion() );
	}

	if ( $insufficient_wp ) {
		/* translators: 1: required WP version number, 2: available WP version number */
		return sprintf( __( 'Themesetup requires at least WordPress version %1$s. You are running version %2$s. Please update and try again.', 'themesetup' ), THEMESETUP_MINIMUM_WP_VERSION, $wp_version );
	}

	if ( $insufficient_php ) {
		/* translators: 1: required PHP version number, 2: available PHP version number */
		return sprintf( __( 'Themesetup requires at least PHP version %1$s. You are running version %2$s. Please update and try again.', 'themesetup' ), THEMESETUP_MINIMUM_PHP_VERSION, phpversion() );
	}

	return '';
}

/**
 * Prevents switching to the theme when requirements are not met.
 *
 * Switches to the default theme.
 */
function themesetup_switch_theme() { // phpcs:ignore NeutronStandard.Globals.DisallowGlobalFunctions.GlobalFunctions
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );

	add_action( 'admin_notices', 'themesetup_upgrade_notice' );
}
add_action( 'after_switch_theme', 'themesetup_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to the theme
 * when requirements are not met.
 */
function themesetup_upgrade_notice() { // phpcs:ignore NeutronStandard.Globals.DisallowGlobalFunctions.GlobalFunctions
	printf( '<div class="error"><p>%s</p></div>', esc_html( themesetup_get_insufficient_requirements_message() ) );
}

/**
 * Prevents the Customizer from being loaded when requirements are not met.
 */
function themesetup_customize() { // phpcs:ignore NeutronStandard.Globals.DisallowGlobalFunctions.GlobalFunctions
	wp_die(
		esc_html( themesetup_get_insufficient_requirements_message() ),
		'',
		[
			'back_link' => true,
		]
	);
}
add_action( 'load-customize.php', 'themesetup_customize' );

/**
 * Prevents the Theme Preview from being loaded when requirements are not met.
 */
function themesetup_preview() { // phpcs:ignore NeutronStandard.Globals.DisallowGlobalFunctions.GlobalFunctions
	if ( isset( $_GET['preview'] ) ) {
		wp_die( esc_html( themesetup_get_insufficient_requirements_message() ) );
	}
}
add_action( 'template_redirect', 'themesetup_preview' );
