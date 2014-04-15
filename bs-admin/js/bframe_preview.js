/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class bframe.Preview
	// 
	// -------------------------------------------------------------------------
	bframe.Preview = function() {
		var cb = new Array;

		this.submit = function(fname, url, method, iframe_name) {
			previewExecuteCallBack();

			// set form object
			if(fname) {
				var form = document.forms[fname];
			}
			else {
				var form = document.forms[0];
			}

			var save_action = form.action;
			var save_target = form.target;

			form.action = url;
			form.target = iframe_name;

			bframe.appendHiddenElement(form, 'method', method);

			form.submit();

			form.action = save_action;
			form.target = save_target;
		}

		this.registCallBackFunction = function(func) {
			cb.push(func);
		}

		this.removeCallBackFunction = function(func) {
			for(var i=0 ; i<cb.length ; i++) {
				if(func == cb[i]) {
					cb.splice(i, 1);
				}
			}
		}

		previewExecuteCallBack = function() {
			for(var i=0 ; i<cb.length ; i++) {
				func = cb[i];
				func();
			}
		}
	}

	bframe.preview = new bframe.Preview;
