<?php
/**
 * Functions and definitions
 *
 * @package themesetup
 */

declare( strict_types=1 );

define( 'THEMESETUP_MINIMUM_WP_VERSION', '4.5' ); // phpcs:ignore NeutronStandard.Constants.DisallowDefine.Define
define( 'THEMESETUP_MINIMUM_PHP_VERSION', '7.0' ); // phpcs:ignore NeutronStandard.Constants.DisallowDefine.Define

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], THEMESETUP_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), THEMESETUP_MINIMUM_PHP_VERSION, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

// TGM.
require_once get_parent_theme_file_path( 'packages/tgm/plugins.php' );

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

// Setup autoloader (via Composer or custom).
if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require get_template_directory() . '/vendor/autoload.php';
} else {
	/**
	 * Custom autoloader function for theme classes.
	 *
	 * @access private
	 *
	 * @param string $class_name Class name to load.
	 * @return bool True if the class was loaded, false otherwise.
	 */
	function _themesetup_autoload( $class_name ): bool {
		$namespace = 'Themesetup';

		if ( strpos( $class_name, $namespace . '\\' ) !== 0 ) {
			return false;
		}

		$parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );

		$path = get_template_directory() . '/inc';
		foreach ( $parts as $part ) {
			$path .= '/' . $part;
		}
		$path .= '.php';

		if ( ! file_exists( $path ) ) {
			return false;
		}

		require_once $path;

		return true;
	}
	spl_autoload_register( '_themesetup_autoload' );
}

// Load the `themesetup()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'Themesetup\themesetup' ); // phpcs:ignore NeutronStandard.Functions.DisallowCallUserFunc.CallUserFunc
