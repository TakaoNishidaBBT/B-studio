/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load', bframeEditCheckInit);

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
		}

		function caution(event) {
			if(editing_obj && editing_obj.value != start_value) {
				edit_flag=true;
			}

			// call back
			editCheckExecuteCallBack();

			if(edit_flag == true) {
				bframe.fireEvent(document, 'mouseup');
				return '編集内容はDBに登録されていません。\n（編集内容は失われます）';
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

		function onFocus(event) {
			editing_obj = bframe.getEventSrcElement(event);
			start_value = editing_obj.value;
		}

		function onBlur(event) {
			editing_obj = null;
			start_value = '';
		}

		function setFunction(tag_name) {
			var obj = document.getElementsByTagName(tag_name);

			for(i=0 ; i < obj.length ; i++){
				bframe.addEventListner(obj[i], 'change', setEditFlag);
				bframe.addEventListner(obj[i], 'focus', onFocus);
				bframe.addEventListner(obj[i], 'blur', onBlur);
			}
		}

		var p = document.getElementsByTagName('p');
		for(var i in p) {
			if(p[i].className == 'error-message') {
				setEditFlag();
				break;
			}
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

		function editCheckExecuteCallBack() {
			for(var i=0 ; i<cb.length ; i++) {
				func = cb[i];
				func();
			}
		}

		this.executeCallBack = editCheckExecuteCallBack;

		this.registResetCallBackFunction = function(func) {
			rcb.push(func);
		}

		this.removeResetCallBackFunction = function(func) {
			for(var i=0 ; i<rcb.length ; i++) {
				if(func == rcb[i]) {
					rcb.splice(i, 1);
				}
			}
		}

		function editCheckExecuteResetCallBack() {
			for(var i=0 ; i<rcb.length ; i++) {
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
