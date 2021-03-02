<?php
/**
 * Themesetup\Customizer\Register_Settings class
 *
 * @package themesetup
 */

declare( strict_types=1 );

namespace Themesetup\Customizer;

use Themesetup\Customizer\Controls\Range;
use Themesetup\Customizer\Controls\Title;
use Themesetup\Customizer\Controls\Toggle;
use Themesetup\Customizer\Controls\Presets;
use Themesetup\Customizer\Controls\Focus_Button;
use Themesetup\Customizer\Controls\Icon_Checkbox;
use Themesetup\Customizer\Controls\Expanded_Section;
use Themesetup\Customizer\Controls\Responsive_Range;

/**
 * Class for managing Customizer settings.
 */
class Register_Settings {

	/**
	 * WP_Customize object
	 *
	 * @var WP_Customize_Manager $wp_customize object
	 */
	protected $customizer;

	/**
	 * Panels to register.
	 *
	 * @var array Panels that will be registered.
	 */
	public static $panels = [];

	/**
	 * Sections to register.
	 *
	 * @var array Sections that will be registered.
	 */
	public static $sections = [];

	/**
	 * Settings to register.
	 *
	 * @var array Settings that will be registered.
	 */
	public static $settings = [];

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {

		add_action( 'customize_register', [ $this, 'action_customize_register' ] );

	}

	/**
	 * Register controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( $wp_customize ) {
		$this->customizer = $wp_customize;
		$this->create_settings_array();
		$this->register_controls();
		$this->register_panels();
		$this->register_sections();
		$this->register_settings();

	}

	/**
	 * Set settings array.
	 */
	public function create_settings_array() {
		require_once get_template_directory() . '/inc/Customizer/Settings/example-settings.php';

		require_once get_template_directory() . '/inc/Customizer/Settings/global-settings.php';
	}

	/**
	 * Register control types.
	 */
	public function register_controls() {
		$this->customizer->register_control_type( Title::class );
		$this->customizer->register_section_type( Expanded_Section::class );
		$this->customizer->register_control_type( Presets::class );
		$this->customizer->register_control_type( Toggle::class );
		$this->customizer->register_control_type( Range::class );
		$this->customizer->register_control_type( Responsive_Range::class );
		$this->customizer->register_control_type( Focus_Button::class );
		$this->customizer->register_control_type( Icon_Checkbox::class );
	}

	/**
	 * Register panels.
	 */
	private function register_panels() {

		$panels = self::$panels;
		foreach ( $panels as $panel_key => $panel ) {
			$this->customizer->add_panel( $panel_key, $panel );
		}

	}

	/**
	 * Register sections.
	 */
	private function register_sections() {

		$sections = self::$sections;
		foreach ( $sections as $section_key => $section ) {
			if ( isset( $section['custom_section'] ) && ! empty( $section['custom_section'] ) ) {
				$section_class = 'Themesetup\Customizer\Controls\\' . $section['custom_section'];
				$this->customizer->add_section( new $section_class( $this->customizer, $section_key, $section['section_args'] ) );
			} else {
				$this->customizer->add_section( $section_key, $section['section_args'] );
			}
		}

	}

	/**
	 * Register settings.
	 */
	private function register_settings() {

		$settings = self::$settings;
		foreach ( $settings as $setting_key => $setting ) {

			// Add setting.
			$this->customizer->add_setting( $setting_key, $setting['setting_args'] );

			// Add control.
			if ( isset( $setting['custom_control'] ) && ! empty( $setting['custom_control'] ) ) {
				$control_class = 'Themesetup\Customizer\Controls\\' . $setting['custom_control'];
				$this->customizer->add_control( new $control_class( $this->customizer, $setting_key, $setting['control_args'] ) );
			} else {
				$new_control  = $this->customizer->add_control( $setting_key, $setting['control_args'] );
			}

			// Add partial.
			if ( isset( $setting['partial_args'] ) && ! empty( $setting['partial_args'] ) ) {
				if ( isset( $this->customizer->selective_refresh ) ) {
					$this->customizer->selective_refresh->add_partial(
						$setting_key,
						$setting['partial_args']
					);
				}
			}
		}

	}

	/**
	 * Adds panels to panels array.
	 *
	 * @param array $new_panels an array of panels.
	 */
	public static function add_panels( $new_panels ) {
		self::$panels = array_merge( $new_panels, self::$panels );
	}

	/**
	 * Adds sections to settings array.
	 *
	 * @param array $new_sections an array of settings.
	 */
	public static function add_sections( $new_sections ) {
		self::$sections = array_merge( $new_sections, self::$sections );
	}

	/**
	 * Adds settings to settings array.
	 *
	 * @param array $new_settings an array of settings.
	 */
	public static function add_settings( $new_settings ) {
		self::$settings = array_merge( $new_settings, self::$settings );
	}

}
