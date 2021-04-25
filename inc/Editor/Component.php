<?php
/**
 * Themesetup\Editor\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Editor;

use Themesetup\Component_Interface;
use function Themesetup\themesetup;

/**
 * Class for integrating with the block editor.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'editor';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_editor_support' ] );
		add_action( 'init', [ $this, 'action_register_block_styles' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'action_enqueue_editor_styles' ] );
	}

	/**
	 * Adds support for various editor features.
	 */
	public function action_add_editor_support() {

		/*
		// Add support for editor styles.
		// @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles.
		add_theme_support( 'editor-styles' );

		 // Enqueue block editor stylesheet.
		 // Automatically transform editor styles by selectively rewriting certain CSS selectors. This also
		 // allows the block editor to leverage editor style in block variation previews.
		 //
		 // @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles.
		add_editor_style( 'public/css/editor.css' );

		// Add support for default slightly more opinionated block styles for the front end.
		// @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#default-block-styles.
		// add_theme_support( 'wp-block-styles' );
		*/

		// Adjust the color of the UI to work on dark backgrounds.
		// @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#dark-backgrounds.
		// add_theme_support( 'dark-editor-style' );

		// Add support for full and wide aligned images.
		add_theme_support( 'align-wide' );

		// Add support for custom line height controls.
		add_theme_support( 'custom-line-height' );

		// Add support for custom units.
		// This was removed in WordPress 5.6 but is still required to properly support WP 5.5.
		add_theme_support( 'custom-units' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );

		// Default editor color palette.
		$black      = '#000000';
		$dark_gray  = '#28303D';
		$gray       = '#39414D';
		$light_gray = '#f0f0f0';
		$green      = '#D1E4DD';
		$blue       = '#D1DFE4';
		$purple     = '#D1D1E4';
		$red        = '#E4D1D1';
		$orange     = '#E4DAD1';
		$yellow     = '#EEEADD';
		$white      = '#FFFFFF';

		$editor_color_palette = [
			[
				'name'  => esc_html__( 'Black', 'themesetup' ),
				'slug'  => 'black',
				'color' => $black,
			],
			[
				'name'  => esc_html__( 'Dark gray', 'themesetup' ),
				'slug'  => 'dark-gray',
				'color' => $dark_gray,
			],
			[
				'name'  => esc_html__( 'Gray', 'themesetup' ),
				'slug'  => 'gray',
				'color' => $gray,
			],
			[
				'name'  => esc_html__( 'Light gray', 'themesetup' ),
				'slug'  => 'light-gray',
				'color' => $light_gray,
			],
			[
				'name'  => esc_html__( 'Green', 'themesetup' ),
				'slug'  => 'green',
				'color' => $green,
			],
			[
				'name'  => esc_html__( 'Blue', 'themesetup' ),
				'slug'  => 'blue',
				'color' => $blue,
			],
			[
				'name'  => esc_html__( 'Purple', 'themesetup' ),
				'slug'  => 'purple',
				'color' => $purple,
			],
			[
				'name'  => esc_html__( 'Red', 'themesetup' ),
				'slug'  => 'red',
				'color' => $red,
			],
			[
				'name'  => esc_html__( 'Orange', 'themesetup' ),
				'slug'  => 'orange',
				'color' => $orange,
			],
			[
				'name'  => esc_html__( 'Yellow', 'themesetup' ),
				'slug'  => 'yellow',
				'color' => $yellow,
			],
			[
				'name'  => esc_html__( 'White', 'themesetup' ),
				'slug'  => 'white',
				'color' => $white,
			],
		];

		/**
		 * Filters the list of custom theme colors to make available in the block editor.
		 *
		 * @param array $editor_color_palette List of custom theme color data sets.
		 */
		$editor_color_palette = apply_filters( 'themesetup_editor_color_palette', $editor_color_palette );

		/**
		 * Add support for color palettes.
		 *
		 * To preserve color behavior across themes, use these naming conventions:
		 * - Use primary and secondary color for main variations.
		 * - Use `theme-[color-name]` naming standard for standard colors (red, blue, etc).
		 * - Use `custom-[color-name]` for non-standard colors.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-colors' );
		 */
		add_theme_support(
			'editor-color-palette',
			$editor_color_palette
		);

		$editor_color_gradients = [
			[
				'name'     => esc_html__( 'Black to gray', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $black . ' 0%, ' . $gray . ' 100%)',
				'slug'     => 'black-to-gray',
			],
			[
				'name'     => esc_html__( 'Purple to yellow', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $purple . ' 0%, ' . $yellow . ' 100%)',
				'slug'     => 'purple-to-yellow',
			],
			[
				'name'     => esc_html__( 'Yellow to purple', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $purple . ' 100%)',
				'slug'     => 'yellow-to-purple',
			],
			[
				'name'     => esc_html__( 'Green to yellow', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $green . ' 0%, ' . $yellow . ' 100%)',
				'slug'     => 'green-to-yellow',
			],
			[
				'name'     => esc_html__( 'Yellow to green', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $green . ' 100%)',
				'slug'     => 'yellow-to-green',
			],
			[
				'name'     => esc_html__( 'Red to yellow', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $red . ' 0%, ' . $yellow . ' 100%)',
				'slug'     => 'red-to-yellow',
			],
			[
				'name'     => esc_html__( 'Yellow to red', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $red . ' 100%)',
				'slug'     => 'yellow-to-red',
			],
			[
				'name'     => esc_html__( 'Purple to red', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $purple . ' 0%, ' . $red . ' 100%)',
				'slug'     => 'purple-to-red',
			],
			[
				'name'     => esc_html__( 'Red to purple', 'themesetup' ),
				'gradient' => 'linear-gradient(160deg, ' . $red . ' 0%, ' . $purple . ' 100%)',
				'slug'     => 'red-to-purple',
			],
		];

		/**
		 * Filters the list of theme color gradients to make available in the block editor.
		 *
		 * @param array $editor_color_gradients List of custom theme color gradients data sets.
		 */
		$editor_color_gradients = apply_filters( 'themesetup_editor_color_gradients', $editor_color_gradients );

		/**
		 * Overwrite default gradient presets.
		 *
		 * @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-gradient-presets.
		 */
		add_theme_support(
			'editor-gradient-presets',
			$editor_color_gradients
		);

		/*
		 * Add support custom font sizes.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-font-sizes' );
		 */
		$base_font_size = 18; // Must match --global-fs-base
		add_theme_support(
			'editor-font-sizes',
			[
				[
					'name'      => esc_html_x( 'Small', 'Name of the small font size in Gutenberg', 'themesetup' ),
					'shortName' => esc_html_x( 'S', 'Short name of the small font size in the Gutenberg editor.', 'themesetup' ),
					'size'      => round( $base_font_size * .882 ),
					'slug'      => 'small',
				],
				[
					'name'      => esc_html_x( 'Regular', 'Name of the regular font size in Gutenberg', 'themesetup' ),
					'shortName' => esc_html_x( 'M', 'Short name of the regular font size in the Gutenberg editor.', 'themesetup' ),
					'size'      => $base_font_size,
					'slug'      => 'normal',
				],
				[
					'name'      => esc_html_x( 'Large', 'Name of the large font size in Gutenberg', 'themesetup' ),
					'shortName' => esc_html_x( 'L', 'Short name of the large font size in the Gutenberg editor.', 'themesetup' ),
					'size'      => round( $base_font_size * 1.176 ),
					'slug'      => 'large',
				],
				[
					'name'      => esc_html_x( 'Larger', 'Name of the larger font size in Gutenberg', 'themesetup' ),
					'shortName' => esc_html_x( 'XL', 'Short name of the larger font size in the Gutenberg editor.', 'themesetup' ),
					'size'      => round( $base_font_size * 1.294 ),
					'slug'      => 'larger',
				],

				// [
				// 	'name'      => esc_html__( 'Extra small', 'themesetup' ),
				// 	'shortName' => esc_html_x( 'XS', 'Font size', 'themesetup' ),
				// 	'size'      => 14,
				// 	'slug'      => 'extra-small',
				// ],
				// [
				// 	'name'      => esc_html__( 'Small', 'themesetup' ),
				// 	'shortName' => esc_html_x( 'S', 'Font size', 'themesetup' ),
				// 	'size'      => 16,
				// 	'slug'      => 'small',
				// ],
				// [
				// 	'name'      => esc_html__( 'Medium', 'themesetup' ),
				// 	'shortName' => esc_html_x( 'M', 'Font size', 'themesetup' ),
				// 	'size'      => 18,
				// 	'slug'      => 'medium',
				// ],
				// [
				// 	'name'      => esc_html__( 'Large', 'themesetup' ),
				// 	'shortName' => esc_html_x( 'L', 'Font size', 'themesetup' ),
				// 	'size'      => 20,
				// 	'slug'      => 'large',
				// ],
				// [
				// 	'name'      => esc_html__( 'Extra large', 'themesetup' ),
				// 	'shortName' => esc_html_x( 'XL', 'Font size', 'themesetup' ),
				// 	'size'      => 24,
				// 	'slug'      => 'extra-large',
				// ],
				// [
				// 	'name'      => esc_html__( 'Huge', 'themesetup' ),
				// 	'shortName' => esc_html_x( 'XXL', 'Font size', 'themesetup' ),
				// 	'size'      => 40,
				// 	'slug'      => 'huge',
				// ],
				// [
				// 	'name'      => esc_html__( 'Gigantic', 'themesetup' ),
				// 	'shortName' => esc_html_x( 'XXXL', 'Font size', 'themesetup' ),
				// 	'size'      => 96,
				// 	'slug'      => 'gigantic',
				// ],
			]
		);
	}

	/**
	 * Register block styles.
	 */
	public function action_register_block_styles() {

		// Columns: Overlap.
		register_block_style(
			'core/columns',
			[
				'name'  => 'themesetup-columns-overlap',
				'label' => esc_html__( 'Overlap', 'themesetup' ),
			]
		);

		// Latest posts: Card
		register_block_style(
			'core/latest-posts',
			[
				'name'  => 'themesetup-latest-posts-card',
				'label' => esc_html__( 'Card', 'themesetup' ),
			]
		);

		// Media-text: Overlap gradient
		register_block_style(
			'core/media-text',
			[
				'name'  => 'themesetup-media-text-overlap-gradient',
				'label' => esc_html__( 'Overlap gradient', 'themesetup' ),
			]
		);

		// Social-links: Logo only monochrome
		register_block_style(
			'core/social-links',
			[
				'name'  => 'logos-only-monochrome',
				'label' => esc_html__( 'Logos Only Monochrome', 'themesetup' ),
			]
		);
	}

	/**
	 * Enqueue scripts for the editor.
	 */
	public function action_enqueue_editor_styles() {

		// Enqueue editor styles.
		$editor_css_uri = get_theme_file_uri( '/public/css/editor.css' );
		$editor_css_dir = get_theme_file_path( '/public/css/editor.css' );
		wp_enqueue_style(
			'themesetup-editor',
			$editor_css_uri,
			[],
			themesetup()->get_asset_version( $editor_css_dir )
		);
	}
}
