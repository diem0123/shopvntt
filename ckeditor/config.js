/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:	 
	 config.allowedContent = true;
	 config.removeFormatAttributes = '';	 
	 config.language = 'vi';
	// config.uiColor = '#AADC6E';
	config.width = '800px';
	config.height = '350px';	
	config.toolbar = [	

{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },

{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
                               '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
							   
{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },		

{ name: 'styles', items : [ 'Format','Font','FontSize' ] },
 
{ name: 'colors', items : [ 'TextColor','BGColor' ] },

{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },

{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar','Iframe' ] },

'/',					   

{ name: 'document', items : [ 'NewPage','DocProps','Preview','Print','-','Templates' ] },
 
{ name: 'editing', items : [ 'Find','Replace','-','SelectAll' ] },  
 
{ name: 'tools', items : [ 'Maximize', 'ShowBlocks', 'Source' ] } 
	    
	];
};
