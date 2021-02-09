<?php
/**
 * Themesetup\Custom_Logo\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Custom_Logo;

use Themesetup\Component_Interface;
use WP_Customize_Manager;

/**
 * Class for adding custom logo support.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'custom_logo';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', [ $this, 'action_customize_register' ] );
		add_filter( 'themesetup_filter_customizer_pane_inline_style', [ $this, 'filter_customizer_pane_inline_style' ] );
		add_action( 'after_setup_theme', [ $this, 'action_add_custom_logo_support' ] );
		add_filter( 'get_custom_logo', [ $this, 'filter_get_custom_logo' ] );
	}

	/**
	 * Adds Customizer settings and controls for modifying the custom logo.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ) {

		$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';

		$wp_customize->add_setting(
			'themesetup_logo_size',
			[
				'default'              => 50,
				'theme_supports'       => 'custom-logo',
				'transport'            => 'refresh', // @todo Add transport postMessage or add partial refresh
				'sanitize_callback'    => 'absint',
				'sanitize_js_callback' => 'absint',
			]
		);

		$wp_customize->add_control(
			'themesetup_logo_size',
			[
				'label'       => esc_html__( 'Logo Size', 'themesetup' ),
				'section'     => 'title_tagline',
				'priority'    => 8,
				'type'        => 'range',
				'settings'    => 'themesetup_logo_size',
				'input_attrs' => [
					'step'             => 5,
					'min'              => 0,
					'max'              => 100,
					'aria-valuemin'    => 0,
					'aria-valuemax'    => 100,
					'aria-valuenow'    => 50,
					'aria-orientation' => 'horizontal',
				],
			]
		);

	}

	/**
	 * Add inline style to customizer pane.
	 *
	 * @param string $style The additional inline style.
	 */
	public function filter_customizer_pane_inline_style( $style ) {

		$style .= '#customize-control-themesetup_logo_size input[type="range"] { width: 100%; }';

		return $style;

	}

	/**
	 * Adds support for the Custom Logo feature.
	 */
	public function action_add_custom_logo_support() {
		add_theme_support(
			'custom-logo',
			apply_filters(
				'themesetup_custom_logo_args',
				[
					'flex-width'  => true,
					'flex-height' => true,
				]
			)
		);
	}

	/**
	 * Add support for logo resizing by filtering `get_custom_logo`.
	 *
	 * @param string $html The custom logo HTML.
	 * @return string Filtered custom logo HTML.
	 */
	public function filter_get_custom_logo( $html ) {

		$size           = get_theme_mod( 'themesetup_logo_size', 50 );
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		// set the short side minimum.
		$min = 48;

		// don't use empty() because we can still use a 0.
		if ( is_numeric( $size ) && is_numeric( $custom_logo_id ) ) {

			// we're looking for $img['width'] and $img['height'] of original image.
			$logo = wp_get_attachment_metadata( $custom_logo_id );
			if ( ! $logo ) {
				return $html;
			}

			// get the logo support size.
			$sizes = get_theme_support( 'custom-logo' );

			$logo_max_width = ( $logo['width'] > 600 ) ? 600 : $logo['width'];

			// Check for max height and width, default to image sizes if none set in theme.
			$max = [];
			$max['height'] = isset( $sizes[0]['height'] ) ? $sizes[0]['height'] : $logo['height'];
			$max['width']  = isset( $sizes[0]['width'] ) ? $sizes[0]['width'] : $logo_max_width;

			// landscape or square.
			if ( $logo['width'] >= $logo['height'] ) {
				$output = $this->logo_resize_min_max(
					$logo['height'],
					$logo['width'],
					$max['height'],
					$max['width'],
					$size,
					$min
				);
				$img = [
					'height' => $output['short'],
					'width'  => $output['long'],
				];
				// portrait.
			} elseif ( $logo['width'] < $logo['height'] ) {
				$output = $this->logo_resize_min_max( $logo['width'], $logo['height'], $max['width'], $max['height'], $size, $min );
				$img    = [
					'height' => $output['long'],
					'width'  => $output['short'],
				];
			}

			$mobile_max_width  = 175;
			$mobile_max_height = 65;

			$mobile = $this->logo_small_sizes( $img['width'], $img['height'], $mobile_max_width, $mobile_max_height );

			// add the CSS.
			$css = '
			<style>
			.site-header .custom-logo {
				height: ' . $img['height'] . 'px;
				max-height: ' . $max['height'] . 'px;
				max-width: ' . $max['width'] . 'px;
				width: ' . $img['width'] . 'px;
			}

			@media (max-width: 781px) {
				.site-header .custom-logo {
					max-width: ' . $mobile['width'] . 'px;
					max-height: ' . $mobile['height'] . 'px;
				}
			}
			</style>';

			$html = $css . $html;
		}

		return $html;

	}

	/**
	 * Helper function to determine the max size of the logo.
	 *
	 * @param int $short $short.
	 * @param int $long $long.
	 * @param int $short_max $short_max.
	 * @param int $long_max $long_max.
	 * @param int $percent $percent.
	 * @param int $min $min.
	 * @return array $size
	 */
	protected function logo_resize_min_max( $short, $long, $short_max, $long_max, $percent, $min ) {
		$ratio        = ( $long / $short );
		$max          = [];
		$max['long']  = ( $long_max >= $long ) ? $long : $long_max;
		$max['short'] = ( $short_max >= ( $max['long'] / $ratio ) ) ? floor( $max['long'] / $ratio ) : $short_max;

		$ppp = ( $max['short'] - $min ) / 100;

		$size          = [];
		$size['short'] = round( $min + ( $percent * $ppp ) );
		$size['long']  = round( $size['short'] / ( $short / $long ) );

		return $size;
	}

	/**
	 * Helper function to return smaller version of the logo size
	 *
	 * @param int $width $width.
	 * @param int $height $height.
	 * @param int $max_width $max_width.
	 * @param int $max_height $max_height.
	 * @return array
	 */
	protected function logo_small_sizes( $width, $height, $max_width, $max_height ) {
		$size = [
			'width'  => round( $max_height * ( $width / $height ) ),
			'height' => $max_height,
		];

		if ( $size['width'] > $max_width ) {
			$size['height'] = round( $max_width * ( $height / $width ) );
			$size['width']  = $max_width;
		}

		return $size;
	}

}
