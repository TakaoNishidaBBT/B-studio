/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class bframe.Inline
	// 
	// -------------------------------------------------------------------------
	bframe.Inline = function() {
		var cb = new Array;
		var bcb = new Array;

		this.submit = function(fname, url, method, iframe_name) {
			var info = bframe.getPageInfo();

			inlineExecuteCallBack();

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

			bframe.appendHiddenElement(form, 'terminal_id', info['terminal_id']);
			bframe.appendHiddenElement(form, 'method', method);

			form.submit();

			form.action = save_action;
			form.target = save_target;
		}

		this.registerCallBackFunction = function(func) {
			cb.push(func);
		}

		this.removeCallBackFunction = function(func) {
			for(var i=0; i < cb.length; i++) {
				if(func == cb[i]) {
					cb.splice(i, 1);
				}
			}
		}

		inlineExecuteCallBack = function() {
			for(var i=0; i < cb.length; i++) {
				func = cb[i];
				func();
			}
		}

		this.registerBlurCallBackFunction = function(func) {
			bcb.push(func);
		}

		this.removeBlurCallBackFunction = function(func) {
			for(var i=0; i < bcb.length; i++) {
				if(func == bcb[i]) {
					bcb.splice(i, 1);
				}
			}
		}

		this.blur = function() {
			inlineBlurExecuteCallBack();
		}

		inlineBlurExecuteCallBack = function() {
			for(var i=0; i < bcb.length; i++) {
				func = bcb[i];
				func();
			}
		}
	}

	bframe.inline = new bframe.Inline;
