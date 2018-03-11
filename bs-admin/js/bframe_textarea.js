/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeTextareaInit);

	function bframeTextareaInit() {
		var objects = document.querySelectorAll('textarea.bframe_textarea');

		for(var i=0, j=0; i < objects.length; i++) {
			var t = new bframe.textarea(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.textarea
	// 
	// -------------------------------------------------------------------------
	bframe.textarea = function(_target) {
		var self = this;
		var target = _target;
		var container;
		var textarea;
		var placeholder;
		var resizer;
		var dragging;
		var draggStartMousePosition;
		var draggStartElementPosition;
		var overlay;
		var scroll;
		var minWidth = 50;
		var minHeight = 30;

		//container
		container = document.createElement('div');
		container.style.position = 'relative';
		container.style.boxSizing = 'border-box';

		target.parentNode.insertBefore(container, target);
		var container_position = bframe.getElementPosition(container);

		// placeholder
		placeholder = target.cloneNode(true);

		//textarea
		textarea = document.createElement('pre');

		var style = bframe.getStyle(target);
		for(var i=0; i < style.length; i++) {
			var property = style[i];
			var value = style.getPropertyValue(property);
			switch(property) {
			case '-webkit-user-modify':
				continue;

			default:
				textarea.style.setProperty(property, value);
				placeholder.style.setProperty(property, value);
				break;
			}
		}
		textarea.style.boxSizing = 'border-box';
		textarea.style.resize = 'none';
		textarea.style.overflow = 'hidden';
		textarea.style.display = 'block';
		textarea.style.zIndex = '2';
		textarea.style.backgroundColor = 'transparent';
		textarea.contentEditable = true;

		container.appendChild(textarea);

		container.style.width = textarea.style.width;
		container.style.height = textarea.style.height;

		target.style.display = 'none';

		placeholder.style.position = 'absolute';
		placeholder.style.top = '0';
		placeholder.style.zIndex = '1';

		container.appendChild(placeholder);

		if(target.innerHTML) {
			textarea.innerHTML = target.innerHTML;
		}
		else {
//			if(target.placeholder) {
//				placeholder.placeholder = target.placeholder;
//			}
			textarea.innerHTML = '<br />';
		}

		// resizer
		resizer = document.createElement('img');
		resizer.src = 'images/common/resizer.png';
		resizer.style.position = 'absolute';
		resizer.style.right = 0;
		resizer.style.bottom = 0;
		resizer.style.cursor = 'nwse-resize';

		container.appendChild(resizer);

		createOverlay();

		scroll = new bframe.scroll(textarea);

		bframe.addEventListener(textarea, 'keyup' , onKeyup);
		bframe.addEventListener(resizer, 'mousedown' , onMouseDown);
		bframe.addEventListener(window, 'mousemove' , onMouseMove);
		bframe.addEventListener(window, 'mouseup' , onMouseUp);

		function onKeyup(event) {
			target.innerHTML = textarea.innerText;
			scroll.onResize();
		}

		function onMouseDown(event) {
			draggStartMousePosition = bframe.getMousePosition(event);
			draggStartElementPosition = bframe.getElementPosition(textarea);

			var wsize = bframe.getWindowSize();

			overlay.style.width = wsize.width + 'px';
			overlay.style.height = wsize.height + 'px';
			overlay.style.cursor = 'nwse-resize';

			dragging = true;

			event.preventDefault();
		}

		function onMouseMove(event) {
			resizer.style.cursor = 'nwse-resize';
			if(!dragging) return;

			var mpos = bframe.getMousePosition(event);
			var width = draggStartElementPosition.width + mpos.x - draggStartMousePosition.x;
			var height = draggStartElementPosition.height + mpos.y - draggStartMousePosition.y;
			if(width < minWidth) width = minWidth;
			if(height < minHeight) height = minHeight;

			container.style.width = width + 'px';
			container.style.height = height + 'px';
			textarea.style.width = width + 'px';
			textarea.style.height = height + 'px';
		}

		function onMouseUp(event) {
			if(!dragging) return;

			overlay.style.width = 0;
			overlay.style.height = 0;
			overlay.style.cursor = 'pointer';
			resizer.style.cursor = 'pointer';

			dragging = false;

			bframe.fireEvent(window, 'resize');
		}

		function createOverlay() {
			overlay = document.getElementById('bframe_textarea_dragg_overlay');
			if(!overlay) {
				overlay = document.createElement('div');
				overlay.id = 'bframe_textarea_dragg_overlay';
				overlay.style.position = 'absolute';
				overlay.style.top = 0;
				overlay.style.left = 0;
				overlay.style.width = 0;
				overlay.style.height = 0;
				overlay.style.opacity = '0';
				overlay.style.zIndex = 900;
				overlay.style.cursor = 'nwse-resize';

				document.body.appendChild(overlay);
			}
		}
	}
