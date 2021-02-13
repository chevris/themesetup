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
 * Reading section.
 */

Register_Settings::add_sections(
	[
		'themesetup_global_reading_section' => [
			'section_args' => [
				'title'    => esc_html__( 'Reading Settings', 'themesetup' ),
				'priority' => 1,
				'panel'    => 'themesetup_global_panel',
			],
		],
	]
);

Register_Settings::add_settings(
	[
		'themesetup_date_format_title' => [
			'setting_args' => [
				'transport' => 'postMessage',
			],
			'control_args' => [
				'label'    => 'Date Format',
				'section'  => 'themesetup_global_reading_section',
				'priority' => 10,
			],
			'custom_control' => 'Title',
		],
	]
);

Register_Settings::add_settings(
	[
		'themesetup_date_format_time_ago_activate' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'themesetup_date_format_time_ago_activate' ),
				'sanitize_callback' => [ themesetup(), 'sanitize_checkbox' ],
				'transport'         => 'refresh',
			],
			'control_args' => [
				'type'     => 'checkbox',
				'label'    => esc_html__( 'Use time ago date format', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 11,
			],
		],

		'themesetup_date_format_time_ago_days_number' => [
			'setting_args' => [
				'default'           => 14,
				'sanitize_callback' => 'absint',
				'transport'         => 'refresh',
			],
			'control_args' => [
				'type'     => 'number',
				'label'    => esc_html__( 'Only use for (in days):', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 11,
				'active_callback' => function() {
					return themesetup()->get_setting( 'themesetup_date_format_time_ago_activate' ) === true;
				},
			],
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
				'priority' => 2,
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
				'type'     => 'checkbox',
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
