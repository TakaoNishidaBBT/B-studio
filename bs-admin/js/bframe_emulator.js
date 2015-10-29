/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeDeviceEmultorInit);

	function bframeDeviceEmultorInit(){
	    var div = document.getElementsByTagName('div');

	    for(var i=0; i<div.length; i++) {
			if(bframe.checkClassName('bframe_emulator', div[i])) {
				bframeEmulator = new bframe.emulator(div[i]);
			}
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
				bframe.addEventListner(a, 'mousedown',func);
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

			iframe.style.marginTop = '';
			iframe.style.marginBottom = '';
			iframe.style.maxHeight = '';
			iframe.style.maxWidth = '';

			bframe.fireEvent(window, 'resize');
		}

		function resize_tablet_vertical() {
			iframe.style.marginTop = '20px';
			iframe.style.marginBottom = '60px';
			iframe.style.maxHeight = '1024px';
			iframe.style.maxWidth = '768px';			

			bframe.fireEvent(window, 'resize');
		}

		function resize_tablet_horizontal() {
			iframe.style.marginTop = '20px';
			iframe.style.marginBottom = '60px';
			iframe.style.maxHeight = '768px';
			iframe.style.maxWidth = '1024px';

			bframe.fireEvent(window, 'resize');
		}

		function resize_smart_phone_vertical() {
			if(viewport_width == 'device-width') {
				iframe.style.maxHeight = '480px';
				iframe.style.maxWidth = '320px';
			}
			else {
				var ratio = 320 / viewport_width;
				iframe.style.maxWidth = viewport_width + 'px';
				iframe.style.maxHeight = (480 * viewport_width / 320) + 'px';
				iframe.style.transformOrigin = '50% 0';
				iframe.style.transform = 'scale(' + ratio + ')';
			}
			iframe.style.marginTop = margin_top + 'px';
			iframe.style.marginBottom = margin_bottom + 'px';

			bframe.fireEvent(window, 'resize');
		}

		function resize_smart_phone_horizontal() {
			iframe.style.marginTop = '20px';
			iframe.style.marginBottom = '60px';
			iframe.style.maxHeight = '320px';
			iframe.style.maxWidth = '480px';

			bframe.fireEvent(window, 'resize');
		}
	}