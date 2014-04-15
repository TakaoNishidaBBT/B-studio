/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeSuggestInit);

	function bframeSuggestInit(){
	    var input = document.getElementsByTagName('input');

	    for(var i=0; i<input.length; i++) {
			var s = new bframe.suggest(input[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.suggest
	// 
	// -------------------------------------------------------------------------

	bframe.suggest = function(target) {
		if(!bframe.checkClassName('bframe_suggest', target)) {
			return false;
		}

		var target_id = bframe.getID(target);

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

		var open = false;
		var	response_wait = false;
		var row_no = -1;
		var timer;
		var select_status = false;

		var httpObj;
		var xmlData;

		var last_value = '';
		var save_value = '';
		var start_value = '';

		var relation_object = new Array();
		var relation_object_cnt = 0;
		var display_object = target;

		var property;
		var ext_width = 0;

		var context_menu_frame = window;

		// set event handller
		target.onfocus = start;
		target.onblur = stop;
		target.onkeydown = onkeydown;

		bframe.addEventListner(document, 'click', hide);
		bframe.addEventListner(document, 'contextmenu', hide);

		// auto complete off
		target.setAttribute('autocomplete', 'off');

		function init() {
			var param;

			param = 'terminal_id='+terminal_id+'&class=bframe_suggest&id='+target.id;
			httpObj = createXMLHttpRequest(initResponse);

			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse(){
			var rel;

			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				if(property.relation) {
					if(bframe.isArray(property.relation)) {
						for(var i=0; i<property.relation.length; i++) {
							if(rel = bframe.getRelationObject(target, property.relation[i])) {
								relation_object[relation_object_cnt++] = rel;
							}
						}
					}
					else {
						if(rel = bframe.getRelationObject(target, property.relation)) {
							relation_object[relation_object_cnt++] = rel;
						}
					}
				}
				if(property.context_menu_frame) {
					context_menu_frame = eval(property.context_menu_frame);
					bframe.context_menu.setDocument(context_menu_frame.window);
				}
				else {
					bframe.context_menu.setDocument(window);
				}
				if(property.context_menu_width) {
					context_menu_width = property.context_menu_width;
				}

				if(property.display && property.display.position) {
					if(disp = bframe.getRelationObject(target, property.display.position)) {
						display_object = disp;
					}
				}
				if(property.display && property.display.max_height) {
					bframe.context_menu.setMaxHeight(property.display.max_height);
				}
				if(property.display && property.display.ext_width) {
					ext_width = property.display.ext_width;
				}
				bframe.context_menu.setBorder('1px #ACA899 solid');

				response_wait = false;
			}
		}

		function start() {
			if(!property) {
				init();
			}

			last_value = target.value;
			start_value = target.value;
			save_value = target.value;

			bframe.context_menu.setSelectFunction(setValue);
			bframe.context_menu.setOnClickFunction(insertText);

			if(target.value) {
				select_status = true;
			}
			timer = setInterval(compare, 50);
		}

		function stop() {
			clearInterval(timer);
			if(start_value != target.value) {
				bframe.fireEvent(target, 'change');
			}
			if(!select_status && property.select == 'must') {
				// clear target value
				target.value = '';
			}
		}

		function compare() {
			if(target.value != last_value && response_wait == false) {
				last_value = target.value;

				if(target.value.length == 0) {
					if(open) {
						bframe.context_menu.hide();
					}
				}
				else {
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
				clear();
		    }
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
					bframe.context_menu.setWidth(display_object.offsetWidth-2 + parseInt(ext_width));
					bframe.context_menu.setOffsetHeight(display_object.offsetHeight-1);
					bframe.context_menu.setPosition(position);
					bframe.context_menu.show();
				}
				else {
					bframe.context_menu.hide();
				}

				document.body.style.cursor = 'auto';
				bframe.context_menu.setCursor('auto');
				response_wait = false;
				open = true;
			}
		}

		function hide() {
			if(!open) return;

			if(bframe.context_menu.getRowIndex() == -1) {
				autoSelect(target.value);
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
			else {
				var keycode = event.keyCode;
			}

			switch(keycode) {
			case 37: //Å©
			case 39: //Å®
				return;

			case 9:  //tab
				last_value = target.value;
				hide();
				return;

			case 13: //enter
				if(open) {
					last_value = target.value;
					hide();
					return false;
				}
				return;

			case 38: //Å™
				if(!open) return;

				if(element = bframe.context_menu.getElement()) {
					var row = bframe.context_menu.getRowIndex();

					if(row <= 0) {
						row = -1;
						bframe.context_menu.select(row);

						target.value = save_value;
						last_value = target.value;
						clear();
					}
					else{
						if(row+1 <= element.rows.length) {
							row--;
							bframe.context_menu.select(row);
							setValue(row);

							target.value = getTargetValue(row)
							last_value = target.value;
						}
					}
				}
				return;

			case 40: //Å´
				if(!open) return;

				if(element = bframe.context_menu.getElement()) {
					var row = bframe.context_menu.getRowIndex();
					if(row == -1) {
						row = 0;
						bframe.context_menu.select(row);
						setValue(row);
						save_value = target.value;

						target.value = getTargetValue(row)
						last_value = target.value;
					}
					else{
						if(element.rows.length != row+1) {
							row++;
							bframe.context_menu.select(row);
							setValue(row);
							target.value = getTargetValue(row)
							last_value = target.value;
						}
					}
				}

				return;

			default:
				break;

			}
			return;
		}

		function getTargetValue(row_index) {
			var nodes = xmlData.getElementsByTagName(target_id);
			return  nodes.item(row_index).childNodes[0].nodeValue;
		}

		function insertText(row_index) {
			var nodes = xmlData.getElementsByTagName(target_id);
			target.value = nodes.item(row_index).childNodes[0].nodeValue;
			setValue(row_index);
		}

		function autoSelect(value) {
			if(!open || target.value == '') return;

			target_column = xmlData.getElementsByTagName(target_id);
			for(var i=0; i<target_column.length; i++) {
				if(target_column[i].firstChild.nodeValue == target.value) {
					setValue(i);
					return;
				}
			}
		}

		function setValue(row) {
			var node = xmlData.getElementsByTagName('response');
			_setValue(node[0].childNodes[row]);
			select_status = true;
		}

		function _setValue(node) {
			var obj;

			for(var i=0; i<node.childNodes.length; i++) {
				var child = node.childNodes[i];

				if(child.tagName && child.tagName != 'array' && child.tagName != target_id) {
					if(obj = bframe.getRelationObject(target, child.tagName)) {
						switch(obj.tagName.toLowerCase()) {
						case 'input':
							if(child.childNodes.length) {
								obj.value = child.childNodes[0].nodeValue;
							}
							else {
								obj.value = '';
							}
							break;

						case 'span':
							if(child.childNodes.length) {
								obj.innerHTML = child.childNodes[0].nodeValue;
							}
							else {
								obj.innerHTML = '';
							}
							break;

						case 'img':
							if(child.childNodes.length) {
								obj.src = child.childNodes[0].nodeValue;
							}
							else {
								obj.src = '';
							}
							break;

						case 'select':
							obj.innerHTML = "";
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

		function clear() {
			for(var i=0; i<relation_object.length ; i++) {
				if(!setDefault(relation_object[i])) {
					switch(relation_object[i].tagName.toLowerCase()) {
					case 'input':
						relation_object[i].value = '';
						break;

					case 'select':
						for(var j=relation_object[i].options.length-1; j>=0; j--) {
							relation_object[i].removeChild(relation_object[i].options[j]);
						}
						break;
					}
				}
			}
			select_status = false;
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
	}
