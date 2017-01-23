/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class bframe.dialog
	// 
	// -------------------------------------------------------------------------
	bframe.dialog = function(params) {

		var overlay = document.createElement('div');
		var container = document.createElement('div');
		var containerHeader = document.createElement('div');
		var box = document.createElement('div');
		var message = document.createElement('p');
		var buttons = document.createElement('div');

		overlay.className = 'overlay';
		overlay.id = params.id;
		document.body.appendChild(overlay);

		container.className = 'container';
		overlay.appendChild(container);

		containerHeader.className = 'containerHeader';
		containerHeader.innerHTML = params.title;
		container.appendChild(containerHeader);

		box.className = 'box';
		container.appendChild(box);

		message.className = 'message';
		message.innerHTML = params.message;
		box.appendChild(message);

		buttons.className = 'buttons';
		box.appendChild(buttons);

		for(var i=0; i<params.buttons.length; i++) {
			var a = createButton(params.buttons[i]);
			buttons.appendChild(a);
		}

		function createButton(params) {
			var a = document.createElement('a');
			a.className = params.className;
			a.innerHTML = params.name;
			a.onclick = function() {
				params.action();
				document.body.removeChild(overlay);
				return false;
			};
			return a;
		}
	}
