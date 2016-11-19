/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' ,bframeComboBoxInit);

	function bframeComboBoxInit(){
		var input = document.getElementsByTagName('img');

		for(var i=0; i<input.length; i++) {
			var s = new bframe.comboBox(input[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.comboBox
	// 
	// -------------------------------------------------------------------------
	bframe.comboBox = function(target) {
		if(!bframe.checkClassName('bframe_combo_box', target)) return;

		var target_id = bframe.getID(target);
		var open = false;
		var	response_wait = false;
		var httpObj;
		var row_no = -1;

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

		var relation_object = new Array();
		var relation_object_cnt = 0;
		var display_object = target;
		var display_element;

		var save_value = new Array();

		var property;

		var context_menu_frame = window;
		var context_menu_max_height = 300;

		// set event handller
		target.onclick = onclick;

		target.style.cursor = 'pointer';

		bframe.addEventListner(document, 'click' ,hide);
		bframe.addEventListner(top.document, 'click' ,hide);

		// auto complete off
		target.setAttribute('autocomplete', 'off');

		function onclick() {
			if(property) {
				getMenu();
			}
			else {
				init();
			}
		}

		function init() {
			var param = 'terminal_id='+terminal_id+'&class=bframe_combo_box&id='+target.id;
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse(){
			var rel;

			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				if(property.relation) {
					for(var i=0; i<property.relation.length; i++) {
						if(rel = bframe.getRelationObject(target, property.relation)) {
							relation_object[relation_object_cnt++] = rel;
						}
					}
				}
				if(property.display && property.display.position) {
					if(disp = bframe.getRelationObject(target, property.display.position)) {
						display_object = disp;
					}
				}
				if(property.context_menu_frame) {
					context_menu_frame = eval(property.context_menu_frame);
					bframe.context_menu.setDocument(context_menu_frame.window);
				}
				else {
					bframe.context_menu.setDocument(window);
				}
				if(property.display && property.display.element) {
					display_element = property.display.element;
				}
				if(property.display && property.display.max_height) {
					bframe.context_menu.setMaxHeight(property.display.max_height);
				}
				if(property.display && property.display.ext_width) {
					var ext_width = property.display.ext_width;
				}
				bframe.context_menu.setWidth(display_object.offsetWidth-2 + parseInt(ext_width));
				bframe.context_menu.setSelectFunction(setValue);
				bframe.context_menu.setOnClickFunction(setValue);

				response_wait = false;
				getMenu();
			}
		}

		function checkClassName(obj) {
			if(obj.className) {
				var arr = obj.className.split(' ');
				for(var j=0; j<arr.length; j++) {
					if(arr[j] == 'bframe_combo_box') {
						return true;
					}
				}
			}
			return false;
		}

		function getMenu() {
			if(response_wait == false) {
				var param = 'terminal_id='+terminal_id;

				for(var i in property.value) {
					var value_obj = bframe.getRelationObject(target, property.value[i]);
					if(value_obj) {
						param += '&' + property.value[i] + '='+value_obj.value;
					}
				}

				httpObj = createXMLHttpRequest(show);
				eventHandler(httpObj, property.module, property.file, property.method, 'POST', param);
				document.body.style.cursor = 'wait';
				if(open) {
					bframe.context_menu.setCursor('wait');
				}
				response_wait = true;
			}
			return false;
		}

		function show(){
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				xmlData = httpObj.responseXML;

				bframe.context_menu.createElementFromXml(xmlData);

				if(bframe.context_menu.getLength() > 0) {
					var position = bframe.getElementPosition(display_object);
					var frame_offset = bframe.getFrameOffset(window, context_menu_frame);

					position.left += frame_offset.left;
					position.top += frame_offset.top;
					bframe.context_menu.setPosition(position);
					bframe.context_menu.show();
				}
				else {
					bframe.context_menu.hide();
				}

				// save value
				for(var i=0 ; i<relation_object_cnt ; i++) {
					save_value[i] = relation_object[i].value;
				}

				document.body.style.cursor = 'auto';
				bframe.context_menu.setCursor('auto');
				response_wait = false;
				open = true;
			}
		}

		function hide() {
			if(!open) return;

			for(var i=0 ; i<relation_object_cnt ; i++) {
				if(save_value[i] != relation_object[i].value) {
					bframe.fireEvent(relation_object[i], 'change');
					break;
				}
			}

			bframe.context_menu.hide();
			open = false;
			response_wait = false;
			document.body.style.cursor = 'auto';
		}

		function onkeydown(event) {
			var element;
			if(window.event) {
				var keycode = window.event.keyCode;
			}
			if(top.window.event) {
				var keycode = top.window.event.keyCode;
			}
			else {
				var keycode = event.keyCode;
			}

			switch(keycode) {
			case 37: //©
			case 39: //¨
				return;

			case 9:  //tab
			case 13: //enter
				hide();
				return;

			case 38: //ª
				if(!open) return;

				if(element = bframe.context_menu.getElement()) {
					var row = bframe.context_menu.getRowIndex();

					if(row <= 0) {
						row = -1;
						bframe.context_menu.select(row);
					}
					else{
						if(row+1 <= element.rows.length) {
							row--;
							bframe.context_menu.select(row);
							target.value = getTargetValue(row)
						}
					}
				}
				return;

			case 40: //«
				if(!open) return;

				if(element = bframe.context_menu.getElement()) {
					var row = bframe.context_menu.getRowIndex();
					if(row == -1) {
						row = 0;
						bframe.context_menu.select(row);
						target.value = getTargetValue(row)
					}
					else{
						if(element.rows.length != row+1) {
							row++;
							bframe.context_menu.select(row);
							target.value = getTargetValue(row)
						}
					}
				}

				return;

			default:
				break;

			}
			return;
		}

		function setValue(row) {
			var node = xmlData.getElementsByTagName('response');
			_setValue(node[0].childNodes[row]);
		}

		function _setValue(node) {
			var obj;

			for(var i=0; i<node.childNodes.length; i++) {
				var child = node.childNodes[i];

				if(child.tagName && child.tagName != 'array' && child.tagName != target_id) {
					if(obj = bframe.getRelationObject(target, child.tagName)) {
						switch(obj.tagName.toLowerCase()) {
						case 'input':
							obj.value = child.childNodes[0].nodeValue;
							break;

						case 'img':
							obj.src = child.childNodes[0].nodeValue;
							break;

						case 'select':
							obj.innerHTML = '';
							var ids = child.getElementsByTagName('id');
							var values = child.getElementsByTagName('value');

							for(var j=0; j<ids.length; j++) {
								obj.options[j] = new Option(values[j].childNodes[0].nodeValue, ids[j].childNodes[0].nodeValue);
							}
							obj.options[0].selected = true;
							break;
						}
					}
				}
				else if(child.childNodes.length) {
					_setValue(child);
				}
			}
		}

		function getTargetValue(row_index) {
			var nodes = xmlData.getElementsByTagName(target_id);
			return  nodes.item(row_index).childNodes[0].nodeValue;
		}
	}
