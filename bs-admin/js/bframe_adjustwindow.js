/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeAdjustWindowInit);

	function bframeAdjustWindowInit() {
		var i, aw, ap;

		var objects = document.getElementsByClassName('bframe_adjustwindow');
		for(i=0; i<objects.length; i++) {
			aw = new bframe.adjustwindow(objects[i]);
		}

		var objects = document.getElementsByClassName('bframe_adjustparent');
		for(i=0; i<objects.length; i++) {
			ap = new bframe.adjustparent(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.adjustwindow
	// 
	// -------------------------------------------------------------------------
	bframe.adjustwindow = function(target) {
		var self = target;
		var param = target.getAttribute('data-param');
		var margin = 0;

		if(param) {
			var m = bframe.getParam('margin', param);
			if(m) margin = m;
		}

		if(self.tagName.toLowerCase() == 'iframe') {
			var offset = bframe.getFrameOffset(self.contentWindow, '');
		}

		bframe.addEventListener(target, 'load' , adjustWindow);
		bframe.resize_handler.registerCallBackFunction(adjustWindow);
		bframe.resize_handler.onResize();

		function adjustWindow() {
			if(self.tagName.toLowerCase() == 'iframe') {
				var wsize = bframe.getWindowSize();
				self.height = wsize.height - offset.top - margin + 'px';
			}
			else {
				var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
				var pos = bframe.getElementPosition(self);
				var height = h - pos.top - margin;
				if(height > 0) {
					if(self.tagName.toLowerCase() == 'img') {
						self.style.maxHeight = height + 'px';
					}
					else {
						self.style.height = height + 'px';
					}
				}
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.adjustparent
	// 
	// -------------------------------------------------------------------------
	bframe.adjustparent = function(target) {
		var self = target;
		var pos = bframe.getElementPosition(self);
		var param = target.getAttribute('data-param');
		var margin = 0;
		var param_height = 100;

		if(param) {
			var m = bframe.getParam('margin', param);
			if(m) margin = m;
			var ph = bframe.getParam('height', param);
			if(ph) param_height = ph.replace(/%/, '');
		}

		bframe.resize_handler.registerCallBackFunction(adjustParent);
		bframe.resize_handler.onResize();

		function adjustParent() {
			var p, h;

			if(self.parentNode.tagName.toLowerCase() == 'form') {
				p = self.parentNode.parentNode;
			}
			else {
				p = self.parentNode;
			}

			if(p.clientHeight) {
				h = p.clientHeight;
			}
			else {
				h = p.style.height;
				h = h.replace('px', '');
			}

			var style = window.getComputedStyle(self);
			var margin_top = parseInt(style.marginTop);	
			var margin_bottom = parseInt(style.marginBottom);
			var padding_top = parseInt(style.paddingTop);	
			var padding_bottom = parseInt(style.paddingBottom);
			var border_top = parseInt(style.borderTopWidth);	
			var border_bottom = parseInt(style.borderBottomWidth);

			if(style.boxSizing == 'border-box') {
				var height = h - margin - margin_top - margin_bottom;
			}
			else {
				var height = h - margin - margin_top - margin_bottom - padding_top - padding_bottom - border_top - border_bottom;
			}

			if(style.transform) {
				var transform = style.transform.replace('matrix', '').replace('(', '').replace(')', '').split(',');
				var scaleY = transform[3];
			}
			if(param_height) {
				height = height * param_height / 100;
			}
			if(self.tagName.toLowerCase() == 'iframe') {
				if(scaleY) {
					self.height = (height / scaleY) + 'px';
				}
				else {
					self.height = height + 'px';
				}
			}
			else if(self.tagName.toLowerCase() == 'img') {
				self.style.maxHeight = height + 'px';
			}
			else {
				self.style.height = height + 'px';
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.windowResizeHandler
	// 
	// -------------------------------------------------------------------------
	bframe.windowResizeHandler = function() {
		var resize_status = false;
		var last_size;
		var timer;
		var cb = [];

		bframe.addEventListener(window, 'resize' , onResize);

		function onResize() {
			if(resize_status) return;

			resize_status = true;
			start();
		}

		function start() {
			if(typeof bframe != 'undefined' && bframe){
				last_size = bframe.getWindowSize();
				timer = setInterval(compare, 4);
			}
		}

		function stop() {
			clearInterval(timer);
			resize_status = false;
		}

		function compare() {
			if(!last_size) return;
			current_size = bframe.getWindowSize();
			if(last_size.width == current_size.width && last_size.height == current_size.height) {
				adjustWindowExecuteCallBack();
				stop();
			}
			else {
				last_size = current_size;
			}
		}

		this.registerCallBackFunction = function(func) {
			cb.push(func);
		}

		function adjustWindowExecuteCallBack() {
			for(var i=0 ; i<cb.length ; i++) {
				func = cb[i];
				func();
			}
		}

		this.onResize = onResize;
		this.executeCallBack = adjustWindowExecuteCallBack;
	}

	if(!bframe.resize_handler) {
		bframe.resize_handler = new bframe.windowResizeHandler;
	}
