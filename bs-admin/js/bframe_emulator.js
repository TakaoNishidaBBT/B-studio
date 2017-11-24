/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeDeviceEmultorInit);

	function bframeDeviceEmultorInit() {
		var objects = document.getElementsByClassName('bframe_emulator');

		for(var i=0; i < objects.length; i++) {
			bframeEmulator = new bframe.emulator(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.emulator
	// 
	// -------------------------------------------------------------------------
	bframe.emulator = function(target) {
		var self = this;
		var parent = target.parentNode;
		var iframe = document.getElementById('preview_frame');
		var viewport_width;
		var initail_scale;
		var margin_top = 20;
		var margin_bottom = 60;

		// create control
		createControl();

		function createControl() {
			var li;

			// control
			control = document.createElement('ul');
			target.appendChild(control);

			li = createControlButton('images/common/icon_pc.png', 'pc', resize_pc);
			control.appendChild(li);
			li = createControlButton('images/common/icon_tb_v.png', 'tablet vertical', resize_tablet_vertical);
			control.appendChild(li);
			li = createControlButton('images/common/icon_tb_h.png', 'tablet horizontal', resize_tablet_horizontal);
			control.appendChild(li);
			li = createControlButton('images/common/icon_sp_v.png', 'smart phone vertical', resize_smart_phone_vertical);
			control.appendChild(li);
			li = createControlButton('images/common/icon_sp_h.png', 'smart phone horizontal', resize_smart_phone_horizontal);
			control.appendChild(li);
		}

		function createControlButton(icon_img, title, func) {
			var li = document.createElement('li');
			var a = document.createElement('a');
			a.title = title;
			if(func) {
				bframe.addEventListener(a, 'mousedown',func);
			}
			li.appendChild(a);
			img = document.createElement('img');
			img.src = icon_img;
			a.appendChild(img);

			return li;
		}

		function setViewport(width, scale) {
			viewport_width = width;
			inital_scale = scale;
		}
		this.setViewport = setViewport;

		function resize_pc() {
			iframe.style.transform = 'none';

			iframe.parentNode.style.width = '100%';
			iframe.style.width = '100%';
			iframe.style.marginTop = '';
			iframe.style.marginBottom = '';
			iframe.style.maxHeight = '';
			iframe.style.maxWidth = '';

			bframe.fireEvent(window, 'resize');
		}

		function resize_tablet_vertical() {
			resize(768, 1024);
		}

		function resize_tablet_horizontal() {
			resize(1024, 768);
		}

		function resize_smart_phone_vertical() {
			resize(320, 568);
		}

		function resize_smart_phone_horizontal() {
			resize(568, 320);
		}

		function resize(width, height) {
			if(viewport_width == 'device-width') {
				iframe.style.maxWidth = width + 'px';
				iframe.style.maxHeight = height + 'px';
			}
			else {
				var ratio = width / viewport_width;
				iframe.parentNode.style.width = width + 'px';
				iframe.style.width = viewport_width + 'px';
				iframe.style.maxHeight = (height * viewport_width / width) + 'px';
				iframe.style.transformOrigin = '0 0';
				iframe.style.transform = 'scale(' + ratio + ')';
			}
			iframe.style.marginTop = margin_top + 'px';
			iframe.style.marginBottom = margin_bottom + 'px';

			bframe.fireEvent(window, 'resize');
		}
	}
