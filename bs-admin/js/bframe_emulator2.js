/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , deviceEmulator);

	// -------------------------------------------------------------------------
	// class bframe.emu
	// 
	// -------------------------------------------------------------------------
	function deviceEmulator() {
		var meta = bframe.getPageInfo();
		if(meta['viewport']) {
			var width = bframe.getParam('width', meta['viewport'], ',', '=');
			var scale = bframe.getParam('initial-scale', meta['viewport'], ',', '=');
		}

		if(width && width.toLowerCase() != 'device_width') {
			width = parseInt(width);
		}
		if(!width) {
			width = 980;
		}

		parent.bframeEmulator.setViewport(width, scale);
	}
