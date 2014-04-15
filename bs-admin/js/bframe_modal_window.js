/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeModalWindowInit);

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
		var window_width_default = 250;
		var window_height_default = 250;
		var window_width = 250;
		var window_height = 250;
		var window_status;
		var children = Array();
		var opener;
		var baseIndex = 5000;
		var callBackFunction;

		overlay.className = 'overlay';
		overlay.style.display = 'none';
		overlay.style.position = 'absolute';
		overlay.style.top = 0;
		overlay.style.left = 0;

		overlay.style.backgroundColor = '#000';
		overlay.style.opacity = 0.5;

		document.body.appendChild(overlay);

		modal_window.className = 'modal_window';
		modal_window.style.display = 'none';
		modal_window.style.position = 'absolute';
		modal_window.style.opacity = 0;
		document.body.appendChild(modal_window);

		title_bar.className = 'title_bar';
		modal_window.appendChild(title_bar);

		title_bar.appendChild(title);

		close.className = 'close';
		close.innerHTML = 'close';
		bframe.addEventListner(close, 'click', deactivate);
		title_bar.appendChild(close);

		container.className = 'container';
		modal_window.appendChild(container);

		containerHeader.className = 'containerHeader';
		containerHeader.style.position = 'relative';
		container.appendChild(containerHeader);

		containerHeader.onmousedown = onContainerHeaderMouseDown;
		containerHeader.onmousemove = onContainerHeaderMouseMove;
		containerHeader.onmouseup = onContainerHeaderMouseUp;

		containerBody.name = 'modal_window' + window_id;
		containerBody.className = 'containerBody';
		containerBody.style.position = 'relative';
		containerBody.frameBorder = 0;
		containerBody.deactivate = deactivate;
		container.appendChild(containerBody);

		bframe.addEventListner(overlay, 'click', deactivate);

		var drag_control = new dragControl();

		function onContainerHeaderMouseDown(event) {
			drag_control.dragStart(event);
		}

		function onContainerHeaderMouseMove(event) {
			drag_control.dragging(event);
		}

		function onContainerHeaderMouseUp(event) {
			drag_control.dragStop();
		}

		this.activate = function(target, window) {
			opener = window;
			var child;

			if(window_status == 'activate') {
				for(var i=0 ; i < children.length ; i++) {
					if(!children[i].getWindowStatus()) {
						child = children[i];
						break;
					}
				}
				if(!child) {
					var id = window_id*10 + children.length+1;
					child = new bframe.modal_window(id);
					child.setBaseIndex(baseIndex + 1000);
				}
				child.setOverlayOpacity(0);
				child.activate(target, window);
				children.push(child);
				return;
			}

			if(t = target.getAttribute('title')) {
				title.innerHTML = t;
			}
			var params = target.getAttribute('params');
			if(params) {
				if(w = bframe.getParam('width', params)) {
					window_width_default = w;
				}
				if(h = bframe.getParam('height', params)) {
					window_height_default = h;
				}
			}
			window_width = window_width_default;
			window_height = window_height_default;

			resizeOverlay();

			// arguments
			for(var i=2 ; i<arguments.length; i++) {
				var obj = window.document.getElementById(arguments[i]);
				if(obj) {
					bframe.setLinkParam(target, arguments[i], obj.value);
				}
			}
			containerBody.src = target.href;
			containerBody.opener = window;

			// set zIndex
			overlay.style.zIndex = baseIndex + 1;
			modal_window.style.zIndex = baseIndex + 2;
			drag_control.setZindex(baseIndex + 3);
			containerHeader.style.zIndex = baseIndex + 4;

			overlay.style.display = 'block';
			modal_window.style.display = 'block';
			modal_window.style.opacity = 0;
			bframe.effect.fadeIn(modal_window, 300, 0, 100, 100);

			window_status = 'activate';
		};

		function deactivate(param) {
			for(var i=children.length; i > 0; i--) {
				var child = children[i-1];
				if(child.getWindowStatus()) {
					child.deactivate(param);
					return;
				}
			}
			overlay.style.display = 'none';
			modal_window.style.display = 'none';
			modal_window.style.opacity = 0;
			containerBody.src = '';
			window_status = false;

			executeCallBack(param);
		}

		function resizeOverlay() {
			var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
			var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
			overlay.style.width = w + 'px';
			overlay.style.height = h + 'px';
			var margin = 65;

			window_width = Math.round(w * 0.6) < window_width_default ? Math.round(w * 0.6) : window_width_default;
			window_height = Math.round(h * 0.8) - margin < window_height_default ? Math.round(h * 0.8) - margin : window_height_default;
			modal_window.style.left = ((w - window_width) / 2) + 'px';
			modal_window.style.top = ((h - window_height - margin) / 2) + 'px';

			containerBody.style.width = window_width + 'px';
			containerBody.style.height = window_height + 'px';
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

		this.registCallBackFunction = function(func) {
			for(var i=children.length; i > 0; i--) {
				var child = children[i-1];
				if(child.getWindowStatus()) {
					child.registCallBackFunction(func);
					return;
				}
			}
			if(func) callBackFunction = func;
		}

		function executeCallBack(param) {
			if(callBackFunction) callBackFunction(param);
		}

		bframe.resize_handler.registCallBackFunction(resizeOverlay);

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
			drag_overlay.style.position = 'absolute';
			drag_overlay.style.top = 0;
			drag_overlay.style.left = 0;
			drag_overlay.style.opacity = 0;

			document.body.appendChild(drag_overlay);

			bframe.addEventListner(window, 'mousemove', onMouseMove);
			bframe.addEventListner(window, 'mouseup', onMouseUp);
			bframe.addEventListner(drag_overlay, 'mousemove', dragging);
			bframe.addEventListner(drag_overlay, 'mousemove', onMouseMove);
			bframe.addEventListner(drag_overlay, 'mouseup', onMouseUp);

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

				modal_window.style.left = parseInt(m.screenX - m.scrollLeft - offset_x) + 'px';
				modal_window.style.top = parseInt(m.screenY - m.scrollTop - offset_y) + 'px';
			}
		}
	}
