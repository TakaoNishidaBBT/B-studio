/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeContentEditorInit);

	function bframeContentEditorInit() {
		var objects = document.querySelectorAll('textarea.bframe_contenteditor');
		for(var i=0, j=0; i < objects.length; i++) {
			if(objects[i].getAttribute('data-bframe-contenteditor')) continue;
			var t = new bframe.contenteditor(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.contenteditor
	// 
	// -------------------------------------------------------------------------
	bframe.contenteditor = function(target) {
		var self = target;
		var textarea;
		var scroll;
		var setMaxSize;
		var param = target.getAttribute('data-param');
		var minHeight = bframe.getStyle(self).height;
		if(param) {
			// autogrow
			var autogrow = bframe.getParam('autogrow', param);
		}

		self.style.resize = 'none';

		var footer = document.createElement('div');
		footer.className = 'bframe-contenteditor-footer';
		self.parentNode.appendChild(footer);

		bframe.addEventListener(self, 'keyup', onKeyUp);
		bframe.addEventListener(window, 'resize', onResize);

		if(autogrow) {
			self.style.overflow = 'hidden';
			setHeight();
		}
		else {
			scroll = new bframe.scroll(self, 'textarea');
		}

		self.setAttribute('data-bframe-contenteditor', true);

		function setHeight() {
			if(!autogrow) return;

			if(self.clientHeight < self.scrollHeight) {
				self.style.height = self.scrollHeight + 'px';
			}
			else {
				self.style.height = minHeight;
				if(self.clientHeight < self.scrollHeight) {
					self.style.height = self.scrollHeight + 'px';
				}
			}
		}

		function onResize(event) {
			if(bframe.isVisible(self) && !setMaxSize) {
				self.style.width = '100%';
				footer.style.position = 'fixed';
				footer.style.bottom = '0';

				setMaxSize = true;
			}

			footer.style.width = self.offsetWidth + 'px';
		}

		function onKeyUp(event) {
			setHeight();
			if(scroll) scroll.onResize();
			bframe.fireEvent(window, 'resize');
		}
	}
