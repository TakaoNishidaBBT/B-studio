/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' ,bframeGridInit);

	function bframeGridInit(){
		var objects = document.querySelectorAll('table.bframe_grid');

		for(var i=0; i < objects.length; i++) {
			var s = new bframe.grid(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.grid
	// 
	// -------------------------------------------------------------------------
	bframe.grid = function(target) {

		var open = false;
		var	response_wait = false;
		var httpObj;

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

		var currentRow;
		var drag_obj;
		var property;

		var context_menu = new bframe.contextMenu(3);
		var context_menu_frame = window;
		var context_menu_frame_offset;
		var context_menu_width = 100;
		var context_menu_height = 100;
		var context_menu_element = {};

		var property;
		var ext_width = 0;

		var up_control;
		var down_control;

		// set event handller
		for(var i=0; i < target.rows.length; i++) {
			target.rows[i].ondblclick = selectRow;
		}

		bframe.addEventListener(document, 'click', cleanUp);

		function cleanUp() {
			hideContextMenu();
			unselectRow();
			UpDownBtnControl();
		}

		init();

		function init() {
			var param = 'terminal_id='+terminal_id+'&class=bframe_grid&id='+target.id;
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse(){
			var rel;

			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				setControl();
				setContextMenu();
				setOnKeyDown();

				response_wait = false;
			}
		}

		function setContextMenu(){
			if(property.context_menu_frame) {
				context_menu_frame = eval(property.context_menu_frame);
				context_menu.setDocument(context_menu_frame.window);
			}
			else {
				context_menu.setDocument(window);
			}

			context_menu_frame_offset = bframe.getFrameOffset(window, context_menu_frame);

			if(property.context_menu_width) {
				context_menu_width = property.context_menu_width;
			}
			context_menu.createElementFromObject(property.context_menu, this);
			context_menu.setWidth(context_menu_width);
			context_menu_element.size = context_menu.getElementSize();
			context_menu_height = context_menu_element.size.height;

			var tr = target.getElementsByTagName('tr');
			for(i=1; i < tr.length; i++){
				tr[i].oncontextmenu=showContextMenu;
			}
		}

		function setControl() {
			if(property.control) {
				up_control = document.getElementById(property.control.up);
				bframe.addEventListener(up_control, 'click', clickUpButton);

				down_control = document.getElementById(property.control.down);
				bframe.addEventListener(down_control, 'click', clickDownButton);

				if(_isIE) {
					bframe.addEventListener(up_control, 'dblclick', clickUpButton);
					bframe.addEventListener(down_control, 'dblclick', clickDownButton);
				}
			}
		}

		function setOnKeyDown() {
			itemListonkeydown
			var tr = target.getElementsByTagName('tr');
			for(i=1; i < tr.length; i++) {
				var current_tr = tr[i];
				for(var j=0; j < current_tr.cells.length; j++) {
					var current_td = current_tr.cells[j];
					for(var k=0; k < current_td.childNodes.length; k++) {
						if(current_td.childNodes[k].tagName && current_td.childNodes[k].tagName.toLowerCase() == 'input') {
							bframe.addEventListener(current_td.childNodes[k], 'keydown', itemListonkeydown);
						}
					}
				}
			}
		}

		function showContextMenu(event){

			if(context_menu.getLength() > 0) {
				var position = getPosition(event);
				position.left += context_menu_frame_offset.left;
				position.top += context_menu_frame_offset.top;

				context_menu.positionAbsolute(position);
				context_menu.show();
			}
			bframe.addEventListener(document, 'mousewheel', cancelEvent);
			selectRow(event);

			return false;
		}

		function getPosition(event) {
			var top;
			var left;

			if(window.event) {
				if(((document.body.offsetWidth - window.event.clientX) > parseInt(context_menu_width) + 20) || parseInt(context_menu_width) > window.event.clientX) {
					left = window.event.clientX;
				}
				else {
					left = window.event.clientX - parseInt(context_menu_width);
				}
				if(((document.body.offsetHeight - window.event.clientY) > parseInt(context_menu_height) + 20) || parseInt(context_menu_height) > window.event.clientY) {
					top = window.event.clientY;
				}
				else {
					top = window.event.clientY - parseInt(context_menu_height);
				}
				left += document.body.scrollLeft;
				top += document.body.scrollTop;
			}
			else {
				var scrollTop = document.documentElement.scrollTop;
				var scrollLeft = document.documentElement.scrollLeft;
				if(((scrollLeft + document.documentElement.clientWidth - event.pageX) > parseInt(context_menu_width) + 20) || parseInt(context_menu_width) > event.pageX) {
					left = event.pageX;
				}
				else {
					left = event.pageX - parseInt(context_menu_width);
				}
				if(((scrollTop + document.documentElement.clientHeight - event.pageY) > parseInt(context_menu_height) + 0) || parseInt(context_menu_height) > event.pageY) {
					top = event.pageY;
				}
				else {
					top = event.pageY - parseInt(context_menu_height);
				}
			}
			return {left:Math.round(left), top:Math.round(top)};
		}

		function hideContextMenu() {
			if(document.detachEvent) {
				document.detachEvent('onmousewheel', cancelEvent);
			}
			context_menu.hide();
		}

		function selectRow(event) {
			if(event) {
				var	obj = event.target;
			}
			else {
				var	obj = window.event.srcElement;
			}
			tr = searchParentByTagName(obj, 'tr');

			unselectRow();

			currentRow = tr;
			row_class_name = currentRow.className;
			tr.className = 'selected';

			UpDownBtnControl();
		}

		function unselectRow() {
			if(currentRow) {
				currentRow.className = row_class_name;
			}
			currentRow = '';
		}

		function searchParentByTagName(obj, tag_name) {
			if(obj) {
				if(obj.tagName.toLowerCase() == tag_name.toLowerCase()) {
					return obj;
				}
				var p = obj.parentNode;
				return searchParentByTagName(p, tag_name);
			}
		}

		insertRowUpper = function() {
			insertRowValue(currentRow.rowIndex, currentRow.rowIndex, true);
			unselectRow();
			if(property.fixed_row_cnt) {
				target.deleteRow(parseInt(property.max_row_cnt)+1);
			}
			UpDownBtnControl();
		}

		insertRowUpperAdd = function() {
			insertRowValue(currentRow.rowIndex, currentRow.rowIndex, true);
			unselectRow();
			UpDownBtnControl();
		}

		insertRowLower = function() {
			insertRowValue(currentRow.rowIndex, currentRow.rowIndex+1, true);
			unselectRow();
			if(property.fixed_row_cnt) {
				target.deleteRow(parseInt(property.max_row_cnt)+1);
			}
			UpDownBtnControl();
		}

		insertRowLowerAdd = function() {
			insertRowValue(currentRow.rowIndex, currentRow.rowIndex+1, true);
			unselectRow();
			UpDownBtnControl();
		}

		clearRow = function() {
			target.deleteRow(currentRow.rowIndex);
			insertRowValue(2, target.rows.length, true);
			unselectRow();
			UpDownBtnControl();
		}

		deleteRow = function() {
			target.deleteRow(currentRow.rowIndex);
			unselectRow();
			UpDownBtnControl();
		}

		function clickUpButton(event) {
			clickUpDownButton('UP', event);
		}

		function clickDownButton(event) {
			clickUpDownButton('DOWN', event);
		}

		function clickUpDownButton(dir, event) {
			if(currentRow) {
				// child node
				if(dir == 'UP') {
					var newTR = insertRowValue(currentRow.rowIndex, currentRow.rowIndex-1, false);
					var i = currentRow.rowIndex-2;
					target.deleteRow(currentRow.rowIndex);
				}
				else {
					var newTR = insertRowValue(currentRow.rowIndex, currentRow.rowIndex+2, false);
					target.deleteRow(currentRow.rowIndex);
				}

				currentRow = newTR;
				row_class_name = currentRow.className;
				currentRow.className = 'selected';
			}
			bframe.stopPropagation(event);
			UpDownBtnControl();
		}

		function UpDownBtnControl() {
			if(!up_control || !down_control) return;

			if(!currentRow) {
				up_control.disabled  = true;
				down_control.disabled  = true;
				return;
			}
			if(currentRow.rowIndex <= property.header_row_cnt) {
				up_control.disabled  = true;
			}
			else {
				up_control.disabled  = false;
			}
			if(currentRow.rowIndex+1 >= target.rows.length) {
				down_control.disabled  = true;
			}
			else {
				down_control.disabled  = false;
			}
		}

		function insertRowValue(org, dest, reset) {
			var newTR = target.rows[org].cloneNode(true);
			newTR.className = row_class_name;
			newTR.oncontextmenu=target.rows[org].oncontextmenu;
			newTR.ondblclick=target.rows[org].ondblclick;

			var p = target.rows[org].parentNode;
			if(target.rows[dest]) {
				p.insertBefore(newTR, target.rows[dest]);
			}
			else {
				p.appendChild(newTR);
			}

			for(var i=0; i < newTR.cells.length; i++) {
				var newTD = newTR.cells[i];

				for(var j=0; j < newTD.childNodes.length; j++) {
					if(reset) {
						if(!setDefault(newTD.childNodes[j])) {
							if(newTD.childNodes[j].tagName) {
								switch(newTD.childNodes[j].tagName.toLowerCase()) {
								case 'input':
									if(newTD.childNodes[j].type != 'hidden') {
										newTD.childNodes[j].value = '';
										newTD.childNodes[j].className = target.rows[org].cells[i].childNodes[j].className;
									}
									break;

								default:
									if(newTD.childNodes[j].value) {
										newTD.childNodes[j].value = '';
									}
									break;
								}
							}
						}
					}
					if(bframe.suggest) {
						var s = new bframe.suggest(newTD.childNodes[j]);
					}
					if(bframe.comboBox) {
						var s = new bframe.comboBox(newTD.childNodes[j]);
					}
					if(newTD.childNodes[j].id == property.error_message_id) {
						newTD.removeChild(newTD.childNodes[j]);
					}
					bframe.addEventListener(newTD.childNodes[j], 'keydown', itemListonkeydown);
				}
			}
			return newTR;
		}

		function setDefault(object) {
			var ret = false;

			if(property.default_value) {
				for(var i=0; i<property.default_value.length; i++) {
					if(property.default_value[i].id == object.id) {
						if(object.value && property.default_value[i].value) {
							object.value = property.default_value[i].value;
							ret = true;
						}
						if(object.src && property.default_value[i].src) {
							object.src = property.default_value[i].src;
							ret = true;
						}
					}
				}
			}
			return ret;
		}

		function cancelEvent(e) {
			e.preventDefault ? e.preventDefault() : e.returnValue = false;
		}

		function itemListonkeydown(event) {
			if(window.event) {
				var	obj = window.event.srcElement;
			}
			else {
				var	obj = event.target;
			}
			var p = obj.parentNode;
			var tr = searchParentByTagName(obj, 'tr');
			var i;

			switch(event.keyCode) {
			case 13: //enter
				if(_isIE) {
					var cellIndex = bframe.getAbsoluteIndex(tr, obj.id);
				}
				else {
					var cellIndex = p.cellIndex;
				}

				for(i=cellIndex+1; i < tr.cells.length; i++) {
					for(var j=0; j < tr.cells[i].childNodes.length; j++) {
						var item = tr.cells[i].childNodes[j];
						if(item.tagName && item.tagName.toLowerCase() == 'input') {
							item.focus();
							item.select();
							break;
						}
					}
					if(j < tr.cells[i].childNodes.length) {
						break;
					}
				}
				if(i == tr.cells.length) {
					if(target.rows.length > tr.rowIndex+parseInt(property.header_row_cnt)) {
						tr = target.rows[tr.rowIndex+1];
					}
					else {
						tr = target.rows[property.header_row_cnt];
					}
					for(i=0; i < tr.cells.length; i++) {
						for(var j=0; j < tr.cells[i].childNodes.length; j++) {
							var item = tr.cells[i].childNodes[j];
							if(item.tagName && item.tagName.toLowerCase() == 'input') {
								item.focus();
								item.select();
								break;
							}
						}
						if(j < tr.cells[i].childNodes.length) {
							break;
						}
					}
				}
				break;

			default:
				break;
			}
		}
	}
