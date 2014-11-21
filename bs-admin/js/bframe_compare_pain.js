/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeComparePainInit);

	function bframeComparePainInit(){
		var pc = new bframe.compare_pain_container();
	    var div = document.getElementsByTagName('div');
		var p = new Array();

	    for(var i=0, j=0; i<div.length; i++) {
			if(bframe.checkClassName('bframe_compare_pain', div[i])) {
				p[j++] = new bframe.compare_pain(pc, div[i], j);
			}
		}

		pc.setPains(p);
	}

	// -------------------------------------------------------------------------
	// class bframe.compare_pain_container
	// 
	// -------------------------------------------------------------------------
	bframe.compare_pain_container = function() {
		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;
		var node_id;
		var target_id;
		var pains;
		var pain_disp_change;
		var pain_disp_change_select;

		node_id = document.getElementById('node_id').value;
		target_id = document.getElementById('target_id').value;
		pain_disp_change = document.getElementById('bframe_pain_disp_change');
		pain_disp_change_select = bframe.searchNodeByTagName(pain_disp_change, 'select');
		bframe.addEventListner(pain_disp_change_select, 'change', change_disp_mode);

		this.setPains = function(p) {
			pains = p;
		}

		function change_disp_mode() {
			var param;

			param = 'terminal_id='+terminal_id;
			param+= '&module='+module;
			param+= '&page='+page;
			param+= '&method=select';
			param+= '&node_id='+node_id;
			param+= '&target_id='+target_id;
			param+= '&disp_mode='+pain_disp_change_select.options[pain_disp_change_select.selectedIndex].value;

			location.href="index.php?" + param;
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.compare_pain
	// 
	// -------------------------------------------------------------------------
	bframe.compare_pain = function(container, target, index) {
		var pc = container;
		var self = this;
		var target_id = bframe.getID(target);
		var target_index = index;

		var	response_wait = false;
		var httpObj;

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

	}
