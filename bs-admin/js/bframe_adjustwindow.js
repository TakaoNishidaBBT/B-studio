/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeAdjustWindowInit);

	function bframeAdjustWindowInit(){
		var i, aw, ap;

		var div = document.getElementsByTagName('div');
		for(i=0; i<div.length; i++) {
			if(bframe.checkClassName('bframe_adjustwindow', div[i])) {
				aw = new bframe.adjustwindow(div[i]);
			}
			if(bframe.checkClassName('bframe_adjustparent', div[i])) {
				aw = new bframe.adjustparent(div[i]);
			}
		}

		var iframe = document.getElementsByTagName('iframe');
		for(i=0; i<iframe.length; i++) {
			if(bframe.checkClassName('bframe_adjustwindow', iframe[i])) {
				aw = new bframe.adjustwindow(iframe[i]);
			}
			if(bframe.checkClassName('bframe_adjustparent', iframe[i])) {
				aw = new bframe.adjustparent(iframe[i]);
			}
		}

		var textarea = document.getElementsByTagName('textarea');
		for(i=0; i<textarea.length; i++) {
			if(bframe.checkClassName('bframe_adjustparent', textarea[i])) {
				ap = new bframe.adjustparent(textarea[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.adjustwindow
	// 
	// -------------------------------------------------------------------------
	bframe.adjustwindow = function(target) {
		var self = target;
		var param = target.getAttribute('param');
		var margin = 0;

		if(param) {
			var m = bframe.getParam('margin', param);
			if(m) margin = m;
		}

		if(self.tagName.toLowerCase() == 'iframe') {
			var offset = bframe.getFrameOffset(self.contentWindow, '');
		}

		bframe.addEventListner(target, 'load' , adjustWindow);
		bframe.resize_handler.registCallBackFunction(adjustWindow);
		bframe.resize_handler.onResize();

		function adjustWindow() {
			if(self.tagName.toLowerCase() == 'iframe') {
				var wsize = bframe.getWindowSize();
				self.height = wsize.height - offset.top - margin + 'px';
			}
			else {
				var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
				var pos = bframe.getElementPosition(self);
				self.style.height = h - pos.top - margin + 'px';
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
		var param = target.getAttribute('param');
		var margin = 0;
		var param_height = 100;

		if(param) {
			var m = bframe.getParam('margin', param);
			if(m) margin = m;
			var ph = bframe.getParam('height', param);
			if(ph) param_height = ph.replace(/%/, '');
		}

		bframe.resize_handler.registCallBackFunction(adjustParent);
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

			var height = h - margin;
			if(param_height) {
				height = height * param_height / 100;
			}
			if(self.tagName.toLowerCase() == 'iframe') {
				self.height = height + 'px';
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

		bframe.addEventListner(window, 'resize' , onResize);

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

		this.registCallBackFunction = function(func) {
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
