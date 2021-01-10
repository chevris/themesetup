/**
 * Theme Export Script
 *
 * Exports the production-ready theme with only the files and folders needed for
 * uploading to a site or zipping. Edit the `files` or `folders` variables need to
 * be ajust.
 *
 * @package Themesetup
 */


const mix = require( 'laravel-mix' );
const rimraf = require( 'rimraf' );
const fs = require( 'fs' ); // import File System module from Node js

// The folder name to export the files to.
let exportPath = 'themesetup';

// Root-level files to include.
let files = [
	'style.css',
	'CHANGELOG.md',
	'license.md',
	'README.md',
	'screenshot.png',

	'functions.php',
	'index.php'
];

// Folders to include.
let folders = [
	'inc',
	'languages',
	'packages',
	'public',
	'resources',
	'vendor'
];

// Delete the previous export to start clean.
rimraf.sync( exportPath );

// Loop through the root files and copy them over.
files.forEach( file => {

	if ( fs.existsSync( file ) ) {
		mix.copy( file, `${exportPath}/${file}` );
	}
});

// Loop through the folders and copy them over.
folders.forEach( folder => {

	if ( fs.existsSync( folder ) ) {
		mix.copyDirectory( folder, `${exportPath}/${folder}` );
	}
});

// Delete the `vendor/bin` and `vendor/composer/installers` folder, which can
// get left over, even in production. Mix will also create an additional
// `mix-manifest.json` file in the root, which we don't need.
mix.then( () => {

	let files = [
		'mix-manifest.json',
		`${exportPath}/vendor/bin`,
		`${exportPath}/vendor/composer/installers`
	];

	files.forEach( file => {
		if ( fs.existsSync( file ) ) {
			rimraf.sync( file );
		}
	});
});
