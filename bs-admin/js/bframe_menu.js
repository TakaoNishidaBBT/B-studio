/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeMenuInit);

	function bframeMenuInit(){
		var mc = new bframe.menu_container();
		var m = [];
		var objects = document.getElementsByClassName('bframe_menu');

		for(var i=0, j=0; i < objects.length; i++) {
			m[j++] = new bframe.menu(objects[i], mc);
		}
		mc.setMenus(m);

		bframe.menuContainer = mc;
	}

	// -------------------------------------------------------------------------
	// class bframe.menu_container
	// 
	// -------------------------------------------------------------------------
	bframe.menu_container = function() {
		var menu;

		this.setMenus = function(obj) {
			menu = obj;
		}

		this.closeAll = function() {
			for(var i=0; i < menu.length; i++) {
				if(menu[i].hidePullDownMenu) {
					menu[i].hidePullDownMenu();
				}
			}
		}

		this.reload = function() {
			for(var i=0; i < menu.length; i++) {
				menu[i].reload();
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.menu
	// 
	// -------------------------------------------------------------------------
	bframe.menu = function(target, mc) {
		var target_id = bframe.getID(target);
		var menu_container = mc;

		var open = false;
		var	response_wait = false;
		var row_no = -1;
		var timer;

		var httpObj;
		var xmlData;

		var last_value = '';
		var save_value = '';
		var start_value = '';

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

		var property;
		var ext_width = 0;

		var context_menu;
		var context_menu_frame = window;
		var context_menu_frame_offset;
		var context_menu_width = 100;

		var mark_span;

		this.hidePullDownMenu = hidePullDownMenu;
		this.reload = init;
		bframe.addEventListener(target, 'mousedown', showContextMenu);
		bframe.addEventListener(target, 'click', showContextMenu);

		init();

		function init() {
			var param;

			param = 'terminal_id='+terminal_id+'&class=bframe_menu&id='+target.id;
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse() {
			var rel;

			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				if(bframe.isObject(property)) {
					setContextMenu();
					setEventHandler();
					setMark();
					response_wait = false;
				}
			}
		}

		function setEventHandler() {
			// set event handller
			bframe.addEventListenerAllFrames(top, 'load', hidePullDownMenuAllFrames);
			bframe.addEventListenerAllFrames(top, 'mousedown', hidePullDownMenu);
		}

		function hidePullDownMenuAllFrames(event) {
			bframe.addEventListenerAllFrames(top, 'mousedown', hidePullDownMenu);
			bframe.addEventListenerAllFrames(top, 'load', hidePullDownMenuAllFrames);
		}

		function setMark() {
			if(mark_span) return;
			mark_span = document.createElement('span');
			mark_span.style.fontSize = '11px';
			target.appendChild(mark_span);
			var mark_text = document.createTextNode(property.context_menu_mark);
			mark_span.appendChild(mark_text);
		}

		function setContextMenu() {
			context_menu = new bframe.contextMenu(2000);

			if(property.context_menu_frame) {
				context_menu_frame = eval(property.context_menu_frame);
				context_menu.setDocument(context_menu_frame.window);
			}
			else {
				bframe.context_menu.setDocument(window);
			}
			if(property.context_menu_width) {
				context_menu_width = property.context_menu_width;
			}
			if(target.offsetWidth > context_menu_width) {
				context_menu_width = target.offsetWidth;
			}

			context_menu_frame_offset = bframe.getFrameOffset(window, context_menu_frame);
			context_menu.setWidth(context_menu_width);
			context_menu.createElementFromObject(property.context_menu, this);

			context_menu.setOffsetHeight(target.offsetHeight-1);
			bframe.addEventListener(target, 'mouseout', context_menu.setTimer);
			bframe.addEventListener(target, 'mouseover', context_menu.clearTimer);
		}

		function showContextMenu(event) {
			if(bframe.getButton(event) != 'L') return;
			bframe.cancelEvent(event);
			if(context_menu.opened()) return false;
			if(bframe.isObject(property)) {
				menu_container.closeAll();
				var position = bframe.getElementPosition(target);
				position.left += (context_menu_frame_offset.left + document.body.scrollLeft);
				position.top += (context_menu_frame_offset.top + document.body.scrollTop) + 1;
				context_menu.positionAbsolute(position);

				context_menu.show();
				bframe.addEventListener(document, 'mousewheel', bframe.cancelEvent);
			}
			return false;
		}

		function hidePullDownMenu(event) {
			if(event && bframe.getEventSrcElement(event) == target) return;

			if(document.detachEvent) {
				document.detachEvent('onmousewheel', bframe.cancelEvent);
			}
			context_menu.hide();
		}

		openUrl = function(param) {
			p = param.split(',');
			if(p.length == 2) {
				var rel = bframe.getFrameByName(top, p[1]);
				rel.location.href = p[0].replace(/&amp;/, '&');
			}
			menu_container.closeAll();
		}
	}
