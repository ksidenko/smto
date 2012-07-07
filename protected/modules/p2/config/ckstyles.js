/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.addStylesSet( 'default',
[
	/* Block Styles */

	// These styles are already available in the "Format" combo, so they are
	// not needed here by default. You may enable them to avoid placing the
	// "Format" combo in the toolbar, maintaining the same features.
	/*{ name : 'Blue Title'		, element : 'h3', styles : { 'color' : 'Blue' } },
	{ name : 'Red Title'		, element : 'h3', styles : { 'color' : 'Red' } },
	*/

	/* Inline Styles */

	// These are core styles available as toolbar buttons. You may opt enabling
	// some of them in the Styles combo, removing them from the toolbar.
	/*
	{ name : 'Strong'			, element : 'strong', overrides : 'b' },
	{ name : 'Emphasis'			, element : 'em'	, overrides : 'i' },
	{ name : 'Underline'		, element : 'u' },
	{ name : 'Strikethrough'	, element : 'strike' },
	{ name : 'Subscript'		, element : 'sub' },
	{ name : 'Superscript'		, element : 'sup' },
	*/

/*	{ name : 'Marker: Yellow'	, element : 'span', styles : { 'background-color' : 'Yellow' } },
	{ name : 'Marker: Green'	, element : 'span', styles : { 'background-color' : 'Lime' } },
*/
	{ name : 'Big'				, element : 'big' },
	{ name : 'Small'			, element : 'small' },
	{ name : 'Typewriter'		, element : 'tt' },

	{ name : 'Title'		    , class : 'title' },

/*	{ name : 'Computer Code'	, element : 'code' },
	{ name : 'Keyboard Phrase'	, element : 'kbd' },
	{ name : 'Sample Text'		, element : 'samp' },
	{ name : 'Variable'			, element : 'var' },

	{ name : 'Deleted Text'		, element : 'del' },
	{ name : 'Inserted Text'	, element : 'ins' },

	{ name : 'Cited Work'		, element : 'cite' },
	{ name : 'Inline Quotation'	, element : 'q' },*/

	{ name : 'Language: RTL'	, element : 'span', attributes : { 'dir' : 'rtl' } },
	{ name : 'Language: LTR'	, element : 'span', attributes : { 'dir' : 'ltr' } },


	{
		name : 'Two Columns',
		element : 'table',
		attributes :
		{
			'style' : '',
                        'class' : 'twoColumns',
			'border' : '0'
		}
	},

        /* Object Styles */
        /* we strongly recommend to use CSS classes only! */

	{
		name : 'Image on Left',
		element : 'img',
		attributes :
		{
			'style' : '',
                        'class' : 'imageOnLeft',
			'border' : '0',
                        'vspace' : '0',
                        'hspace' : '0',
			'align' : ''
		}
	},

	{
		name : 'Image on Right',
		element : 'img',
		attributes :
		{
			'style' : '',
                        'class' : 'imageOnRight',
			'border' : '0',
                        'vspace' : '0',
                        'hspace' : '0',
			'align' : ''
		}
	},

]);
