/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , deviceEmulator);

	// -------------------------------------------------------------------------
	// deviceEmulator
	// 
	// -------------------------------------------------------------------------
	function deviceEmulator() {
		var meta = bframe.getPageInfo();
		if(meta['viewport']) {
			var width = bframe.getParam('width', meta['viewport'], ',', '=');
			var scale = bframe.getParam('initial-scale', meta['viewport'], ',', '=');
		}

		if(width && width.toLowerCase() != 'device-width') {
			width = parseInt(width);
		}
		if(!width) {
			width = 980;
		}

		parent.bframeEmulator.setViewport(width, scale);
	}
