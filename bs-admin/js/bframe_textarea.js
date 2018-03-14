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
	bframe.textarea = function(target) {
		var self = target;
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
		container.style.overflow = 'hidden';
		container.style.maxWidth = self.offsetWidth + 'px';
		container.style.height = self.offsetHeight + 'px';

		self.style.resize = 'none';

		self.parentNode.insertBefore(container, self);
		container.appendChild(self);

		// resizer
		resizer = document.createElement('img');
		resizer.src = 'images/common/resizer.png';
		resizer.style.position = 'absolute';
		resizer.style.zIndex = '999999';
		resizer.style.right = 0;
		resizer.style.bottom = 0;
		resizer.style.cursor = 'nwse-resize';

		container.appendChild(resizer);

		createOverlay();

		scroll = new bframe.scroll(self, 'textarea');

		bframe.addEventListener(self, 'keyup', onKeyUp);
		bframe.addEventListener(resizer, 'mousedown', onMouseDown);
		bframe.addEventListener(window, 'mousemove', onMouseMove);
		bframe.addEventListener(window, 'mouseup', onMouseUp);

		function onKeyUp(event) {
			scroll.onResize();
		}

		function onMouseDown(event) {
			draggStartMousePosition = bframe.getMousePosition(event);
			draggStartElementPosition = bframe.getElementPosition(self);

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
/*
			container.style.width = width + 'px';
			container.style.height = height + 'px';
			self.style.width = width + 'px';
			self.style.height = height + 'px';
*/
			container.style.maxWidth = width + 'px';
			container.style.height = height + 'px';
			self.style.maxWidth = width + 'px';
			self.style.height = height + 'px';
		}

		function onMouseUp(event) {
			if(!dragging) return;

			overlay.style.width = 0;
			overlay.style.height = 0;
			overlay.style.cursor = 'auto';
			resizer.style.cursor = 'auto';

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
