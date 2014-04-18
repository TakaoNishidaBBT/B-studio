/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeSplitterInit);

	function bframeSplitterInit(){
	    var div = document.getElementsByTagName('div');

	    for(var i=0; i<div.length; i++) {
			if(bframe.checkClassName('bframe_splitter', div[i])) {
				s = new bframe.splitter(div[i]);
			}
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
		var param = target.getAttribute('param');
		var margin = 0;

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
		var pain = new bframe.pain(self);
		var ep = bframe.getElementPosition(self);
		pain.setTotalWidth(w);
		pain.setElementPosition(ep);
		pain.position(ep);

		var size = {width: target.offsetWidth, height: target.offsetHeight};
		bframe.resize_handler.registCallBackFunction(_adjustWindow);

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

		bframe.addEventListner(self, 'mousedown', start);
		bframe.addEventListner(drag_overlay, 'mousemove', resize);
		bframe.addEventListner(drag_overlay, 'mouseup', stop);

		setEventHandler();

		function setEventHandler() {
			// set event handller
			bframe.addEventListnerAllFrames(top, 'load', setEventHandlerAllFrames);
			bframe.addEventListnerAllFrames(top, 'mousemove', resize);
			bframe.addEventListnerAllFrames(top, 'mouseup', stop);
		}

		function setEventHandlerAllFrames() {
			if(typeof bframe == 'undefined' || !bframe){
				return;
			}
			bframe.addEventListnerAllFrames(top, 'mousemove', resize);
			bframe.addEventListnerAllFrames(top, 'mouseup', stop);
		}

		function cleanUp() {
			bframe.removeEventListnerAllFrames(top, 'load', setEventHandlerAllFrames);
			bframe.removeEventListnerAllFrames(top, 'mousemove', resize);
			bframe.removeEventListnerAllFrames(top, 'mouseup', stop);

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
			pain.setElementPosition(ep);
			start_x = mp.screenX - ep.left;
			var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
			pain.setTotalWidth(w);
			splitbar.position(ep);
			splitbar.show();
			self.style.visibility = 'hidden';

			bframe.cancelEvent(event);

			resize_status = true;
		}

		function resize(event) {
			if(!resize_status) {
				return;
			}
			var mp = bframe.getMousePosition(event);
			var ep = bframe.getElementPosition(self);
			ep.left = parseInt(mp.screenX - start_x);
			splitbar.position(ep);
		}

		function stop(event) {
			if(!resize_status) {
				return;
			}
			splitbar.hide();
			var mp = bframe.getMousePosition(event);
			var ep = bframe.getElementPosition(self);
			ep.left = parseInt(mp.screenX - start_x);
			pain.position(ep);
			splitbar.hide();
			self.style.visibility = 'visible';
			drag_overlay.style.display = 'none';

			resize_status = false;
		}

		function _adjustWindow() {
			var w = document.documentElement.clientWidth  || document.body.clientWidth;
			var h = document.documentElement.clientHeight || document.body.clientHeight;

			size.width = target.offsetWidth;
			size.height = h - pos.top - margin;
			target.style.height = size.height + 'px';
			splitbar.size(size);
			pain.setTotalWidth(w);
			var ep = bframe.getElementPosition(self);
			pain.setElementPosition(ep);
			pain.position(ep);
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
			popup.size(s);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.pain
	// 
	// -------------------------------------------------------------------------
	bframe.pain = function(splitbar) {
		var left_pain, right_pain;
		var splitbar_width = splitbar.offsetWidth;
		var total_width;
		var element_position;
		var right_pain_width;

	    var iframe = document.getElementsByTagName('iframe');
		setPain(iframe);

		if(!left_pain && !right_pain) {
		    var div = document.getElementsByTagName('div');
			setPain(div);
		}

		function setPain(obj) {
		    for(var i=0; i<obj.length; i++) {
				if(bframe.checkClassName('bframe_splitter_pain', obj[i])) {
					if(!left_pain) {
						left_pain = obj[i];
					}
					else {
						right_pain = obj[i];
						break;
					}
				}
			}
		}

		this.setElementPosition = function(ep) {
			element_position = ep;
		}

		this.position = function(p) {
			left_pain.style.width = parseInt(p.left + left_pain.offsetWidth - element_position.left)+ 'px';
			if(right_pain.tagName.toLowerCase() == 'iframe') {
				right_pain.style.width = parseInt(total_width - p.left - splitbar_width) + 'px';
			}
		}

		this.positionLeft = function(p) {
			left_pain.style.width = parseInt(p.left + left_pain.offsetWidth - element_position.left)+ 'px';
		}

		this.setTotalWidth = function(width) {
			if(width) {
				total_width = width;
			}
			else {
				total_width = left_pain.offsetWidth+ right_pain.offsetWidth;
			}
		}
	}
