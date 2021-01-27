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

