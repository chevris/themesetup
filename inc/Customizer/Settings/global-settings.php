<?php
/**
 * Example settings
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Settings;

use function Themesetup\themesetup;
use Themesetup\Customizer\Register_Settings;

Register_Settings::add_panels(
	[
		'themesetup_global_panel' => [
			'title'    => esc_html__( 'Global', 'themesetup' ),
			'priority' => 300,
		],
	]
);

/**
 * Performance section.
 */

Register_Settings::add_sections(
	[
		'themesetup_global_performance_section' => [
			'section_args' => [
				'title'    => esc_html__( 'Performance', 'themesetup' ),
				'priority' => 1,
				'panel'    => 'themesetup_global_panel',
			],
		],
	]
);

Register_Settings::add_settings(
	[
		'themesetup_preload_style' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'themesetup_preload_style' ),
				'sanitize_callback' => [ themesetup(), 'sanitize_checkbox' ],
				'transport'       => 'postMessage',
			],
			'control_args' => [
				'type'     => 'themesetup_toggle_control',
				'label'    => esc_html__( 'Preload Styles', 'themesetup' ),
				'section'  => 'themesetup_global_performance_section',
				'priority' => 10,
				'active_callback' => function() {
					return ! themesetup()->is_amp();
				},
			],
		],
	]
);
