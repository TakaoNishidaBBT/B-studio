	bframe.addEventListner(window, 'load' , bframeTextEditorInit);

	function bframeTextEditorInit(){
	    var ta = document.getElementsByTagName('textarea');

	    for(var i=0; i<ta.length; i++) {
			if(window.getSelection && bframe.checkClassName('bframe_contenteditor', ta[i])) {
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
		var container, line_number, editor, prepre, pre, terminator, contents;
		var debug_counter=0;
		var first_node_of_the_line;
		var shiftKey, ctrlKey;
		var imeEnter;
		var history;
		var lineHeight = 14;
		var selection;
		var timer;
		var caret;
		var line;
		var ime_position;
		var g_line_cnt=0;
		var undoFlag=false;
		var changeByMyself;
		var scrollTop;
		var default_index=0;
		var syntax = target.getAttribute('syntax');
		var regist_button;
		var widget;

		if(!parent) {
			var parent = target.parentNode;
			target.style.display = 'none';
		}

		if(!syntax) {
			var syntax = 'xml';
		}
		var sh = new bframe.syntax_highlighter(syntax);

		var multiLineCommentCss = sh.get('multiLineCommentCss');
		var multiLineCDataCss = sh.get('multiLineCDataCss');
		var multiLinePhpCss = sh.get('multiLinePhpCss');
		var multiLineDoubleQuotedStringCss = sh.get('multiLineDoubleQuotedStringCss');
		var multiLineSingleQuotedStringCss = sh.get('multiLineSingleQuotedStringCss');

		var multiLineCommentStartTag = sh.get('multiLineCommentStartTag');
		var multiLineCommentEndTag = sh.get('multiLineCommentEndTag');
		var multiLineCDataStartTag = sh.get('multiLineCDataStartTag');
		var multiLineCDataEndTag = sh.get('multiLineCDataEndTag');
		var multiLinePhpStartTag = sh.get('multiLinePhpStartTag');
		var multiLinePhpEndTag = sh.get('multiLinePhpEndTag');
		var multiLineXmlStartTag = sh.get('multiLineXmlStartTag');
		var multiLineXmlEndTag = sh.get('multiLineXmlEndTag');

		init();

		Element.prototype.indexOf = function(el) {
			var nodeList = this.childNodes;
			var array = [].slice.call(nodeList, 0);
			var index = array.indexOf(el);
			return index;
		}

		getStartObject = function(range) {
			if(range.startContainer.nodeType == 3) return range.startContainer;
			return range.startContainer.childNodes[range.startOffset] || range.startContainer.childNodes[range.startOffset-1];
		}

		getEndObject = function(range) {
			if(range.endContainer.nodeType == 3) return range.endContainer;
			return range.endContainer.childNodes[range.endOffset] || range.endContainer.childNodes[range.endOffset-1];
		}

		if(!container.offsetHeight) {
			timer = setInterval(function() {
				var aw = new bframe.adjustparent(container);
				stop();
			}, 1);
		}

		function createControl() {
			// control
			control = document.createElement('ul');
			control.className = 'control';
			parent.appendChild(control);

			li = createControlButton('images/common/undo.png', 'undo (ctrl-z)', undo);
			control.appendChild(li);
			li = createControlButton('images/common/redo.png', 'undo (ctrl-y)', redo);
			control.appendChild(li);

			widget = bframe.searchNodeById(parent, 'open_widgetmanager');
			if(widget) {
				li = createControlButton('images/common/widget.png', 'widget', openWidget);
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
			// regist button
			regist_button = document.getElementById('regist');

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

			// line number
			line_number = document.createElement('div');
			line_number.style.lineHeight = lineHeight + 'px';
			line_number.className = 'line_number';

			container.appendChild(line_number);

			// editor
			editor = document.createElement('div');
			editor.className = 'editor';

			// terminator
			terminator = document.createElement('div');
			contents = document.createTextNode('contents');
			terminator.appendChild(contents);
			terminator.style.display = 'none';

			container.appendChild(editor);

			text = target.innerHTML.replace(/\r/g, '');

			// suntax highlight
			text = sh.getHtml(text);

			text = text.replace(/\n/g, '<br>');

			if(_isIE) {
				prepre = document.createElement('pre');
				prepre.innerHTML = '<pre>' + text + '</pre>';
				pre = prepre.childNodes.item(0);
			}
			else {
				pre = document.createElement('pre');
				// always add <br> to the last
				pre.innerHTML = text+'<br>';
			}

			pre.contentEditable = true;
			pre.style.margin = 0;
			pre.style.background = 'transparent';
			pre.style.wordWrap = 'break-word';
			pre.style.wordBreak = 'break-all';
			pre.style.whiteSpace = 'pre-wrap';
			pre.style.lineHeight = lineHeight + 'px';

			editor.appendChild(pre);
			editor.appendChild(terminator);

			// set event handler
			bframe.addEventListner(container,	'click',	containerClick);
			bframe.addEventListner(parent,		'focus',	onFocusParent);
			bframe.addEventListner(pre,			'focus',	onFocus);
			bframe.addEventListner(pre,			'mousedown',preMouseDown);
			bframe.addEventListner(pre,			'click',	preClick);
			bframe.addEventListner(pre,			'copy',		copy);
			bframe.addEventListner(target,		'change',	updateEditor);

			if(_isFF) {
				pre.onpaste = paste;
				pre.oncut = cut;
				pre.ondrop = drop;
				bframe.addEventListner(pre,	'keydown' ,	onkeydownForFireFox);
				bframe.addEventListner(pre,	'keypress',	onkeypressForFireFox);
				bframe.addEventListner(pre,	'keyup',	onkeyupForFireFox);
			}
			else if(_isIE) {
				pre.onbeforepaste = paste;
				pre.oncut = cut;
				pre.ondrop = drop;
				bframe.addEventListner(pre,	'keydown' ,	onkeydownForIE);
				bframe.addEventListner(pre,	'keypress',	onkeypressForIE);
				bframe.addEventListner(pre,	'keyup',	onkeyupForIE);
			}
			else {
				pre.onpaste = paste;
				pre.oncut = cut;
				pre.ondrop = drop;
				bframe.addEventListner(pre,	'keydown' ,	onkeydownForWebKit);
				bframe.addEventListner(pre,	'keypress',	onkeypressForWebKit);
				bframe.addEventListner(pre,	'keyup',	onkeyupForWebKit);
			}

			bframe.resize_handler.registCallBackFunction(setLineNumber);

			// for submit
			if(bframe.ajaxSubmit) {
				bframe.ajaxSubmit.registCallBackFunction(updateTarget);
			}

			// for inline editor
			if(bframe.inline) {
				bframe.inline.registCallBackFunction(updateTarget);
			}

			// for preview
			if(bframe.preview) {
				bframe.preview.registCallBackFunction(updateTarget);
			}

			// for edit check
			if(bframe.editCheck_handler) {
				bframe.editCheck_handler.registCallBackFunction(editCheckCallback);
				bframe.editCheck_handler.registResetCallBackFunction(resetDirtyCallback);
			}

			history = new historyControl(pre);
			caret = new caretControl();
			line = new lineNumberControl(line_number);
			if(window.getSelection) {
				selection = window.getSelection();
			}

			setLineNumber();
		}

		function undo(event) {
			onFocusParent();
			history.undo();
			setLineNumber();
			bframe.stopPropagation(event);
		}

		function redo(event) {
			onFocusParent();
			history.redo();
			setLineNumber();
			bframe.stopPropagation(event);
		}

		function openWidget() {
			if(selection.rangeCount) {
				var range = selection.getRangeAt(0);
			}
			else {
				var range = document.createRange();
			}
			caret.savePositionFromRange(range);
			activateLightWindow(widget.id, 350, 400, setWidget);
		}

		function setWidget(code) {
			onFocusParent();

			if(!selection.rangeCount) return;

			var node = document.createTextNode(code);
			var range = selection.getRangeAt(0);
			var clone = new cloneRange(range);

			if(!_isIE) {
				// set range must be before last 'br' (for updateTarget)
				var end = getEndObject(range);
				if(end.nodeName.toLowerCase() == 'br' && end == pre.lastChild) {
					range.setEndBefore(end);
					range.collapse(range.collapsed);
				}
			}

			var sr = surroundRange(range);
			index = getRangeIndex(sr);
			var os = index.start;
			var oe = index.end;

			range.deleteContents();
			range.insertNode(node);
			caret.savePosition(node);
			range.selectNode(node);

			caret.savePosition(node);
			range.selectNode(node);

			hint = updateSyntax(range, 'paste');
			hint.os = os;
			hint.oe = oe;

			caret.setRange(range);

			setLineNumber();
			setScrollTop(range);
			history.push(clone, hint);

			selection.removeAllRanges();
			selection.addRange(range);
		}

		function containerClick(event) {
			pre.focus();
		}

		function onFocusParent() {
			setLineNumber();
			if(_isIE) {
				onFocus();
			}
			else {
				pre.focus();
			}
		}

		function onFocus() {
			var range = document.createRange();
			if(!caret.setRange(range)) return;

			selection.removeAllRanges();
			selection.addRange(range);
			if(scrollTop) {
				container.scrollTop = scrollTop;
			}
		}

		function preMouseDown(event) {
			caret.clear();
		}

		function preClick(event) {
			bframe.stopPropagation(event);
		}

		function setLineNumber() {
			line.set(pre.offsetHeight);
		}

		function onkeydownForFireFox(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}

			if(!selection.rangeCount) return;
			switch(keycode) {
			case 9:  // Tab
				tab();
				bframe.stopPropagation(event);
				break;

			case 13:  // Enter
				bframe.stopPropagation(event);
				enter();
				imeEnter = true;
				break;

			case 8:		// BackSpace
			case 46:	//Delete
				recordHistory();
				break;

			case 83:  // s
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
//				updateTarget();
				bframe.fireEvent(regist_button, 'click');
				onFocus();
				bframe.stopPropagation(event);
				break;

			case 89:  // y
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
				history.redo();
				bframe.stopPropagation(event);
				break;

			case 90:  // z
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
				history.undo();
				bframe.stopPropagation(event);
				break;

			case 229:	//IME
			case 0:
				getImePosition();
				break;

			default:
				return;
			}

			setLineNumber();
		}

		function onkeypressForFireFox(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}
			if(!charcode || !selection.rangeCount) return;

			if(key(charcode)) bframe.stopPropagation(event);
		}

		function onkeyupForFireFox(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}

			var range = selection.getRangeAt(0);

			switch(keycode) {
			case 13:  // Enter
			case 32:  // Space
				bframe.stopPropagation(event);
				if(imeEnter) {
					imeEnter = false;
					return;
				}
				hint = updateSyntax(range);
				history.push(ime_position, hint);
				setLineNumber();
				break;

			case 83:  // s
			case 89:  // y
			case 90:  // z
				undoFlag = false;
				break;
			}
		}

		function onkeydownForIE(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}

			switch(keycode) {
			case 9:  // Tab
				tab();
				bframe.stopPropagation(event);
				break;

			case 13:  // Enter
				enter();
				bframe.stopPropagation(event);
				break;

			case 8:		// BackSpace
			case 46:	//Delete
				recordHistory();
				break;

			case 83:  // s
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
				updateTarget();
				bframe.fireEvent(regist_button, 'click');
				onFocus();
				bframe.stopPropagation(event);
				break;

			case 89:  // y
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
				history.redo();
				bframe.stopPropagation(event);
				break;

			case 90:  // z
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
				history.undo();
				bframe.stopPropagation(event);
				break;

			case 229:	//IME
				recordHistoryIME();
				break;

			default:
				return;
			}

			setLineNumber();
		}

		function onkeypressForIE(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}

			if(!keycode) return;

			if(key(keycode)) {
				setLineNumber();
				bframe.stopPropagation(event);
			}
		}

		function onkeyupForIE(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}

			switch(keycode) {
			case 83:  // s
			case 89:  // y
			case 90:  // z
				undoFlag = false;
				break;
			}
		}

		function onkeydownForWebKit(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}

			if(!selection.rangeCount) return;

			switch(keycode) {
			case 9:  // Tab
				tab();
				bframe.stopPropagation(event);
				break;

			case 13:  // Enter
				enter();
				bframe.stopPropagation(event);
				break;

			case 8:		// BackSpace
			case 46:	//Delete
				recordHistory();
				break;

			case 83:  // s
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
				updateTarget();
				bframe.fireEvent(regist_button, 'click');
				onFocus();
				bframe.stopPropagation(event);
				break;

			case 89:  // y
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
				history.redo();
				bframe.stopPropagation(event);
				break;

			case 90:  // z
				if(!ctrlKey) {
					if(undoFlag) bframe.stopPropagation(event);
					return;
				}
				undoFlag = true;
				history.undo();
				bframe.stopPropagation(event);
				break;

			case 229:	//IME
				recordHistoryIME();
				break;
			}

			setLineNumber();
		}

		function onkeypressForWebKit(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}

			if(!charcode || !selection.rangeCount) return;

			if(key(charcode)) bframe.stopPropagation(event);
		}

		function onkeyupForWebKit(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
				var charcode = window.event.charCode;
				shiftKey = window.event.shiftKey;
				ctrlKey = window.event.ctrlKey;
 			}
			else {
				var keycode = event.keyCode;
				var charcode = event.charCode;
				shiftKey = event.shiftKey;
				ctrlKey = event.ctrlKey;
			}

			switch(keycode) {
			case 83:  // s
			case 89:  // y
			case 90:  // z
				undoFlag = false;
				break;
			}
		}

		function copy(event) {
			if(timer) return true;

			var range = selection.getRangeAt(0);
			caret.saveEndPositionFromRange(range);

			if(_isIE) {
				var contents = range.extractContents();
				var div = document.createElement('span');
				div.appendChild(contents);
				div.innerHTML = div.innerHTML.replace(/<br>/g, '\n');
				range.insertNode(div);
				range.selectNode(div);
				selection.removeAllRanges();
				selection.addRange(range);
			}
			else if(range.endContainer == pre && range.endOffset == pre.childNodes.length) {
				var end = getEndObject(range);
				range.setEndBefore(end);
			}


			timer = setInterval(function() {
				stop();
				var range = selection.getRangeAt(0).cloneRange();

				if(_isIE) {
					div.innerHTML = div.innerHTML.replace(/\n/g, '<br>');
					var fragment = document.createDocumentFragment();

					for(var i=0; i<div.childNodes.length; i++) {
						fragment.appendChild(cloneNode(div.childNodes[i]));
					}

					range.deleteContents();
					range.insertNode(fragment);
				}
				caret.setRange(range);
				selection.removeAllRanges();
				selection.addRange(range);

			}, 1);

			return true;
		}

		function paste(event) {
			var os, oe, ns, ne;

			if(timer) return false;

			var range = selection.getRangeAt(0);
			var cr = range.cloneRange();
			var clone = new cloneRange(range);
			var sr = surroundRange(range);
			index = getRangeIndex(sr);
			os = index.start;
			oe = index.end;

			if(_isIE) {
				var clipboard = document.createElement('textarea');
			}
			else {
				var clipboard = document.createElement('div');
			}
			var contents = document.createTextNode('__contents__');
			clipboard.appendChild(contents);

			if(_isIE || _isFF) {
				range.deleteContents();
				pre.appendChild(clipboard);
				range.selectNode(contents);
				selection.removeAllRanges();
				selection.addRange(range);

				clipboard.style.display = 'none';
			}
			else {
				range.deleteContents();
				range.insertNode(clipboard);
				range.selectNode(contents);
				selection.removeAllRanges();
				selection.addRange(range);

				clipboard.style.height = "1px";
				clipboard.style.overflow = 'hidden';
				var cs = container.scrollTop;
			}
			var index = pre.indexOf(clipboard);

			timer = setInterval(function() {
				stop();
				if(clipboard.innerHTML == '__contents__') return;
				clipboard.innerHTML = clipboard.innerHTML.replace(/<br>/g, '\n');

				if(selection.rangeCount) {
					var range = selection.getRangeAt(0);
				}
				else {
					var range = document.createRange();
					selection.addRange(range);
				}
				range.selectNode(clipboard);

				if(_isIE) {
					var dummy = document.createTextNode('e');
					clipboard.appendChild(dummy);

					var html = range.toString();
					html = html.substr(0, html.length-1).replace(/\r/g, '');
				}
				else {
					var html = range.toString();
				}
				var node = document.createTextNode(html);

				if(pre.indexOf(clipboard) != index && clipboard.previousSibling.nodeName.toLowerCase() == 'br') {
					pre.removeChild(clipboard.previousSibling);
				}

				range.deleteContents();
				if(pre.innerHTML == '' && !_isIE) {
					pre.innerHTML = '<br>';
				}
				cr.insertNode(node);
				caret.savePosition(node);
				cr.selectNode(node);

				hint = updateSyntax(cr, 'paste');
				hint.os = os;
				hint.oe = oe;

				caret.setRange(cr);

				selection.removeAllRanges();
				selection.addRange(cr);

				if(!_isIE && !_isFF) {
					container.scrollTop = cs;
				}
				setLineNumber();
				setScrollTop(cr);
				history.push(clone, hint);
			}, 1);
			return true;
		}

		function cut() {
			recordHistory();
		}

		function drop(event) {
			bframe.stopPropagation(event);
		}

		function recordHistory() {
			if(timer) return true;
			var range = selection.getRangeAt(0);
			var clone = new cloneRange(range);
			timer = setInterval(function() {
				stop();
				setLineNumber();
				var range = selection.getRangeAt(0);

				var hint = updateSyntax(range);
				cleanUpEmptyTextNode();
				history.push(clone, hint);
			}, 1);

			return true;
		}

		function recordHistoryIME() {
			if(timer) return true;
			var range = selection.getRangeAt(0);
			var clone = new cloneRange(range);
			var sr = surroundRange(range);
			var index = getRangeIndex(sr);
			var os = index.start;
			var oe = index.end;
			timer = setInterval(function() {
				stop();
				setLineNumber();
				var range = selection.getRangeAt(0);
				var sr = surroundRange(range);
				var index = getRangeIndex(sr);
				var ns = index.start;
				var ne = index.end;

				history.push(clone, {os: os, oe: oe, ns: ns, ne: ne});
			}, 1);

			return true;
		}

		function getImePosition(event) {
			if(timer) return true;
			var range = selection.getRangeAt(0);
			ime_position = new cloneRange(range);
			return true;
		}

		function stop() {
			clearInterval(timer);
			timer = '';
		}

		function key(charcode) {
			var node
			if(ctrlKey) return;
			if(!charcode) return true;

			var keychar = String.fromCharCode(charcode);
			var range = selection.getRangeAt(0);
			var clone = new cloneRange(range);

			if(undoFlag && (keychar == 'z' || keychar == 'y')) return;
			if(range.collapsed) {
				if(range.startContainer.nodeType != 3) {
					var offset = 0;
					var start = range.startContainer.childNodes[range.startOffset];
					var prev = range.startContainer.childNodes[range.startOffset-1];
					if(!start || (start && start.nodeName.toLowerCase() == 'br')) {
						if(!prev || (prev && prev.nodeName.toLowerCase() == 'br')) {
							node = document.createTextNode(keychar);
							range.insertNode(node);
						}
						else {
							node = getLastTextNode(start || prev);
							node.nodeValue = node.nodeValue + keychar;
						}
						offset = node.nodeValue.length;
					}
					else {
						node = getFirstTextNode(range.startContainer.childNodes[range.startOffset]);
						node.nodeValue = keychar + node.nodeValue;
						offset = 1;
					}
					range.setStart(node, offset);
					range.collapse(true);
				}
				else {
					node = range.startContainer;
					var offset = range.startOffset;

					node.nodeValue = node.nodeValue.substr(0, offset) + keychar + node.nodeValue.substr(offset);

					range.setStart(node, offset+1);
					range.collapse(true);
				}
			}
			else {
				var node = document.createTextNode(keychar);
				range.deleteContents();
				if(!pre.childNodes.length) {
					var br = document.createElement('br');
					range.insertNode(br);
					range.setStartBefore(br);
				}
				range.insertNode(node);
				range.setStartAfter(node);
				range.collapse(true);
			}

			selection.removeAllRanges();
			selection.addRange(range);

			hint = updateSyntax(range);
			history.push(clone, hint);
			setLineNumber();

			return true;
		}

		function enter() {
			var os, oe, ns, ne;

			var range = selection.getRangeAt(0);

			caret.optimizeRangeForIE(range);

			var clone = new cloneRange(range);
			var sr = surroundRange(range);
			index = getRangeIndex(sr);
			os = index.start;
			oe = index.end;

			var br = document.createElement('br');

			if(!range.collapsed) {
				range.deleteContents();
			}
			range.insertNode(br);
			range.setStartAfter(br);
			range.collapse(true);
			selection.removeAllRanges();
			selection.addRange(range);

			setScrollTop(range);

			hint = updateSyntax(range);

			index = getRangeIndex(sr);
			ns = index.start;
			ne = index.end;

			if(hint) {
				hint.os = Math.min(hint.os, os);
				hint.oe = Math.max(hint.oe, oe);
				hint.ns = Math.min(hint.ns, ns);
				hint.ne = Math.max(hint.ne, ne);
			}
			else {
				hint = {os: os, oe: oe, ns: ns, ne: ne};
			}

			cleanUpEmptyTextNode();

			history.push(clone, hint);
		}

		function cleanUpEmptyTextNode() {
			var range = selection.getRangeAt(0);
			var nodes = new Array();
			var start = getStartObject(range);

			var node = allNodeWalk(start, 'backword', function(n, nodes) {
				if(n == start) return;
				if(n.nodeType == 3) {
					if(n.nodeValue == '') {
						nodes.push(n);
					}
					else {
						if(n.nodeValue.match(/\t/)) {
							return false;
						}
						return true;
					}
				}
			}, nodes);

			if(node && node.parentNode) node.parentNode.removeChild(node);
			for(var i=0; i<nodes.length; i++) {
				nodes[i].parentNode.removeChild(nodes[i]);
			}

			nodes = new Array();
			var node = allNodeWalk(start, 'forword', function(n, nodes) {
				if(n == start) return;
				if(n.nodeType == 3) {
					if(n.nodeValue == '') {
						nodes.push(n);
					}
					else {
						return true;
					}
				}
			}, nodes);

			if(node && node.parentNode) node.parentNode.removeChild(node);
			for(var i=0; i<nodes.length; i++) {
				nodes[i].parentNode.removeChild(nodes[i]);
			}
		}

		function tab() {
			var start, end;
			var range = selection.getRangeAt(0);
			var clone = new cloneRange(range);
			var keychar = '\t';

			cnt = selection.rangeCount;
			text = range.startContainer.nodeValue;
			var contents = range.cloneContents();
			lines = nodeWalk(contents, 'forward', isBRExist);

			if(range.collapsed) {
				if(shiftKey) return;

				if(range.startContainer.nodeType != 3) {
					var offset = 0;
					var start = range.startContainer.childNodes[range.startOffset];
					var prev = range.startContainer.childNodes[range.startOffset-1];
					if(!start || (start && start.nodeName.toLowerCase() == 'br')) {
						if(!prev || (prev && prev.nodeName.toLowerCase() == 'br')) {
							node = document.createTextNode(keychar);
							range.insertNode(node);
							offset = node.nodeValue.length;
						}
						else {
							node = getLastTextNode(range.startContainer.childNodes[range.startOffset]);
							node.nodeValue = node.nodeValue + keychar;
							offset = node.nodeValue.length;
						}
					}
					else {
						node = getFirstTextNode(range.startContainer.childNodes[range.startOffset]);
						node.nodeValue = keychar + node.nodeValue;
						offset = 1;
					}

					range.setEnd(node, offset);
					range.setStart(node, offset);
				}
				else {
					node = range.startContainer;
					var offset = range.startOffset;

					node.nodeValue = node.nodeValue.substr(0, offset) + keychar + node.nodeValue.substr(offset);

					range.setStart(node, offset+1);
					range.setEnd(node, offset+1);
				}

	            selection.removeAllRanges();
	            selection.addRange(range);
				hint = updateSyntax(range);
			}
			else if(!lines) {
				range.deleteContents();
				var tab = document.createTextNode(keychar);
				range.insertNode(tab);

				range.setStart(tab.nextSibling, 0);
				range.setEnd(tab.nextSibling, 0);
				hint = updateSyntax(range);
			}
			else if(!range.toString()) {
				range.setStart(range.startContainer, range.startOffset);
				range.setEnd(range.startContainer, range.startOffset);

	            selection.removeAllRanges();
	            selection.addRange(range);
				hint = updateSyntax(range);
			}
			else {
				start = getStartObject(range);
				if(start.nodeName.toLowerCase() == 'br') {
					start = getFirstTextNode(start);
				}
				else {
					start = getStartNodeBackword(start);
				}
				if(!start) start = pre.firstChild;

				if(range.endContainer.nodeType == 3) {
					if(!range.endOffset && range.endContainer.textContent.length) {
						end = getEndNodeBackword(range.endContainer);
					}
					else {
						end = getEndNodeForward(range.endContainer);
					}
				}
				else if(range.endContainer.nodeName.toLowerCase() == 'br') {
					end = getEndNodeBackword(range.endContainer);
				}
				else if(range.endContainer.childNodes[range.endOffset]) {
					end = getEndNodeBackword(range.endContainer.childNodes[range.endOffset]);
				}

				if(!end) end = pre.lastChild;

				first_node_of_the_line = true;
				allNodeWalk(start, 'forward', function(node) {

					if(first_node_of_the_line) {
						if(node.nodeType == 3) {
							if(shiftKey) {
								if(node.nodeValue.substr(0, 1) == '\t') {
									node.nodeValue = node.nodeValue.substr(1);
								}
								else {
									for(var i=0, n=node ; i<4 && n && n.nodeType == 3;) {
										if(!n.nodeValue.length) {
											n = n.nextSibling;
											continue;
										}
										if(n.nodeValue.substr(0, 1) == '\t') {
											n.nodeValue = n.nodeValue.substr(1);
											break;
										}
										if(n.nodeValue.substr(0, 1) != ' ') break;
										n.nodeValue = n.nodeValue.substr(1);
										i++;
									}
								}
							}
							else {
								node.nodeValue = '\t'+node.nodeValue;
							}
						}
						else if(!shiftKey && node.parentNode && node.nodeName.toLowerCase() != 'br') {
							var tab = document.createTextNode('\t');
							node.parentNode.insertBefore(tab, node);
						}
						first_node_of_the_line = false;
					}
					if(node.nodeName.toLowerCase() == 'br') {
						first_node_of_the_line = true;
					}

					if(node == end) return true;
				});

				range.setStart(start, 0);
				range.setEndAfter(end);

	            selection.removeAllRanges();
	            selection.addRange(range);

				var index = getRangeIndex(range);
				hint = {os: index.start, oe: index.end, ns: index.start, ne: index.end}
			}

			cleanUpEmptyTextNode();

			history.push(clone, hint);
		}

		function getFirstTextNode(node) {
			return nodeWalk(node, 'forward', isText);
		}

		function getSecondTextNode(node) {
			return allNodeWalk(node, 'forward', isSecondText);
		}

		function getLastTextNode(node) {
			return nodeWalk(node, 'backword', isText);
		}

		function getStartNodeBackword(node) {
			return allNodeWalk(node, 'backword', isBRNext);
		}

		function getEndNodeForward(node) {
			return allNodeWalk(node, 'forward', isBR);
		}

		function getEndNodeBackword(node) {
			return allNodeWalk(node, 'backword', isBR);
		}

		function isBRExist(node) {
			if(node.nodeName.toLowerCase() == 'br') {
				return node;
			}
		}

		function isText(node) {
			if(node.nodeType == 3) {
				return node;
			}
		}

		function isSecondText(n, nodes) {
			if(n.nodeType != 3) return;
			if(nodes.length) {
				return n;
			}
			nodes.push(n);
		}

		function isBR(n, nodes) {
			if(n.nodeName.toLowerCase() == 'br') return n;
		}

		function isBRNext(n, nodes) {
			if(nodes.length && n.nodeName.toLowerCase() == 'br') {
				return nodes.pop();
			}
			nodes.push(n);
		}

		function nodeWalk(node, direction, callback) {
			var n, i;

			if(!node) return;

			for(n=node, i=0; n; n = direction == 'forward' ? n.nextSibling : n.previousSibling, i++) {
				if(ret = callback(n, i)) {
					return ret;
				}
				if(n.childNodes.length) {
					child = direction == 'forward' ? n.firstChild : n.lastChild;
					if(ret = nodeWalk(child, direction, callback)) {
						return ret;
					}
				}
			}
		}

		function allNodeWalk(node, direction, callback, nodes, parent) {
			if(!node) return;

			if(!nodes) nodes = new Array();

			for(var n=node; n; n = direction == 'forward' ? n.nextSibling : n.previousSibling, i++) {
				if(ret = callback(n, nodes)) {
					return ret;
				}
				if(n != node && n.childNodes.length) {
					ch = direction == 'forward' ? n.firstChild : n.lastChild;
					if(ret = allNodeWalk(ch, direction, callback, nodes, n)) {
						return ret;
					}
				}
			}

			if(parent) return;
			if(node.parentNode == pre) return nodes.pop();
			if(node.parentNode) {
				return allNodeWalk(node.parentNode, direction, callback, nodes);
			}
		}

		function insertTab(node) {
			if(node.nodeType == 3 && node.nodeValue.length && first_node_of_the_line) {
				if(shiftKey) {
					if(node.nodeValue.substr(0, 1) == '\t') {
						node.nodeValue = node.nodeValue.substr(1);
					}
				}
				else {
					node.nodeValue = '\t'+node.nodeValue;
				}
				first_node_of_the_line = false;
			}
			else if(node.nodeName.toLowerCase() == 'br') {
				first_node_of_the_line = true;
			}
		}

		function escapeHTML(string) {
			return string.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
		}

		function unescapeHTML(string) {
			return string.stripTags().replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&').replace(/&nbsp;/g,' ');
		}

		function searchParentByClassName(obj, className) {
			if(!obj || obj == pre) return false;
			if(bframe.checkClassName(className, obj)) {
				return obj;
			}
			return searchParentByClassName(obj.parentNode, className);
		}

		function getRange(range) {
			var line;
			var start = getStartObject(range);

			var node = searchParentByClassName(start, multiLineCommentCss);
			if(!node) node = searchParentByClassName(start, multiLineCDataCss);
			if(!node) node = searchParentByClassName(start, multiLinePhpCss);
			if(!node) node = searchParentByClassName(start, multiLineDoubleQuotedStringCss);
			if(!node) node = searchParentByClassName(start, multiLineSingleQuotedStringCss);
			if(node) {
				// inside of multi line syntax
				line = document.createRange();
				line.selectNode(node);
				line = getLineRange(line);
			}
			else {
				line = getLineRange(range);
			}

			var line_start = getStartObject(line);
			var line_end = getEndObject(line);

			// only br
			if(line.collapse && line_start && line_start.nodeName.toLowerCase() == 'br') {
				return line;
			}

			var text = escapeHTML(line.toString());
			var sc = text.match(multiLineCommentStartTag);
			// for  /*/
			text = text.replace(multiLineCommentStartTag, '');

			var sc_length = sc ? sc.length : 0;
			var ec = text.match(multiLineCommentEndTag);
			var ec_length = ec ? ec.length : 0;
			var scd = text.match(multiLineCDataStartTag);
			var scd_length = scd ? scd.length : 0;
			var ecd = text.match(multiLineCDataEndTag);
			var ecd_length = ecd ? ecd.length : 0;
			var spd = text.match(multiLinePhpStartTag);
			var spd_length = spd ? spd.length : 0;
			var epd = text.match(multiLinePhpEndTag);
			var epd_length = epd ? epd.length : 0;
			var dq = text.match(/"/g);
			var dq_length = dq ? dq.length : 0;
			var sq = text.match(/'/g);
			var sq_length = sq ? sq.length : 0;

			var forward = [], backword = [];

			// double quoted string
			if(dq_length%2) {
				forward.push(/"/g);
				backword.push(/"/g);
			}
			// single quoted string
			if(sq_length%2) {
				forward.push(/'/g);
				backword.push(/'/g);
			}
			// multi line comment
			if(sc_length > ec_length) {
				forward.push(multiLineCommentEndTag);
			}
			if(sc_length < ec_length) {
				backword.push(multiLineCommentStartTag);
			}
			// multi line CDATA
			if(scd_length > ecd_length) {
				forward.push(multiLineCDataEndTag);
			}
			if(scd_length < ecd_length) {
				backword.push(multiLineCDataStartTag);
			}
			// multi line php script
			if(spd_length > epd_length) {
				forward.push(multiLinePhpEndTag);
			}
			if(spd_length < epd_length) {
				backword.push(multiLinePhpStartTag);
			}

			// go forward from line_end
			if(forward.length) {
				allNodeWalk(line_end, 'forward', function(n, nodes) {
					if(n.nodeType == 3) {
						for(var i=0 ; i<forward.length ; i++) {
							if(n.nodeValue.match(forward[i])) {
								forward.splice(i, 1);
								line.setEnd(n, n.nodeValue.length);
							}
						}
					}
					if(!forward.length) return true;
				});
			}

			// go backword from line_start
			if(backword.length) {
				allNodeWalk(line_start, 'backword', function(n, nodes) {
					if(n.nodeType != 3) return;
					if(n == line_start) return;
					for(var i=0 ; i<backword.length ; i++) {
						if(n.nodeValue.match(backword[i])) {
							backword.splice(i, 1);
							line.setStart(n, 0);
						}
					}
					if(!backword.length) return true;
				});
			}

			if(syntax == 'xml') {
				line = getElementRange(line);
			}

			var line_start = getStartObject(line);
			var line_end = getEndObject(line);

			// start and end must be children of pre
			if(line_start && line_start.parentNode && line_start.parentNode != pre) {
				for(n=line_start; n.parentNode && n.parentNode != pre; n=n.parentNode);
				line.setStartBefore(n);
			}
			if(line_end && line_end.parentNode && line_end.parentNode != pre) {
				for(n=line_end; n.parentNode && n.parentNode != pre; n=n.parentNode);
				line.setEndAfter(n);
			}
			return line;
		}

		function getElementRange(range) {
			var line = range.cloneRange();

			// set start
			var start = getStartObject(range);

			allNodeWalk(start, 'backword', function(n, nodes) {
				if(n.nodeType != 3 || n == start) return;
				if(n.nodeValue.match(/(&lt;|<)/g) || n.nodeValue.match(/(&gt;|>)/g)) {
					start = n;
					return true;
				}
			});

			if(start.nodeType == 3) {
				line.setStart(start, 0);
			}
			else {
				line.setStart(start.parentNode, start.parentNode.indexOf(start));
			}

			// set end
			var end = getEndObject(range);
			allNodeWalk(end, 'forward', function(n, nodes) {
				if(n.nodeType != 3 || n == end) return;
				if(n.nodeValue.match(/(&lt;|<)/g) || n.nodeValue.match(/(&gt;|>)/g)) {
					end = n;
					return true;
				}
			});

			if(end.nodeType == 3) {
				line.setEnd(end, end.nodeValue.length);
			}
			else {
				line.setEndAfter(end);
			}

			return line;
		}

		function getLineRange(range) {
			var line = range.cloneRange();

			// set startd
			var start = getStartObject(range);
			start = getStartNodeBackword(start);
			if(start) {
				if(start.nodeType == 3) {
					line.setStart(start, 0);
				}
				else {
					line.setStart(start.parentNode, start.parentNode.indexOf(start));
				}
			}

			// set end
			var end = getEndObject(range);
			end = getEndNodeForward(end);
			if(end) {
				if(end.nodeType == 3) {
					line.setEnd(end, end.nodeValue.length);
				}
				else {
					line.setEnd(end.parentNode, end.parentNode.indexOf(end));
				}
			}

			return line;
		}

		function updateSyntax(range, mode) {
			var os, oe, ns, ne;

			if(!mode) {
				caret.savePositionFromRange(range);
			}

			var line = getRange(range);
			if(!line.toString()) return;

			var sr = surroundRange(line);
			index = getRangeIndex(sr);
			os = index.start;
			oe = index.end;

			var div = document.createElement('div');
			var contents = line.extractContents();
			div.appendChild(contents);
			div.innerHTML = div.innerHTML.replace(/<br>/g, '\n');

			if(_isIE) {
				var dummy = document.createTextNode('e');
				div.appendChild(dummy);

				line.insertNode(div);
				line.selectNode(div);
				var text = escapeHTML(line.toString());
				text = text.substr(0, text.length-1).replace(/\r/g, '');
			}
			else {
				line.insertNode(div);
				line.selectNode(div);

				var text = escapeHTML(line.toString());
			}
			var html = sh.getHtml(text);

			html = html.replace(/\r/g, '');
			div.innerHTML = html.replace(/\n/g, '<br>');
			var fragment = document.createDocumentFragment();

			for(var i=0; i<div.childNodes.length; i++) {
				fragment.appendChild(cloneNode(div.childNodes[i]));
			}

			line.deleteContents();
			line.insertNode(fragment);

			index = getRangeIndex(sr);
			ns = index.start;
			ne = index.end;

			if(!mode) {
				caret.setRange(range);
			}
			selection.removeAllRanges();
			selection.addRange(range);

			return {os: os, oe: oe, ns: ns, ne: ne};
		}

		function surroundRange(range) {
			if(!range.toString()) {
				return range.cloneRange();
			}

			if(range.startContainer.nodeType == 3 || range.startContainer.nodeName.toLowerCase() == 'br') {
				start = range.startContainer;
			}
			else if(range.startContainer.childNodes[range.startOffset]) {
				start = range.startContainer.childNodes[range.startOffset];
			}
			else {
				start = range.startContainer.childNodes[range.startOffset-1];
			}
			if(range.endContainer.nodeType == 3 || range.endContainer.nodeName.toLowerCase() == 'br') {
				end = range.endContainer;
			}
			else if(range.endContainer.childNodes[range.endOffset]) {
				end = range.endContainer.childNodes[range.endOffset];
			}
			else {
				end = range.endContainer.childNodes[range.endOffset-1];
			}

			// up to pre
			for(; start.parentNode != pre ; start = start.parentNode);
			for(; end.parentNode != pre ; end = end.parentNode);

			clone = range.cloneRange();
			clone.setStartBefore(start);
			clone.setEndAfter(end);
			if(clone.endContainer.nodeType != 3 && clone.endContainer.nodeName.toLowerCase() != 'br') {
				if(!clone.endContainer.childNodes[clone.endOffset]) {
					clone.setEnd(end.parentNode, end.parentNode.indexOf(end));
				}
			}

			return clone;
		}

		function getRangeIndex(range) {
			for(start = getStartObject(range) ; start && start.parentNode != pre ; start = start.parentNode);
			for(end = getEndObject(range) ; end && end.parentNode != pre ; end = end.parentNode);

			if(!start) {
				return {start: 0, end : 0};
			}
			if(end) {
				return {start: pre.indexOf(start), end : pre.indexOf(end)};
			}
			else {
				return {start: pre.indexOf(start), end : pre.childNodes.length};
			}
		}

		function setStartNext(range) {
			if(range.endContainer.nodeType == 3) {
				range.setStartAfter(range.endContainer);
			}
			else {
				range.setStart(range.endContainer.childNodes[range.endOffset]);
			}
			range.collapse(true);
		}

		function updateEditor() {
			if(timer) return false;

			if(changeByMyself) {
				changeByMyself = false;
				return;
			}

			var range = document.createRange();
			range.setStart(pre.firstChild, 0);
			range.setEnd(terminator.firstChild, 0);
			var clone = new cloneRange(range);

			text = escapeHTML(target.value);

			text = sh.getHtml(text);
			text = text.replace(/\n/g, '<br>');

			if(_isIE) {
				pre.innerHTML = text;
			}
			else {
				// always add <br> to the last
				pre.innerHTML = text+'<br>';
			}

			history.push(clone);

			timer = setInterval(function() {
				stop();
				setLineNumber();
			}, 100);
		}

		function updateTarget() {
			if(!selection.rangeCount) return;
			if(!history.checkDirty()) return;

			var range = selection.getRangeAt(0);
			caret.savePositionFromRange(range);
			scrollTop = container.scrollTop;

			var clone = cloneNode(pre);
			editor.insertBefore(clone, terminator);

			clone.innerHTML = clone.innerHTML.replace(/<br>/g, '\n');
			clone.innerHTML = clone.innerHTML.replace(/&/g, '&amp;');

			range.selectNode(clone);

			if(_isIE) {
				var dummy = document.createTextNode('e');
				clone.appendChild(dummy);
				var value = range.toString();
				value = value.substr(0, value.length-1).replace(/\r/g, '');
			}
			else {
				// remove the last <br>
				var value = range.toString();
				value = value.substr(0, value.length-1);
			}
			target.value = unescapeHTML(value);

			editor.removeChild(clone);

			changeByMyself = true;
			bframe.fireEvent(target, 'change');
			history.setDirty();
		}

		function editCheckCallback() {
			if(history.checkDirty()) {
				bframe.editCheck_handler.setEditFlag();
			}
		}

		function resetDirtyCallback() {
			history.resetDirty();
		}

		function setScrollTop(range) {
			var padding=10;

			if(range.startContainer.nodeType == 3 || range.startContainer.nodeName.toLowerCase() == 'br') {
				node = range.startContainer;
			}
			else {
				node = range.startContainer.childNodes[range.startOffset];
				if(!node) node = range.startContainer.childNodes[range.startOffset-1];
			}

			if(_isFF || _isIE) {
				var offsetTop = 0;
				allNodeWalk(node, 'backword', function(n, i) {
					if(n.nodeName.toLowerCase() == 'br') {
						offsetTop = n.offsetTop - container.offsetTop;
						return true;
					}
				});
			}
			else {
				var span = document.createElement('span');
				var contents = document.createTextNode('contents');
				span.appendChild(contents);
				node.parentNode.insertBefore(span, node);
				var offsetTop = span.offsetTop - container.offsetTop;
				node.parentNode.removeChild(span);
			}
			var adjust = container.clientHeight % lineHeight;
			if((container.scrollTop + container.clientHeight - padding) < (offsetTop + lineHeight + adjust)) {
				// current line is under bottom of the view port
				if(offsetTop < container.scrollTop + container.clientHeight) {
					container.scrollTop += lineHeight;
				}
				else {
					container.scrollTop = offsetTop - container.clientHeight + lineHeight*2 + parseInt(padding);
				}
			}
			else if(container.scrollTop + parseInt(padding) > offsetTop) {
				// current line is above top of the view port
				if(offsetTop + lineHeight < container.scrollTop) {
					container.scrollTop = offsetTop - parseInt(padding);
				}
				else {
					container.scrollTop -= lineHeight;
				}
			}
		}

		function cloneNode(node) {
			if(!_isIE) return node.cloneNode(true);

			var clone = node.nodeType == 3 ? document.createTextNode(node.nodeValue) : node.cloneNode(false);
			var child = node.firstChild;
			while(child) {
			    clone.appendChild(cloneNode(child));
			    child = child.nextSibling;
			}
			return clone;
		}

		// -------------------------------------------------------------------------
		// class lineNumberControl
		// -------------------------------------------------------------------------
		function lineNumberControl(target) {
			var current_cnt=0;
			var lineNumber=target;

			function set(offsetHeight) {
				line_cnt = Math.max(Math.floor(offsetHeight / lineHeight), 1);
				if(line_cnt == current_cnt) return;

				if(current_cnt < line_cnt) {
					for(var i=current_cnt; i < line_cnt; i++) {
						var br = document.createElement('br');
						var number = document.createTextNode(i+1);
						if(i) {
						    lineNumber.appendChild(br);
						}
					    lineNumber.appendChild(number);
					}
				}
				else {
					for(var i=current_cnt; i > line_cnt; i--) {
					    lineNumber.removeChild(lineNumber.childNodes[(i-1)*2]);
					    lineNumber.removeChild(lineNumber.childNodes[(i-1)*2-1]);
					}
				}
				current_cnt = line_cnt;
			}

			this.set = set;
		}

		// -------------------------------------------------------------------------
		// class caret
		// -------------------------------------------------------------------------
		function caretControl() {
			var position = '';

			caretControl.prototype.clear = function() {
				position = '';
			}

			caretControl.prototype.savePosition = function(node) {
				var cnt=0;

				nodeWalk(pre.childNodes[0], 'forward', function(n, i) {
					if(n.nodeType == 3) {
						cnt +=n.nodeValue.length;
					}
					if(n.nodeName.toLowerCase() == 'br') {
						cnt++;
					}
					if(n == node) return true;
				});

				position = cnt;
			}

			caretControl.prototype.savePositionFromRange = function(range) {
				var cnt=0;
				var offset=0;
				if(range.startContainer.nodeType == 3) {
					var node = range.startContainer;
					var offset = range.startOffset;
				}
				else {
					var node = range.endContainer.childNodes[range.endOffset]
					if(!node) {
						node = range.endContainer.childNodes[range.endOffset-1];
						var offset = 1;
					}
				}
				if(!node) return;

				nodeWalk(pre.childNodes[0], 'forward', function(n, i) {
					if(n == node) return true;
					if(n.nodeType == 3) {
						cnt +=n.nodeValue.length;
					}
					if(n.nodeName.toLowerCase() == 'br') {
						cnt++;
					}
				});

				position = cnt+offset;
			}

			caretControl.prototype.saveEndPositionFromRange = function(range) {
				var cnt=0;
				var offset=0;
				if(range.endContainer.nodeType == 3) {
					var node = range.endContainer;
					var offset = range.endOffset;
				}
				else {
					var node = range.endContainer.childNodes[range.endOffset]
					if(!node) {
						node = range.endContainer.childNodes[range.endOffset-1];
						var offset = 1;
					}
				}
				if(!node) return;

				nodeWalk(pre.childNodes[0], 'forward', function(n, i) {
					if(n == node) return true;
					if(n.nodeType == 3) {
						cnt +=n.nodeValue.length;
					}
					if(n.nodeName.toLowerCase() == 'br') {
						cnt++;
					}
				});

				position = cnt+offset;
			}

			caretControl.prototype.setRange = function(range) {
				if(position == '') return false;

				var cnt=0;
				var node = pre;
				var offset = pre.childNodes.length-1;

				var ret = nodeWalk(pre.childNodes[0], 'forward', function(n, i) {
					if(n.nodeType == 3) {
						if((position < cnt + n.nodeValue.length) || (position == cnt + n.nodeValue.length && !n.nextSibling)) {
							node = n;
							offset = position - cnt;
							return true;
						}
						cnt += n.nodeValue.length;
					}
					if(n.nodeName.toLowerCase() == 'br') {
						if(position < ++cnt) {
							node = n.parentNode;
							offset = i;
							return true;
						}
					}
				});

				if(ret) {
					range.setStart(node, offset);
				}
				else {
					if(pre.lastChild) {
						range.setStartAfter(pre.lastChild);
					}
					else {
						range.setStart(pre, 0);
					}
				}
				range.collapse(true);

				optimizeRangeForIE(range);

				return true;
			}

			function optimizeRangeForIE(range) {
				var start = getStartObject(range);

				// for IE
				if(range.startContainer.nodeType == 3 && start.nodeValue.length == range.startOffset) {
					for(var node=start; node.parentNode != pre && !node.nextSibling; node=node.parentNode);
					range.setStartAfter(node);
					range.collapse(range.collapsed);
				}
				// for IE
				if(range.startContainer.nodeType == 3 && range.startOffset == 0) {
					for(var node=start; node.parentNode != pre && !node.previousSibling; node=node.parentNode);
					range.setStart(node.parentNode, node.parentNode.indexOf(node));
					range.collapse(range.collapsed);
				}
			}
			caretControl.prototype.optimizeRangeForIE = optimizeRangeForIE;
		}

		// -------------------------------------------------------------------------
		// class cloneRange
		// -------------------------------------------------------------------------
		function cloneRange(range) {
			var startNodeOffset;
			var startCharOffset;
			var endNodeOffset;
			var endCharOffset;

			if(range.startContainer == pre) {
				startNodeOffset = range.startOffset;
				startCharOffset = 0;
			}
			else {
				for(var n = range.startContainer; n && n.parentNode != pre; n = n.parentNode);
				if(n) {
					for(var i=0; pre.childNodes[i] != n; i++);
					startNodeOffset = i;

					var offset=0;
					if(range.startContainer.childNodes.length && range.startOffset) {
						var start = range.startContainer.childNodes[range.startOffset];
						var offset = 0;
					}
					else {
						var start = range.startContainer;
						if(start.nodeType == 3) {
							offset = range.startOffset;
						}
					}
					var cnt=0;
					nodeWalk(n, 'forward', function(n, i) {
						if(n == start) return true;
						if(n.nodeType == 3) {
							cnt +=n.nodeValue.length;
						}
						if(n.nodeName.toLowerCase() == 'br') {
							cnt++;
						}
					});
					startCharOffset = cnt+offset;
				}
				else {
					startNodeOffset = 0;
					startCharOffset = 0;
				}
			}
			if(range.collapsed) {
				endNodeOffset = startNodeOffset;
				endCharOffset = startCharOffset;
			}

			this.setRange = function(range) {
				var start = pre.childNodes[startNodeOffset];
				if(!start) {
					start = pre.childNodes[startNodeOffset-1];
					if(start) {
						range.setStartAfter(start);
						return;
					}
				}

				var node;
				var cnt=0;

				nodeWalk(start, 'forward', function(n, i) {
					if(n.nodeType == 3) {
						if(startCharOffset <= cnt + n.nodeValue.length) {
							node = n;
							offset = startCharOffset - cnt;

							if(startCharOffset < cnt + n.nodeValue.length) {
								return true;
							}
						}
						cnt += n.nodeValue.length;
					}
					if(n.nodeName.toLowerCase() == 'br') {
						if(startCharOffset < ++cnt) {
							node = n.parentNode;
							offset = i;
							if(n.parentNode == pre) {
								offset+= startNodeOffset;
							}
							return true;
						}
					}
				});
				if(node) {
					range.setStart(node, offset);
				}
				else {
					range.setStart(pre, 0);
				}
				range.collapse(true);
			}
		}

		// -------------------------------------------------------------------------
		// class historyControl
		// -------------------------------------------------------------------------
		function historyControl(pre) {
			var self = this;
			var pre = pre;
			var prev = cloneNode(pre);

			var buffer = new Array();
			var index = 0;
			var current;
			var edit_flag;

			this.push = function(range, hint) {
				while(index < buffer.length) {
					buffer.pop();
				}

				if(hint) {
					os = ns = hint.os < hint.ns ? hint.os : hint.ns;
					oe_length = prev.childNodes.length - hint.oe;
					ne_length = pre.childNodes.length - hint.ne;
					end_counter = Math.min(oe_length, ne_length);
					if(end_counter < 5) end_counter = 1;
				}
				else {
					os = ns = 0;
					end_counter = 1;
				}

				if(prev) {
					for(; os<prev.childNodes.length && ns<pre.childNodes.length;os++, ns++) {
						if(!diff(prev.childNodes[os], pre.childNodes[ns])) {
							var changed = true;
							break;
						}
					}
					if(!changed && prev.childNodes.length == pre.childNodes.length) return;
					for(oe=prev.childNodes.length - end_counter, ne=pre.childNodes.length - end_counter; oe>os && ne>ns; oe--, ne--) {
						if(!diff(prev.childNodes[oe], pre.childNodes[ne])) {
							break;
						}
					}
					var h = new history_inf(os, oe, ns, ne, range);
					buffer.push(h);
					index = buffer.length;
				}

				// update prev
				for(var i=h.os; i<=h.oe; i++) {
					prev.removeChild(prev.childNodes.item(h.os));
				}

				if(prev.childNodes[h.ns]) {
					prev.insertBefore(cloneNode(h._new), prev.childNodes[h.ns]);
				}
				else {
					prev.appendChild(cloneNode(h._new));
				}
			}

			function diff(a, b) {
				if(a.nodeType != b.nodeType) return false;
				if(a.nodeName.toLowerCase() != b.nodeName.toLowerCase()) return false;
				if(a.nodeType == 3) {
					return a.nodeValue == b.nodeValue;
				}
				else {
					if(a.childNodes.length != b.childNodes.length) {
						return false;
					}
					for(var i=0 ; i<a.childNodes.length ; i++) {
						if(!diff(a.childNodes[i], b.childNodes[i])) {
							return false;
						}
					}
				}
				return true;
			}

			this.checkDirty = function() {
				return index != default_index || edit_flag;
			}

			this.setDirty = function() {
				edit_flag = true;
			}

			this.resetDirty = function() {
				edit_flag = false;
				default_index = index;
			}

			this.redo = function() {
				if(index >= buffer.length) return;

				if(selection.rangeCount) {
					var range = selection.getRangeAt(0);
				}
				else {
					var range = document.createRange();
					range.setStart(pre.firstChild, 0);
					range.collapse(true);
				}

				var inf = buffer[index++];
				if(buffer[index]) {
					var _range = buffer[index].range;
				}
				else {
					var _range = current;
				}

				for(var i=inf.os; i<=inf.oe; i++) {
					pre.removeChild(pre.childNodes.item(inf.os));
					prev.removeChild(prev.childNodes.item(inf.os));
				}
				if(inf._new.childNodes.length) {
					if(pre.childNodes[inf.ns]) {
						pre.insertBefore(cloneNode(inf._new), pre.childNodes[inf.ns]);
						prev.insertBefore(cloneNode(inf._new), prev.childNodes[inf.ns]);
					}
					else {
						pre.appendChild(cloneNode(inf._new));
						prev.appendChild(cloneNode(inf._new));
					}
				}
				_range.setRange(range);
				selection.removeAllRanges();
				selection.addRange(range);

				setScrollTop(range);
			}

			this.undo = function() {
				if(!index) return true;

				if(selection.rangeCount) {
					var range = selection.getRangeAt(0);
				}
				else {
					var range = document.createRange();
					range.setStart(pre.firstChild, 0);
					range.collapse(true);
				}

				if(index == buffer.length) {
					current = new cloneRange(range);
				}
				var inf = buffer[--index];
				for(var i=inf.ns; i<=inf.ne; i++) {
					pre.removeChild(pre.childNodes.item(inf.ns));
					prev.removeChild(prev.childNodes.item(inf.ns));
				}
				if(inf._old.childNodes.length) {
					if(pre.childNodes[inf.os]) {
						pre.insertBefore(cloneNode(inf._old), pre.childNodes[inf.os]);
						prev.insertBefore(cloneNode(inf._old), prev.childNodes[inf.os]);
					}
					else {
						pre.appendChild(cloneNode(inf._old));
						prev.appendChild(cloneNode(inf._old));
					}
				}
				inf.range.setRange(range);
				selection.removeAllRanges();
				selection.addRange(range);

				setScrollTop(range);
			}

			function history_inf(os, oe, ns, ne, range) {
				this._old = document.createDocumentFragment();
				this._new = document.createDocumentFragment();
				this.os = os;
				this.oe = oe;
				this.ns = ns;
				this.ne = ne;
				this.range = range;

				for(var i=os; i<=oe; i++) {
				    this._old.appendChild(cloneNode(prev.childNodes[i]));
				}
				for(var i=ns; i<=ne; i++) {
				    this._new.appendChild(cloneNode(pre.childNodes[i]));
				}
			}
		}
	}