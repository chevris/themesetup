/**
 * Laravel Mix configuration file.
 *
 * @link https://laravel-mix.com/docs/5.0/installation
 * @todo Upgrade to version 6.*
 *
 * @package Themesetup
 */

// Import required packages.
const mix = require( 'laravel-mix' );

/*
 * -----------------------------------------------------------------------------
 * Theme Export Process
 *
 * Configures export process in `webpack.mix.export.js`. Should remain at the
 * top of the file here so that it bails early when the`export` command is run.
 * -----------------------------------------------------------------------------
 */

if ( process.env.export ) {
	const exportTheme = require( './webpack.mix.export.js' );
	return;
}

/*
 * -----------------------------------------------------------------------------
 * Build Process
 *
 * Handles processing, compiling, transpiling, and combining all of the
 * theme's assets into their final location.
 * -----------------------------------------------------------------------------
 */

/*
 * Sets the development path to assets. By default, this is the `/resources`
 * folder in the theme.
 */
const devPath  = 'resources';

/*
 * Sets the path to the generated assets. By default, this is the `/public` folder
 * in the theme. If doing something custom, make sure to change this everywhere.
 */
mix.setPublicPath( 'public' );

/*
 * Set Laravel Mix options.
 */
mix.options( {
	postCss        : [ require( 'postcss-preset-env' )() ],
	processCssUrls : false
} );

/*
 * Builds sources maps for assets.
 */
mix.sourceMaps();

/*
 * Compile JavaScript.
 * syntax: mix.js( `${devPath}/js/[name].js`, 'js' );
 *         mix.react( `${devPath}/js/[name].js`, 'js' );
 */
mix.js( `${devPath}/js/main.js`, 'js' );
mix.js( `${devPath}/js/customizer-preview.js`, 'js' );
mix.js( `${devPath}/js/customizer-pane.js`, 'js' );
mix.react( `${devPath}/js/customizer-controls.js`, 'js' );

/*
 * Compile CSS.
 * syntax: mix.sass( `${devPath}/scss/[name].scss`, 'css' );
 */

// Compile SASS/CSS.
mix.sass( `${devPath}/scss/global.scss`, 'css' );
mix.sass( `${devPath}/scss/in-body/archive-loop.scss`, 'css/in-body' );
mix.sass( `${devPath}/scss/customizer-preview.scss`, 'css' );
mix.sass( `${devPath}/scss/customizer-pane.scss`, 'css' );
mix.sass( `${devPath}/scss/customizer-controls.scss`, 'css' );

/*
 * Copy some assets.
 *
 * @todo Minimize images
 */
mix.copy( `${devPath}/img/*.*`, 'public/img');
