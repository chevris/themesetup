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
			'panel_args' => [
				'title'    => esc_html__( 'Global Settings', 'themesetup' ),
				'description' => esc_html__( 'Site wide settings.', 'themesetup' ),
				'priority' => 300,
			],
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
		// Date Format.
		'themesetup_date_format_title' => [
			'setting_args' => [
				'transport' => 'postMessage',
			],
			'control_args' => [
				'label'    => esc_html( 'Date Format', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 10,
			],
			'custom_control' => 'Title',
		],

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
				'priority' => 10,
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
				'priority' => 10,
				'active_callback' => function() {
					return themesetup()->get_setting( 'themesetup_date_format_time_ago_activate' ) === true;
				},
			],
		],

		// Categories.
		'themesetup_categories_title' => [
			'setting_args' => [
				'transport' => 'postMessage',
			],
			'control_args' => [
				'label'    => esc_html( 'Categories', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 20,
			],
			'custom_control' => 'Title',
		],

		'themesetup_yoast_primary_category_display' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'themesetup_yoast_primary_category_display' ),
				'sanitize_callback' => [ themesetup(), 'sanitize_checkbox' ],
				'transport'         => 'refresh',
			],
			'control_args' => [
				'type'     => 'checkbox',
				'label'    => esc_html__( 'Display the Yoast primary category (Yoast plugin required).', 'themesetup' ),
				'description' => themesetup()->get_control_details(
					[
						'label'   => esc_html__( 'Details', 'themesetup' ),
						'details' => __( '<p>If Yoast plugin is active, the most important category can be selected for each post. If selected, only this category will be displayed and used in the breadcrumb.</p>', 'themesetup' ),
					]
				),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 20,
			],
		],

		'themesetup_yoast_primary_category_not_single' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'themesetup_yoast_primary_category_not_single' ),
				'sanitize_callback' => [ themesetup(), 'sanitize_checkbox' ],
				'transport'         => 'refresh',
			],
			'control_args' => [
				'type'     => 'checkbox',
				'label'    => esc_html__( 'Display all categories on individual blog posts only.', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 20,
				'active_callback' => function() {
					return themesetup()->get_setting( 'themesetup_yoast_primary_category_display' ) === true;
				},
			],
		],

		// Reading Time.
		'themesetup_reading_time_title' => [
			'setting_args' => [
				'transport' => 'postMessage',
			],
			'control_args' => [
				'label'    => esc_html( 'Reading Time', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 30,
			],
			'custom_control' => 'Title',
		],

		'themesetup_reading_time_words' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'themesetup_reading_time_words' ),
				'sanitize_callback' => 'absint',
				'transport'         => 'refresh',
			],
			'control_args' => [
				'type'     => 'number',
				'label'    => esc_html__( 'Words to read per minute', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 30,
			],
		],

		'themesetup_reading_time_text_before' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'themesetup_reading_time_text_before' ),
				'sanitize_callback' => 'wp_filter_nohtml_kses',
				'transport'         => 'refresh',
			],
			'control_args' => [
				'type'     => 'text',
				'label'    => esc_html__( 'Text before the number of minute read', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 30,
			],
		],

		'themesetup_reading_time_text_after' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'themesetup_reading_time_text_after' ),
				'sanitize_callback' => 'wp_filter_nohtml_kses',
				'transport'         => 'refresh',
			],
			'control_args' => [
				'type'     => 'text',
				'label'    => esc_html__( 'Text after the number of minute read', 'themesetup' ),
				'section'  => 'themesetup_global_reading_section',
				'priority' => 30,
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
