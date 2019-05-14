/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load', bframeEditCheckInit);

	function bframeEditCheckInit(){
		bframe.editCheck_handler.init();
	}

	// -------------------------------------------------------------------------
	// class bframe.editCheck
	// 
	// -------------------------------------------------------------------------
	bframe.editCheck = function() {
		var edit_flag = false;
		var editing_obj;
		var start_value;
		var cb = new Array;
		var rcb = new Array;

		this.init = function() {
			window.onbeforeunload = caution;

			setFunction('input');
			setFunction('select');
			setFunction('textarea');

			var error_obj = document.getElementsByClassName('error-message');
			if(error_obj.length) {
				setEditFlag();
			}
		}

		function caution(event) {
			if(editing_obj && editing_obj.value != start_value) {
				edit_flag=true;
			}

			if(edit_flag == true || editCheckExecuteCallBack()) {
				bframe.fireEvent(document, 'mouseup');
				return true;
			}
		};

		function resetBeforeUnload() {
			window.onbeforeunload = '';
		};

		function setEditFlag() {
			edit_flag=true;
		};

		this.setEditFlag = setEditFlag;

		this.getEditFlag = function() {
			return edit_flag;
		};

		this.resetEditFlag = function() {
			edit_flag=false;

			// call back
			editCheckExecuteResetCallBack();
		};

		function setFunction(tag_name) {
			var obj = document.getElementsByTagName(tag_name);

			for(var i=0; i < obj.length; i++) {
				if(bframe.checkClassName('no-check', obj[i])) continue;
				bframe.addEventListener(obj[i], 'change', setEditFlag);
			}
		}

		this.registerCallBackFunction = function(func) {
			cb.push(func);
		}

		this.removeCallBackFunction = function(func) {
			for(var i=0; i<cb.length; i++) {
				if(func == cb[i]) {
					cb.splice(i, 1);
				}
			}
		}

		function editCheckExecuteCallBack() {
			var ret;

			for(var i=0; i<cb.length; i++) {
				func = cb[i];
				ret |= func();
			}
			return ret;
		}

		this.executeCallBack = editCheckExecuteCallBack;

		this.registerResetCallBackFunction = function(func) {
			rcb.push(func);
		}

		this.removeResetCallBackFunction = function(func) {
			for(var i=0; i<rcb.length; i++) {
				if(func == rcb[i]) {
					rcb.splice(i, 1);
				}
			}
		}

		function editCheckExecuteResetCallBack() {
			for(var i=0; i<rcb.length; i++) {
				func = rcb[i];
				func();
			}
		}

		this.executeResetCallBack = editCheckExecuteResetCallBack;
	}

	// create instance
	if(!bframe.editCheck_handler) {
		bframe.editCheck_handler = new bframe.editCheck();
	}
