/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.


/*	config.skin = 'office2013'; '/',
	config.skin = 'myskin,/customstuff/myskin/';*/

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.


	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	//HEIGHT
	config.height = 100;
	config.height = '25em';
	config.height = '100px';

};
