/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeComparePaneInit);

	function bframeComparePaneInit(){
		var pc = new bframe.compare_pane_container();
	}

	// -------------------------------------------------------------------------
	// class bframe.compare_pane_container
	// 
	// -------------------------------------------------------------------------
	bframe.compare_pane_container = function() {
		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;
		var node_id;
		var target_id;
		var panes;
		var display_thumbnail;
		var display_detail;
		var display_mode;

		node_id = document.getElementById('node_id').value;
		target_id = document.getElementById('target_id').value;

		display_thumbnail = document.getElementById('display_thumbnail');
		display_detail = document.getElementById('display_detail');

		init();

		function init() {
			var param;

			param = 'terminal_id='+terminal_id+'&class=compare_pane_container&id=display_mode';
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse(){
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				response_wait = false;

				setDispChange();
			}
		}

		this.setPanes = function(p) {
			panes = p;
		}

		function setDispChange() {
			display_thumbnail = document.getElementById(property.display_mode.thumbnail.id);
			display_detail = document.getElementById(property.display_mode.detail.id);

			bframe.addEventListener(display_thumbnail, 'click', display_thumbnail_mode);
			bframe.addEventListener(display_detail, 'click', display_detail_mode);
			display_mode = property.display_mode.default;

			if(display_mode == 'detail') {
				bframe.appendClass('current', display_detail);
			}
			else {
				bframe.appendClass('current', display_thumbnail);
			}
		}

		function display_thumbnail_mode(event) {
			if(display_mode == 'thumbnail') return;

			display_mode = 'thumbnail';
			change_disp_mode();
		}

		function display_detail_mode(event) {
			if(display_mode == 'detail') return;

			display_mode = 'detail';
			change_disp_mode();
		}

		function change_disp_mode(mode) {
			var param;

			param = 'terminal_id='+terminal_id;
			param+= '&module='+module;
			param+= '&page='+page;
			param+= '&method=select';
			param+= '&node_id='+node_id;
			param+= '&target_id='+target_id;
			param+= '&display_mode='+display_mode;

			location.href="index.php?" + param;
		}
	}
