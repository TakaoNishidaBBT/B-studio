/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' ,bframeFormInit);

	function bframeFormInit() {
		var objects = document.querySelectorAll('table.bframe_form');

		for(var i=0; i < objects.length; i++) {
			var s = new bframe.form(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.form
	// 
	// -------------------------------------------------------------------------
	bframe.form = function(target) {
		var	response_wait = false;
		var httpObj;

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

		var currentRow;
		var targetRow;
		var drag_obj;
		var property;

		var property_pane;
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

		Element.prototype.reflectionNode = function(flag) {
			var clone = this.cloneNode(flag);
			reflection(this, clone);
			return clone;
		}

		function reflection(src, dest) {
			for(var i=0 ; i < src.childNodes.length ; i++) {
				if(src.childNodes[i].tagName) {
					switch(src.childNodes[i].tagName.toLowerCase()) {
					case 'textarea':
						dest.childNodes[i].value = src.childNodes[i].value;
						break;

					case 'select':
						for(var j=0 ; j < src.childNodes[i].options.length ; j++) {
							dest.childNodes[i].options[j].selected = src.childNodes[i].options[j].selected;
						}
						break;

					case 'input':
						if(src.childNodes[i].id) {
							if(src.childNodes[i].id.substr(-3, 3) == '___') {
								dest.childNodes[i].id = src.childNodes[i].id.substr(0, src.childNodes[i].id.length - 3);
							}
							else {
								dest.childNodes[i].id = src.childNodes[i].id + '___';
							}
						}
						if(src.childNodes[i].name) {
							if(src.childNodes[i].name.substr(-3, 3) == '___') {
								dest.childNodes[i].name = src.childNodes[i].name.substr(0, src.childNodes[i].name.length - 3);
							}
							else {
								dest.childNodes[i].name = src.childNodes[i].name + '___';
							}
						}
						break;

					case 'label':
						if(src.childNodes[i].htmlFor) {
							if(src.childNodes[i].htmlFor.substr(-3, 3) == '___') {
								dest.childNodes[i].htmlFor = src.childNodes[i].htmlFor.substr(0, src.childNodes[i].htmlFor.length - 3);
							}
							else {
								dest.childNodes[i].htmlFor = src.childNodes[i].htmlFor + '___';
							}
						}
						break;

					default:
						reflection(src.childNodes[i], dest.childNodes[i]);
						break;
					}
				}
			}
			return;
		}

		Element.prototype.copyNode = function(flag) {
			var clone = this.cloneNode(flag);
			copy(this, clone);
			return clone;
		}

		function copy(src, dest) {
			for(var i=0 ; i < src.childNodes.length ; i++) {
				if(src.childNodes[i].tagName) {
					switch(src.childNodes[i].tagName.toLowerCase()) {
					case 'textarea':
						dest.childNodes[i].value = src.childNodes[i].value;
						break;

					case 'select':
						for(var j=0 ; j < src.childNodes[i].options.length ; j++) {
							dest.childNodes[i].options[j].selected = src.childNodes[i].options[j].selected;
						}
						break;

					case 'input':
						if(src.childNodes[i].type == 'radio') {
							if(src.childNodes[i].id) {
								dest.childNodes[i].id = src.childNodes[i].id + 'x';
							}
							if(src.childNodes[i].name) {
								dest.childNodes[i].name = src.childNodes[i].name + 'x';
							}
						}
						if(src.childNodes[i].type == 'hidden') {
							if(src.childNodes[i].value) {
								dest.childNodes[i].value = src.childNodes[i].value + 'x';
							}
						}
						break;

					case 'label':
						if(src.childNodes[i].htmlFor) {
							dest.childNodes[i].htmlFor = src.childNodes[i].htmlFor + 'x';
						}
						break;

					default:
						copy(src.childNodes[i], dest.childNodes[i]);
						break;
					}
				}
			}
			return;
		}

		init();

		function init() {
			var param = 'terminal_id='+terminal_id+'&class=bframe_form&id='+target.id;
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse(){
			var rel;

			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				setPropertyPane();
				setControl();
				setContextMenu();
				selectFirstRow();

				response_wait = false;
			}
		}

		function selectFirstRow() {
			var tr = target.getElementsByTagName('tr');
			bframe.fireEvent(tr[0], 'click');
		}

		function setPropertyPane() {
			if(property.property_pane) {
				property_pane = document.getElementById(property.property_pane);
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
			for(i=0; i < tr.length; i++){
				if(bframe.searchParentByTagName(tr[i], 'table') != target) continue;

				tr[i].oncontextmenu = showContextMenu;
				tr[i].onclick = onClick;
			}
			bframe.addEventListener(document, "click", hideContextMenu);
		}

		function onClick(event){
			selectRow(event);
			bframe.stopPropagation(event);
		}

		function reflectionProperty(tr) {
			for(var i=0; i < tr.cells.length; i++) {
				var td = tr.cells[i];
				if(bframe.checkClassName('bframe_form_property', td)) {
					var clone = td.childNodes[0].reflectionNode(true);
					if(property_pane.firstChild) {
						property_pane.replaceChild(clone, property_pane.firstChild);
					}
					else {
						property_pane.appendChild(clone);
					}

					addEventListenerAllEditableElement(clone, 'input', 'blur', synchro);
					addEventListenerAllEditableElement(clone, 'input', 'click', synchro);
					addEventListenerAllEditableElement(clone, 'input', 'change', changeFieldValue);
					addEventListenerAllEditableElement(clone, 'textarea', 'change', changeFieldValue);
					addEventListenerAllEditableElement(clone, 'select', 'change', selectFieldType);
				}
			}
		}

		function addEventListenerAllEditableElement(node, type, event, func) {
			if(node.tagName && node.tagName.toLowerCase() == type) {
				bframe.addEventListener(node, event, func);
			}

			for(var i=0; i<node.childNodes.length; i++) {
				addEventListenerAllEditableElement(node.childNodes[i], type, event, func);
			}
		}

		function synchro(event) {
			var clone = property_pane.childNodes[0].reflectionNode(true);
			for(var i=0; i < currentRow.cells.length; i++) {
				var td = currentRow.cells[i];
				if(bframe.checkClassName('bframe_form_property', td)) {
					current_cell = td;
					break;
				}
			}

			if(current_cell.firstChild) {
				current_cell.replaceChild(clone, current_cell.firstChild);
			}
			else {
				current_cell.appendChild(clone);
			}
		}

		function changeFieldValue(event) {
			synchro();
			var param = 'terminal_id='+terminal_id;

			var form = document.forms[property.property_pane];
			var data = $(form).serialize(); // use jquery
			if(data) {
				param+= '&' + data;
			}

			httpObj = createXMLHttpRequest(showItem);

			eventHandler(httpObj, module, page, 'changeFieldValue', 'POST', param);
			target.style.cursor = 'wait';
			targetRow = currentRow;
			response_wait = true;
			bframe.editCheck_handler.setEditFlag();
		}

		function selectFieldType(event) {
			synchro();
			var param = 'terminal_id='+terminal_id;

			var form = document.forms[property.property_pane];
			var data = $(form).serialize(); // use jquery
			if(data) {
				param+= '&' + data;
			}

			httpObj = createXMLHttpRequest(showItem);

			eventHandler(httpObj, module, page, 'selectFieldType', 'POST', param);
			target.style.cursor = 'wait';
			targetRow = currentRow;
			response_wait = true;
			bframe.editCheck_handler.setEditFlag();
		}

		function showItem() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				var xmlData = httpObj.responseXML;
				var item = xmlData.getElementsByTagName('item')[0].firstChild.nodeValue;

				targetRow.innerHTML = item;
				reflectionProperty(currentRow);
				response_wait = false;
				target.style.cursor = 'default';
			}
		}

		function showContextMenu(event){
			if(context_menu.getLength() > 0) {
				var position = context_menu.getPosition(event);
				position.left += context_menu_frame_offset.left;
				position.top += context_menu_frame_offset.top;

				context_menu.positionAbsolute(position);
				context_menu.show();
			}
			bframe.addEventListener(document, 'mousewheel', cancelEvent);
			selectRow(event);

			return false;
		}

		function hideContextMenu(){
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
			var tr = searchParentByTagName(obj, 'tr');
			var table = searchParentByTagName(tr, 'table');
			if(table != target) {
				var tr = searchParentByTagName(table, 'tr');
			}
			_selectRow(tr);
		}

		function _selectRow(tr) {
			unselectRow();

			reflectionProperty(tr);
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
			var newTR = insertRowValue(currentRow.rowIndex, currentRow.rowIndex, true);
			if(property.fixed_row_cnt) {
				target.deleteRow(parseInt(property.max_row_cnt)+1);
			}
			_selectRow(newTR);
		}

		insertRowUpperAdd = function() {
			var newTR = insertRowValue(currentRow.rowIndex, currentRow.rowIndex, true);
			_selectRow(newTR)
		}

		insertRowLower = function() {
			var newTR = insertRowValue(currentRow.rowIndex, currentRow.rowIndex+1, true);
			if(property.fixed_row_cnt) {
				target.deleteRow(parseInt(property.max_row_cnt)+1);
			}
			_selectRow(newTR);
		}

		insertRowLowerAdd = function() {
			var newTR = insertRowValue(currentRow.rowIndex, currentRow.rowIndex+1, true);
			_selectRow(newTR);
		}

		clearRow = function() {
			target.deleteRow(currentRow.rowIndex);
			var newTR = insertRowValue(2, target.rows.length, true);
			unselectRow();
			UpDownBtnControl();
		}

		deleteRow = function() {
			var index = currentRow.rowIndex;
			target.deleteRow(currentRow.rowIndex);
			unselectRow();
			currentRow = target.rows[index];
			row_class_name = currentRow.className;
			currentRow.className = 'selected';
			bframe.fireEvent(currentRow, 'click');

			hideContextMenu();
			UpDownBtnControl();
		}

		function clickUpButton(event){
			clickUpDownButton('UP', event);
		}

		function clickDownButton(event){
			clickUpDownButton('DOWN', event);
		}

		function clickUpDownButton(dir, event){
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
			var newTR = target.rows[org].copyNode(true);
			newTR.className = row_class_name;
			newTR.oncontextmenu=target.rows[org].oncontextmenu;
			newTR.ondblclick=target.rows[org].ondblclick;
			newTR.onclick=target.rows[org].onclick;

			var p = target.rows[org].parentNode;
			if(target.rows[dest]) {
				p.insertBefore(newTR, target.rows[dest]);
			}
			else {
				p.appendChild(newTR);
			}

			for(var i=0 ; i < newTR.cells.length ; i++) {
				var newTD = newTR.cells[i];

				for(var j=0 ; j < newTD.childNodes.length ; j++) {
					if(bframe.suggest) {
						var s = new bframe.suggest(newTD.childNodes[j]);
					}
					if(bframe.comboBox) {
						var s = new bframe.comboBox(newTD.childNodes[j]);
					}
					if(newTD.childNodes[j].id == property.error_message_id) {
						newTD.removeChild(newTD.childNodes[j]);
					}
				}
			}
			bframe.editCheck_handler.setEditFlag();
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
			e.preventDefault? e.preventDefault() : e.returnValue = false;
		}
	}
