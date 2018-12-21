/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load', bframeVisualEditorInit);

	function bframeVisualEditorInit() {
		var v = [];
		var objects = document.querySelectorAll('textarea.bframe_visualeditor');

		for(var i=0; i < objects.length; i++) {
			v[i] = new bframe.visualeditor(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.visualeditor
	// 
	// -------------------------------------------------------------------------
	bframe.visualeditor = function(target) {
		var self = this;
		var editor;
		var param = target.getAttribute('data-param');
		var updating_flag;
		var cke_controll;
		var cke_controll_position;
		var cke_controll_style;
		var cke_controll_style_width;
		var cke_contents;
		var cke_contents_height;

		var cke_bottom;
		var scroller;
		var scroller_position;
		var parent;
		var container;
		var visual_editor_body_class;

		if(param) {
			// parent
			var parent_id = bframe.getParam('parent', param);
			if(parent_id) parent = document.getElementById(parent_id);

			// container
			var container_id = bframe.getParam('container', param);
			if(container_id) container = document.getElementById(container_id);
			if(container) {
				bframe.addEventListener(container, 'focus', onFocus);
			}

			// scroller
			var scroller_id = bframe.getParam('scroller', param);
			if(scroller_id) scroller = document.getElementById(scroller_id);
			if(scroller) {
				bframe.addEventListener(scroller, 'scroll', onScroll);
				if(bframe.resize_handler) {
					bframe.resize_handler.registerCallBackFunction(onWindowResize);
				}
			}
			if(bframe.ajaxSubmit) {
				bframe.ajaxSubmit.registerCallBackFunctionAfter(onWindowResize);
			}

			// body class
			visual_editor_body_class = bframe.getParam('bodyclass', param);
		}

		setUpCKEditor();

		function setUpCKEditor() {

			CKEDITOR.config.toolbar = [
			 ['Undo','Redo','-','Bold','Italic','Underline','Strike','-','Subscript','Superscript']
			,['JustifyLeft','JustifyCenter','JustifyRight']
			,['Link','Unlink','Anchor']
			,['Image','Table']
			,'/'
			,['Styles','Format','FontSize']
			,['TextColor','BGColor']
			,['ShowBlocks']
			,['Find','Replace']
			,['Source','Preview','Templates']
			];

			// language
			var visual_editor_language = document.getElementById('visual_editor_language');
			CKEDITOR.config.language = visual_editor_language.value;
			CKEDITOR.config.autoParagraph = false;

			CKEDITOR.config.extraPlugins = 'autogrow';
			CKEDITOR.config.autoGrow_onStartup = true;
			CKEDITOR.config.autoGrow_minHeight = 360;

			// automatic color
			CKEDITOR.config.colorButton_enableAutomatic = false;

			// for HTML5 tags(cancel Automatic ACF Mode)
			CKEDITOR.config.allowedContent = true;

			// br settings
			CKEDITOR.config.enterMode = 2;
			CKEDITOR.config.shiftEnterMode = 1;

			if(parent) {
				var h = parent.style.height;
			}
			else {
				var h = target.style.height;
			}

			CKEDITOR.config.coreStyles_bold = {element: 'span', attributes: {'style': 'font-weight:bold'}};
			CKEDITOR.config.coreStyles_italic = {element: 'span', attributes: {'style': 'font-style:italic'}};

			// protect php
			CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);

			// disable resize editor
			CKEDITOR.config.resize_enabled = false;
			// root dir
			var root_dir = document.getElementById('root_dir');
			if(root_dir) {
				CKEDITOR.config.root_dir =  root_dir.value;
			}

			// filebrowser
			var filebrowser = document.getElementById('filebrowser');
			CKEDITOR.config.filebrowserBrowseUrl = filebrowser.href;
			CKEDITOR.config.filebrowserUploadUrl = filebrowser.href;

			// styles
			var visual_editor_styles = document.getElementById('visual_editor_styles');
			if(visual_editor_styles) {
				CKEDITOR.config.stylesSet = visual_editor_styles.value;
			}

			// css
			var visual_editor_css = document.getElementById('visual_editor_css');
			if(visual_editor_css) {
				CKEDITOR.config.contentsCss = [visual_editor_css.value];
			}

			// templates
			var visual_editor_templates = document.getElementById('visual_editor_templates');
			if(visual_editor_templates) {
				CKEDITOR.config.templates_files = [visual_editor_templates.value];
			}
			CKEDITOR.config.templates_replaceContent = false;

			// format tags
			CKEDITOR.config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre;address;div';

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

			// baseHref
			var baseHref = document.getElementById('baseHref');
			if(baseHref) {
				CKEDITOR.config.baseHref = baseHref.value;
			}

			// readOnly
			var readOnly = document.getElementById('readOnly');
			if(readOnly) {
				CKEDITOR.config.readOnly = readOnly.value;
			}

			if(parent) {
				editor = CKEDITOR.appendTo(parent.id, {
					height: h,
					on: {
						instanceReady: function(ev) {
							var tag_group1 = new Array('h1', 'h2', 'h3', 'h4', 'h5', 'div', 'p', 'ul', 'ol', 'dl', 'dt', 'dd', 'li'
													, 'section', 'nav', 'article', 'aside', 'header', 'footer', 'figure', 'figcaption'
													, 'embed', 'video', 'canvas', 'audio', 'source');
							var tag_group2 = new Array('span', 'a', 'img', 'td', 'time', 'mark');
							var tag_group3 = new Array('tr');

							for(var i=0; i < tag_group1.length; i++) {
								this.dataProcessor.writer.setRules(tag_group1[i], {
									indent: true,
									breakBeforeOpen: true,
									breakAfterOpen: true,
									breakBeforeClose: true,
									breakAfterClose: true
								});
							}
							for(var i=0; i < tag_group2.length; i++) {
								this.dataProcessor.writer.setRules(tag_group2[i], {
									indent: true,
									breakBeforeOpen: false,
									breakAfterOpen: false,
									breakBeforeClose: false,
									breakAfterClose: false
								});
							}
							for(var i=0; i < tag_group3.length; i++) {
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

							// for sychronization
							bframe.addEventListener(target, 'change', applyValueToEditor);

							// for submit
							if(bframe.ajaxSubmit) {
								bframe.ajaxSubmit.registerCallBackFunction(onblurEditor);
							}

							// for edit check
							if(bframe.editCheck_handler) {
								bframe.editCheck_handler.registerCallBackFunction(editCheckCallback);
								bframe.editCheck_handler.registerResetCallBackFunction(resetDirtyCallback);
							}

							// body class
							var body = ev.editor.document.getBody();
							body.addClass(visual_editor_body_class);
						},

						mode: function(ev) {
							bframe.fireEvent(window, 'resize');
						},
					}
				});
			}
			else {
				editor = CKEDITOR.replace(target.id, {
					height: h,
					on: {
						instanceReady: function(ev) {
							var tag_group1 = new Array('h1', 'h2', 'h3', 'h4', 'h5', 'div', 'p', 'ul', 'ol', 'dl', 'dt', 'dd', 'li'
													, 'section', 'nav', 'article', 'aside', 'header', 'footer', 'figure', 'figcaption'
													, 'embed', 'video', 'canvas', 'audio', 'source');
							var tag_group2 = new Array('span', 'a', 'img', 'td', 'time', 'mark');
							var tag_group3 = new Array('tr');

							for(var i=0; i < tag_group1.length; i++) {
								this.dataProcessor.writer.setRules(tag_group1[i], {
									indent: true,
									breakBeforeOpen: true,
									breakAfterOpen: true,
									breakBeforeClose: true,
									breakAfterClose: true
								});
							}
							for(var i=0; i < tag_group2.length; i++) {
								this.dataProcessor.writer.setRules(tag_group2[i], {
									indent: true,
									breakBeforeOpen: false,
									breakAfterOpen: false,
									breakBeforeClose: false,
									breakAfterClose: false
								});
							}
							for(var i=0; i < tag_group3.length; i++) {
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

							// for sychronization
							bframe.addEventListener(target, 'change', applyValueToEditor);

							// for submit
							if(bframe.ajaxSubmit) {
								bframe.ajaxSubmit.registerCallBackFunction(onblurEditor);
							}

							// for edit check
							if(bframe.editCheck_handler) {
								bframe.editCheck_handler.registerCallBackFunction(editCheckCallback);
								bframe.editCheck_handler.registerResetCallBackFunction(resetDirtyCallback);
							}

							bframe.fireEvent(window, 'resize');
							onScroll();
						},

						mode: function(ev) {
							if(editor.mode == 'source') {
								cke_source = bframe.searchNodeByClassName(container, 'cke_source');
								cke_contents_height = cke_contents.style.height;
								cke_contents.style.height = 0;
								cke_contents.style.height = cke_source.scrollHeight + 'px';

								// for autogrow
								bframe.addEventListener(cke_source, 'input', autogrowSource);
							}
							else if(cke_contents_height) {
								editor.execCommand('autogrow');
								cke_contents_height = 0;
								bframe.removeEventListener(cke_source, 'input', autogrowSource);
							}

							bframe.fireEvent(window, 'resize');
						},

						resize: function(ev) {
							bframe.fireEvent(window, 'resize');
						},

						contentDom: function(ev) {
							// body class
							var body = ev.editor.document.getBody();
							body.addClass(visual_editor_body_class);
						},
					}
				});
			}

			bframe.addEventListener(window, 'unload', cleanUp);
		}

		editor.on('afterCommandExec', handleAfterCommandExec);

		function handleAfterCommandExec(event) {
			var commandName = event.data.name;
			switch(commandName) {
			case'showblocks':
				onWindowResize();
				break;
			}
		}

		function autogrowSource(event) {
			if(cke_contents.style.height != cke_source.scrollHeight + 'px') {
				// grow
				cke_contents.style.height = cke_source.scrollHeight + 'px';
				bframe.fireEvent(window, 'resize');
			}
			else {
				// same or shrink
				var lineHeight = Number(cke_source.style.lineHeight.split('px')[0]);
				lineHeight = lineHeight ? lineHeight : '15';
				var height = Number(cke_contents.style.height.split('px')[0]);
				var initialHeight = height;

				while(true) {
					height -= lineHeight;
					cke_contents.style.height = height + 'px'; 
					if(height < cke_source.scrollHeight) {
						if(cke_source.scrollHeight < initialHeight) {
							bframe.fireEvent(window, 'resize');
						}
						cke_contents.style.height = cke_source.scrollHeight + 'px';
						break;
					}
	    		}
			}
		}

		function onScroll() {
			if(!cke_controll) {
				var container = editor.container.$;

				cke_controll = bframe.searchNodeByClassName(container, 'cke_top');
				cke_controll_position = bframe.getElementPosition(cke_controll);
				cke_controll_style = bframe.getStyle(cke_controll);
				cke_controll_style_width = cke_controll_style.width;

				scroller_position = bframe.getElementPosition(scroller);

				cke_contents = bframe.searchNodeByClassName(container, 'cke_contents');
				cke_contents_position = bframe.getElementPosition(cke_contents);

				cke_bottom = bframe.searchNodeByClassName(container, 'cke_bottom');
				cke_bottom.style.position = 'fixed';
				cke_bottom.style.width = cke_controll_style_width;
				cke_bottom.style.bottom = '0';
			}
			if(cke_controll_position.top - scroller_position.top < scroller.scrollTop) {
				if(cke_controll.style.position == 'fixed') return;

				// save scrollTop
				var scrollTop = scroller.scrollTop;

				cke_controll.style.position = 'fixed';
				cke_controll.style.top = scroller_position.top + 'px';
				cke_contents.style.marginTop = cke_controll.clientHeight + 'px';
				cke_controll_style = bframe.getStyle(cke_controll);
				cke_controll.style.width = cke_controll_style_width;

				// restore scrollTop
				scroller.scrollTop = scrollTop
			}
			else {
				if(cke_controll.style.position == 'static') return;
				cke_controll.style.position = 'static';
				cke_contents.style.marginTop = 0;
				cke_controll.style.width = '';
			}
		}

		function onWindowResize() {
			onResize();
		}

		function onResize() {
			if(!cke_controll) return;

			var position = cke_controll.style.position;

			// reset to static
			cke_controll.style.position = 'static';
			cke_controll.style.width = '';

			// set position
			cke_controll_position = bframe.getElementPosition(cke_controll);
			cke_controll_position.top += scroller.scrollTop;

			// get style and reset width
			cke_controll_style = bframe.getStyle(cke_controll);

			cke_controll_style_width = cke_controll_style.width;
			cke_controll.style.width = cke_controll_style_width;

			// restore original position
			cke_controll.style.position = position;

			// set footer style
			cke_bottom.style.width = cke_controll_style_width;
		}

		function onFocus() {
			cke_controll = '';
			onScroll();
			setTimeout(function(){editor.execCommand('autogrow');}, 100);
		}

		function onblurEditor() {
			if(!updating_flag && editor.checkDirty()) {
				target.value = editor.getData();
				bframe.fireEvent(target, 'change');
			}
		}

		function applyValueToEditor() {
			if(target.value != editor.getData()) {
				updating_flag = true;
				editor.setData(target.value, function() {resetUpdatingFlag(); editor.resetDirty()}, false);
			}
		}

		function resetUpdatingFlag() {
			updating_flag = false;
		}

		function editCheckCallback() {
			if(editor.checkDirty()) {
				bframe.editCheck_handler.setEditFlag();
			}
		}

		function resetDirtyCallback() {
			editor.resetDirty();
		}

		function cleanUp() {
			if(bframe.ajaxSubmit) bframe.ajaxSubmit.removeCallBackFunction(onblurEditor);
			if(bframe.editCheck_handler) {
				bframe.editCheck_handler.removeCallBackFunction(editCheckCallback);
				bframe.editCheck_handler.removeResetCallBackFunction(resetDirtyCallback);
			}
			for(var instanceName in CKEDITOR.instances) {
				CKEDITOR.instances[instanceName].destroy(true);
			}
		}
	}
