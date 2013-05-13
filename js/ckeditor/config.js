/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
    config.toolbar = 'Advanced';

    config.toolbar_Advanced =
    [
        ['Cut','Copy','Paste','-','Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['uploader','-','Image','Table','HorizontalRule','-','Source'],
        '/',
        ['Styles','Format'],
        ['Bold','Italic','Strike'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['Link','Unlink'],
        ['Maximize']
    ];

	config.toolbar_Basic =
	[
		['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','Source']
	];

	config.ProcessHTMLEntities = false;
};
