/*
 * B-studio : Content Management System
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	if(typeof bstudio == 'undefined' || !bstudio) {
		var bstudio = {};
	}

	// -------------------------------------------------------------------------
	// class bstudio
	// 
	// -------------------------------------------------------------------------
	bstudio.activateCalendar = function(target_id) {
		bframe.calendarContainer.activate(target_id);
	}

	bstudio.activateModalWindow = function(a, w, h, func, param) {
		var p = 'width:' + w + ',height:' + h;
		a.setAttribute('data-param', p);

		top.bframe.modalWindow.activate(a, window, param);
		if(func) top.bframe.modalWindow.registerCallBackFunction(func);
	}

	bstudio.insertIMG = function(dir, file_path, img_size, img_obj_id, hidden_obj_id, width, height) {
		window.frameElement.opener.bstudio._insertIMG(dir, file_path, img_size, img_obj_id, hidden_obj_id, width, height);
		window.frameElement.deactivate();

		return false;
	}

	bstudio._insertIMG = function(dir, file_path, img_size, img_obj_id, hidden_obj_id, w, h) {
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
			img.src = thumb;
			target.appendChild(img);
		}
	}

	bstudio.insertResourceIMG = function(dir, file_path, img_size, img_obj_id, hidden_obj_id, width, height) {
		window.frameElement.opener.bstudio._insertResourceIMG(dir, file_path, img_size, img_obj_id, hidden_obj_id, width, height);
		window.frameElement.deactivate();

		return false;
	}

	bstudio._insertResourceIMG = function(dir, file_path, img_size, img_obj_id, hidden_obj_id, w, h) {
		var hidden_target = document.getElementById(hidden_obj_id);
		if(hidden_target) {
			// set hidden target value (real value)
			hidden_target.value = file_path;
			bframe.fireEvent(hidden_target, 'change');
		}

		var target = document.getElementById(img_obj_id);
		if(target) {
			// set imag to a target child
			var image = target.getElementsByTagName('img');
			if(image.length) {
				var img = image[0];
			}
			else {
				var img = document.createElement('img');
			}
			img.src = dir + file_path;
			target.appendChild(img);
		}
	}

	bstudio.setTemplate = function(node_id, node_value) {
		if(node_id == 'root') return;
		bstudio.insertValue(window.frameElement.opener, 'template_id', node_id, 'template', node_value);
		window.frameElement.deactivate();
	}

	bstudio.setCategory = function(node_id, node_value) {
		if(node_id == 'root') return;
		bstudio.insertValue(window.frameElement.opener, 'category_id', node_id, 'category', node_value);
		window.frameElement.deactivate();
	}

	bstudio.setTags = function(separater1, target_id, separater2, hidden_target_id) {
		var tag, tags;
		var nodes = bframe_tree.getCurrentNodes();

		for(var i=0, tags='', tag_id=''; i<nodes.length; i++) {
			if(tags) tags += separater1;
			tags += nodes[i].path;
			if(tag_id) tag_id += separater2;
			tag_id += nodes[i].id.substr(1);
		}

		bstudio.insertTag(window.frameElement.opener, 'tags', tags, 'tag_id', tag_id);
		window.frameElement.deactivate();
	}

	bstudio.insertTag = function(opener, target_id, target_value, hidden_target_id, hidden_target_value) {
		opener.bstudio._insertTag(target_id, target_value, hidden_target_id, hidden_target_value);
	}

	bstudio._insertTag = function(target_id, target_value, hidden_target_id, hidden_target_value) {
		var target = document.getElementById(target_id);
		if(!target) {
			return;
		}
		target.value = target_value;

		var hidden_target = document.getElementById(hidden_target_id);
		if(!hidden_target) {
			return;
		}
		hidden_target.value = hidden_target_value;

		var html='';

		if(target_value) {
			var tags = target_value.split(',');
			for(let i=0; i<tags.length; i++) {
				html+= '<span>' + tags[i] + '</span>';
			}
		}

		var tag_list = document.getElementById('tag_list');
		tag_list.innerHTML = html;

		if(bframe.fireEvent) bframe.fireEvent(target, 'change');

		return false;
	}

	bstudio.clearTag = function() {
		var tag_list = document.getElementById('tag_list');
		if(tag_list && tag_list.innerHTML) {
			tag_list.innerHTML = '';
		}
		var tags = document.getElementById('tags');
		if(tags && tags.value) {
			tags.value = '';
		}
		var tag_id = document.getElementById('tag_id');
		if(tag_id && tag_id.value) {
			tag_id.value = '';
		}
	}

	bstudio.insertValue = function(opener, target_id, target_value, hidden_target_id, hidden_target_value) {
		opener.bstudio._insertValue(target_id, target_value, hidden_target_id, hidden_target_value);
	}

	bstudio._insertValue = function(target_id, target_value, hidden_target_id, hidden_target_value) {
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

	bstudio.setWidget = function(node_id, node_value, node_type) {
		if(node_type != 'widget') return;
		var code = window.frameElement.opener.bstudio._setWidget(node_id, node_value);
		window.frameElement.deactivate(code);
	}

	bstudio._setWidget = function(node_id, node_value) {
		return "<?php widget('" + node_id + "'); // " + node_value + " ?>";
	}

	bstudio.reloadTree = function() {
		if(typeof bframe_tree !== 'undefined') bframe_tree.reload();
	}

	bstudio.clearForm = function(form) {
		var element = document.getElementById(form);
		var value;

		var obj = element.getElementsByTagName('input');
		for(i=0; i < obj.length; i++) {
			if(obj[i].type == 'text') {
				if(value = obj[i].getAttribute('data-default')) {
					obj[i].value = value;
				}
				else {
					obj[i].value = '';
				}
			}
			if(obj[i].type == 'checkbox') {
				obj[i].checked = false;
			}
		}
		var obj = element.getElementsByTagName('select');

		for(i=0; i < obj.length; i++) {
			if(value = obj[i].getAttribute('data-default')) {
				obj[i].value = value;
			}
			else {
				obj[i].value = '';
			}
			bframe.fireEvent(obj[i], 'change');
		}
	}

	bstudio.clearIMG = function(target_id, hidden_target_id, hidden_target_id2) {
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

	bstudio.clearText = function(target_id1, target_id2) {
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

	bstudio.updateHtml = function(html) {
		var target = document.getElementById('html1');
		target.value = html;
		bframe.fireEvent(target, 'change');
	}

	bstudio.reloadMenu = function() {
		if(top.bframe.menuContainer) {
			top.bframe.menuContainer.reload();
		}
	}

	bstudio.registerEditor = function(fname, module, page, method, mode, nocheck) {
		if(window.opener) {
			bframe.ajaxSubmit.removeCallBackFunctionAfter(window.opener.bstudio.reloadTree);
			bframe.ajaxSubmit.registerCallBackFunctionAfter(window.opener.bstudio.reloadTree);
		}
		bframe.ajaxSubmit.submit(fname, module, page, method, mode, nocheck);
	}

	bstudio.articleDetailInit = function(flag1, flag2) {
		description_flag_1 = document.getElementById(flag1);
		description_flag_2 = document.getElementById(flag2);
		if(description_flag_1 && description_flag_2) {
			if(description_flag_1.checked == true) {
				bstudio.articleDetailControl(description_flag_1, 'external', 'external_link', 'url', 'external_window');
			}
			else {
				bstudio.articleDetailControl(description_flag_2, 'external', 'external_link', 'url', 'external_window');
			}
		}
	}

	bstudio.articleDetailControl = function(me, external_id, external_link_id, external_url_id, external_window_id) {
		var external = document.getElementById(external_id);
		var external_link = document.getElementById(external_link_id);
		var external_url = document.getElementById(external_url_id);
		var external_window = document.getElementById(external_window_id);
		if(me.value=='1') {
			external.classList.add('disabled');
			if(external_link) {
				external_link.disabled='disabled';
			}
			if(external_url) {
				external_url.disabled='disabled';
			}
			if(external_window) {
				external_window.disabled='disabled';
			}
		}
		if(me.value=='2') {
			external.classList.remove('disabled');
			if(external_link) {
				external_link.disabled='';
			}
			if(external_url) {
				external_url.disabled='';
			}
			if(external_window) {
				external_window.disabled='';
			}
		}
	}

	bstudio.setProperty = function(module) {
		bframe.ajaxSubmit.registerCallBackFunctionAfter(window.frameElement.deactivate);
		bframe.ajaxSubmit.submit('F1', module, 'property', 'register', '', true);
	}

	bstudio.backupAll = function(fname, module, page, method, mode, nocheck) {
		var info = bframe.getPageInfo();

		param = 'terminal_id='+info['terminal_id']+'&mode='+mode;
		if(mode) param+= '&mode='+mode;

		var httpObj = createXMLHttpRequest(showprogress);
		eventHandler(httpObj, module, page, method, 'POST', param);

		var params = {
			'id': 				'backupDialog', 
			'icon': 			'images/common/process.png',
			'complete_icon': 	'images/common/complete.png',
		}
		progress = new bframe.progressBar(params);

	 	function showprogress() {
			if((httpObj.readyState == 3) && httpObj.status == 200) {
				var response = eval('('+httpObj.responseText+')');
				var animate = '';
				switch(response['status']) {
				case 'show':
					progress.show();
					if(response['message']) progress.setMessage(response['message']);

				case 'progress':
					if(response['progress']) var animate = ' animate';
					progress.setProgress(response['progress'], animate);
					progress.setStatus(Math.round(response['progress']) + '%');
					if(response['message']) progress.setMessage(response['message']);
					break;

				case 'message':
					progress.setMessage(response['message']);
					if(response['icon']) progress.setIcon(response['icon']);
					break;

				case 'complete':
					if(response['progress']) var animate = ' animate';
					progress.setProgress(response['progress'], animate);
					progress.setStatus(Math.round(response['progress']) + '%');
					progress.complete(response['message']);
					break;

				case 'error':
					alert(response['message']);
					break;
				}
			}

			if((httpObj.readyState == 4) && httpObj.status == 200) {
				var response = eval('('+httpObj.responseText+')');
				if(response['status'] == 'download') {
					progress.remove();
					param = '&file_name='+response['file_name']+'&file_path='+response['file_path']+'&remove='+response['remove'];
					param+= '&mode=download';

					var form = fname ? document.forms[fname] : document.forms[0];
					bframe.appendHiddenElement(form, 'file_name', response['file_name']);
					bframe.appendHiddenElement(form, 'file_path', response['file_path']);

					bframe.submit(fname, module, page, method, 'download', nocheck);
				}
			}
		}
	}
