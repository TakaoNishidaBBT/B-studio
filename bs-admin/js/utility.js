/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	function activateModalWindow(a, w, h, func) {
		var p = 'width:' + w + ',height:' + h;
		a.setAttribute('params', p);

		top.bframe.modalWindow.activate(a, window);
		if(func) top.bframe.modalWindow.registCallBackFunction(func);
	}

	function insertIMG(dir, file_path, img_size, img_obj_id, hidden_obj_id, width, height) {
		window.frameElement.opener._insertIMG(dir, file_path, img_size, img_obj_id, hidden_obj_id, width, height);
		window.frameElement.deactivate();

		return false;
	}

	function _insertIMG(dir, file_path, img_size, img_obj_id, hidden_obj_id, w, h) {
		var hidden_target = document.getElementById(hidden_obj_id);
		if(hidden_target) {
			// set hidden target value (real value)
			hidden_target.value = file_path;
			bframe.fireEvent(hidden_target, 'change');
		}

		var target = document.getElementById(img_obj_id);
		if(target) {
			// set thumbnail to a target child
			var image = target.getElementsByTagName('img');
			if(image.length) {
				var img = image[0];
			}
			else {
				var img = document.createElement('img');
			}
			var file_path_array = file_path.split('/');
			file_path_array[file_path_array.length-1] = 'thumb_' + file_path_array[file_path_array.length-1];
			var thumb = file_path_array.join('/');
			if(dir.substr(-1) == '/' && thumb.substr(0, 1) == '/') thumb = thumb.substr(1);
			img.src = dir + thumb;
			target.appendChild(img);
		}
	}

	function setTemplate(node_id, node_value) {
		if(node_id == 'root') return;
		insertValue(window.frameElement.opener, 'template_id', node_id, 'template_name', node_value);
		window.frameElement.deactivate();
	}

	function setCategory(node_id, node_value) {
		if(node_id == 'root') return;
		insertValue(window.frameElement.opener, 'category_id', node_id, 'category_name', node_value);
		window.frameElement.deactivate();
	}

	function insertValue(opener, target_id, target_value, hidden_target_id, hidden_target_value) {
		opener._insertValue(target_id, target_value, hidden_target_id, hidden_target_value);
	}

	function _insertValue(target_id, target_value, hidden_target_id, hidden_target_value) {
		var target = document.getElementById(target_id);
		if(!target) {
			return;
		}
		target.value = target_value;

		var hidden_target = document.getElementById(hidden_target_id);
		hidden_target.value = hidden_target_value;

		if(bframe.fireEvent) bframe.fireEvent(target, 'change');

		return false;
	}

	function setWidget(node_id, node_value, node_type) {
		if(node_type != 'widget') return;
		var code = window.frameElement.opener._setWidget(node_id, node_value);
		window.frameElement.deactivate(code);
	}

	function _setWidget(node_id, node_value) {
		return "<?php widget('" + node_id + "'); // " + node_value + " ?>";
	}

	function reloadTree() {
		if(typeof bframe_tree !== 'undefined') bframe_tree.reload();
	}

	function clearForm(form, row_per_page) {
		var element = document.getElementById(form);

		var obj = element.getElementsByTagName('input');
		for(i=0 ; i < obj.length ; i++){
			if(obj[i].type == 'text') {
				obj[i].value = '';
			}
			if(obj[i].type == 'checkbox') {
				obj[i].checked = false;
			}
			if(obj[i].type == 'hidden') {
				if(obj[i].name != 'terminal_id' && obj[i].name != 'default_row_per_page') {
					obj[i].value = '';
				}
			}
		}
		var obj = element.getElementsByTagName('select');
		var default_row_per_page = document.getElementById('default_row_per_page');

		for(i=0 ; i < obj.length ; i++){
			if(obj[i].name == 'row_per_page') {
				if(default_row_per_page) {
					obj[i].value = default_row_per_page.value;
				}
				else {
					obj[i].value = row_per_page;
				}
			}
			else {
				obj[i].value = '';
			}
			bframe.fireEvent(obj[i], 'change');
		}
	}

	function clearIMG(target_id, hidden_target_id, hidden_target_id2) {
		var target = document.getElementById(target_id);
		if(!target) return;

		target.removeChild(target.lastChild);

		if(hidden_target_id) {
			var hidden_target = document.getElementById(hidden_target_id);
			if(!hidden_target) return;

			hidden_target.value = '';
		}

		if(hidden_target_id2) {
			var hidden_target2 = document.getElementById(hidden_target_id2);
			if(!hidden_target2) return;

			hidden_target2.value = '';
		}

		if(bframe.fireEvent) {
			bframe.fireEvent(target, 'change');
			bframe.fireEvent(hidden_target, 'change');
		}

		return false;
	}

	function clearText(target_id1, target_id2) {
		var target1 = document.getElementById(target_id1);
		if(target1 && target1.value) {
			target1.value = '';
			if(bframe.fireEvent) bframe.fireEvent(target1, 'change');
		}
		if(target_id2) {
			var target2 = document.getElementById(target_id2);
			if(target2 && target2.value) {
				target2.value = '';
				if(bframe.fireEvent) bframe.fireEvent(target2, 'change');
			}
		}
	}

	function updateHtml(html) {
		var target = document.getElementById('html1');
		target.value = html;
		bframe.fireEvent(target, 'change');
	}

	function reloadMenu() {
		if(top.bframe.menuContainer) {
			top.bframe.menuContainer.reload();
		}
	}

	function articleDetailInit(flag1, flag2) {
		description_flag_1 = document.getElementById(flag1);
		description_flag_2 = document.getElementById(flag2);
		if(description_flag_1 && description_flag_2) {
			if(description_flag_1.checked == true) {
				articleDetailControl(description_flag_1, 'external_link', 'url', 'external_window');
			}
			else {
				articleDetailControl(description_flag_2, 'external_link', 'url', 'external_window');
			}
		}
	}

	function articleDetailControl(me, external_link_id, external_url_id, external_window_id) {
		external_link = document.getElementById(external_link_id);
		external_url = document.getElementById(external_url_id);
		external_window = document.getElementById(external_window_id);
		if(me.value=='1') {
			if(external_link) {
				external_link.disabled=true;
			}
			if(external_url) {
				external_url.disabled=true;
			}
			if(external_window) {
				external_window.disabled=true;
			}
		}
		if(me.value=='2') {
			if(external_link) {
				external_link.disabled=false;
			}
			if(external_url) {
				external_url.disabled=false;
			}
			if(external_window) {
				external_window.disabled=false;
			}
		}
	}

	function setProperty(module) {
		bframe.ajaxSubmit.registCallBackFunctionAfter(window.frameElement.deactivate);
		bframe.ajaxSubmit.submit('F1', module, 'property', 'regist', '', true);
	}
