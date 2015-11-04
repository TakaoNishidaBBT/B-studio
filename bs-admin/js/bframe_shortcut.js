/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeShortcutInit);

	function bframeShortcutInit(){
	    var d = document.getElementsByTagName('div');

	    for(var i=0; i<d.length; i++) {
			if(window.getSelection && bframe.checkClassName('bframe_shortcut', d[i])) {
				var s = new bframe.shortcut(d[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.shortcut
	// 
	// -------------------------------------------------------------------------
	bframe.shortcut = function(target) {
		var self = target;

		// regist button
		var regist_button = document.getElementById('regist');

		bframe.addEventListner(target, 'keydown', keydown);

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
					bframe.fireEvent(regist_button, 'click');
					event.preventDefault();
				}
				break;
			}
		}
	}