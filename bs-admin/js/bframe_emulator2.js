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
		var width = bframe.getParam('width', meta['viewport'], ',', '=');
		var scale = bframe.getParam('initial-scale', meta['viewport'], ',', '=');
		var cw = document.body.clientWidth;
		var ch = document.body.clientHeight;

		if(width && width.toLowerCase() != 'device_width') {
			width = width.replace('px', '');
		}

		parent.bframeEmulator.setViewport(width, scale);
/*	
		onResize();	
console.log('width', width, 'initial_scale', initial_scale);

//		document.body.style.transform = 'scale(0.5, 0.5)';

		bframe.addEventListner(window, 'resize' , onResize);

		function onResize() {
			var w = window.innerWith || document.documentElement.clientWidth || document.body.clientWidth;
			var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;


console.log('w', w, 'h', h, 'cw', cw, 'ch', ch);

			if(width == 'device-width') return;

			width = width.replace('px', '');
			ratio = w / width;
			var transform = 'translate(-' + ((1-ratio) * cw)/2 + 'px, -' + ((1-ratio) * ch)/2 + 'px) scale(' + ratio + ') '; 
console.log('transform', transform);
//			document.body.style.transform = 'none';
//			document.body.style.transform = transform;
		}
*/
	}
