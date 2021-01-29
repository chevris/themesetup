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
		'example_panel' => [
			'title'    => esc_html__( 'Example Panel', 'themesetup' ),
			'priority' => 1000,
		],
	]
);

Register_Settings::add_sections(
	[
		'example_expanded_section' => [
			'section_args' => [
				'title'    => esc_html__( 'Expanded Section', 'themesetup' ),
				'priority' => 1,
				'panel'    => 'example_panel',
			],
			'custom_section' => 'Expanded_Section',
		],
	]
);

Register_Settings::add_settings(
	[
		'example_expanded_setting' => [
			'setting_args' => [
				'default'           => 'option-1',
				'sanitize_callback' => function ( $value ) {
					$allowed_values = [ 'option-1', 'option-2' ];
					if ( ! in_array( $value, $allowed_values, true ) ) {
						return 'option-1';
					}
					return esc_html( $value );
				},
			],
			'control_args' => [
				'type'     => 'select',
				'label'    => esc_html__( 'example_expanded_setting', 'themesetup' ),
				'section'  => 'example_expanded_section',
				'priority' => 10,
				'choices'  => [
					'option-1' => esc_html__( 'Option 1', 'themesetup' ),
					'option-2' => esc_html__( 'Option 2', 'themesetup' ),
				],
			],
		],
	]
);

Register_Settings::add_settings(
	[
		'example_expanded_setting_2' => [
			'setting_args' => [
				'transport' => 'postMessage',
			],
			'control_args' => [
				'label'    => 'Focus outer section',
				'section'  => 'example_expanded_section',
				'priority' => 10,
				'input_attrs'  => [
					'section' => 'example_outer_section',
				],
			],
			'custom_control' => 'Focus_Button',
		],
	]
);

Register_Settings::add_sections(
	[
		'example_outer_section' => [
			'section_args' => [
				'title'    => esc_html__( 'Outer Section', 'themesetup' ),
				'priority' => 0,
				'type'     => 'outer',
				'panel'    => 'example_panel',
			],
		],
	]
);

Register_Settings::add_settings(
	[
		'example_outer_setting' => [
			'setting_args' => [
				'default'           => 'option-1',
				'sanitize_callback' => function ( $value ) {
					$allowed_values = [ 'option-1', 'option-2' ];
					if ( ! in_array( $value, $allowed_values, true ) ) {
						return 'option-1';
					}
					return esc_html( $value );
				},
			],
			'control_args' => [
				'type'     => 'select',
				'label'    => esc_html__( 'example_outer_setting', 'themesetup' ),
				'section'  => 'example_outer_section',
				'priority' => 10,
				'choices'  => [
					'option-1' => esc_html__( 'Option 1', 'themesetup' ),
					'option-2' => esc_html__( 'Option 2', 'themesetup' ),
				],
			],
		],
	]
);

Register_Settings::add_sections(
	[
		'example_section_1' => [
			'section_args' => [
				'title'    => esc_html__( 'Example Section', 'themesetup' ),
				'priority' => 300,
				'panel'    => 'example_panel',
			],
			'custom_section' => '',
		],
	]
);

Register_Settings::add_settings(
	[

		'example_preset' => [
			'setting_args' => [
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			],
			'control_args' => [
				'label'    => esc_html__( 'example_preset', 'themesetup' ),
				'section'  => 'example_section_1',
				'priority' => 10,
				'presets'  => [
					[
						'label' => 'Preset 1',
						'image' => get_parent_theme_file_uri( '/public/img/Classic.jpg' ),
						'setup' => '{"example_setting_1": 70,"example_setting_2": { "mobile": 1000, "tablet": 1500, "desktop": 2000 },"example_setting_3": "option-2"}',
					],
					[
						'label' => 'Preset 2',
						'image' => get_parent_theme_file_uri( '/public/img/Classic.jpg' ),
						'setup' => '{"example_setting_1": 10,"example_setting_2": { "mobile": 10, "tablet": 80, "desktop": 700 },"example_setting_3": "option-1"}',
					],
				],
			],
			'custom_control' => 'Presets',
		],

		'example_setting_1' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'example_setting_1' ),
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			],
			'control_args' => [
				'label'        => esc_html__( 'example_setting_1', 'themesetup' ),
				'section'      => 'example_section_1',
				'priority'     => 10,
				'input_attrs'  => [
					'min'        => 0,
					'max'        => 100,
					'defaultVal' => themesetup()->get_default( 'example_setting_1' ), // For reset.
				],
			],
			'custom_control' => 'Range',
			'partial_args' => [
				'selector'        => '.partial-example-1',
				'render_callback' => function() {
					$example1 = themesetup()->get_setting( 'example_setting_1' );
					var_dump( $example1 );
				},
			],
		],

		'example_setting_2' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'example_setting_2' ),
				'sanitize_callback' => [ themesetup(), 'sanitize_responsive_range' ],
				'transport'         => 'postMessage',
			],
			'control_args' => [
				'label'        => esc_html__( 'example_setting_2', 'themesetup' ),
				'section'      => 'example_section_1',
				'priority'     => 10,
				'input_attrs'  => [
					'min'        => 200,
					'max'        => 2000,
					'units'      => [ 'px' ],
					'defaultVal' => [
						'mobile'  => 748,
						'tablet'  => 992,
						'desktop' => 1170,
					],
				],
			],
			'custom_control' => 'Responsive_Range',
			'partial_args' => [],
		],

		'example_setting_3' => [
			'setting_args' => [
				'default'           => themesetup()->get_default( 'example_setting_3' ),
				'sanitize_callback' => function ( $value ) {
					$allowed_values = [ 'option-1', 'option-2' ];
					if ( ! in_array( $value, $allowed_values, true ) ) {
						return 'option-1';
					}
					return esc_html( $value );
				},
			],
			'control_args' => [
				'type'     => 'select',
				'label'    => esc_html__( 'example_setting_3', 'themesetup' ),
				'section'  => 'example_section_1',
				'priority' => 10,
				'choices'  => [
					'option-1' => esc_html__( 'Option 1', 'themesetup' ),
					'option-2' => esc_html__( 'Option 2', 'themesetup' ),
				],
			],
		],

	]
);

