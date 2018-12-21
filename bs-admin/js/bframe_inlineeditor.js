/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeInlineEditorInit);

	CKEDITOR.disableAutoInline = true;

	function bframeInlineEditorInit() {
		var objects = document.getElementsByClassName('bframe_inlineeditor');

		for(var i=0; i < objects.length; i++) {
			var s = new bframe.inlineeditor(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.inlineeditor
	// 
	// -------------------------------------------------------------------------
	bframe.inlineeditor = function(target) {
		var self = target;
		var editor;
		var param = target.getAttribute('data-param');
		if(param) {
			var filebrowser = bframe.getParam('filebrowser', param);
			var language = bframe.getParam('language', param);
			var templates = bframe.getParam('templates', param);
		}

		setUpCKEditor();

		function setUpCKEditor() {

			CKEDITOR.config.toolbar = [
			 ['Undo','Redo','-','Bold','Italic','Underline','Strike','-','Subscript','Superscript']
			,['Outdent','Indent']
			,['JustifyLeft','JustifyCenter','JustifyRight']
			,['Link','Unlink','Anchor']
			,['Image','Flash','Table']
			,'/'
			,['Styles','Format','FontSize']
			,['TextColor','BGColor']
			,['ShowBlocks']
			,['Print']
			,['Find','Replace']
			,['Templates']
			];

			// language
			CKEDITOR.config.language = language;
			CKEDITOR.config.autoParagraph = false;

			CKEDITOR.config.coreStyles_bold = {element: 'span', attributes: {'style': 'font-weight:bold' }};
			CKEDITOR.config.coreStyles_italic = {element: 'span', attributes: {'style': 'font-style:italic' }};

			// automatic color
			CKEDITOR.config.colorButton_enableAutomatic = false;

			// for HTML5 tags(cancel Automatic ACF Mode)
			CKEDITOR.config.allowedContent = true;

			// menu bar position
			CKEDITOR.config.floatSpaceDockedOffsetY = 30;

			// br settings
			CKEDITOR.config.enterMode = 2;
			CKEDITOR.config.shiftEnterMode = 1;
			CKEDITOR.config.templates_replaceContent = false;

			// protect source
			CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);
			CKEDITOR.config.protectedSource.push(/<body[\s\S]*?>/g);

			// filebrowser
			if(filebrowser) {
				CKEDITOR.config.filebrowserBrowseUrl = filebrowser;
				CKEDITOR.config.filebrowserUploadUrl = filebrowser;
			}

			// templates
			if(templates) {
				CKEDITOR.config.templates_files = [templates];
			}
			CKEDITOR.config.templates_replaceContent = false;

			var base = document.getElementsByTagName('base');
			if(base) {
				CKEDITOR.config.baseHref = base[0].getAttribute('href');
			}

			// for magicline plugin
			CKEDITOR.config.magicline_everywhere = {
				table: 1,
				hr: 1,
				h1: 1,
				h2: 1,
				h3: 1,
				h4: 1,
				h5: 1,
				h6: 1,
				div: 1,
				ul: 1,
				ol: 1,
				dl: 1,
				form: 1,
				blockquote: 1
			}

			// empty tag 0: remain 1:remove
			CKEDITOR.dtd.$removeEmpty = {
				abbr: 0,
				acronym: 0,
				b: 0,
				bdi: 0,
				bdo: 0,
				big: 0,
				cite: 0,
				code: 0,
				del: 0,
				dfn: 0,
				em: 0,
				font: 0,
				i: 0,
				ins: 0,
				label: 0,
				kbd: 0,
				mark: 0,
				meter: 0,
				output: 0,
				q: 0,
				ruby: 0,
				s: 0,
				samp: 0,
				small: 0,
				span: 0,
				strike: 0,
				strong: 0,
				sub: 0,
				sup: 0,
				time: 0,
				tt: 0,
				u: 0
			};

			self.contentEditable = true;

			editor = CKEDITOR.inline(target, {
				on: {
					instanceReady: function(ev) {
						var tag_group1 = new Array('h1', 'h2', 'h3', 'h4', 'h5', 'div', 'p', 'ul', 'ol', 'dl', 'dt', 'dd', 'li'
													, 'section', 'nav', 'article', 'aside', 'header', 'footer', 'figure', 'figcaption'
													, 'embed', 'video', 'canvas', 'audio', 'source');
						var tag_group2 = new Array('span', 'a', 'img', 'td', 'time', 'mark');
						var tag_group3 = new Array('tr');

						for(var i=0; i<tag_group1.length; i++) {
							this.dataProcessor.writer.setRules(tag_group1[i], {
								indent: true,
								breakBeforeOpen: true,
								breakAfterOpen: true,
								breakBeforeClose: true,
								breakAfterClose: true
							});
						}
						for(var i=0; i<tag_group2.length; i++) {
							this.dataProcessor.writer.setRules(tag_group2[i], {
								indent: true,
								breakBeforeOpen: false,
								breakAfterOpen: false,
								breakBeforeClose: false,
								breakAfterClose: false
							});
						}
						for(var i=0; i<tag_group3.length; i++) {
							this.dataProcessor.writer.setRules(tag_group3[i], {
								indent: true,
								breakBeforeOpen: true,
								breakAfterOpen: true,
								breakBeforeClose: true,
								breakAfterClose: false
							});
						}
						this.dataProcessor.writer.setRules('comment', {
							indent: true,
							breakAfterClose: true
						});

						// for submit
						if(parent.bframe.ajaxSubmit) {
							parent.bframe.ajaxSubmit.registerCallBackFunction(onblurEditor);
						}

						// for change tab
						if(parent.bframe.inline) {
							parent.bframe.inline.registerBlurCallBackFunction(onblurEditor);
						}

						// for preview
						if(parent.bframe.preview) {
							parent.bframe.preview.registerCallBackFunction(onblurEditor);
						}

						// for edit check
						if(parent.bframe.editCheck_handler) {
							parent.bframe.editCheck_handler.registerCallBackFunction(editCheckCallback);
							parent.bframe.editCheck_handler.registerResetCallBackFunction(resetDirtyCallback);
						}
					},
				}
			});

			bframe.addEventListener(window, 'unload', cleanUp);
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
				parent.bstudio.updateHtml(data);
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
