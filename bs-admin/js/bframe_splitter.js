/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load', bframeSplitterInit);

	function bframeSplitterInit(){
		var objects = document.getElementsByClassName('bframe_splitter');

		for(var i=0; i < objects.length; i++) {
			s = new bframe.splitter(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.splitter
	// 
	// -------------------------------------------------------------------------
	bframe.splitter = function(target) {
		var self = target;
		var resize_status = false;
		var start_x;
		var param = target.getAttribute('data-param');
		var margin = 0;
		var splitbar_width = target.offsetWidth;

		if(param) {
			m = bframe.getParam('margin', param);
			if(m) margin = m;
		}

		target.style.visibility = 'visible';
		var pos = bframe.getElementPosition(target);

		var w = window.innerWidth  || document.documentElement.clientWidth  || document.body.clientWidth;
		var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

		target.style.height = h - pos.top - margin + 'px';
		target.style.cursor = 'e-resize';
		self.style.cursor = 'e-resize';
		var splitbar = new bframe.splitbar(this, target);
		var pane = new bframe.pane(self);
		var ep = bframe.getElementPosition(self);
		pane.setTotalWidth(w);
		pane.setElementPosition(ep);
		pane.position(ep);

		var size = {width: target.offsetWidth, height: target.offsetHeight};
		bframe.resize_handler.registerCallBackFunction(_adjustWindow);

		var drag_overlay = document.createElement('div');

		drag_overlay.className = 'drag_overlay';
		drag_overlay.style.display = 'none';
		drag_overlay.style.position = 'absolute';
		drag_overlay.style.top = 0;
		drag_overlay.style.left = 0;
		drag_overlay.style.opacity = 0;
		drag_overlay.style.backgroundColor = '#f00';
		drag_overlay.style.cursor = 'e-resize';

		document.body.appendChild(drag_overlay);

		bframe.addEventListener(self, 'mousedown', start);
		bframe.addEventListener(drag_overlay, 'mousemove', resize);
		bframe.addEventListener(drag_overlay, 'mouseup', stop);

		setEventHandler();

		function setEventHandler() {
			// set event handller
			bframe.addEventListenerAllFrames(top, 'load', setEventHandlerAllFrames);
			bframe.addEventListenerAllFrames(top, 'mousemove', resize);
			bframe.addEventListenerAllFrames(top, 'mouseup', stop);
		}

		function setEventHandlerAllFrames() {
			if(typeof bframe == 'undefined' || !bframe){
				return;
			}
			bframe.addEventListenerAllFrames(top, 'mousemove', resize);
			bframe.addEventListenerAllFrames(top, 'mouseup', stop);
		}

		function cleanUp() {
			bframe.removeEventListenerAllFrames(top, 'load', setEventHandlerAllFrames);
			bframe.removeEventListenerAllFrames(top, 'mousemove', resize);
			bframe.removeEventListenerAllFrames(top, 'mouseup', stop);

			splitbar.cleanUp();
		}


		function start(event) {
			setEventHandlerAllFrames();

			var w = top.window.innerWidth || top.document.documentElement.clientWidth || top.document.body.clientWidth;
			var h = top.window.innerHeight || top.document.documentElement.clientHeight || top.document.body.clientHeight;
			drag_overlay.style.width = w + 'px';
			drag_overlay.style.height = h + 'px';
			drag_overlay.style.display = 'block';

			var mp = bframe.getMousePosition(event);
			var ep = bframe.getElementPosition(self);
			pane.setElementPosition(ep);
			start_x = mp.screenX - ep.left;
			var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
			pane.setTotalWidth(w);
			splitbar.position(ep);
			splitbar.show();
			self.style.visibility = 'hidden';

			bframe.cancelEvent(event);

			resize_status = true;
		}

		function resize(event) {
			if(!resize_status) return;

			var w = document.documentElement.clientWidth  || document.body.clientWidth;
			var mp = bframe.getMousePosition(event);
			var ep = bframe.getElementPosition(self);
			var rp = mp.screenX - start_x;

			if(w - rp < splitbar_width) {
				ep.left = parseInt(w - splitbar_width);
			}
			else if(rp < 0) {
				ep.left = 0;
			}
			else {
				ep.left = rp;
			}
			splitbar.position(ep);
		}

		function stop(event) {
			if(!resize_status) return;

			var w = document.documentElement.clientWidth  || document.body.clientWidth;
			var mp = bframe.getMousePosition(event);
			var ep = bframe.getElementPosition(self);
			var rp = mp.screenX - start_x;

			if(w - rp < splitbar_width) {
				ep.left = parseInt(w - splitbar_width);
			}
			else if(rp < 0) {
				ep.left = 0;
			}
			else {
				ep.left = rp;
			}

			pane.position(ep);
			splitbar.hide();
			self.style.visibility = 'visible';
			drag_overlay.style.display = 'none';

			resize_status = false;

			bframe.fireEvent(window, 'resize');
		}

		function _adjustWindow() {
			var w = document.documentElement.clientWidth  || document.body.clientWidth;
			var h = document.documentElement.clientHeight || document.body.clientHeight;

			size.width = target.offsetWidth;
			size.height = h - pos.top - margin;
			target.style.height = size.height + 'px';
			splitbar.size(size);
			pane.setTotalWidth(w);
			var ep = bframe.getElementPosition(self);
			pane.setElementPosition(ep);
			pane.position(ep);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.splitbar
	// 
	// -------------------------------------------------------------------------
	bframe.splitbar = function(split, obj) {
		var target = obj;
		var popup = new bframe.popup(window, 10000, false);
		var width, height;
		var element = window.document.createElement('div');
		var style = '';

		function setStyle() {
			style = bframe.getStyle(target);
			if(!style) return;

			element.style.backgroundColor = style.backgroundColor;
			element.style.backgroundImage = style.backgroundImage;
			element.style.backgroundRepeat = style.backgroundRepeat;
			if(style.backgroundPosition) {
				element.style.backgroundPosition = style.backgroundPosition;
			}
			else {
				element.style.backgroundPositionX = style.backgroundPositionX;
				element.style.backgroundPositionY = style.backgroundPositionY;
			}
			element.style.width = '100%';
			element.style.height = '100%';
			element.style.overflow = 'hidden';
			element.style.cursor = 'e-resize';
			var size = {width: obj.offsetWidth, height: obj.offsetHeight};
			popup.size(size);
		}

		ep = bframe.getElementPosition(obj);
		popup.position(ep);

		popup.appendChild(element);

		this.getElement = function() {
			return element;
		}

		this.getImg = function() {
			return img;
		}

		this.show = function() {
			setStyle();
			popup.show();
		}

		this.hide = function() {
			popup.hide();
		}

		this.position = function(p) {
			popup.position(p);
		}

		this.cleanUp = function() {
			popup.cleanUp();
		}

		this.size = function(s) {
			popup.size(s, true);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.pane
	// 
	// -------------------------------------------------------------------------
	bframe.pane = function(splitbar) {
		var left_pane, right_pane;
		var splitbar_width = splitbar.offsetWidth;
		var total_width;
		var element_position;
		var right_pane_width;

		var objects = document.getElementsByClassName('bframe_splitter_pane');

		for(var i=0; i < objects.length; i++) {
			if(!left_pane) {
				left_pane = objects[i];
			}
			else {
				right_pane = objects[i];
				break;
			}
		}

		this.setElementPosition = function(ep) {
			element_position = ep;
		}

		this.position = function(p) {
			left_pane.style.width = parseInt(p.left + left_pane.offsetWidth - element_position.left)+ 'px';
			if(right_pane.tagName.toLowerCase() == 'iframe') {
				right_pane.style.width = parseInt(total_width - p.left - splitbar_width - 1) + 'px';
			}
		}

		this.positionLeft = function(p) {
			left_pane.style.width = parseInt(p.left + left_pane.offsetWidth - element_position.left)+ 'px';
		}

		this.setTotalWidth = function(width) {
			if(width) {
				total_width = width;
			}
			else {
				total_width = left_pane.offsetWidth+ right_pane.offsetWidth;
			}
		}
	}
