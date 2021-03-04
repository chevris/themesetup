<?php
/**
 * Sitewide header settings
 *
 * @package themesetup
 */

namespace Themesetup\Customizer\Settings;

use Themesetup\Customizer\Register_Settings;

$sitewide_header_panel = [
	'sitewide_header_panel' => [
		'panel_args' => [
			'title'    => esc_html__( 'Header', 'themesetup' ),
			'priority' => 400,
		],
	],
];

$sitewide_header_sections = [

	'sitewide_header_layout_section' => [
		'section_args' => [
			'title'    => esc_html__( 'Header Layout', 'themesetup' ),
			'priority' => 1,
			'panel'    => 'sitewide_header_panel',
		],
		'custom_section' => 'Expanded_Section',
	],

	'sitewide_header_layout_outer_section' => [
		'section_args' => [
			'title'    => esc_html__( 'Header Layouts', 'themesetup' ),
			'priority' => 0,
			'type'     => 'outer',
			'panel'    => 'sitewide_header_panel',
		],
	],

];

$sitewide_header_layout_controls = [

	'sitewide_header_layout_trigger' => [
		'setting_args' => [
			'transport' => 'postMessage',
		],
		'control_args' => [
			'label'    => 'Appearance',
			'section'  => 'sitewide_header_layout_section',
			'priority' => 10,
			'input_attrs'  => [
				'section' => 'sitewide_header_layout_outer_section',
			],
		],
		'custom_control' => 'Focus_Button',
	],

	'sitewide_header_layout_choices' => [
		'setting_args' => [
			'default'           => 'header-1',
			'sanitize_callback' => function ( $value ) {
				$allowed_values = [ 'header-1', 'header-2' ];
				if ( ! in_array( $value, $allowed_values, true ) ) {
					return 'header-1';
				}
				return esc_html( $value );
			},
			'transport'         => 'postMessage',
		],
		'control_args' => [
			'section'  => 'sitewide_header_layout_outer_section',
			'priority' => 10,
			'choices'  => [
				'header-1' => [
					'name' => __( 'Header 1', 'themesetup' ),
					'image' => get_parent_theme_file_uri( '/public/img/Classic.jpg' ),
				],
				'header-2' => [
					'name' => __( 'Header 2', 'themesetup' ),
					'image' => get_parent_theme_file_uri( '/public/img/Classic.jpg' ),
				],
			],
		],
		'custom_control' => 'Radio_Image',
	],

];

Register_Settings::add_panels( $sitewide_header_panel );
Register_Settings::add_sections( $sitewide_header_sections );
Register_Settings::add_settings( $sitewide_header_layout_controls );
