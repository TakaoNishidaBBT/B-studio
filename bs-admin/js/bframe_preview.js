/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class bframe.preview
	// 
	// -------------------------------------------------------------------------
	bframe.preview = function() {
		var cb = new Array,
			previewExecuteCallBack = function() {
				for(var i=0 ; i<cb.length ; i++) {
					func = cb[i];
					func();
				}
			}

		return {
			submit: function(fname, url, method, iframe_name) {
				// execute callback function before submit
				previewExecuteCallBack();

				// set form object
				if(fname) {
					var form = document.forms[fname];
				}
				else {
					var form = document.forms[0];
				}

				// save original action and target
				var save_action = form.action;
				var save_target = form.target;

				// change action and target from parameter
				form.action = url;
				form.target = iframe_name;

				// append method from parameter
				bframe.appendHiddenElement(form, 'method', method);

				// submit
				form.submit();

				// restore original action and target
				form.action = save_action;
				form.target = save_target;
			},

			registCallBackFunction: function(func) {
				cb.push(func);
			},

			removeCallBackFunction: function(func) {
				for(var i=0 ; i<cb.length ; i++) {
					if(func == cb[i]) {
						cb.splice(i, 1);
					}
				}
			},
		}
	}();
