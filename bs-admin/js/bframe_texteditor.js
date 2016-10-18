/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeTextEditorInit);

	function bframeTextEditorInit(){
		var ta = document.getElementsByTagName('textarea');

		for(var i=0; i<ta.length; i++) {
			if(window.getSelection && bframe.checkClassName('bframe_texteditor', ta[i])) {
				var s = new bframe.texteditor(ta[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.texteditor
	// 
	// -------------------------------------------------------------------------
	bframe.texteditor = function(target) {
		var self = this;
		var container, editor;
		var syntax = target.getAttribute('syntax');
		var register_button;
		var widget;
		var ace_editor, theme, Split, split, UndoManager;
		var edit_flag = false;
		var command = {
			name: 'save',
			bindKey: {
				mac: 'Command-S',
				win: 'Ctrl-S'
			},
			exec: function(){
				save()
			}
		}

		if(!parent) {
			var parent = target.parentNode;
			target.style.display = 'none';
		}

		if(!syntax) {
			var syntax = 'php';
		}

		init();

		function createControl() {
			// control
			control = document.createElement('ul');
			control.className = 'control';
			parent.appendChild(control);

			li = createControlButton('images/editor/undo.png', 'undo (ctrl-z)', undo);
			control.appendChild(li);
			li = createControlButton('images/editor/redo.png', 'redo (ctrl-y)', redo);
			control.appendChild(li);
			li = createControlButton('images/editor/splith.png', 'split horizontal', splith);
			control.appendChild(li);
			li = createControlButton('images/editor/splitv.png', 'split vertical', splitv);
			control.appendChild(li);
			li = createControlButton('images/editor/indent_guide.png', 'show indent guide', indentGuide);
			control.appendChild(li);
			li = createControlButton('images/editor/invisible_object.png', 'show invisibles', invisible);
			control.appendChild(li);
			widget = bframe.searchNodeById(parent, 'open_widgetmanager');
			if(widget) {
				li = createControlButton('images/editor/gear.png', 'widgets', openWidget);
				control.appendChild(li);
			}
		}

		function createControlButton(icon_img, title, func) {
			var li = document.createElement('li');
			li.style.cssFloat = 'left';

			var a = document.createElement('a');
			a.title = title;
			if(func) {
				bframe.addEventListner(a, 'mousedown',func);
			}
			li.appendChild(a);
			img = document.createElement('img');
			img.src = icon_img;
			a.appendChild(img);

			return li;
		}

		function init() {
			// register button
			register_button = document.getElementById('register');

			// create control
			createControl();

			// container
			container = document.createElement('div');
			container.style.height = (target.offsetHeight) + 'px';
			container.className = 'container';
			container.onSelectStart = function() {return false;};
			var param = target.getAttribute('param');
			container.setAttribute('param', param);
			parent.appendChild(container);
			var aw = new bframe.adjustparent(container);

			// editor
			editor = document.createElement('div');

			container.appendChild(editor);
			editor.style.height = target.style.height;
			editor.style.position = 'absolute';
			editor.style.top = '32px';
			editor.style.left = '0';
			editor.style.bottom = '0';
			editor.style.right = '0';
			var ap = new bframe.adjustparent(editor);

			theme = require('ace/theme/twilight');
			Split = require('ace/split').Split;
			split = new Split(editor, theme, 1);
			ace_editor = split.getEditor(0);
			ace_editor.getSession().setValue(target.value);
			mode = require('ace/mode/' + syntax).Mode;
			ace_editor.getSession().setMode(new mode());

			UndoManager = require('ace/undomanager').UndoManager;
			var session = split.getEditor(0).session;
			session.setUndoManager(new UndoManager());

			ace_editor.renderer.setHScrollBarAlwaysVisible(false);
			ace_editor.renderer.setShowPrintMargin(false);
			ace_editor.renderer.setShowInvisibles(true);
			ace_editor.getSession().setFoldStyle('markbeginend');
			ace_editor.getSession().setUseWrapMode(true);
			ace_editor.getSession().setUseSoftTabs(false);
			ace_editor.getSession().on('change', setEditFlag);

			ace_editor.setScrollSpeed(2);
			ace_editor.commands.addCommand(command);

			target.style.display = 'none';

			bframe.addEventListner(parent,				'focus',	onFocus);
			bframe.addEventListner(parent.parentNode,	'focus',	onFocus);
			bframe.addEventListner(target,				'change',	updateEditor);

			bframe.resize_handler.registerCallBackFunction(onFocus);

			// for submit
			if(bframe.ajaxSubmit) {
				bframe.ajaxSubmit.registerCallBackFunction(updateTarget);
			}

			// for inline editor
			if(bframe.inline) {
				bframe.inline.registerCallBackFunction(updateTarget);
			}

			// for preview
			if(bframe.preview) {
				bframe.preview.registerCallBackFunction(updateTarget);
			}

			// for edit check
			if(bframe.editCheck_handler) {
				bframe.editCheck_handler.registerCallBackFunction(editCheckCallback);
				bframe.editCheck_handler.registerResetCallBackFunction(resetDirtyCallback);
			}
		}

		function undo(event) {
			var session = split.getEditor(0).session;
			var undoManager = session.getUndoManager();
			undoManager.undo();
		}

		function redo(event) {
			ace_editor.redo();
		}

		function openWidget(event) {
			bstudio.activateModalWindow(widget, 350, 400, insert);
			bframe.stopPropagation(event);
		}

		function insert(code) {
			ace_editor.insert(code);
		}

		function splith(event) {
			if(split.getSplits() == 1 || split.getOrientation() != split.BELOW) {
				split.setOrientation(split.BELOW);
				split.setSplits(2);
				var session = split.getEditor(0).session;
				var newSession = split.setSession(session, 1);
				newSession.name = session.name;

				var newEditor = split.getEditor(1);
				newEditor.renderer.setHScrollBarAlwaysVisible(ace_editor.renderer.getHScrollBarAlwaysVisible());
				newEditor.renderer.setShowPrintMargin(ace_editor.renderer.getShowPrintMargin());
				newEditor.renderer.setShowInvisibles(ace_editor.renderer.getShowInvisibles());
				newEditor.setDisplayIndentGuides(ace_editor.getDisplayIndentGuides());
				newEditor.commands.addCommand(command);
			}
			else{
				split.setSplits(1);
				ace_editor.focus();
			}
		}

		function splitv(event) {
			if(split.getSplits() == 1 || split.getOrientation() != split.BESIDE) {
				split.setOrientation(split.BESIDE);
				split.setSplits(2);
				var session = split.getEditor(0).session;
				var newSession = split.setSession(session, 1);
				newSession.name = session.name;

				var newEditor = split.getEditor(1);
				newEditor.renderer.setHScrollBarAlwaysVisible(ace_editor.renderer.getHScrollBarAlwaysVisible());
				newEditor.renderer.setShowPrintMargin(ace_editor.renderer.getShowPrintMargin());
				newEditor.renderer.setShowInvisibles(ace_editor.renderer.getShowInvisibles());
				newEditor.setDisplayIndentGuides(ace_editor.getDisplayIndentGuides());
				newEditor.commands.addCommand(command);
			}
			else {
				split.setSplits(1);
			}
		}

		function indentGuide(event) {
			if(ace_editor.getDisplayIndentGuides()) {
				ace_editor.setDisplayIndentGuides(false);
				if(split.getSplits() == 2) {
					split.getEditor(1).setDisplayIndentGuides(false);
				}
			}
			else {
				ace_editor.setDisplayIndentGuides(true);
				if(split.getSplits() == 2) {
					split.getEditor(1).setDisplayIndentGuides(true);
				}
			}
		}

		function invisible(event) {
			if(ace_editor.renderer.getShowInvisibles()) {
				ace_editor.renderer.setShowInvisibles(false);
				if(split.getSplits() == 2) {
					split.getEditor(1).renderer.setShowInvisibles(false);
				}
			}
			else {
				ace_editor.renderer.setShowInvisibles(true);
				if(split.getSplits() == 2) {
					split.getEditor(1).renderer.setShowInvisibles(true);
				}
			}
		}

		function onFocus() {
			split.resize();
			split.focus();
		}

		function updateEditor() {
			ace_editor.selectAll();
			var range = ace_editor.getSelectionRange();
			ace_editor.clearSelection();
			ace_editor.getSession().replace(range, target.value);
			ace_editor.renderer.alignCursor(0);
		}

		function updateTarget() {
			target.value = ace_editor.getSession().getValue();
		}

		function editCheckCallback() {
			if(ace_editor.getSession().getUndoManager().hasUndo() && edit_flag) {
				bframe.editCheck_handler.setEditFlag();
			}
		}

		function setEditFlag() {
			edit_flag = true;
		}

		function resetDirtyCallback() {
			edit_flag = false;
		}

		function save() {
			bframe.fireEvent(register_button, 'click');
		}
	}