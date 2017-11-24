/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeShortcutInit);

	function bframeShortcutInit() {
		var objects = document.getElementsByClassName('bframe_shortcut');

		for(var i=0; i < objects.length; i++) {
			if(window.getSelection) {
				var s = new bframe.shortcut(objects[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.shortcut
	// 
	// -------------------------------------------------------------------------
	bframe.shortcut = function(target) {
		var self = target;

		// register button
		var register_button = document.getElementById('register');

		bframe.addEventListener(target, 'keydown', keydown);

		function keydown(event) {
			if(window.event) {
				var e = window.event;
				var keycode = window.event.keyCode;
			}
			else {
				var e = event;
				var keycode = event.keyCode;
			}
			switch(keycode) {
			case 83:	// save
				if(e.ctrlKey || e.metaKey) {
					var obj = bframe.getEventSrcElement(event);
					bframe.fireEvent(obj, 'focus');
					bframe.fireEvent(register_button, 'click');
					event.preventDefault();
				}
				break;
			}
		}
	}
