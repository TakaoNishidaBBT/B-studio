/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeInlineEditorInit);

	CKEDITOR.disableAutoInline = true;

	function bframeInlineEditorInit(){
	    var div = document.getElementsByTagName('div');

	    for(var i=0; i<div.length; i++) {
			if(bframe.checkClassName('bframe_inlineeditor', div[i])) {
				var s = new bframe.inlineeditor(div[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.inlineeditor
	// 
	// -------------------------------------------------------------------------
	bframe.inlineeditor = function(target) {
		var self = target;
		var editor;
		var param = target.getAttribute('bframe_inlineeditor_param');

		if(param) {
			var filebrowser = bframe.getParam('filebrowser', param);
		}

		setUpCKEditor();

		function setUpCKEditor() {

			CKEDITOR.config.toolbar = [
			 ['Undo','Redo','-','Bold','Italic','Underline','Strike','-','Subscript','Superscript']
			,['Outdent','Indent','Blockquote']
			,['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
			,['Link','Unlink','Anchor']
			,['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar']
			,'/'
			,['Styles','Format','Font','FontSize']
			,['TextColor','BGColor']
			,['ShowBlocks']
			,['Print']
			,['Find','Replace']
			,['Templates']
			];

			CKEDITOR.config.language = 'ja';
			CKEDITOR.config.autoParagraph = false;

			// Bold and Italic tags change to default because icons are "B" and "I"
			CKEDITOR.config.coreStyles_bold = {element : 'b'};
			CKEDITOR.config.coreStyles_italic = {element : 'i'};

			// for HTML5 tags(cancel Automatic ACF Mode)
			CKEDITOR.config.allowedContent = true;

			// menu bar position
			CKEDITOR.config.floatSpaceDockedOffsetY = 30;

			// br settings
			CKEDITOR.config.enterMode = 2;
			CKEDITOR.config.shiftEnterMode = 1;
			CKEDITOR.config.templates_replaceContent = false;

			CKEDITOR.config.protectedSource.push( /<\?[\s\S]*?\?>/g );

			if(filebrowser) {
				CKEDITOR.config.filebrowserBrowseUrl = filebrowser;
				CKEDITOR.config.filebrowserUploadUrl = filebrowser;
			}

			self.contentEditable = true;

			editor = CKEDITOR.inline(target, {
		        on : {
					instanceReady : function( ev ) {
						var tag_group1 = new Array('div', 'p', 'ul', 'ol', 'dl', 'dt', 'dd', 'li'
													, 'section', 'nav', 'article', 'aside', 'header', 'footer', 'figure', 'figcaption'
													, 'embed', 'video', 'canvas', 'audio', 'source');
						var tag_group2 = new Array('h1', 'h2', 'h3', 'h4', 'h5', 'span', 'a', 'img', 'td', 'time', 'mark');
						var tag_group3 = new Array('tr');

						for(var i=0; i<tag_group1.length; i++) {
							this.dataProcessor.writer.setRules( tag_group1[i], {
								indent : true,
								breakBeforeOpen : true,
								breakAfterOpen : true,
								breakBeforeClose : true,
								breakAfterClose : true
						    });
						}
						for(var i=0; i<tag_group2.length; i++) {
							this.dataProcessor.writer.setRules( tag_group2[i], {
								indent : true,
								breakBeforeOpen : false,
								breakAfterOpen : false,
								breakBeforeClose : false,
								breakAfterClose : true
							});
						}
						for(var i=0; i<tag_group3.length; i++) {
							this.dataProcessor.writer.setRules( tag_group3[i], {
								indent : true,
								breakBeforeOpen : true,
								breakAfterOpen : true,
								breakBeforeClose : true,
								breakAfterClose : false
							});
						}
						this.dataProcessor.writer.setRules( 'comment', {
							indent : true,
							breakAfterClose : true
						});

						// for submit
						if(parent.bframe.ajaxSubmit) {
							parent.bframe.ajaxSubmit.registCallBackFunction(onblurEditor);
						}

						// for change tab
						if(parent.bframe.inline) {
							parent.bframe.inline.registBlurCallBackFunction(onblurEditor);
						}

						// for preview
						if(parent.bframe.preview) {
							parent.bframe.preview.registCallBackFunction(onblurEditor);
						}

						// for edit check
						if(parent.bframe.editCheck_handler) {
							parent.bframe.editCheck_handler.registCallBackFunction(editCheckCallback);
							parent.bframe.editCheck_handler.registResetCallBackFunction(resetDirtyCallback);
						}
					},
				}
			});

			bframe.addEventListner(window, 'unload', cleanUp);
		}

		function onblurEditor() {
			if(editor.checkDirty()) {
				var data = editor.getData();
				// for IE
				data = data.replace(/{C}<!-----\?/g, '<?');

				// for chrome
				data = data.replace(/&lt;!-----\?/g, '<?');
				data = data.replace(/\?-----&gt;/g, '?>');

				// protected source
				data = data.replace(/<!-----\?/g, '<?');
				data = data.replace(/\?----->/g, '?>');
				parent.updateHtml(data);
				editor.resetDirty();
			}
		}

		function cleanUp() {
			if(parent.bframe.ajaxSubmit) parent.bframe.ajaxSubmit.removeCallBackFunction(onblurEditor);
			if(parent.bframe.inline) parent.bframe.inline.removeBlurCallBackFunction(onblurEditor);
			if(parent.bframe.preview) parent.bframe.preview.removeCallBackFunction(onblurEditor);
			if(parent.bframe.editCheck_handler) {
				parent.bframe.editCheck_handler.removeCallBackFunction(editCheckCallback);
				parent.bframe.editCheck_handler.removeResetCallBackFunction(resetDirtyCallback);
			}
			editor.destroy(true);
		}

		function editCheckCallback() {
			if(editor.checkDirty()) {
				parent.bframe.editCheck_handler.setEditFlag();
			}
		}

		function resetDirtyCallback() {
			editor.resetDirty();
		}
	}
