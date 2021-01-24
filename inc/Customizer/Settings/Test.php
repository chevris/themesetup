<?php
/**
 * Themesetup\Customizer\Settings\Test class
 *
 * @package wp_rig
 */

declare( strict_types=1 );

namespace Themesetup\Customizer\Settings;

use function Themesetup\themesetup;
use Themesetup\Customizer\Settings\Section;
use Themesetup\Customizer\Settings\Setting;
use Themesetup\Customizer\Settings\Settings_Base;
use Themesetup\Customizer\Controls\Responsive_Range;

/**
 * Test class
 */
class Test extends Settings_Base {

	/**
	 * Add customizer settings.
	 */
	public function add_settings() {

		$this->add_section(
			new Section(
				'test_section',
				[
					'priority' => 300,
					'title'    => esc_html__( 'test', 'themesetup' ),
				]
			)
		);

		$this->add_setting(
			new Setting(
				'test_setting',
				[
					'default'           => 'option-1',
					'sanitize_callback' => [ $this, 'sanitize_test_setting' ],
				],
				[
					'label'    => esc_html__( 'Select control example', 'themesetup' ),
					'section'  => 'test_section',
					'priority' => 10,
					'type'     => 'select',
					'choices'  => [
						'option-1' => esc_html__( 'Option 1', 'themesetup' ),
						'option-2' => esc_html__( 'Option 2', 'themesetup' ),
					],
				]
			)
		);

		$this->add_setting(
			new Setting(
				'custom_control_test_setting4',
				[
					'default'           => themesetup()->get_default( 'custom_control_test_setting4' ),
					'sanitize_callback' => [ themesetup(), 'sanitize_responsive_range' ],
					'transport'         => $this->selective_refresh,
				],
				[
					'label'       => esc_html__( 'custom control example', 'themesetup' ),
					'section'     => 'test_section',
					'type'        => 'themesetup_responsive_range_control',
					'priority'    => 10,
					'input_attrs' => [
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
				Responsive_Range::class,
				[
					'selector'        => '.partial-test',
					'render_callback' => function() {
						$test = themesetup()->get_setting( 'custom_control_test_setting4' );
						$decoded_test = json_decode( $test, true );
						var_dump( $decoded_test );
					},
				]
			)
		);

	}

	/**
	 * Sanitize test-setting
	 *
	 * @param string $value value from the control.
	 *
	 * @return bool
	 */
	public function sanitize_test_setting( $value ) {
		$allowed_values = [ 'option-1', 'option-2' ];
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'option-1';
		}

		return esc_html( $value );
	}

}
