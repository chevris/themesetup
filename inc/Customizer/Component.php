<?php
/**
 * Themesetup\Customizer\Component class
 *
 * @package themesetup
 */

declare( strict_types=1 );

namespace Themesetup\Customizer;

use WP_Customize_Manager;
use function Themesetup\themesetup;
use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;

/**
 * Class for managing Customizer integration.
 *
 * Exposes template tags:
 * * `themesetup()->get_default()`
 * * `themesetup()->get_defaults()`
 * * `themesetup()->get_setting()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Holds default theme option values
	 *
	 * @var array|null
	 */
	protected static $defaults = null;

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'customizer';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {

		add_action( 'customize_register', [ $this, 'action_modify_default_settings' ] );
		add_action( 'customize_preview_init', [ $this, 'action_enqueue_customizer_preview_assets' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'action_enqueue_customizer_pane_assets' ] );

		$register_settings = new Register_Settings();
		$register_settings->initialize();
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `themesetup()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return [
			'get_default' => [ $this, 'get_default' ],
			'get_defaults' => [ $this, 'get_defaults' ],
			'get_setting' => [ $this, 'get_setting' ],
		];
	}

	/**
	 * Modify default options.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_modify_default_settings( WP_Customize_Manager $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_control( 'header_text' )->label         = __( 'Display Site Title', 'themesetup' );

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				[
					'selector'        => '.site-title a',
					'render_callback' => function() {
						bloginfo( 'name' );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				[
					'selector'        => '.site-description',
					'render_callback' => function() {
						bloginfo( 'description' );
					},
				]
			);
		}

	}

	/**
	 * Enqueues CSS and JS for the Customizer preview.
	 */
	public function action_enqueue_customizer_preview_assets() {
		wp_enqueue_script(
			'themesetup-customizer-preview',
			get_theme_file_uri( '/public/js/customizer-preview.js' ),
			[ 'customize-preview' ],
			themesetup()->get_asset_version( get_theme_file_path( '/public/js/customizer-preview.js' ) ),
			true
		);
		global $post_id;
		wp_localize_script(
			'themesetup-customizer-preview',
			'themesetupCustomizePreviewLocalize',
			apply_filters(
				'themesetup_filter_customize_preview_localize',
				[]
			)
		);

		wp_enqueue_style(
			'themesetup-customizer-preview',
			get_theme_file_uri( '/public/css/customizer-preview.css' ),
			[],
			themesetup()->get_asset_version( get_theme_file_path( '/public/css/customizer-preview.css' ) )
		);
	}

	/**
	 * Enqueues CSS and JS for customizer controls panel.
	 */
	public function action_enqueue_customizer_pane_assets() {

		/**
		 * Global scripts for customizer panel.
		 */
		wp_enqueue_script(
			'themesetup-customizer-pane',
			get_theme_file_uri( '/public/js/customizer-pane.js' ),
			[ 'customize-controls', 'jquery' ],
			themesetup()->get_asset_version( get_theme_file_path( '/public/js/customizer-pane.js' ) ),
			true
		);

		/**
		 * Global style for customizer panel.
		 */
		wp_enqueue_style(
			'themesetup-customizer-pane',
			get_theme_file_uri( '/public/css/customizer-pane.css' ),
			[],
			themesetup()->get_asset_version( get_theme_file_path( '/public/css/customizer-pane.css' ) )
		);

		// Allows customizer panel style to be filtered from other components.
		$inline_style = apply_filters( 'themesetup_filter_customizer_pane_inline_style', '' );
		wp_add_inline_style( 'themesetup-customizer-pane', $inline_style );

		/**
		 * Scripts for customizer controls.
		 */
		wp_enqueue_script(
			'themesetup-customizer-controls',
			get_theme_file_uri( '/public/js/customizer-controls.js' ),
			[
				'lodash',
				'wp-element',
				'wp-components',
				'wp-i18n',
			],
			themesetup()->get_asset_version( get_theme_file_path( '/public/js/customizer-controls.js' ) ),
			true
		);
		wp_localize_script(
			'themesetup-customizer-controls',
			'themesetupCustomizerControlsLocalize',
			apply_filters(
				'themesetup_filter_customizer_controls_localize',
				[]
			)
		);

		/**
		 * Style for customizer controls.
		 */
		wp_enqueue_style(
			'themesetup-customizer-controls',
			get_theme_file_uri( '/public/css/customizer-controls.css' ),
			[],
			themesetup()->get_asset_version( get_theme_file_path( '/public/css/customizer-controls.css' ) )
		);
	}

	public static function get_defaults() {

		if ( is_null( self::$defaults ) ) {

			$defaults = [
				'example_setting_1' => 50,
				'example_setting_2' => '{ "mobile": 748, "tablet": 992, "desktop": 1170 }',
				'example_setting_3' => 'option-2',

				'themesetup_preload_style' => true,
			];

			$all_cpt = themesetup()->get_cpt_objects();
			$excluded_cpt = themesetup()->get_excluded_cpt();

			foreach ( $all_cpt as $cpt ) {
				$cpt_name = $cpt->name;
				if ( ! in_array( $cpt_name, $excluded_cpt, true ) ) {
					$defaults[ $cpt_name . '_test_setting' ] = 'test';
				}
			}

			self::$defaults = apply_filters( 'themesetup_filter_defaults', $defaults );

		}

		return self::$defaults;
	}

	/**
	 * Gets default for a setting
	 *
	 * @param string $setting
	 * @return string|null
	 */
	public function get_default( $setting ) {
		// $defaults = self::$defaults;
		$defaults = self::get_defaults();
		$value    = ( isset( $defaults[ $setting ] ) && '' !== $defaults[ $setting ] ) ? $defaults[ $setting ] : null;
		return $value;
	}

	/**
	 * Get a setting value
	 *
	 * @param string $setting
	 * @return mixed
	 */
	public function get_setting( $setting ) {
		// $defaults = self::$defaults;
		$defaults = self::get_defaults();
		$value = get_theme_mod( $setting, null );

		if ( is_null( $value ) || ( isset( $value ) && '' === $value ) ) {
			$value = ( isset( $defaults[ $setting ] ) && '' !== $defaults[ $setting ] ) ? $defaults[ $setting ] : null;
		}

		// Fallback to an empty string.
		if ( is_null( $value ) ) {
			$value = '';
		}

		return $value;
	}
}
