<?php
/**
 * Themesetup\Customizer\Settings\Settings_Base class
 *
 * @package themesetup
 */

declare( strict_types=1 );

namespace Themesetup\Customizer\Settings;

use WP_Customize_Manager;
use Themesetup\Customizer\Settings\Panel;
use Themesetup\Customizer\Settings\Section;
use Themesetup\Customizer\Settings\Setting;
use Themesetup\Customizer\Settings\Partial;

/**
 * Parent class for a setting module.
 */
abstract class Settings_Base {

	/**
	 * WP_Customize object
	 *
	 * @var WP_Customize_Manager $wp_customize object
	 */
	protected $customizer;

	/**
	 * Selective refresh.
	 *
	 * @var string transport or postMessage
	 */
	protected $selective_refresh;

	/**
	 * Panels to register
	 *
	 * @var array  Panels that will be registered.
	 */
	private $panels_to_register = [];

	/**
	 * Sections to register
	 *
	 * @var array  Controls that will be registered.
	 */
	private $sections_to_register = [];

	/**
	 * Controls to register.
	 *
	 * @var array  Controls that will be registered.
	 */
	private $settings_to_register = [];

	/**
	 * Partials to register.
	 *
	 * @var array Partials that will be registered.
	 */
	private $partials_to_register = [];

	/**
	 * Initialization.
	 */
	public function initialize() {
		add_action( 'customize_register', [ $this, 'action_customize_register' ] );
	}

	/**
	 * Register controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ) {

		$this->customizer = $wp_customize;
		$this->set_selective_refresh();
		$this->add_settings();
		$this->register_panels();
		$this->register_sections();
		$this->register_settings();
		$this->register_partials();

	}

	/**
	 * Check selective refresh.
	 */
	private function set_selective_refresh() {
		$this->selective_refresh = isset( $this->customizer->selective_refresh ) ? 'postMessage' : 'refresh';
	}

	/**
	 * Function that should be extended to add customizer controls.
	 */
	abstract public function add_settings();

	/**
	 * Add a panel to register.
	 *
	 * @param Panel $panel panel to add.
	 */
	public function add_panel( Panel $panel ) {
		array_push( $this->panels_to_register, $panel );

	}

	/**
	 * Add a section to register.
	 *
	 * @param Section $section section to add.
	 */
	public function add_section( Section $section ) {
		array_push( $this->sections_to_register, $section );
	}

	/**
	 * Add a setting to register.
	 *
	 * @param Setting $setting setting to add.
	 */
	public function add_setting( Setting $setting ) {
		array_push( $this->settings_to_register, $setting );
	}

	/**
	 * Add a partial to register.
	 *
	 * @param Partial $partial partial to add.
	 */
	public function add_partial( Partial $partial ) {
		if ( empty( $partial->args ) ) {
			return;
		}
		array_push( $this->partials_to_register, $partial );
	}

	/**
	 * Register all the defined panels.
	 */
	private function register_panels() {
		$panels = $this->panels_to_register;
		foreach ( $panels as $panel ) {
			$this->customizer->add_panel( $panel->id, $panel->args );
		}
	}

	/**
	 * Register all the defined sections.
	 */
	private function register_sections() {
		$sections = $this->sections_to_register;
		foreach ( $sections as $section ) {
			if ( null !== $section->custom_section ) {
				$this->customizer->add_section( new $section->custom_section( $this->customizer, $section->id, $section->args ) );
			} else {
				$this->customizer->add_section( $section->id, $section->args );
			}
		}
	}

	/**
	 * Register all the defined settings.
	 */
	private function register_settings() {
		$settings = $this->settings_to_register;
		foreach ( $settings as $setting ) {
			$this->customizer->add_setting( $setting->id, $setting->setting_args );
			$control_type = null;
			if ( null !== $setting->custom_control ) {
				$this->customizer->add_control( new $setting->custom_control( $this->customizer, $setting->id, $setting->control_args ) );
				$control_type = $setting->custom_control;
			} else {
				$new_control  = $this->customizer->add_control( $setting->id, $setting->control_args );
				$control_type = isset( $setting->control_args['type'] ) ? $setting->control_args['type'] : $new_control->type;
			}
			if ( isset( $setting->control_args['live_refresh_selector'] ) ) {
				$control_args = [
					'selector' => $setting->control_args['live_refresh_selector'],
					'id'       => $setting->id,
					'type'     => $control_type,
				];
				add_filter(
					'themesetup-customizer-preview',
					function ( $array ) use ( $control_args ) {
						if ( ! isset( $array[ $control_args['type'] ] ) ) {
							$array[ $control_args['type'] ] = [];
						}
						$array[ $control_args['type'] ][ $control_args['id'] ] = [ 'selector' => $control_args['selector'] ];

						return $array;
					}
				);
			}
			if ( isset( $setting->partial ) ) {
				$this->add_partial( new Partial( $setting->id, $setting->partial ) );
			}
		}
	}

	/**
	 * Register all the defined partials.
	 */
	private function register_partials() {
		$partials = $this->partials_to_register;
		foreach ( $partials as $partial ) {
			if ( empty( $partial ) ) {
				continue;
			}
			$this->customizer->selective_refresh->add_partial( $partial->id, $partial->args );
		}
	}

}
