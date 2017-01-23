/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeMessageInit);

	function bframeMessageInit() {
		bframe.message = new bframe.Message;
	}

	// -------------------------------------------------------------------------
	// class bframe.Message
	// 
	// -------------------------------------------------------------------------
	bframe.Message = function() {
		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;
		var property;

		init();

		function init() {
			var param;
			var info = bframe.getPageInfo();

			param = 'terminal_id='+info.terminal_id+'&class=bframe_message&id=bframe_message';
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse() {
			var rel;

			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
			}
		}

		this.getProperty = function(name) {
			return property[name];
		}
	}
