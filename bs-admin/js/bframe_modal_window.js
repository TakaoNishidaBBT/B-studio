/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeModalWindowInit);

	function bframeModalWindowInit() {
		if(!bframe.modalWindow) {
			bframe.modalWindow = new bframe.modal_window(1);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.modal_window
	// 
	// -------------------------------------------------------------------------
	bframe.modal_window = function(id) {
		var window_id = id;
		var overlay = document.createElement('div');
		var modal_window = document.createElement('div');
		var title_bar = document.createElement('div');
		var container = document.createElement('div');
		var containerHeader = document.createElement('div');
		var containerBody = document.createElement('iframe');
		var title = document.createElement('span');
		var close = document.createElement('span');
		var window_width_default;
		var window_height_default;
		var window_status;
		var window_loaded;
		var set_window_size = false;
		var child;
		var opener;
		var baseIndex = 5000;
		var callBackFunction;
		var set_width;
		var set_height;

		overlay.className = 'overlay';
		overlay.style.display = 'none';
		overlay.style.position = 'fixed';
		overlay.style.top = 0;
		overlay.style.left = 0;

		document.body.appendChild(overlay);

		modal_window.className = 'modal_window';
		modal_window.style.display = 'none';
		modal_window.style.position = 'fixed';
		modal_window.style.filter = 'alpha(opacity=0)';
		document.body.appendChild(modal_window);

		title_bar.className = 'title_bar';
		modal_window.appendChild(title_bar);

		title.className = 'title';
		title_bar.appendChild(title);

		close.className = 'close';
		close.style.zIndex = baseIndex + 5;
		close.innerHTML = 'close';
		bframe.addEventListener(close, 'click', deactivate);
		title_bar.appendChild(close);

		container.className = 'container';
		modal_window.appendChild(container);

		containerHeader.className = 'containerHeader';
		container.appendChild(containerHeader);

		containerHeader.onmousedown = onContainerHeaderMouseDown;
		containerHeader.onmousemove = onContainerHeaderMouseMove;
		containerHeader.onmouseup = onContainerHeaderMouseUp;

		containerBody.id = 'modal_window' + window_id;
		containerBody.name = 'modal_window' + window_id;
		containerBody.className = 'containerBody';
		containerBody.frameBorder = 0;
		containerBody.deactivate = deactivate;
		containerBody.scrolling = 'no';
		if(containerBody.attachEvent) {
			containerBody.attachEvent('onload', onloadModalWindow);
		}
		else {
			containerBody.onload = onloadModalWindow;
		}
		container.appendChild(containerBody);

		bframe.addEventListener(overlay, 'click', deactivate);

		var drag_control = new dragControl();

		function onKeyDown(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
			}
			else {
				if(event) var keycode = event.keyCode;
			}
			if(keycode == 8) {	// BackSpace
				var ae = containerBody.contentDocument.activeElement.tagName;
				// active element
				if(ae && ae.toLowerCase() != 'textarea' && ae.toLowerCase() != 'input') {
					bframe.stopPropagation(event);
				}
			}
		}

		function onContainerHeaderMouseDown(event) {
			drag_control.dragStart(event);
			bframe.fireEvent(top.document, 'click');
		}

		function onContainerHeaderMouseMove(event) {
			drag_control.dragging(event);
		}

		function onContainerHeaderMouseUp(event) {
			drag_control.dragStop();
		}

		this.activate = function(target, window) {
			// for PC only
			if(bframe.getDevice() != 'pc') {
				window.location.href = target.href;
				return;
			}

			// arguments
			for(var i=2; i < arguments.length; i++) {
				var obj = window.document.getElementById(arguments[i]);
				if(obj) {
					bframe.setLinkParam(target, arguments[i], obj.value);
				}
			}

			if(window_status == 'activate') {
				if(!child) {
					var id = window_id*10;
					child = new bframe.modal_window(id);
					child.setBaseIndex(baseIndex + 1000 + i*10);
				}
				child.setOverlayOpacity(0);
				child.activate(target, window);
				return;
			}

			window_status = 'activate';

			window_width_default = 10000;
			window_height_default = 10000;

			containerBody.style.width = window_width_default + 'px';
			containerBody.style.height = window_height_default + 'px';

			if(t = target.getAttribute('title')) {
				title.innerHTML = t;
			}

			set_width = false;
			set_height = false;
			var param = target.getAttribute('data-param');
			if(param) {
				if(w = bframe.getParam('width', param)) {
					window_width_default = w;
					set_width = true;
				}
				if(h = bframe.getParam('height', param)) {
					window_height_default = h;
					set_height = true;
				}
			}

			resizeOverlay();

			containerBody.contentWindow.location.replace(target.href);
			containerBody.opener = window;

			// set zIndex
			overlay.style.zIndex = baseIndex + 1;
			modal_window.style.zIndex = baseIndex + 2;
			drag_control.setZindex(baseIndex + 3);
			containerHeader.style.zIndex = baseIndex + 4;

			overlay.style.display = 'block';
			modal_window.style.display = 'block';
			modal_window.style.filter = 'alpha(opacity=0)';
			modal_window.className = 'modal_window pre-fadein';

			//focus
			modal_window.focus();
			frames[containerBody.id].focus();
		};

		function onloadModalWindow() {
			if(containerBody.contentWindow.location == 'about:blank') return;
			if(window_loaded) return;

			try {
				containerBody.contentDocument.onkeydown = onKeyDown;
			} catch(e) {}

			try {
				w = window_width_default;
				h = window_height_default;

				if(!set_width) {
					var w = containerBody.contentDocument.body.clientWidth;
				}
				if(!set_height) {
					var h = containerBody.contentDocument.body.clientHeight;
				}

				setWindowSize(w, h);
				modal_window.className = modal_window.className.replace(' pre-fadein', ' fadein');
				modal_window.style.filter = 'alpha(opacity=100)';
				window_loaded = true;
			} catch(e) {}
		}

		function setWindowSize(width, height) {
			if(!width || !height) return;

			window_width_default = parseInt(width);
			window_height_default = parseInt(height);
			resizeOverlay();
		}

		function deactivate(param) {
			if(child && child.getWindowStatus()) {
				child.deactivate(param);
				return;
			}

			overlay.style.display = 'none';
			modal_window.style.display = 'none';
			modal_window.style.filter = 'alpha(opacity=0)';
			modal_window.className = modal_window.className.replace(' fadein', '');

			containerBody.contentWindow.location.replace('about:blank');
			window_status = false;
			window_loaded = false;

			executeCallBack(param);
		}

		function resizeOverlay() {
			if(window_status != 'activate') return;

			var margin_w = 20;
			var margin_h = 60;

			var ow = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
			var oh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
			overlay.style.width = ow + 'px';
			overlay.style.height = oh + 'px';

			w = Math.round(ow * 0.8) - margin_w < window_width_default ? Math.round(ow * 0.8) - margin_w : window_width_default;
			h = Math.round(oh * 0.8) - margin_h < window_height_default ? Math.round(oh * 0.8) - margin_h : window_height_default;
			modal_window.style.left = ((ow - w - margin_w) / 2) + 'px';
			modal_window.style.top = ((oh - h - margin_h) / 2) + 'px';
			containerBody.style.width = w + 'px';
			containerBody.style.height = h + 'px';
		}

		this.setBaseIndex = function(value) {
			baseIndex = value;
		}

		this.setOverlayOpacity = function(value) {
			overlay.style.opacity = value;
		}

		this.getWindowStatus = function(value) {
			return window_status;
		}

		this.registerCallBackFunction = function(func) {
			if(child && child.getWindowStatus()) {
				child.registerCallBackFunction(func);
				return;
			}
			if(func) callBackFunction = func;
		}

		function executeCallBack(param) {
			if(callBackFunction) callBackFunction(param);
		}

		this.getActiveWindow = function(window_name) {
			if(!window_status) return false;
			if(child)  return child.getActiveWindow() || containerBody.name;
			return containerBody.name;
		}

		bframe.resize_handler.registerCallBackFunction(resizeOverlay);

		// -------------------------------------------------------------------------
		// class dragControl
		// -------------------------------------------------------------------------
		function dragControl() {
			var self = this;
			var button_status;
			var drag_status;
			var offset_x, offset_y;
			var start_position;
			var drag_overlay = document.createElement('div');
			this.onMouseUp = onMouseUp;

			drag_overlay.className = 'drag_overlay';
			drag_overlay.style.display = 'none';
			drag_overlay.style.position = 'fixed';
			drag_overlay.style.top = 0;
			drag_overlay.style.left = 0;
			drag_overlay.style.opacity = 0;
			document.body.appendChild(drag_overlay);

			bframe.addEventListener(window, 'mousemove', onMouseMove);
			bframe.addEventListener(window, 'mouseup', onMouseUp);
			bframe.addEventListener(drag_overlay, 'mousemove', dragging);
			bframe.addEventListener(drag_overlay, 'mousemove', onMouseMove);
			bframe.addEventListener(drag_overlay, 'mouseup', onMouseUp);

			this.dragStart = function(event) {
				if(bframe.getButton(event) != 'L') return;

				e = window.event ? window.event : event;
				button_status = true;

				var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
				var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
				drag_overlay.style.width = w + 'px';
				drag_overlay.style.height = h + 'px';
				drag_overlay.style.display = 'block';

				var pos = bframe.getElementPosition(modal_window);
				var m = bframe.getMousePosition(event);
				start_position = bframe.getMousePosition(event);

				offset_x = m.screenX - pos.left;
				offset_y = m.screenY - pos.top;

				if(_isIE) {
					window.event.returnValue = false;
				}
				else {
					event.preventDefault();
				}
			}

			function dragging(event) {
				if(!button_status) return;
				if(!drag_status) {
					current_position = bframe.getMousePosition(event);
					if(Math.abs(start_position.screenX - current_position.screenX) > 3 || Math.abs(start_position.screenY - current_position.screenY) > 3) {
						drag_status = true;
					}
					else {
						return;
					}
				}
			}
			this.dragging = dragging;

			function onMouseMove(event) {
				if(!drag_status) return;

				var m = bframe.getMousePosition(event);

				setWindowPosition(event);
			}

			this.dragStop = function() {
				if(!drag_status) return;

				drag_status = false;
				button_status = false;
				drag_overlay.style.display = 'none';
			}

			this.setZindex = function(value) {
				drag_overlay.style.zIndex = value;
			}

			function onMouseUp(event) {
				drag_status = false;
				button_status = false;
				drag_overlay.style.display = 'none';
			}

			function setWindowPosition(event) {
				var m = bframe.getMousePosition(event);

				modal_window.style.left = parseInt(m.screenX - offset_x) + 'px';
				modal_window.style.top = parseInt(m.screenY - offset_y) + 'px';
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.modalWindowEventHandler
	// 
	// -------------------------------------------------------------------------
	bframe.modalWindowEventHandler = function() {
		var cba = [];
		var cbd = [];

		this.registerActivateCallBackFunction = function(func) {
			cba.push(func);
		}
		function activateExecuteCallBack() {
			for(var i=0; i < cba.length; i++) {
				func = cba[i];
				func();
			}
		}
		this.activateExecuteCallBack = activateExecuteCallBack;

		this.registerDeactivateCallBackFunction = function(func) {
			cbd.push(func);
		}
		function deactivateExecuteCallBack() {
			for(var i=0; i < cbd.length; i++) {
				func = cbd[i];
				func();
			}
		}
		this.deactivateExecuteCallBack = deactivateExecuteCallBack;
	}
	if(!bframe.modalWindow_eventHandler) {
		bframe.modalWindow_eventHandler = new bframe.modalWindowEventHandler;
	}
