/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load', bframeTreeInit);

	function bframeTreeInit() {
		var objects = document.getElementsByClassName('bframe_tree');

		for(var i=0; i < objects.length; i++) {
			bframe_tree = new bframe.tree(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.tree
	// 
	// -------------------------------------------------------------------------
	bframe.tree = function(target) {
		var self = this;
		var target_id = bframe.getID(target);

		var response_wait = false;
		var paste_mode = false;
		var response;
		var httpObj;

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

		var property;

		var root;
		var root_ul;
		var trash_ul;

		var node_number = 0;
		var current_node = new currentNodeControl();
		var selected_node = new currentNodeControl();
		var current_edit_node;
		var eventPlace;
		var new_node;

		var tab_control;
		var drag_control;
		var file_upload;

		var context_menu = new bframe.contextMenu(10000);
		var context_menu_frame = window;
		var context_menu_element = {};

		var trash_context_menu = new bframe.contextMenu(10000);
		var trash_context_menu_frame = window;
		var trash_context_menu_element = {};

		var context_menu_width = 100;
		var context_menu_height = 100;
		var context_cut_index = -1;
		var context_copy_index = -1;
		var context_paste_index = -1;
		var context_delete_index = -1;
		var context_editname_index = -1;

		var clipboard = {};

		var pane;
		var pane_div;
		var pane_ul;
		var pane_table;
		var pane_tbody;
		var pane_offset;
		var sort_key;
		var display_mode;
		var display_thumbnail;
		var display_detail;

		var progress;

		var opener = window.opener;

		init();

		function init() {
			var param;

			param = 'terminal_id='+terminal_id+'&class=bframe_tree&id='+target.id;
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait) {
				property = eval('('+httpObj.responseText+')');
				response_wait = false;
				getPane();

				if(property.editable == 'true' || property.sort == 'manual') {
					setTabControl();
					setDragControl();
					setFileUpload();
					setContextMenu();
					context_menu.setFilter(context_filter);
					setTrashContextMenu();
					setEventHandler();
				}
				if(property.selectable == 'true') {
					target.onclick = resetCurrentObject;
				}
				getNodeList('');
			}
		}

		function setTabControl() {
			if(property.editor_mode == 'true') {
				tab_control = new tabControl();
			}
		}

		function setDragControl() {
			drag_control = new dragControl(top.window);
		}

		function setFileUpload() {
			if(property.upload) {
				file_upload = new fileUpload();
				file_upload.init();
			}
		}

		function getPane() {
			if(property.relation && property.relation.pane) {
				pane = document.getElementById(property.relation.pane.id);
				if(property.editable == 'true' || property.sort == 'manual') {
					pane.oncontextmenu=showContextMenu;
					pane.onclick = resetSelectedObject;
				}
				if(property.display_mode) {
					setDispChange();
				}
			}
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
			bframe.stopPropagation(event);
			if(display_mode == 'thumbnail') return;

			display_mode = 'thumbnail';
			getNodeList(current_node.id());
		}

		function display_detail_mode(event) {
			bframe.stopPropagation(event);
			if(display_mode == 'detail') return;

			display_mode = 'detail';
			getNodeList(current_node.id());
		}

		function setEventHandler() {
			bframe.addEventListener(window, 'beforeunload', cleanUp);
			bframe.addEventListener(window, 'resize', hideContextMenu);

			// set event handller
			bframe.addEventListenerAllFrames(top, 'load', hideContextMenuAllFrames);
			bframe.addEventListenerAllFrames(top, 'mousedown', hideContextMenu);
			bframe.addEventListenerAllFrames(top, 'click', hideContextMenu);
			bframe.addEventListenerAllFrames(top, 'mouseup', drag_control.onMouseUp);
			bframe.addEventListenerAllFrames(top, 'mousedown', saveName);
			bframe.addEventListenerAllFrames(top, 'keydown', keydown);
			bframe.addEventListenerAllFrames(top, 'drop', preventDefault);
			bframe.addEventListenerAllFrames(top, 'dragover', preventDefault);
		}

		function preventDefault(event) {
			event.preventDefault();
		}

		function keydown(event) {
			var keycode;
			var active_window_name;

			if(window.event) {
				keycode = window.event.keyCode;
			}
			else {
				keycode = event.keyCode;
			}

			// if modal window open and content is not myself
			if(top.bframe.modalWindow) active_window_name = top.bframe.modalWindow.getActiveWindow();
			if(active_window_name && active_window_name != window.name) return;

			switch(keycode) {
			case 9:  //tab
				if(current_edit_node) {
					_saveName(event);
				}
				break;

			case 13:	// Enter
				if(current_edit_node) {
					_saveName(event);
					return;
				}

				if(!pane) return;
				if(file_upload.isUploading()) return;
				var node_count = selected_node.length();
				if(!node_count) return;
				var node = current_node.object();
				if(bframe.searchParentById(node, 'ttrash')) return;

				if(node_count == 1) {
					var node_id = selected_node.id();
					var node_type = document.getElementById('nt' + node_id);
					if(node_type.value == 'folder') {
						getNodeList(node_id);
					}
					else {
						selectResource(node_id);
					}
				}
				else {
					download();
				}
				break;

			case 37:	// left
				move(event, 'left');
				break;

			case 38:	// up
				move(event, 'up');
				break;

			case 39:	// right
				move(event, 'right');
				break;

			case 40:	// down
				move(event, 'down');
				break;

			case 46:	// Delete
				if(!property.key || !property.key.delete) return;
				if(current_edit_node) return;
				var node = current_node.object();
				if(bframe.searchParentById(node, 'ttrash')) return;
				if(selected_node.exists('troot')) return;
				var node_count = selected_node.length();
				if(!node_count) return;

				var callback = context_menu.getCallBackObject('deleteNode');
				if(node_count > 1) {
					callback.setConfirmMessageKey('plural');
				}
				else {
					callback.setConfirmMessageKey('single');
				}
				var message = callback.getConfirmMessage();
				var node_name = selected_node.name();
				message = message.replace('%NODE_NAME%', node_name);
				message = message.replace('%NODE_COUNT%', node_count);
				callback.setTmpConfirmMessage(message);

				context_menu.enableElement('deleteNode');
				callback.func(event);
				break;

			case 65:	// ctrl+a
				if(event.ctrlKey || event.metaKey) {
					if(!current_edit_node) {
						selectAll();
					}
					bframe.stopPropagation(event);
				}
				break;

			case 67:	// ctrl+c
				if(event.ctrlKey || event.metaKey) {
					if(pane && !current_edit_node) {
						if(selected_node.length()) {
							copyNode();
						}
						bframe.stopPropagation(event);
					}
				}
				break;

			case 86:	// ctrl+v
				if(event.ctrlKey || event.metaKey) {
					if(pane && clipboard.target) {
						selected_node.set(current_node.id());
						pasteNode();
						bframe.stopPropagation(event);
					}
				}
				break;

			case 88:	// ctrl+x
				if(event.ctrlKey || event.metaKey) {
					if(pane && !current_edit_node) {
						if(selected_node.length()) {
							cutNode();
						}
						bframe.stopPropagation(event);
					}
				}
				break;

			case 113:	// F2
				if(!current_edit_node) {
					editName();
				}
				break;
			}
		}

		function hideContextMenuAllFrames() {
			if(typeof bframe == 'undefined' || !bframe) {
				return;
			}
			bframe.addEventListenerAllFrames(top, 'mousedown', hideContextMenu);
			bframe.addEventListenerAllFrames(top, 'click', hideContextMenu);
			bframe.addEventListenerAllFrames(top, 'mouseup', drag_control.onMouseUp);
			bframe.addEventListenerAllFrames(top, 'mousedown', saveName);
			bframe.addEventListenerAllFrames(top, 'keydown', keydown);
		}

		function cleanUp() {
			if(typeof bframe == 'undefined' || !bframe) {
				return;
			}
			drag_control.cleanUp();
			context_menu.cleanUp();
			trash_context_menu.cleanUp();
			bframe.removeEventListenerAllFrames(top, 'load', hideContextMenuAllFrames);
			bframe.removeEventListenerAllFrames(top, 'mousedown', hideContextMenu);
			bframe.removeEventListenerAllFrames(top, 'click', hideContextMenu);
			bframe.removeEventListenerAllFrames(top, 'mouseup', drag_control.onMouseUp);
			bframe.removeEventListenerAllFrames(top, 'mousedown', saveName);
			bframe.removeEventListenerAllFrames(top, 'keydown', keydown);
		}
		this.cleanUp = cleanUp;

		function setContextMenu() {
			if(property.context_menu_frame) {
				context_menu_frame = eval(property.context_menu_frame);
				context_menu.setDocument(context_menu_frame.window);
			}
			else {
				context_menu.setDocument(window);
			}
			if(property.context_menu_width) {
				context_menu_width = property.context_menu_width;
			}

			context_menu.setWidth(context_menu_width);
			context_menu.createElementFromObject(property.context_menu, self);
			context_menu_element.size = context_menu.getElementSize();
			context_menu_height = context_menu_element.size.height;

			context_menu.disableElement('pasteNode');
		}

		function showContextMenu(event) {
			eventPlace = '';
			if(context_menu.getLength() > 0 && !response_wait) {
				var eventSrcObject = bframe.getEventSrcElement(event);
				if(eventSrcObject == pane || eventSrcObject.parentNode == pane) {
					eventPlace = 'pane';
					selected_node.set(current_node.id());
					selected_node.setColor('selected');
					current_node.setColor('current');
					var event_node = current_node.object();
				}
				else {
					var event_node = bframe.searchParentByName(eventSrcObject, 'node');
					if(event_node && !selected_node.exists(event_node.id)) {
						selected_node.set(event_node.id);
						selected_node.setColor('selected');
						current_node.setColor('current');
					}
				}
				var trash = document.getElementById('ttrash');
				nodes = bframe.searchParentByName(event_node, 'nodes');
				if(!bframe.searchNodeById(trash, nodes.id) && !bframe.searchParentById(event_node, 'ttrash') && !bframe.searchParentById(event_node, 'uutrash')) {
					var position = context_menu.getPosition(event);
					var frame_offset = bframe.getFrameOffset(window, context_menu_frame);
					position.left += frame_offset.left;
					position.top += frame_offset.top;

					context_menu.positionAbsolute(position);
					context_menu.show();
				}
			}

			trash_context_menu.hide();
			return false;
		}

		function setTrashContextMenu() {
			if(!property.trash_context_menu) return;

			if(property.context_menu_frame) {
				context_menu_frame = eval(property.context_menu_frame);
				trash_context_menu.setDocument(context_menu_frame.window);
			}
			else {
				bframe.context_menu.setDocument(window);
			}
			if(property.context_menu_width) {
				context_menu_width = property.context_menu_width;
			}

			trash_context_menu.setWidth(context_menu_width);
			trash_context_menu.createElementFromObject(property.trash_context_menu, self);
			trash_context_menu_element.size = trash_context_menu.getElementSize();
			trash_context_menu_height = trash_context_menu_element.size.height;

		}

		function showTrashContextMenu(event) {
			if(trash_context_menu.getLength() > 0 && !response_wait) {
				var obj = bframe.getEventSrcElement(event);
				var event_node = bframe.searchParentByName(obj, 'node');

				if(selected_node.id() != event_node.id) {
					selected_node.set(event_node.id);
				}

				var position = trash_context_menu.getPosition(event);
				var frame_offset = bframe.getFrameOffset(window, context_menu_frame);
				position.left += frame_offset.left;
				position.top += frame_offset.top;

				trash_context_menu.positionAbsolute(position);
				trash_context_menu.show();
			}
			context_menu.hide();

			return false;
		}

		function context_filter() {
			switch(selected_node.id()) {
			case 'troot':
				context_menu.disableElement('cutNode');
				context_menu.disableElement('copyNode');
				context_menu.disableElement('deleteNode');
				context_menu.disableElement('editName');
				context_menu.enableElement('createNode');
				context_menu.enableElement('download');
				context_menu.disableElement('open_property');
				break;

			default:
				context_menu.enableElement('cutNode');
				context_menu.enableElement('copyNode');
				context_menu.enableElement('deleteNode');
				context_menu.enableElement('editName');
				context_menu.enableElement('createNode');
				context_menu.enableElement('upload');
				context_menu.enableElement('download');
				context_menu.enableElement('open_property');

				break;
			}

			if(clipboard.target) {
				context_menu.enableElement('pasteNode');
				if(clipboard.mode == 'copy') {
					context_menu.enableElement('pasteAriasNode');
				}
			}
			else {
				context_menu.disableElement('pasteNode');
				context_menu.disableElement('pasteAriasNode');
			}
			var node = selected_node.object();
			if(node.node_class == 'leaf') {
				context_menu.disableElement('pasteNode');
				context_menu.disableElement('pasteAriasNode');
				context_menu.disableElement('createNode');
				context_menu.disableElement('upload');
			}
			if(node != current_node.object()) {
				context_menu.disableElement('upload');
			}

			var node_name = selected_node.name();

			if(node.node_type == 'page') {
				context_menu.enableElement('preview');
			}
			else {
				var suffix = node_name.substring(node_name.lastIndexOf('.')+1, node_name.length);
				switch(suffix.toLowerCase()) {
				case 'html':
				case 'jpg':
				case 'jpeg':
				case 'gif':
				case 'png':
				case 'bmp':
				case 'svg':
					if(selected_node.length() == 1) {
						context_menu.enableElement('preview');
					}
					else {
						context_menu.disableElement('preview');
					}
					break;

				default:
					context_menu.disableElement('preview');
				}
			}

			if(selected_node.place() == 'pane') {
				context_menu.disableElement('createNode');
			}
			if(eventPlace == 'pane') {
				context_menu.disableElement('cutNode');
				context_menu.disableElement('copyNode');
				context_menu.disableElement('deleteNode');
				context_menu.disableElement('editName');
				context_menu.disableElement('download');
				context_menu.enableElement('createNode');
			}

			var callback = context_menu.getCallBackObject('deleteNode');
			var node_count = selected_node.length();
			if(node_count > 1) {
				callback.setConfirmMessageKey('plural');
			}
			else {
				callback.setConfirmMessageKey('single');
			}

			var message = callback.getConfirmMessage();
			message = message.replace('%NODE_NAME%', node_name);
			message = message.replace('%NODE_COUNT%', node_count);
			callback.setTmpConfirmMessage(message);
		}
		this.context_filter = context_filter;

		function hideContextMenu(event) {
			if(!context_menu || !document || typeof bframe == 'undefined' || !bframe) return;

			context_menu.hide();
			trash_context_menu.hide();
		}

		function getNodeList(id, mode) {
			var param;

			param = 'terminal_id='+terminal_id;
			if(id) {
				param+= '&node_id='+id.substr(1);
			}
			if(display_mode) {
				param+= '&display_mode='+display_mode;
			}
			if(property.sort == 'auto' && sort_key) {
				param+= '&sort_key='+sort_key;
				sort_key = '';
			}
			if(mode) {
				param+= '&mode='+mode;
			}
			if(bframe.progressBar) {
				httpObj = createXMLHttpRequest(showProgress);
				var params = {
					'id': 				property.progress_id, 
					'icon': 			property.progress_icon,
					'complete_icon': 	property.complete_icon,
				}
				progress = new bframe.progressBar(params);
			}
			else {
				httpObj = createXMLHttpRequest(showNode);
			}

			eventHandler(httpObj, property.module, property.file, property.method.getNodeList, 'POST', param);
			target.style.cursor = 'wait';
			if(pane) pane.style.cursor = 'wait';
			if(obj = document.getElementById('a' + id)) {
				obj.style.cursor = 'wait';
			}

			response_wait = true;
		}

		function showProgress() {
			try {
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
					switch(response['status']) {
					case 'finished':
						progress.remove();
						getNodeList(current_node.id());
						break;

					case 'download':
						progress.remove();
						param = '&file_name='+response['file_name']+'&file_path='+response['file_path']+'&remove='+response['remove'];
						param+= '&mode=download';

						var iframe = document.getElementById('download_iframe');
						if(!iframe) {
							var iframe = document.createElement('iframe');
							iframe.id = 'download_iframe';
							iframe.name = 'download_iframe';
							document.body.appendChild(iframe);
						}
						download_iframe.location.href = property.relation.download.url+param;
						response_wait = false;
						break;

					default:
						if(progress) progress.remove();
						showNode();
						break;
					}
				}
			}
			catch(e) {
				return;
			}
		}

		function showNode() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait)　{
				try {
					node_number = 0;
					current_edit_node = '';
					response = eval('('+httpObj.responseText+')');
					var node_info = response.node_info;
				}
				catch(e) {
					alert(top.bframe.message.getProperty('session_time_out'));
					target.style.cursor = 'default';
					if(pane) pane.style.cursor = 'default';
					response_wait = false;
					return;
				}
				// set current node
				if(response.current_node) {
					current_node.set('t'+response.current_node);
				}

				// remove tree
				if(root_ul && root_ul.parentNode == target) {
					target.removeChild(root_ul);
					root_ul = '';
				}

				// remove pane
				if(pane && pane_div && pane_div.parentNode == pane) {
					pane.removeChild(pane_div);
					pane_div = '';
					pane_ul = '';
					pane_tbody = '';
				}

				// create root tree
				root_ul = document.createElement('ul');
				root_ul.className = 'root';
				target.appendChild(root_ul);

				if(property.root_name) {
					node_info[0].node_name = property.root_name;
				}
				_showNode(root_ul, node_info[0]);

				// create trash tree
				if(node_info[1]) {
					if(property.trash_name) {
						node_info[1].node_name = property.trash_name;
					}
					_showNode(root_ul, node_info[1], true);
				}

				// set selected node
				if(response.selected_node) {
					// reset select node
					selected_node.set();

					for(var i=0; i<response.selected_node.length; i++) {
						selected_node.add('p'+response.selected_node[i]);
					}
				}

				if(!selected_node.object()) {
					// set current node to selected node
					selected_node.set(current_node.id());
				}

				if(response.current_nodes) {
					// set plural current nodes
					var nodes = response.current_nodes.split(',');
					for(var i=0 ; i<nodes.length ; i++) {
						current_node.add(nodes[i]);
					}
				}

				// reload and set color
				selected_node.reload();
				selected_node.setColor('selected');
				current_node.reload();
				current_node.setColor('current');

				// paste mode cut
				if(clipboard.mode == 'cut') {
					// clear clipboard
					if(paste_mode && clipboard.target) {
						delete clipboard.target;
						paste_mode = false;
					}
					else {
						setCutStatus();
					}
				}

				// hide upload button in trash
				if(property.upload) {
					var node = current_node.object();
					if(bframe.searchParentById(node, 'ttrash')) {
						file_upload.hide();
					}
					else {
						file_upload.show();
					}
				}

				target.style.cursor = 'default';
				if(pane) pane.style.cursor = 'default';
				response_wait = false;
				node_info = null;

				// create node and set edit mode
				if(new_node) {
					editName();
					new_node = false;
				}

				if(response.message) {
					alert(response.message);
				}

				scrollToLatest();
				if(property.editor_mode == 'true') tab_control.open();

				bframe.fireEvent(window, 'resize');
			}
		}

		function _showNode(parent_node, node_info, trash) {
			li = createNodeObject(parent_node, node_info, 'tree', trash);
			setNewNode(node_info);

			var ul = document.createElement('ul');
			ul.id = 'tu' + node_info.node_id;
			ul.name = 'nodes';
			li.appendChild(ul);

			if(node_info.children) {
				for(var i=0; i < node_info.children.length; i++) {
					if(pane && property.editor_mode != 'true' && node_info.children[i].node_type == 'file') {
						continue;
					}
					_showNode(ul, node_info.children[i], trash);
				}
			}

			// create pane
			if(pane && current_node.id() && node_info.node_id == current_node.id().substr(1)) {
				// create div
				pane_div = document.createElement('div');
				pane.appendChild(pane_div);

				// sort mode
				if(node_info.children && property.sort == 'auto') {
					node_info.children.sort(_sort_callback);
				}

				if(display_mode == 'detail') {
					// detail mode
					pane_div.className = 'detail';
					pane_table = document.createElement('table');
					pane_div.appendChild(pane_table);

					pane_tbody = document.createElement('tbody');
					pane_table.appendChild(pane_tbody);

					pane_tbody.id = 'tt' + node_info.node_id;
					pane_tbody.name = 'nodes';
					pane_tbody.className = 'pane';

					// title
					createDetailTitle(pane_tbody, response.sort_key, response.sort_order);

					if(node_info.children) {
						for(var i=0; i < node_info.children.length; i++) {
							createDetailNodeObject(pane_tbody, node_info.children[i]);
							setNewNode(node_info.children[i]);
						}
					}
					bframe.removeClass('current', display_thumbnail);
					bframe.appendClass('current', display_detail);
				}
				else {
					// thumb nail mode
					pane_div.className = 'thumbs';
					pane_ul = document.createElement('ul');
					pane_ul.id = 'uu' + node_info.node_id;
					pane_ul.name = 'nodes';
					pane_div.appendChild(pane_ul);

					if(node_info.children) {
						for(var i=0; i < node_info.children.length; i++) {
							createNodeObject(pane_ul, node_info.children[i], 'pane', trash);
							setNewNode(node_info.children[i]);
						}
					}
					bframe.removeClass('current', display_detail);
					bframe.appendClass('current', display_thumbnail);
				}
			}
		}

		function setNewNode(node_info) {
			if(node_info['new_node']) {
				if(eventPlace == 'pane') {
					selected_node.set('p'+node_info.node_id);
				}
				else {
					selected_node.set('t'+node_info.node_id);
				}
				new_node = true;
			}
		}

		function _sort_callback(a, b) {
			return a['order'] - b['order'];
		}

		function closeNode(node_id) {
			var param;

			param = 'terminal_id='+terminal_id+'&node_id='+encodeURIComponent(node_id.substr(1));
			httpObj = createXMLHttpRequest(closeNodeResponse);
			eventHandler(httpObj, property.module, property.file, property.method.closeNode, 'POST', param);
			response_wait = true;
			if(current_node.id()) {
				var node = document.getElementById(node_id);
				if(bframe.searchNodeById(node, current_node.id())) {
					selected_node.set(node_id);
					selected_node.setColor('current');
				}
			}
			var node_type = document.getElementById('nt' + node_id);
			if(node_type.value == 'folder') {
				var icon = document.getElementById('i' + node_id);
				icon.src = property.icon[node_type.value].src;
			}

			bframe.fireEvent(window, 'resize');
		}

		function closeNodeResponse() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait) {
				response_wait = false;
			}
		}

		function getEventNode(event) {
			var src_element = bframe.getEventSrcElement(event);
			var node = bframe.searchParentByName(src_element, 'node');
			if(node) {
				return node;
			}
			return;
		}

		function getEventNodeId(event) {
			var src_element = bframe.getEventSrcElement(event);
			var node = bframe.searchParentByName(src_element, 'node');
			if(node) {
				return node.id;
			}
			return;
		}

		function getNextSibling(li, status) {
			if(!status) {
				var child = bframe.searchNodeByName(li, 'nodes');
				if(child && child.display != 'none') {
					return child.firstChild;
				}
			}
			if(li.nextSibling) return li.nextSibling;
			var p = bframe.searchParentByName(li.parentNode, 'node');
			if(p) {
				return getNextSibling(p, true);
			}
		}

		function move(event, dir) {
			var node, n, i, j;
			var dir;

			if(!pane) return;
			if(current_edit_node) return;

			if(selected_node.length() == 0 || (selected_node.length() == 1 && selected_node.place() == 'tree')) {
				if(display_mode != 'detail') {
					if(pane_ul.childNodes.length > 0) {
						selected_node.set(pane_ul.childNodes[0].id);
					}
				}
				else {
					if(pane_tbody.childNodes.length > 1) {
						selected_node.set(pane_tbody.childNodes[1].id);
					}
				}
				selected_node.setColor('selected');
				current_node.setColor('current');
				event.preventDefault();
				return;
			}

			var start_id = selected_node.start_id();
			var end_id = selected_node.end_id();

			switch(dir) {
			case 'left':
				if(display_mode != 'detail') {
					if(selected_node.place() == 'pane' && event.shiftKey) {
						var start_nn = document.getElementById('nn'+start_id);
						var end_nn = document.getElementById('nn'+end_id);

						var from_node = document.getElementById(start_id);
						var end_node = document.getElementById(end_id);

						if(parseInt(start_nn.value) < parseInt(end_nn.value)) {
							selected_node.del(end_id);
							var node_span = document.getElementById('s'+end_id);
							node_span.className = 'node-name ';
						}
						else {
							for(var n=end_node.previousSibling; n; n=n.previousSibling) {
								if(!selected_node.exists(n.id)) break;
								selected_node.add(n.id, 'range', start_id);
							}
							if(n) {
								selected_node.add(n.id, 'range', start_id);
							}
						}
					}
					else {
						var end_id = selected_node.end_id();
						node = document.getElementById(end_id);
						if(node && node.previousSibling) {
							selected_node.set(node.previousSibling.id);
						}
						else {
							selected_node.set(end_id);
						}
					}
				}
				break;

			case 'up':
				if(display_mode == 'detail') {
					if(selected_node.place() == 'pane' && event.shiftKey) {
						var start_nn = document.getElementById('nn'+start_id);
						var end_nn = document.getElementById('nn'+end_id);

						var from_node = document.getElementById(start_id);
						var end_node = document.getElementById(end_id);

						if(parseInt(start_nn.value) < parseInt(end_nn.value)) {
							selected_node.del(end_id);
							var node_span = document.getElementById('s'+end_id);
							node_span.className = 'node-name ';
						}
						else {
							for(var n=end_node.previousSibling; n; n=n.previousSibling) {
								if(!selected_node.exists(n.id)) break;
								selected_node.add(n.id, 'range', start_id);
							}
							if(n && n.rowIndex > 0) {
								selected_node.add(n.id, 'range', start_id);
							}
						}
					}
					else {
						node = document.getElementById(end_id);
						if(node.previousSibling && node.rowIndex > 1) {
							selected_node.set(node.previousSibling.id);
						}
						else {
							selected_node.set(end_id);
						}
					}
				}
				else {
					var cnt = getColumns();
					var range=[];

					if(selected_node.place() == 'pane' && event.shiftKey) {
						var start_nn = document.getElementById('nn'+start_id);
						var end_nn = document.getElementById('nn'+end_id);

						var from_node = document.getElementById(start_id);
						var end_node = document.getElementById(end_id);

						for(n=end_node, i=0; n && i <= cnt; n=n.previousSibling, i++) {
							var node_nn = document.getElementById('nn'+n.id);
							if(parseInt(start_nn.value) < parseInt(node_nn.value) && i < cnt) {
								selected_node.del(n.id);
								var node_span = document.getElementById('s'+n.id);
								node_span.className = 'node-name ';
							}
							else {
								if(n.id != start_id) selected_node.add(n.id, 'range', start_id);
							}
						}
					}
					else {
						var node = document.getElementById(end_id);
						for(n=node.previousSibling, i=1; n && i < cnt; n=n.previousSibling, i++);
						if(n) {
							selected_node.set(n.id);
						}
						else {
							selected_node.set(end_id);
						}
					}
				}
				break;

			case 'right':
				if(display_mode != 'detail') { 
					if(selected_node.place() == 'pane' && event.shiftKey) {
						var start_nn = document.getElementById('nn'+start_id);
						var end_nn = document.getElementById('nn'+end_id);

						var from_node = document.getElementById(start_id);
						var end_node = document.getElementById(end_id);

						if(parseInt(start_nn.value) > parseInt(end_nn.value)) {
							selected_node.del(end_id);
							var node_span = document.getElementById('s'+end_id);
							node_span.className = 'node-name ';
						}
						else {
							for(var n=end_node.nextSibling; n; n=n.nextSibling) {
								if(!selected_node.exists(n.id)) break;
								selected_node.add(n.id, 'range', start_id);
							}
							if(n) {
								selected_node.add(n.id, 'range', start_id);
							}
						}
					}
					else {
						node = document.getElementById(end_id);
						if(node && node.nextSibling) {
							selected_node.set(node.nextSibling.id);
						}
						else {
							selected_node.set(end_id);
						}
					}
				}
				break;

			case 'down':
				if(display_mode == 'detail') { 
					if(selected_node.place() == 'pane' && event.shiftKey) {
						var start_nn = document.getElementById('nn'+start_id);
						var end_nn = document.getElementById('nn'+end_id);

						var from_node = document.getElementById(start_id);
						var end_node = document.getElementById(end_id);

						if(parseInt(start_nn.value) > parseInt(end_nn.value)) {
							selected_node.del(end_id);
							var node_span = document.getElementById('s'+end_id);
							node_span.className = 'node-name ';
						}
						else {
							for(var n=end_node.nextSibling; n; n=n.nextSibling) {
								if(!selected_node.exists(n.id)) break;
								selected_node.add(n.id, 'range', start_id);
							}
							if(n) {
								selected_node.add(n.id, 'range', start_id);
							}
						}
					}
					else {
						node = document.getElementById(end_id);
						if(node.nextSibling) {
							selected_node.set(node.nextSibling.id);
						}
						else {
							selected_node.set(end_id);
						}
					}
				}
				else {
					var cnt = getColumns();
					var range=[];

					if(selected_node.place() == 'pane' && event.shiftKey) {
						var start_nn = document.getElementById('nn'+start_id);
						var end_nn = document.getElementById('nn'+end_id);

						var from_node = document.getElementById(start_id);
						var end_node = document.getElementById(end_id);

						for(n=end_node, i=0; n && i <= cnt; n=n.nextSibling, i++) {
							var node_nn = document.getElementById('nn'+n.id);
							if(parseInt(start_nn.value) > parseInt(node_nn.value) && i < cnt) {
								selected_node.del(n.id);
								var node_span = document.getElementById('s'+n.id);
								node_span.className = 'node-name ';
							}
							else {
								if(n.id != start_id) selected_node.add(n.id, 'range', start_id);
							}
						}
					}
					else {
						var node = document.getElementById(end_id);
						for(n=node.nextSibling, i=1; n && i < cnt; n=n.nextSibling, i++);
						if(n) {
							selected_node.set(n.id);
						}
						else {
							selected_node.set(end_id);
						}
					}
				}
				break;
			}
			selected_node.setColor('selected');
			current_node.setColor('current');
			scrollToLatest();
			event.preventDefault();
		}

		function getColumns() {
			var last_top, cnt, pos;

			for(var n=pane_ul.firstChild, cnt=0; n; n=n.nextSibling, cnt++) {
				pos = bframe.getElementPosition(n);
				if(!last_top) {
					last_top = pos.top;
					continue;
				}
				if(last_top != pos.top) {
					break;
				}
			}
			return cnt;
		}

		function scrollToLatest() {
			var latest_id = selected_node.latest_id();
			if(latest_id) var latest = document.getElementById(latest_id);
			if(latest && selected_node.place() == 'pane') scroll(latest);
		}

		function scroll(obj) {
			if(!pane) return;
			if(!obj) return;
			var pos = bframe.getElementPosition(obj);
			var position = {top:pos.top, bottom:pos.top+obj.offsetHeight};
			var viewport = {top:0, bottom:pane.offsetHeight};

			if(display_mode == 'detail') {
				var pane_pos = bframe.getElementPosition(pane);
				pane_offset = pane_pos.top + pane_table.offsetTop + pane_tbody.childNodes[1].offsetTop;
			}
			else {
				pane_offset = pane_div.offsetTop + pane_ul.childNodes[0].offsetTop;
				var pane_pos = bframe.getElementPosition(pane);
				pane_offset = pane_pos.top + pane_ul.childNodes[0].offsetTop;
			}

			if(position.bottom > viewport.bottom) {
				pane.scrollTop = pane.scrollTop+position.bottom-viewport.bottom;
			}
			if(position.top < pane_offset) {
				pane.scrollTop = pane.scrollTop+position.top - pane_offset;
			}
		}

		function reloadTree() {
			getNodeList(current_node.id());
		}
		this.reloadTree = reloadTree;

		function setCutStatus() {
			if(!clipboard.target) return;

			for(var i=0; i<clipboard.target.length; i++) {
				var node_id = clipboard.target[i].substr(1)

				var node = document.getElementById('t' + node_id);
				if(node) {
					var img_border = bframe.searchNodeByName(node, 'img_border');
					img_border.className = 'img-border cut';
				}

				var node = document.getElementById('p' + node_id);
				if(node) {
					var img_border = bframe.searchNodeByName(node, 'img_border');
					img_border.className = 'img-border cut';
				}
			}
		}

		function resetCutStatus() {
			if(!clipboard.target) return;

			for(var i=0; i<clipboard.target.length; i++) {
				var node_id = clipboard.target[i].substr(1)

				var node = document.getElementById('t' + node_id);
				if(node) {
					var img_border = bframe.searchNodeByName(node, 'img_border');
					img_border.className = 'img-border';
				}

				var node = document.getElementById('p' + node_id);
				if(node) {
					var img_border = bframe.searchNodeByName(node, 'img_border');
					img_border.className = 'img-border';
				}
			}
		}

		function cutNode() {
			if(clipboard.target) {
				resetCutStatus();
				delete clipboard.target;
			}
			clipboard.target = new Array();

			for(var i=0; i<selected_node.length(); i++) {
				clipboard.target[i] = selected_node.id(i);
			}
			setCutStatus();
			clipboard.mode = 'cut';
			context_menu.enableElement(context_paste_index);
		}
		this.cutNode = cutNode;

		function copyNode() {
			if(clipboard.target) {
				resetCutStatus();
				delete clipboard.target;
			}
			clipboard.target = new Array();

			for(var i=0; i<selected_node.length(); i++) {
				clipboard.target[i] = selected_node.id(i);
			}
			clipboard.mode = 'copy';
			context_menu.enableElement(context_paste_index);
		}
		this.copyNode = copyNode;

		function pasteNode() {
			if(clipboard.target) {
				var param;

				param = 'terminal_id='+terminal_id+'&mode='+clipboard.mode;
				for(var i=0; i < clipboard.target.length; i++) {
					param+= '&source_node_id[' + i + ']=' + encodeURIComponent(clipboard.target[i].substr(1));
				}
				param+= '&destination_node_id='+encodeURIComponent(selected_node.id().substr(1));
				httpObj = createXMLHttpRequest(showProgress);
				eventHandler(httpObj, property.module, property.file, property.method.pasteNode, 'POST', param);
				response_wait = true;
				paste_mode = true;
				var params = {
					'id': 		property.copy_progress_id, 
					'icon': 	property.copy_progress_icon,
				}
				progress = new bframe.progressBar(params);
			}
		}
		this.pasteNode = pasteNode;

		function pasteAriasNode() {
			if(clipboard.target) {
				var param;

				param = 'terminal_id='+terminal_id+'&mode=arias';
				for(var i=0; i < clipboard.target.length; i++) {
					param+= '&source_node_id='+encodeURIComponent(clipboard.target[i].substr(1));
				}
				param+= '&destination_node_id='+encodeURIComponent(selected_node.id().substr(1));
				httpObj = createXMLHttpRequest(showNode);
				eventHandler(httpObj, property.module, property.file, property.method.pasteAriasNode, 'POST', param);
				response_wait = true;
				paste_mode = true;
			}
		}
		this.pasteAriasNode = pasteAriasNode;

		function deleteNode() {
			var param;

			if(property.relation && property.relation.deleteNode) {
				if(selected_node.id() == current_node.id()) {
					var rel = bframe.getFrameByName(top, property.relation.deleteNode.frame);
					rel.location.href = property.relation.deleteNode.url+'&node_id='+encodeURIComponent(selected_node.id().substr(1))+'&in_trash=true';
				}
			}
			param = 'terminal_id='+terminal_id;
			for(var i=0; i < selected_node.length(); i++) {
				if(!selected_node.id(i)) continue;
				param+= '&delete_node_id[' + i + ']='+encodeURIComponent(selected_node.id(i).substr(1));
				if(selected_node.exists(current_node.id())) {
					current_node.set(current_node.parent().id);
				}
			}
			if(current_node.id()) {
				param+= '&node_id='+encodeURIComponent(current_node.id().substr(1));
			}

			httpObj = createXMLHttpRequest(showNode);
			if(bframe.searchParentById(selected_node.object(), 'trash')) {
				eventHandler(httpObj, property.module, property.file, property.method.truncateNode, 'POST', param);
			}
			else {
				eventHandler(httpObj, property.module, property.file, property.method.deleteNode, 'POST', param);
			}
			response_wait = true;
		}
		this.deleteNode = deleteNode;

		function truncateNode() {
			var param;

			if(property.relation && property.relation.truncateNode) {
				if(current_node.isParent('ttrash')) {
					var rel = bframe.getFrameByName(top, property.relation.truncateNode.frame);
					rel.location.href = property.relation.truncateNode.url+'&node_id='+encodeURIComponent(selected_node.id().substr(1));
				}
			}

			param = 'terminal_id='+terminal_id+'&node_id='+encodeURIComponent(selected_node.id().substr(1));
			httpObj = createXMLHttpRequest(showProgress);
			eventHandler(httpObj, property.module, property.file, property.method.truncateNode, 'POST', param);
			response_wait = true;

			var params = {
				'id': 		property.truncate_progress_id,
				'icon': 	property.truncate_progress_icon,
			}
			progress = new bframe.progressBar(params);
		}
		this.truncateNode = truncateNode;

		function editName() {
			var sn = selected_node.object();
			if(!sn) return;
			if(sn.id == 'troot' || sn.id == 'ttrash') return;
			if(bframe.searchParentById(sn, 'ttrash')) return;
			if(bframe.searchParentById(sn, 'uutrash')) return;

			if(!current_edit_node) {
				current_edit_node = sn;
			}

			var span = bframe.searchNodeByName(current_edit_node, 'node_span');
			var name = bframe.searchNodeByName(current_edit_node, 'node_name');

			var input = document.createElement('input');
			input.name = 'node_input';

			if(display_mode != 'detail') {
				input.style.width = (span.offsetWidth - 10) + 'px';
			}
			else {
				input.style.width = (span.offsetWidth + 10) + 'px';
			}

			var node_type = document.getElementById('nt' + current_edit_node.id);

			// ime mode
			if(!property.imeMode) {
				input.style.imeMode = 'disabled';
			}
			if(property.icon[node_type.value] && property.icon[node_type.value].ime == 'true') {
				input.style.imeMode = '';
			}

			current_edit_save_value = name.value;
			input.value = name.value;
			span.firstChild.nodeValue = '';
			span.className = 'edit';
			span.appendChild(input);
			input.select();
			input.focus();
		}
		this.editName = editName;

		function saveName(event) {
			if(typeof bframe == 'undefined' || !bframe || response_wait) return;

			// exception
			var obj = bframe.getEventSrcElement(event);
			if(obj && obj.tagName.toLowerCase() == 'input') return;

			_saveName(event);
		}

		function _saveName(event) {
			if(!current_edit_node) return;

			var span = bframe.searchNodeByName(current_edit_node, 'node_span');
			var input = bframe.searchNodeByName(current_edit_node, 'node_input');

			if(current_edit_save_value == input.value.trim()) {
				span.firstChild.nodeValue = shortenText(current_edit_save_value);
				current_edit_save_value = '';
				span.className = 'node-name';
				span.removeChild(input);
				if(current_edit_node.id == current_node.id()) {
					current_edit_node = '';
					selected_node.setColor('current');
				}
				else if(current_edit_node.id == selected_node.id()) {
					current_edit_node = '';
					selected_node.setColor('selected');
				}
			}
			else {
				var param;

				param = 'terminal_id='+terminal_id+'&node_id='+encodeURIComponent(current_edit_node.id.substr(1));
				param+= '&node_name='+encodeURIComponent(input.value.trim());
				httpObj = createXMLHttpRequest(showNode);
				eventHandler(httpObj, property.module, property.file, property.method.saveName, 'POST', param);
				response_wait = true;
			}
		}

		function createNode(p) {
			var param;

			param = 'terminal_id='+terminal_id;
			param+= '&'+p+'&destination_node_id='+encodeURIComponent(selected_node.id().substr(1));
			if(current_node.id()) param+= '&node_id='+encodeURIComponent(current_node.id().substr(1));
			httpObj = createXMLHttpRequest(showNode);
			eventHandler(httpObj, property.module, property.file, property.method.createNode, 'POST', param);
			response_wait = true;
		}
		this.createNode = createNode;

		function select(node_id) {
			if((node_id == current_node.id() && node_id != selected_node.id())) {
				selected_node.set(node_id);
				selected_node.setColor('selected');
			}
			else {
				if(property.relation && property.relation.selectNode) {
					var rel = bframe.getFrameByName(top, property.relation.selectNode.frame);
					current_node.setNodeIdBeforeUnload(node_id);
					selected_node.setNodeIdBeforeUnload(node_id);

					if(document.all) {
						rel.document.body.onunload = setNodeIdAfterUnload;
					}
					else {
						rel.onunload = setNodeIdAfterUnload;
					}
					var in_trash = selected_node.isParent('ttrash');
					var path = document.getElementById('p' + node_id);
					rel.location.href = property.relation.selectNode.url+'&node_id='+encodeURIComponent(node_id.substr(1))+'&path='+path.value+'&in_trash='+in_trash;
				}
			}
			if(pane || !property.relation) {
				selected_node.set(node_id);
				selected_node.setColor('selected');
				current_node.setColor('current');
			}

			if(property.onclick) {
				var func = property.onclick.script;
				var node_span = document.getElementById('s'+node_id);
				var node_type = document.getElementById('nt'+node_id);
				var node_name = node_span.innerHTML;
				bstudio[func](node_id.substr(1), node_name, node_type.value);
			}
		}

		function setNodeIdAfterUnload() {
			current_node.setNodeIdAfterUnload();
			selected_node.setNodeIdAfterUnload();
		}

		function checkSelectFilter(node_id) {
			var node_type = document.getElementById('nt'+node_id);
			if(!node_type) return;
			if(!property.select || !property.select.filter) return;

			// check filter
			for(var i=0; i<property.select.filter.length; i++) {
				if(property.select.filter[i] == node_type.value) return true;
			}
		}

		function checkCurrentFilter(node_id) {
			var node_type = document.getElementById('nt'+node_id);
			if(!node_type) return;
			if(!property.current || !property.current.filter) return;

			// check filter
			for(var i=0; i<property.current.filter.length; i++) {
				if(property.current.filter[i] == node_type.value) return true;
			}
		}

		function selectObject(node_id) {
			if(current_edit_node) return;
			if(checkSelectFilter(node_id)) return;
			selected_node.set(node_id);
			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function currentObject(node_id) {
			if(checkCurrentFilter(node_id)) return;
			current_node.set(node_id);
			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function selectAll() {
			var node_id = current_node.id().substr(1);
			var pane = document.getElementById('uu'+node_id) || document.getElementById('tt'+node_id);
			if(!pane) return;

			for(var n=pane.firstChild; n; n=n.nextSibling) {
				if(n == pane.firstChild) {
					selected_node.set(n.id);
				}
				else {
					selected_node.add(n.id, 'range');
				}
			}

			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function resetSelectedObject() {
			selected_node.set();
			current_node.setColor('current');
		}

		function resetCurrentObject() {
			selected_node.set();
			current_node.set();
			current_node.setColor('current');
		}

		function addSelectedObject(node_id) {
			if(checkSelectFilter(node_id)) return;

			if(selected_node.exists(node_id)) {
				selected_node.del(node_id);
				var node_span = document.getElementById('s'+node_id);
				node_span.className = 'node-name ';
			}
			else {
				selected_node.add(node_id);
			}
			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function addCurrentObject(node_id) {
			if(checkCurrentFilter(node_id)) return;

			if(current_node.exists(node_id)) {
				current_node.del(node_id);
				var node_span = document.getElementById('s'+node_id);
				node_span.className = 'node-name ';
			}
			else {
				current_node.add(node_id);
			}
			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function addRangeSelectedObject(node_id) {
			var start_id = selected_node.start_id();
			if(!start_id) return;

			var start_nn = document.getElementById('nn'+start_id);
			var node_nn = document.getElementById('nn'+node_id);

			var from_node = document.getElementById(start_id);
			var to_node = document.getElementById(node_id);

			if(parseInt(node_nn.value) < parseInt(start_nn.value)) {
				for(var n=from_node.previousSibling; n; n=n.previousSibling) {
					selected_node.add(n.id, 'range', start_id);
					if(n == to_node) break;
				}
			}
			else {
				for(var n=from_node.nextSibling; n; n=n.nextSibling) {
					selected_node.add(n.id, 'range', start_id);
					if(n == to_node) break;
				}
			}

			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function selectResource(node_id, mode='temporry') {
			var node = current_node.object();
			if(bframe.searchParentById(node, 'ttrash')) return;

			var node_span = document.getElementById('s'+node_id);
			var node_type = document.getElementById('nt'+node_id);
			var node_name = node_span.innerHTML;
			var func;


			if(property.relation && property.relation.insertFile) {
				var node_type =  document.getElementById('nt' + node_id).value;
				if(property.relation.insertFile.node_type && property.relation.insertFile.node_type == node_type) {
					if(opener) { 					// open from CKEditor
						insertImageToCKEditor(node_id);
					}
					else {							// open directly
						if(property.method.selectFile) {
							var  suffix = node_name.substring(node_name.lastIndexOf('.')+1,node_name.length).toLowerCase();
							func = property.method.selectFile[suffix];
							if(!func) {
								func = property.method.selectFile.default;
							}
							switch(func) {
							case 'openEditor':
								openEditor(node_id, mode);
								break;

							case 'openPreview':
								openPreview();
								break;

							case 'insertResourceFile':
								insertResourceFile(node_id);
								break;

							default:
								download();
								break;
							}
						}
						else {
							insertFile(node_id);
						}
					}
				}
			}
			else if(property.ondblclick) {
				if(current_edit_node && current_edit_node.id == node_id) return;

				var func = property.ondblclick.script;
				if(bstudio[func]) bstudio[func](node_id.substr(1), node_name, node_type.value);
			}
		}

		function openEditor(node_id) {
			var url = 'index.php';
			var param = '?module='+property.editor.module+
						'&page='+property.editor.file+
						'&method='+property.editor.method+
						'&terminal_id='+terminal_id+
						'&node_id='+encodeURIComponent(node_id.substr(1));

			var settings='width='+property.editor.width+',height='+property.editor.height+
				',scrollbars=yes,resizable=yes,menubar=no,location=no,toolbar=no,directories=no,status=no,dependent=no';

			var editor = window.open(url+param, node_id, settings);
			editor.focus();
		}

		function openEditorResponse() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait) {
				response_wait = false;
			}
		}

		function openPreview() {
			preview();
		}

		function insertImageToCKEditor(node_id) {
			var funcNum = bframe.getUrlParam('CKEditorFuncNum');
			var fileUrl = property.root_url + document.getElementById('p' + node_id).value;
			fileUrl = fileUrl.replace(/\/\/+/g, '/');
			opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
			window.close();
		}

		function insertResourceFile(node_id) {
			if(property.target_id || property.target) {
				var image_size_obj = document.getElementById('his' + node_id);
				var img_size;
				if(image_size_obj) {
					img_size = image_size_obj.value;
				}
				var path = document.getElementById('p' + node_id).value;
				bstudio.insertResourceIMG(property.root_path, path, img_size, property.target, property.target_id);
			}
		}

		function insertFile(node_id) {
			if(property.target_id || property.target) {
				var image_size_obj = document.getElementById('his' + node_id);
				var img_size;
				if(image_size_obj) {
					img_size = image_size_obj.value;
				}

				bstudio.insertIMG(property.root_path, node_id.substr(1), img_size, property.target, property.target_id);
			}
		}

		function shortenText(text) {
			if(display_mode != 'detail') {
				if(text.length > 30 && property.abbr) {
					return text.substr(0, 22) + property.abbr + text.substr(-7);
				}
			}
			return text;
		}

		function upload() {
			file_upload.selectFiles();
		}
		this.upload = upload;

		function download() {
			var  param='';

			if(!selected_node.length()) return;

			param = 'terminal_id='+terminal_id;
			for(var i=0; i < selected_node.length(); i++) {
				if(!selected_node.id(i)) continue;
				param+= '&download_node_id[' + i + ']='+encodeURIComponent(selected_node.id(i).substr(1));
			}
			httpObj = createXMLHttpRequest(showProgress);
			eventHandler(httpObj, property.module, property.file, property.method.download, 'POST', param);
			response_wait = true;
			var params = {
				'id': 				property.download_progress_id,
				'icon': 			property.download_progress_icon,
				'complete_icon': 	property.complete_icon,
			}
			progress = new bframe.progressBar(params);
		}
		this.download = download;

		function preview() {
			var id;

			if(id = selected_node.id()) {
				var woption = 'menubar=yes,toolbar=yes,directories=yes,status=yes,scrollbars=yes,resizable=yes';
				var w = window.open(property.relation.preview.url+'&node_id='+encodeURIComponent(id.substr(1)), 'preview', woption);
				if(w) {
					w.focus();
				}
			}
		}
		this.preview = preview;

		function open_property() {
			var id;

			if(id = selected_node.id()) {
				var a = document.createElement('a');
				document.body.appendChild(a);

				a.href = property.relation.open_property.url+'&node_id='+encodeURIComponent(id.substr(1));
				a.setAttribute('data-param', property.relation.open_property.params);
				a.setAttribute('title', property.relation.open_property.title);

				top.bframe.modalWindow.activate(a, window);
				if(property.relation.open_property.func) {
					top.bframe.modalWindow.registerCallBackFunction(eval(property.relation.open_property.func));
				}
			}
		}
		this.open_property = open_property;

		this.getCurrentFolderId = function() {
			return current_node.id();
		}

		this.getNodeList = function(id) {
			getNodeList(id);
		}

		this.getSelecteNodes = function() {
			return selected_node.nodes();
		}

		this.getCurrentNodes = function() {
			return current_node.nodes();
		}

		this.reload = function() {
			return reloadTree();
		}

		this.setEditFlag = function() {
			tab_control.setEditFlag();
		}

		this.resetEditFlag = function() {
			tab_control.resetEditFlag();
		}

		// -------------------------------------------------------------------------
		// class currentNodeControl
		// -------------------------------------------------------------------------
		function currentNodeControl() {
			var self = this;
			var current_place;
			var current_node = new Array();
			var before_unload_node_id;
			var sn;	// serial_number

			this.id = function(index) {
				if(!current_node[0]) return;
				var i = index ? index : 0;
				return current_node[i].id;
			}

			this.last_id = function() {
				if(current_node.length) {
					return current_node[current_node.length-1].id;
				}
			}

			this.latest_id = function() {
				var n=0;
				var latest=0;

				if(current_node.length) {
					for(var i=0; i < current_node.length; i++) {
						if(n < current_node[i].serial_number) {
							n = current_node[i].serial_number;
							latest = i;
						}
					}
					return current_node[latest].id;
				}
			}

			this.start_id = function() {
				var n=0;
				var start=0;

				if(current_node.length) {
					for(var i=0; i < current_node.length; i++) {
						if(current_node[i].type == 'point' && n < parseInt(current_node[i].serial_number)) {
							n = current_node[i].serial_number;
							start = i;
						}
					}
					return current_node[start].id;
				}
			}

			this.end_id = function() {
				var n=0;
				var range=[];
				var start=0;
				var end;

				if(current_node.length) {
					for(var i=0; i < current_node.length; i++) {
						if(current_node[i].type == 'point' && n < current_node[i].serial_number) {
							n = current_node[i].serial_number;
							start = i;
						}
					}
					for(var i=0, end=start; i < current_node.length; i++) {
						if(current_node[i].type == 'range' && current_node[i].start_id == current_node[start].id) {
							end = i;
							if(parseInt(current_node[i].node_number) < parseInt(current_node[start].node_number)) {
								break;
							}
						}
					}
					return current_node[end].id;
				}
			}

			this.name = function() {
				if(!current_node[0]) return;

				var node = self.object();
				if(node) {
					var span = bframe.searchNodeByName(node, 'node_span');
					return span.innerHTML;
				}
			}

			this.exists = function(node_id) {
				if(node_id) {
					for(var i=0; i < current_node.length; i++) {
						if(current_node[i].id.substr(1) == node_id.substr(1)) {
							return true;
						}
					}
				}

				return false;
			}

			this.isChild = function(node_id) {
				for(var i=0; i < current_node.length; i++) {
					var current_obj = document.getElementById('t'+current_node[i].id.substr(1));
					if(bframe.searchNodeById(current_obj, 't'+node_id.substr(1))) {
						return true;
					}
				}

				return false;
			}

			this.isParent = function(node_id) {
				if(!current_node.length) return false;
				var current_obj = document.getElementById('t'+current_node[0].id.substr(1));
				if(bframe.searchParentById(current_obj, 't'+node_id.substr(1))) {
					return true;
				}

				return false;
			}

			this.parent = function() {
				if(!current_node.length) return false;
				var current_obj = document.getElementById('t'+current_node[0].id.substr(1));
				return bframe.searchParentByTagName(current_obj.parentNode, 'li');
			}

			this.length = function() {
				return current_node.length;
			}

			this.setNodeIdBeforeUnload = function(node_id) {
				before_unload_node_id = node_id;
			}

			this.resetNodeIdBeforeUnload = function() {
				before_unload_node_id = '';
			}

			this.setNodeIdAfterUnload = function() {
				if(!before_unload_node_id) return;

				if(current_node[0]) {
					var node = self.object();
					if(node) {
						var span = bframe.searchNodeByName(node, 'node_span');
						span.className = 'node-name';
					}
				}
				self.set(before_unload_node_id);
				self.setColor('current');
			}

			this.set = function(node_id) {
				for(var i=0; i < current_node.length; i++) {
					var node = self.object(i);
					if(node) {
						var span = bframe.searchNodeByName(node, 'node_span');
						span.className = 'node-name';
					}
				}
				current_node.length = 0;
				sn = 0;
				if(node_id) {
					var pt = document.getElementById('p' + node_id);
					var file_path = pt ? pt.value : '';
					var nn = document.getElementById('nn' + node_id);
					var n = nn ? nn.value : 0;
					current_node[0] = {id: node_id, path: file_path, node_number: n, serial_number: ++sn, type: 'point', start_id: ''};
				}
			}

			this.add = function(node_id, t, start_node) {
				if(!t) t='point';
				var pt = document.getElementById('p' + node_id);
				var file_path = pt ? pt.value : '';
				var nn = document.getElementById('nn' + node_id);
				var n = nn ? nn.value : 0;
				for(var i=0; i < current_node.length; i++) {
					if(current_node[i].id == node_id) {
						current_node.splice(i, 1);
						break;
					}
					if(parseInt(current_node[i].node_number) > parseInt(n)) {
						break;
					}
				}
				current_node.splice(i, 0, {id: node_id, path: file_path, node_number: n, serial_number: ++sn, type: t, start_id: start_node});
			}

			this.del = function(node_id) {
				for(var i=0; i < current_node.length; i++) {
					if(current_node[i].id == node_id) {
						current_node.splice(i, 1);
						break;
					}
				}
			}

			this.reload = function() {
				for(var i=0; i < current_node.length; i++) {
					var node_id = current_node[i].id;
					var sn = current_node[i].serial_number;
					var tp = current_node[i].type;
					var si = current_node[i].start_id;
					var pt = document.getElementById('p' + node_id);
					var file_path = pt ? pt.value : '';
					var nn = document.getElementById('nn' + node_id);
					var n = nn ? nn.value : 0;
					current_node.splice(i, 1, {id: node_id, path: file_path, node_number: n, serial_number: sn, type: tp, start_id: si});
				}
			}

			this.place = function() {
				return current_node[0].id.substr(0, 1) == 't' ? 'tree' : 'pane';
			}

			this.setColor = function(mode) {
				if(!current_node[0]) return;

				for(var i=0; i < current_node.length; i++) {
					if(current_edit_node.id == current_node[i].id) continue;
					var node = self.object(i);
					if(node) {
						var span = bframe.searchNodeByName(node, 'node_span');
						switch(mode) {
						case 'selected':
							span.className = 'node-name selected';
							break;

						case 'current':
							span.className = 'node-name current';
							break;
						}
					}
				}
			}

			this.resetColor = function() {
				if(!current_node[0]) return;

				var node = self.object();
				if(node) {
					var span = bframe.searchNodeByName(node, 'node_span');
					span.className = 'node-name ';
				}
			}

			this.object = function(i) {
				var index = i ? i : 0;

				if(current_node[index]) {
					obj = document.getElementById(current_node[index].id);
					if(obj) return obj;
					if(current_node[index].id.substr(0, 1) == 't') {
						return document.getElementById('t'+current_node[index].id.substr(1));
					}
					else {
						return document.getElementById('p'+current_node[index].id.substr(1));
					}
				}
			}

			this.node_type = function() {
				if(current_node[0]) {
					type = document.getElementById('t' + current_node[0].id);
					return type.value;
				}
			}

			this.nodes = function() {
				return current_node;
			}
		}

		// -------------------------------------------------------------------------
		// class tabControl
		// -------------------------------------------------------------------------
		function tabControl() {
			var self = this;
			var control = document.getElementById(property.relation.tab_control.id);
			var folder = bframe.searchNodeByTagName(control, 'li');
			var editor_container = document.getElementById(property.relation.editor_container.id);
			var folder_container = document.getElementById(property.relation.folder_container.id);
			var tabs = [];
			var zIndex = 1;
			var current_index=0;
			var visible_index=0;
			var scroll_left = document.getElementById(property.relation.tab_scroll.left);
			var scroll_right = document.getElementById(property.relation.tab_scroll.right);
			var scrolling;
			var scroll_out;
			var momentam = 100;
			var drag_start;
			var drag_overlay = document.createElement('div');
			var drag_target;
			var drag_element;
			var drag_clone;
			var start_position;
			var drag_start_scrollLeft;
			var last_position;

			this.open = function(node_id='', mode) {
				if(node_id) {
					// change node_id to inside tree
					node_id = 't'+node_id.substr(1);
				}

				var obj = exists(node_id);
				if(obj) {
					var exist = true;
				}
				else {
					var clone = folder.cloneNode(true);
					var obj = new tab(clone, self);

					tabs.splice(current_index, 0, {'node_id' : node_id, 'obj' : obj});
					openEditor(node_id);
					obj.add(node_id, mode);
				}

				closeAll(obj, exist);
				obj.show(mode);
				select(node_id);
				if(!node_id) { // folder
					var file_name = document.getElementById('nm' + current_node.id()).value;
					obj.setFilename(file_name);
				}

				setOrder();
				scrollTo(obj);
				current_index = getVisibleIndex() + 1;
			}

			function exists(node_id) {
				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].node_id == node_id) return tabs[i].obj;
				}
			}

			function getVisibleIndex() {
				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].obj.isVisible()) return i;
				}
			}

			function openEditor(node_id) {
				var iframe = document.createElement('iframe');
				var src = 'index.php?module='+property.editor.module+
							'&page='+property.editor.file+
							'&method='+property.editor.method+
							'&terminal_id='+terminal_id+
							'&node_id='+encodeURIComponent(node_id.substr(1));

				iframe.id = 'ed' + node_id;
				iframe.src = src;
				iframe.opener = window;
				iframe.setAttribute('data-param', 'margin:99');
				iframe.classList.add('bframe_adjustparent');
				editor_container.appendChild(iframe);

				var ap = new bframe.adjustwindow(iframe);
				return iframe.id;
			}

			function closeAll(except, exist) {
				var remove_index;
				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].obj.isVisible()) tabs[i].obj.hide();
					if(tabs[i].obj.openMode() == 'temporary' && tabs[i].obj != except && !exist) {
						remove_index = i;
					}
				}
				if(remove_index) {
					tabs[remove_index].obj.remove();
					tabs.splice(remove_index, 1);
				}
			}

			this.close = function(event) {
				var evtSrc = bframe.getEventSrcElement(event);

				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].obj.isChild(evtSrc)) {
						closeTab(i);
						break;
					}
				}
				bframe.stopPropagation(event);
			}

			function closeTab(i) {
				if(i == 0) return;
				if(tabs[i].obj.getEditFlag() && !confirm(property.relation.editor_confirm.message)) return;
				if(tabs[i].obj.isVisible())	visible = true;

				var element = tabs[i].obj.element();
				var clone = tabs[i].obj.clone();

				clone.className = element.className;
				clone.style.position = 'absolute';
				clone.style.left = element.offsetLeft + 'px';
				clone.style.top = element.offsetTop + 'px';
				clone.style.marginLeft = 0;

				control.appendChild(clone);
				tabs[i].obj.inVisible();

				if(tabs[i].obj.isVisible()) {
					tabs[i-1].obj.show();
					scrollTo(tabs[i-1].obj);
					select(tabs[i-1].node_id);
					current_index = i;
				}

				animate(
					function(t) {
						return (--t)*t*t+1;
					},
					function(progress) {
						clone.style.top = Math.round(progress * clone.offsetHeight) + 'px';
					},
					200,
					function () {
						let start_width = element.offsetWidth + 50;
						element.style.width = start_width + 'px';
						element.style.minWidth = 0;

						element.style.marginLeft = '-60px';
						control.removeChild(clone);

						animate(
							function(t) {
								return (--t)*t*t+1;
							},
							function(progress) {
								element.style.width = Math.round((1 - progress) * start_width) + 'px';
							},
							400,
							function () {
								tabs[i].obj.remove();
								tabs.splice(i, 1);
							}
						);
					}
				);
			}

			this.click = function(event) {
				var evtSrc = bframe.getEventSrcElement(event);
				controlTab(evtSrc);
				bframe.stopPropagation(event);

				return false;
			}

			function controlTab(evtSrc) {
				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].obj.isChild(evtSrc)) {
						tabs[i].obj.show();
						select(tabs[i].node_id);
						scrollTo(tabs[i].obj);
						current_index = i+1;
					}
					else {
						if(tabs[i].obj.isVisible()) {
							tabs[i].obj.hide();
						}
					}
				}
			}

			function setOrder() {
				for(var i=0; i < tabs.length; i++) {
					tabs[i].obj.setOrder(i);
				}
			}

			function scrollTo(obj) {
				var viewport = bframe.getElementPosition(control);
				let startScrollLeft = control.scrollLeft;
				let position = bframe.getElementPosition(obj.element());

				position.right = position.left + position.width;
				viewport.right = viewport.left + viewport.width;

				if(viewport.left <= position.left && position.right <= viewport.right) return;

				animate(
					function(t) {
						return (--t)*t*t+1;
					},
					function(progress) {
						if((position.right) > viewport.right) {
							control.scrollLeft = startScrollLeft + Math.round(progress * (position.right - viewport.right));
						}
						if(position.left < viewport.left) {
							control.scrollLeft = startScrollLeft - Math.round(progress * (viewport.left - position.left));
						}
					},
					400
				);
			}

			function scrollLeftStart(event) {
				scrolling = true;
				scroll('left');
			}
			function scrollLeftStop(event) {
				scrolling = false;
				inertia('left');
			}
			function scrollRightStart(event) {
				scrolling = true;
				scroll('right');
			}
			function scrollRightStop(event) {
				scrolling = false;
				inertia('right');
			}

			function scroll(direction) {
				let startScrollLeft = control.scrollLeft;
				let start = performance.now();
				if(drag_start) var startStyleLeft = parseInt(drag_clone.style.left);

				requestAnimationFrame(function animate(time) {
					let timeFraction = time - start;

					if(direction == 'right') {
						control.scrollLeft = startScrollLeft + Math.round(timeFraction * momentam / 200);
					}
					else {
						control.scrollLeft = startScrollLeft - Math.round(timeFraction * momentam / 200);
					}
					if(scrolling) {
						requestAnimationFrame(animate);
					}
				});
			}

			function inertia(direction) {
				let startScrollLeft = control.scrollLeft;

				animate(
					function(t) {
						return (--t)*t*t+1;
					},
					function(progress) {
						if(direction == 'right') {
							control.scrollLeft = startScrollLeft + Math.round(progress * momentam);
						}
						else {
							control.scrollLeft = startScrollLeft - Math.round(progress * momentam);
						}
					},
					400
				);
			}

			function animate(timing, callback, duration, endCallBack=null) {
				let start = performance.now();

				requestAnimationFrame(function animate(time) {
					// timeFraction goes from 0 to 1
					let timeFraction = (time - start) / duration;
					if(timeFraction > 1) timeFraction = 1;

					// calculate the current animation state
					let progress = timing(timeFraction);

					callback(progress); // callback function

					if(timeFraction < 1) {
						requestAnimationFrame(animate);
					}
					else if(endCallBack) {
						endCallBack();
					}
				});
			}

			function getEventTab(event) {
				var evtSrc = bframe.getEventSrcElement(event);

				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].obj.isChild(evtSrc)) return tabs[i].obj;
				}
			}

			this.setEditFlag = function() {
				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].obj.isVisible()) tabs[i].obj.setEditFlag();
				}
			}

			this.resetEditFlag = function() {
				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].obj.isVisible()) tabs[i].obj.resetEditFlag();
				}
			}

			this.onMouseDown = function(event) {
				drag_target = getEventTab(event);
				drag_element = drag_target.element();
				if(drag_element == folder) return;

				drag_start = true;
				drag_start_scrollLeft = control.scrollLeft;
				last_position = '';
				drag_overlay.style.display = 'block';
				drag_overlay.style.zIndex = '1000';
				control.style.zIndex = '1001';

				var mouse_position = bframe.getMousePosition(event);

				var position = bframe.getElementPosition(drag_element);
				drag_clone = drag_target.clone();

				drag_clone.className = 'clone selected';
				drag_clone.style.position = 'absolute';
				drag_clone.style.zIndex = '9999';
				drag_clone.style.left = drag_element.offsetLeft + 'px';
				drag_clone.style.top = drag_element.offsetTop + 'px';
				drag_clone.style.marginLeft = 0;

				control.appendChild(drag_clone);

				start_position = mouse_position.x - drag_element.offsetLeft;
				drag_target.inVisible();
			}

			function onMouseMove(event) {
				var left;
				var left_org;
				var viewport = bframe.getElementPosition(control);

				if(!drag_start) return;

				current_position = bframe.getMousePosition(event);
				if(!last_position) last_position = current_position;

				left = left_org = current_position.x - start_position + control.scrollLeft - drag_start_scrollLeft;
				if(left < folder.offsetWidth - 10) left = folder.offsetWidth - 10;
				if(control.scrollWidth - drag_clone.offsetWidth < left) left = control.scrollWidth - drag_clone.offsetWidth;

				// already scrolling
				if(scroll_out && left < control.scrollLeft) return;
				if(scroll_out && left > control.scrollLeft + control.offsetWidth - drag_clone.offsetWidth) return;

				drag_clone.style.left = left + 'px';
				deltaX = current_position.x - last_position.x > 0 ? 'right' : current_position.x - last_position.x < 0 ? 'left' : '';

				if(deltaX == 'right') {
					var next = getNextObject(drag_element);
					if(next) var next_element = next.element();

					if(next_element && next_element.offsetLeft + next_element.offsetWidth * 2 / 3 < left + drag_element.offsetWidth) {
						swap(drag_element, 'right');
					}
					if(viewport.width + control.scrollLeft < left + drag_element.offsetWidth) {
						if(!scroll_out)	{
							scroll_out = true;
							dragScroll('right');
						}
					}
					else {
						scroll_out = false;
					}
				}
				if(deltaX == 'left') {
					var prev = getPrevObject(drag_element);
					if(prev) var prev_element = prev.element();
					if(prev_element && left < prev_element.offsetLeft + prev_element.offsetWidth / 3) {
						swap(drag_element, 'left');
					}
					if(left_org < control.scrollLeft) {
						if(!scroll_out)	{
							scroll_out = true;
							dragScroll('left');
						}
					}
					else {
						scroll_out = false;
					}
				}

				last_position = current_position;
			}

			function dragScroll(direction) {
				let startScrollLeft = control.scrollLeft;
				let start = performance.now();
				if(drag_start) var startStyleLeft = parseInt(drag_clone.style.left);

				requestAnimationFrame(function animate(time) {
					if(!scroll_out) return;

					let timeFraction = time - start;
					let left;

					if(direction == 'right') {
						control.scrollLeft = startScrollLeft + Math.round(timeFraction * momentam / 200);
						left = control.scrollLeft + control.offsetWidth - drag_clone.offsetWidth;
						drag_clone.style.left = left + 'px';

						var next = getNextObject(drag_element);
						if(next) var next_element = next.element();
						if(next_element && next_element.offsetLeft + next_element.offsetWidth * 2 / 3 < left + drag_element.offsetWidth) {
							swap(drag_element, 'right');
						}
					}
					else {
						left = startScrollLeft - Math.round(timeFraction * momentam / 200);
						control.scrollLeft = left;

						if(left < folder.offsetWidth - 10) left = folder.offsetWidth - 10;
						drag_clone.style.left = left + 'px';

						var prev = getPrevObject(drag_element);
						if(prev) var prev_element = prev.element();
						if(prev_element && left < prev_element.offsetLeft + prev_element.offsetWidth / 3) {
							swap(drag_element, 'left');
						}
					}
					if((direction == 'right' && left + drag_clone.offsetWidth < control.scrollWidth) ||
					 (direction == 'left' && control.scrollLeft > 0)) {
						requestAnimationFrame(animate);
					}
				});
			}

			function onMouseUp(event) {
				if(!drag_start) return;

				drag_overlay.style.display = 'none';
				control.removeChild(drag_clone);
				drag_target.visible();
				scrollTo(drag_target);

				scroll_out = false;
				drag_start = false;
			}

			function getIndex(element) {
				for(var i=0; i < tabs.length; i++) {
					if(tabs[i].obj.element() == element) {
						return i;
					}
				}
			}

			function getPrevObject(element) {
				var index = getIndex(element);
				if(index != 1 && tabs[index-1]) return tabs[index-1].obj;
			}

			function getNextObject(element) {
				var index = getIndex(element);
				if(tabs[index+1]) return tabs[index+1].obj;
			}

			function swap(element, direction) {
				var index = getIndex(element);
				if(direction == 'right' && index + 1 == tabs.length) return;
				if(direction == 'left' && index == 1) return;
				if(direction == 'left') index--;

				tabs.splice(index, 2, tabs[index+1], tabs[index]);
				setOrder();
			}

			this.isFolderOpen = function() {
				return getVisibleIndex() == 0;
			}

			// set drag overlay
			drag_overlay.style.position = 'absolute'; 
			drag_overlay.style.top = '0'; 
			drag_overlay.style.right = '0'; 
			drag_overlay.style.bottom = '0'; 
			drag_overlay.style.left = '0'; 
			drag_overlay.style.backgroundColor = '#f00'; 
			drag_overlay.style.opacity = '0'; 
			drag_overlay.style.display = 'none';

			document.body.appendChild(drag_overlay);

			// set folder tab
			var folder_tab = new tab(folder, self);
			tabs[current_index++] = {'node_id' : '', 'obj' : folder_tab};

			// set scroll event handler
			scroll_left.addEventListener('mousedown', scrollLeftStart);
			scroll_left.addEventListener('mouseup', scrollLeftStop);
			scroll_right.addEventListener('mousedown', scrollRightStart);
			scroll_right.addEventListener('mouseup', scrollRightStop);

			// set drag event handler
			drag_overlay.addEventListener('mousemove', onMouseMove);
			document.body.addEventListener('mousemove', onMouseMove);
			document.body.addEventListener('mouseup', onMouseUp);
			bframe.addEventListenerAllFrames(top, 'mousemove', onMouseMove);
			bframe.addEventListenerAllFrames(top, 'mouseup', onMouseUp);

			// -------------------------------------------------------------------------
			// class tab
			// -------------------------------------------------------------------------
			function tab(li, tc) {
				var editor;
				var a = bframe.searchNodeByTagName(li, 'a');
				var close_button = bframe.searchNodeByClassName(li, 'close-button');
				var fname = bframe.searchNodeByClassName(li, 'file_name');
				var visible=true;
				var open_mode;
				var z = zIndex++;
				var edit_flag;

				li.style.zIndex = z;

				// set event handler
				li.addEventListener('mousedown', tc.click);
				li.addEventListener('click', tc.click);
				li.addEventListener('mousedown', tc.onMouseDown);
				close_button.addEventListener('mousedown', nop);
				close_button.addEventListener('click', tc.close);

				this.add = function(node_id, mode) {
					editor = document.getElementById('ed' + node_id);
					var file_name = document.getElementById('nm' + node_id).value;
					fname.innerHTML = file_name;
					fname.classList.add(mode);

					control.appendChild(li);
					open_mode = mode;
				}

				this.remove = function() {
					control.removeChild(li);
					editor_container.removeChild(editor);
				}

				this.isChild = function(obj) {
					return bframe.isChild(li, obj);
				}

				this.show = function(mode) {
					li.classList.add('selected');					
					a.classList.add('selected');
					visible = true;
					li.style.zIndex = '9999';
					if(mode && mode == 'permanent') {
						fname.classList.remove('temporary');
						fname.classList.add(mode);
						open_mode = mode;
					}

					if(editor) {
						editor.style.display = 'block';
					}
					else {
						folder_container.style.display = 'block';
					}
				}

				this.hide = function() {
					li.classList.remove('selected');					
					a.classList.remove('selected');					
					visible = false;
					li.style.zIndex = z;

					if(editor) {
						editor.style.display = 'none';
					}
					else {
						folder_container.style.display = 'none';
					}
				}

				this.inVisible = function() {
					li.style.visibility = 'hidden';
				}

				this.visible = function() {
					li.style.visibility = 'visible';
				}

				this.isVisible = function() {
					return visible;
				}

				this.openMode = function() {
					return open_mode;
				}

				this.element = function() {
					return li;
				}

				this.setFilename = function(file_name) {
					fname.innerHTML = file_name;
				}

				this.setOrder = function(number) {
					li.style.order = number;
				}

				this.setEditFlag = function() {
					edit_flag = true;
					open_mode = 'permanent';
					fname.classList.remove('temporary');
					fname.classList.add('permanent');
					close_button.innerHTML = '●';
				}

				this.resetEditFlag = function() {
					close_button.innerHTML = '×';
					edit_flag = false;
				}

				this.getEditFlag = function() {
					return edit_flag;
				}

				this.clone = function() {
					return li.cloneNode(true);
				}

				function nop(event) {
					bframe.stopPropagation(event);
				}
			}
		}

		// -------------------------------------------------------------------------
		// class dragControl
		// -------------------------------------------------------------------------
		function dragControl(w) {
			var self = this;
			var frame = w;
			var button_status;
			var drag_status;
			var source_node;
			var source_node_id;
			var event_obj;
			var destination_node_id;
			var destination_node;
			var drag_type;
			var clone_control;
			var clone_node;
			var clone_img;
			var clone_img_span;
			var clone_span;
			var clone_text;
			var drop_forbidden;
			var start_position = {};
			var frame_offset;
			var window_offset;
			var pane_flag = false;
			var div_overwrap, div_tree, div_pane;
			var overwrap_remove;
			var myFrame = parent.document.getElementsByName(window.name)[0];
			var baseZindex = bframe.getZindex(myFrame);
			this.onMouseUp = onMouseUp;

			if(!baseZindex) baseZindex = 990;

			div_overwrap = parent.document.getElementById('bframe_tree_overwrap_div');
			if(!div_overwrap && window.frameElement) {
				div_overwrap = parent.document.createElement('div');
				div_overwrap.id = 'bframe_tree_overwrap_div';
				div_overwrap.style.backgroundColor = '#f00';
				div_overwrap.style.opacity = 0;

				div_overwrap.style.width = 0;
				div_overwrap.style.height = 0;
				div_overwrap.style.zIndex = parseInt(baseZindex) - 1;
				div_overwrap.style.position = 'absolute';
				div_overwrap.style.top = 0;
				div_overwrap.style.left = 0;

				myFrame.style.position = 'relative';
				myFrame.style.zIndex = parseInt(baseZindex);
				parent.document.body.appendChild(div_overwrap);
				overwrap_remove = true;
			}

			div_tree = frame.document.getElementById('bframe_tree_drag_tree_div');
			if(!div_tree) {
				div_tree = frame.document.createElement('div');
				div_tree.id = 'bframe_tree_drag_tree_div';
				frame.document.body.appendChild(div_tree);
			}

			div_pane = frame.document.getElementById('bframe_tree_drag_pane_div');
			if(!div_pane) {
				div_pane = frame.document.createElement('div');
				div_tree.id = 'bframe_tree_drag_pane_div';
				div_pane.className = 'bframe_pane';
				frame.document.body.appendChild(div_pane);
			}

			clone_node = frame.document.createElement('div');
			clone_img_span = frame.document.createElement('span');
			clone_img = frame.document.createElement('img');
			clone_span = frame.document.createElement('span');
			clone_text = frame.document.createTextNode('');

			clone_img_span.className = 'img-border';
			clone_span.className = 'node-name';

			clone_node.appendChild(clone_img_span);
			clone_node.appendChild(clone_span);
			clone_span.appendChild(clone_text);
			clone_img_span.appendChild(clone_img);

			with(clone_node.style) {
				position = 'absolute';
				whiteSpace = 'nowrap';
				zIndex = parseInt(baseZindex) + 3;
				left = 0;
				top = 0;
				visibility='hidden';
			}
			bframe.setOpac(70, clone_node);
			clone_node.className = 'clone_node';

			drop_forbidden = frame.document.createElement('img');
			with(drop_forbidden.style) {
				zIndex = parseInt(baseZindex) + 4;
				visibility='hidden';
			}
			drop_forbidden.className = 'forbidden';

			clone_node.appendChild(drop_forbidden);

			setEventHandler();

			this.cleanUp = function() {
				if(div_overwrap && overwrap_remove && div_overwrap.parentNode == parent.document.body) parent.document.body.removeChild(div_overwrap);
				div_overwrap = null;
			}

			function setEventHandler() {
				bframe.addEventListener(window, 'beforeunload', cleanUp);

				// set event handller
				bframe.addEventListenerAllFrames(top, 'load', setEventHandlerAllFrames);
				bframe.addEventListenerAllFrames(top, 'mousemove', onMouseMove);
				bframe.addEventListenerAllFrames(top, 'mouseup', onMouseUp);
			}

			function setEventHandlerAllFrames() {
				if(typeof bframe == 'undefined' || !bframe) {
					return;
				}
				bframe.addEventListenerAllFrames(top, 'mousemove', onMouseMove);
				bframe.addEventListenerAllFrames(top, 'mouseup', onMouseUp);
			}

			function cleanUp() {
				if(typeof bframe == 'undefined' || !bframe) {
					return;
				}
				bframe.removeEventListenerAllFrames(top, 'load', setEventHandlerAllFrames);
				bframe.removeEventListenerAllFrames(top, 'mousemove', onMouseMove);
				bframe.removeEventListenerAllFrames(top, 'mouseup', onMouseUp);
			}

			this.dragStart = function(event, node_id) {
				event_obj = bframe.getEventSrcElement(event);
				frame_offset = bframe.getFrameOffset(window, '');
				button_status = true;
				start_position = bframe.getMousePosition(event);

				var wsize = bframe.getWindowSize();

				if(div_overwrap) {
					div_overwrap.style.width = wsize.width + 'px';
					div_overwrap.style.height = wsize.height + 'px';
				}
				if(bframe.searchParentById(event_obj, 'bframe_pane') && display_mode != 'detail') {
					pane_flag = true;
					div_pane.appendChild(clone_node);
					clone_node.className = 'clone_node_pane';
					drop_forbidden.src = property.icon.forbidden_big.src;
				}
				else {
					pane_flag = false;
					div_tree.appendChild(clone_node);
					clone_node.className = 'clone_node';
					drop_forbidden.src = property.icon.forbidden.src;
				}

				// set selected_node
				if(window.event) {
					var e = window.event;
				}
				else {
					var e = event;
				}
				if(!e.ctrlKey && !e.shiftKey && !e.metaKey && !selected_node.exists(node_id)) {
					selectObject(node_id);
				}

				source_node_id = node_id;
				source_node = document.getElementById(node_id);

				var img = document.getElementById('i' + node_id);
				var span = document.getElementById('s' + node_id);
				var li = document.getElementById(node_id);

				clone_img.src = img.src;
				clone_span.innerHTML = span.innerHTML;

				window_offset= {left: start_position.screenX - start_position.x - frame_offset.left,
								top: start_position.screenY - start_position.y - frame_offset.top};

				setClonePosition(event);
			}

			this.dragging = function(event) {
				if(!button_status) return;
				if(!drag_status) {
					current_position = bframe.getMousePosition(event);
					if(Math.abs(start_position.screenX - current_position.screenX) > 3 || Math.abs(start_position.screenY - current_position.screenY) > 3) {
						drag_status = true;
					}
					else {
						return;
					}
				}

				if(property.editable != 'true') {
					return;
				}

				var src = bframe.getEventSrcElement(event);
				var node = bframe.searchParentByName(src, 'node');

				if(!node) {
					destination_node_id = '';
					destination_node = '';
					return;
				}

				clearBorder();

				var div = bframe.searchNodeByName(node, 'node_div');
				var span = bframe.searchNodeByName(node, 'node_span');
				var position = bframe.getElementPosition(div);

				if(window.event) {
					var pageX = window.event.clientX;
					var pageY = window.event.clientY;
				}
				else {
					var scrollLeft = document.body.scrollLeft || document.documentElement.scrollLeft;
					var scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
					var pageX = event.pageX - scrollLeft;
					var pageY = event.pageY - scrollTop;
				}

				destination_node_id = '';
				destination_node = '';

				if(property.sort == 'manual') {
					if(property.relation && property.relation.pane && bframe.searchParentById(node, property.relation.pane.id) &&
						display_mode != 'detail') {
						// destination is in pane and display mode is icon style
						if(node.node_class == 'leaf') {
							if(position.left < pageX) {
								if(pageX < position.left + div.offsetWidth/2) {
									destination_node_id = node.id;
									destination_node = node;
									drag_type = 'sort';
								}
								else if(pageX <= position.left + div.offsetWidth) {
									if(sibling = getNextSibling(node)) {
										destination_node_id = sibling.id;
										destination_node = sibling;
										drag_type = 'sort';
									}
								}
							}
						}
						else {
							if(position.left < pageX) {
								if(pageX < position.left + div.offsetWidth/3) {
									destination_node_id = node.id;
									destination_node = node;
									drag_type = 'sort';
								}
								else if(pageX < position.left + div.offsetWidth*2/3) {
									destination_node_id = node.id;
									destination_node = node;
									drag_type = 'move';
								}
								else if(pageX <= position.left + div.offsetWidth) {
									if(sibling = getNextSibling(node)) {
										destination_node_id = sibling.id;
										destination_node = sibling;
										drag_type = 'sort';
									}
								}
							}
						}
					}
					else {
						// destination is in tree or detail list in pane
						if(node.node_class == 'leaf') {
							if(position.top < pageY) {
								if(pageY < position.top + div.offsetHeight/2) {
									destination_node_id = node.id;
									destination_node = node;
									drag_type = 'sort';
								}
								else if(pageY <= position.top + div.offsetHeight) {
									if(sibling = getNextSibling(node)) {
										destination_node_id = sibling.id;
										destination_node = sibling;
										drag_type = 'sort';
									}
								}
							}
						}
						else if(position.top < pageY) {
							if(pageY < position.top + div.offsetHeight/3 && node.id != 'troot') {
								destination_node_id = node.id;
								destination_node = node;
								drag_type = 'sort';
							}
							else if(pageY < position.top + div.offsetHeight*2/3) {
								destination_node_id = node.id;
								destination_node = node;
								drag_type = 'move';
							}
							else if(pageY <= position.top + div.offsetHeight) {
								if(sibling = getNextSibling(node)) {
									destination_node_id = sibling.id;
									destination_node = sibling;
									drag_type = 'sort';
								}
							}
						}
					}
				}
				else {
					// sort is disabled
					if(property.relation && property.relation.pane && bframe.searchParentById(node, property.relation.pane.id)) {
						// destination is in pane
						if(node.node_class != 'leaf' && position.left < pageX && pageX < position.left + div.offsetWidth) {
							destination_node_id = node.id;
							destination_node = node;
							drag_type = 'move';
						}
					}
					else {
						// destination is in tree
						if(node.node_class != 'leaf' && position.top < pageY && pageY < position.top + div.offsetHeight) {
							destination_node_id = node.id;
							destination_node = node;
							drag_type = 'move';
						}
					}
				}

				if(destination_node && !selected_node.exists(destination_node_id)) {
					if(drag_type == 'move') {
						span.className = 'node-name selected';
					}
					else {
						var destination_div = bframe.searchNodeByName(destination_node, 'node_div');
						destination_div.className = 'tree selected';
					}
				}

				// if the destination node is source node's child or in trash, drag is prohibited
				if(isPossible()) {
					if(drop_forbidden.style.visibility == 'visible') {
						drop_forbidden.style.visibility = 'hidden';
					}
				}
				else {
					clearBorder();
					destination_node_id = '';
					destination_node = '';
					if(drop_forbidden.style.visibility == 'hidden') {
						drop_forbidden.style.visibility = 'visible';
					}
				}
				current_node.setColor('current');
			}

			function isPossible() {
				if(!destination_node_id || !destination_node) return true;

				var trash = document.getElementById('ttrash');

				if(drag_type == 'move' && selected_node.place() == 'pane' && destination_node_id == current_node.id()) return false;
				if(destination_node_id == 'ttrash') {
					if(bframe.searchNodeById(trash, source_node_id)) {
						return false;
					}
					return true;
				}
				if(bframe.searchParentById(destination_node, 'ttrash')) return false;
				if(bframe.searchParentById(destination_node, 'uutrash')) return false;
				if(selected_node.exists(destination_node_id)) return false;
				if(selected_node.isChild(destination_node_id)) return false;
				if(destination_node_id.substr(0, 1) == 'p') {
					if(selected_node.id() == current_node.id() || selected_node.isChild(current_node.id())) return false;
				}

				return true;
			}

			this.dragStop = function() {
				clone_node.style.top = 0;
				clone_node.style.left = 0;
				clearBorder();

				if(!drag_status) {
					setTimeout(this.dragStop, 100);
				}

				drag_status = false;
				button_status = false;
				if(clone_node.style.visibility == 'visible') {
					clone_node.style.visibility == 'hidden';
				}

				if(property.editable != 'true') {
					return;
				}

				if(!destination_node_id || source_node_id == destination_node_id) {
					return;
				}

				if(drag_type == 'move') {
					var param;

					param = 'terminal_id='+terminal_id+'&mode=cut';

					for(var i=0; i < selected_node.length(); i++) {
						if(!selected_node.id(i)) continue;
						param+= '&source_node_id[' + i + ']='+encodeURIComponent(selected_node.id(i).substr(1));
					}

					param+= '&destination_node_id='+encodeURIComponent(destination_node_id.substr(1));
					httpObj = createXMLHttpRequest(showNode);
					eventHandler(httpObj, property.module, property.file, property.method.pasteNode, 'POST', param);
					target.style.cursor = 'wait';
					if(pane) pane.style.cursor = 'wait';
					response_wait = true;
				}

				if(drag_type == 'sort') {
					var param, p;

					if(destination_node_id == 'trash') {
						var source = document.getElementById(source_node_id);
						var parent = document.getElementById('uroot');

						p='';
						var i, j;
						for(i=0, j=0; i< parent.childNodes.length; i++) {
							if(parent.childNodes[i].id == source_node_id) {
								continue;
							}
							p+= '&node_list[' + j + ']=' + encodeURIComponent(parent.childNodes[i].id.substr(1));
							p+= '&update_datetime[' + j + ']=' + parent.childNodes[i].utime;
							j++;
						}
						p+= '&node_list[' + j + ']=' + encodeURIComponent(source_node_id.substr(1));
						p+= '&update_datetime[' + j + ']=' + source.utime;
						j++;
					}
					else {
						var parent = bframe.searchParentByName(destination_node, 'nodes');
						if(!parent) return;
						p='';
						var i, j, k;
						i=0;
						if(parent.tagName.toLowerCase() == 'tbody') {
							i=1;
						}
						for(j=0; i< parent.childNodes.length; i++) {
							if(parent.childNodes[i].id == destination_node_id) {
								for(k=0; k< selected_node.length(); k++) {
									p+= '&node_list[' + j + ']=' + encodeURIComponent(selected_node.id(k).substr(1));
									p+= '&update_datetime[' + j + ']=' + selected_node.object(k).utime;
									j++;
								}
								p+= '&node_list[' + j + ']=' + encodeURIComponent(parent.childNodes[i].id.substr(1));
								p+= '&update_datetime[' + j + ']=' + parent.childNodes[i].utime;
								j++;
								continue;
							}
							if(selected_node.exists(parent.childNodes[i].id)) {
								continue;
							}
							p+= '&node_list[' + j + ']=' + encodeURIComponent(parent.childNodes[i].id.substr(1));
							p+= '&update_datetime[' + j + ']=' + parent.childNodes[i].utime;
							j++;
						}
					}
					param = 'terminal_id='+terminal_id+'&parent_node_id='+encodeURIComponent(parent.id.substr(2))+p;
					for(var i=0; i < selected_node.length(); i++) {
						param+= '&source_node_id[' + i + ']='+encodeURIComponent(selected_node.id(i).substr(1));
					}

					httpObj = createXMLHttpRequest(showNode);
					eventHandler(httpObj, property.module, property.file, property.method.updateDispSeq, 'POST', param);
					target.style.cursor = 'wait';
					if(pane) pane.style.cursor = 'wait';
					response_wait = true;
				}
				destination_node_id = '';
				destination_node = '';
			}

			this.getDragStatus = function() {
				return drag_status;
			}

			function onMouseUp(event) {
				drag_status = false;
				button_status = false;
				clearBorder();
				destination_node_id = '';
				destination_node = '';

				if(div_overwrap) {
					div_overwrap.style.width = 0;
					div_overwrap.style.height = 0;
				}
				if(clone_node.style.visibility == 'visible') {
					clone_node.style.top = 0;
					clone_node.style.left = 0;
					clone_node.style.visibility = 'hidden';
				}
				if(drop_forbidden.style.visibility == 'visible') {
					drop_forbidden.style.visibility = 'hidden';
				}
			}

			function onMouseMove(event) {
				if(!drag_status) return;

				var m = bframe.getMousePosition(event);
				setClonePosition(event);
				clone_node.style.visibility='visible';

				var node = getEventNode(event);
				if(node) return;

				drop_forbidden.style.visibility = 'hidden';
				clearBorder();
			}

			function setClonePosition(event) {
				var m = bframe.getMousePosition(event);
				clone_node.style.left = parseInt(m.screenX+18 - window_offset.left - m.scrollLeft) + 'px';
				clone_node.style.top = parseInt(m.screenY+2 - window_offset.top - m.scrollTop) + 'px';
			}

			function clearBorder() {
				if(!destination_node_id || !destination_node) return;
				if(selected_node.exists(destination_node_id)) return;
				if(current_node.id == destination_node_id) return;

				var node = destination_node;
				var div = bframe.searchNodeByName(node, 'node_div');
				var span = bframe.searchNodeByName(node, 'node_span');

				div.className = 'tree';
				span.className = 'node-name';
			}
		}

		// -------------------------------------------------------------------------
		// class fileUpload
		// -------------------------------------------------------------------------
		function fileUpload() {
			var upload_button;
			var upload_button_style_display;
			var upload_file;

			var upload_queue = new Array();
			var index;
			var upload_count;
			var mode;
			var extract_mode;
			var form_data = new FormData();
			var progressFieldId;

			var httpObj;
			var files;
			var module;
			var page;
			var overlay;
			var uploading;

			var visibility = true;

			function init() {
				if(property.upload) {
					if(property.upload.button) {
						upload_button = document.getElementById(property.upload.button);
					}
					if(property.upload.file) {
						upload_file = document.getElementById(property.upload.file);
					}
					module = property.upload.module;
					page = property.upload.page;
					upload_button.onclick = selectFiles;
					upload_file.onchange = uploadFiles;
					pane.ondrop = uploadFiles;
					pane.ondragover = dragover;

					createOverlay();
				}
			}
			this.init = init;

			function createOverlay() {
				overlay = document.getElementById('bframe_tree_upload_overlay');
				if(!overlay) {
					overlay = document.createElement('div');
					overlay.id = 'bframe_tree_upload_overlay';
					overlay.style.position = 'absolute';
					overlay.style.top = 0;
					overlay.style.left = 0;
					overlay.style.width = 0;
					overlay.style.height = 0;
					overlay.style.opacity = '0.5';
					overlay.style.zIndex = 900;

					document.body.appendChild(overlay);
				}
			}

			function show() {
				upload_button.style.display = 'block';
				visibility = true;
			}
			this.show = show;

			function hide() {
				upload_button.style.display = 'none';
				visibility = false;
			}
			this.hide = hide;

			function isUploading() {
				return uploading;
			}
			this.isUploading = isUploading;

			function dragover(event) {
				event.preventDefault();
			}

			function selectFiles(event) {
				if(upload_queue[index]) return false;

				bframe.fireEvent(upload_file, 'click');
				bframe.stopPropagation(event);
			}
			this.selectFiles = selectFiles;

			function uploadFiles(event) {
				if(visibility) {
					index = 0;
					upload_queue.length = 0;
					upload_count = 0;
					mode = 'confirm';
					extract_mode = 'confirm';
					if(event.type == 'drop') {
						var files = event.dataTransfer.files;
						var items = event.dataTransfer.items;
					}
					else {
						var files = event.target.files;
					}

					var tree_id = 'tu' + current_node.id().substr(1);
					var pane_id, disp_type;
					if(display_mode == 'detail') {
						pane_id = pane_tbody.id;
						disp_type = 'detail';
					}
					else {
						pane_id = pane_ul.id;
						disp_type = 'thumbnail';
					}

					for(var i=0; i<files.length; i++) {
						files[i].id = i;
						var progress = new fileProgress(files[i], tree_id, pane_id, disp_type);

						if(items && items[i].webkitGetAsEntry().isDirectory) {
							progress.setError();
							progress.setStatus(property.upload.error_message);
						}
						else {
							upload_queue[i] = {'file' : files[i], 'progress' : progress};
						}
					}
					var wsize = bframe.getWindowSize();

					overlay.style.width = wsize.width + 'px';
					overlay.style.height = wsize.height + 'px';
					uploading = true;

					confirm(0);
				}
				event.preventDefault();
				bframe.fireEvent(window, 'resize');
			}

			function confirm(i) {
				var info;

				for(index=i; index < upload_queue.length; index++) {
					if(info = upload_queue[index]) break;
				}

				if(!info) {
					overlay.style.width = 0;
					overlay.style.height = 0;
					uploading = false;

					bframe.fireEvent(window, 'resize');

					return;
				}

				httpObj = new XMLHttpRequest();

				if(httpObj.upload) {
					httpObj.onreadystatechange = confirmResult;
					progress = upload_queue[index].progress;
					progress.setStatus('Uploading...');
				}

				var form_data = new FormData();

				form_data.append('terminal_id', terminal_id);
				form_data.append('module', module);
				form_data.append('page', page);
				form_data.append('method', 'confirm');
				form_data.append('mode', mode);
				form_data.append('session', module);
				form_data.append('node_id', current_node.id().substr(1));
				form_data.append('extract_mode', extract_mode);
				form_data.append('filename', upload_queue[index].file['name']);
				form_data.append('filesize', upload_queue[index].file['size']);

				httpObj.open('POST','index.php');
				httpObj.send(form_data);
			}

			function confirmResult() {
				if(httpObj.readyState == 4 && httpObj.status == 200) {
					try {
						var response = eval('('+httpObj.responseText+')');
					}
					catch(e) {
						var response = {status: false, message: top.bframe.message.getProperty('session_time_out') };
					}

					if(response.status) {
						if(response.mode == 'zipConfirm') {
							showZipConfirmDialog(response.message, extract, extractAll, noextract, cancelAll);
						}
						else if(response.mode == 'confirm') {
							showConfirmDialog(response.message, overwrite, overwriteAll, cancel, cancelAll);
						}
						else {
							overwrite();
						}
					}
					else {
						var progress = upload_queue[index].progress;
						progress.setError();
						progress.setStatus(response.message);
						scroll(progress.object());
						confirm(++index);
					}
				}
			}

			function upload(index) {
				var info = upload_queue[index];
				if(!info) return;

				httpObj = new XMLHttpRequest();

				if(httpObj.upload) {
					if(extract_mode == 'extract') {
						httpObj.onreadystatechange = extracting;
					}
					else {
						httpObj.onreadystatechange = setUploadResult;
					}
					var progress = upload_queue[index].progress;
					progress.setStatus('Uploading...');
					scroll(progress.object());

					httpObj.upload.onprogress = function (e) {
						var percent = Math.ceil((e.loaded / e.total) * 100);
						if(percent) var animate = ' animate';
						progress.setProgress(percent, animate);
						progress.setStatus('Uploading...');
					};
				}

				var form_data = new FormData();

				form_data.append('terminal_id', terminal_id);
				form_data.append('module', module);
				form_data.append('page', page);
				form_data.append('method', 'upload');
				form_data.append('mode', 'register');
				form_data.append('extract_mode', extract_mode);
				form_data.append('node_id', current_node.id().substr(1));
				form_data.append('Filedata', upload_queue[index].file);
				form_data.append('last_file', index + 1 == upload_queue.length ? true : false);
				httpObj.open('POST','index.php');
				httpObj.send(form_data);
			}

			function extracting() {
				if((httpObj.readyState == 3) && httpObj.status == 200) {
					var response = eval('('+httpObj.responseText+')');
					var animate = '';
					if(response['status'] == 'extracting') {
						if(response['progress']) var animate = ' animate';
						progress.setProgress(response['progress'], animate);
						progress.setStatus('Extracting...');
					}
					if(response['status'] == 'creating') {
						if(response['progress']) var animate = ' animate';
						progress.setProgress(response['progress'], animate);
						progress.setStatus('Creating Thumbnails...');
					}
				}
				if((httpObj.readyState == 4) && httpObj.status == 200) {
					var response = eval('('+httpObj.responseText+')');
					result(response);
					confirm(++index);
				}
			}

			function setUploadResult() {
				if(httpObj.readyState == 4 && httpObj.status == 200) {
					var response = eval('('+httpObj.responseText+')');
					result(response);
					confirm(++index);
				}
			}

			function result(responseObj) {
				var progress = upload_queue[index].progress;
				if(responseObj.status == true) {
					progress.setComplete(responseObj.node_info);
					progress.setStatus('Complete.');
					upload_count++;
				}
				else {
					progress.setError();
					progress.setStatus(responseObj.message);
				}
			}

			function extract() {
				extract_mode = 'extract';
				upload(index);
				extract_mode = 'confirm';
			}

			function extractAll() {
				extract_mode = 'extract';
				upload(index);
			}

			function noextract() {
				extract_mode = 'noextract';
				confirm(index);
				extract_mode = 'confirm';
			}

			function overwrite() {
				upload(index);
			}

			function overwriteAll() {
				mode = 'overwrite';
				upload(index);
			}

			function cancel() {
				upload_queue[index].progress.setCancelled();
				upload_queue[index].progress.setStatus('Cancelled.');
				confirm(++index);
			}

			function cancelAll() {
				_cancel();
				for(; upload_queue[index]; index++) {
					cancelUpload();
				}
				overlay.style.width = 0;
				overlay.style.height = 0;
				uploading = false;
			}

			function cancelUpload() {
				upload_queue[index].progress.setCancelled();
				upload_queue[index].progress.setStatus('Cancelled.');
			}

			function _cancel() {
				httpObj = new XMLHttpRequest();
				httpObj.onreadystatechange = cancelResult;

				var form_data = new FormData();

				form_data.append('terminal_id', terminal_id);
				form_data.append('module', module);
				form_data.append('page', page);
				form_data.append('method', 'cancel');
				form_data.append('mode', mode);

				httpObj.open('POST','index.php');
				httpObj.send(form_data);
			}

			function cancelResult() {
				return;
			}

			function showZipConfirmDialog(msg, funcExtract, funcExtractAll, funcNoExtract, cancel) {
				var params = {
					'id': 'confirmDialog',
					'title': '',
					'message': msg,
					'buttons': [
						{
							'name': top.bframe.message.getProperty('upload_zip_confirm_dialog1'),
							'className': 'button',
							'action': funcExtract
						},
						{
							'name': top.bframe.message.getProperty('upload_zip_confirm_dialog2'),
							'className': 'button',
							'action': funcExtractAll
						},
						{
							'name': top.bframe.message.getProperty('upload_zip_confirm_dialog3'),
							'className': 'button',
							'action': funcNoExtract
						},
						{
							'name': top.bframe.message.getProperty('upload_zip_confirm_dialog4'),
							'className': 'button',
							'action': cancel
						}
					]
				};

				var dialog = new bframe.dialog(params);
			}

			function showConfirmDialog(msg, funcYes, funcYesToAll, funcNo, funcNoToAll) {
				var params = {
					'id': 'confirmDialog',
					'title': '',
					'message': msg,
					'buttons': [
						{
							'name': top.bframe.message.getProperty('upload_confirm_dialog1'),
							'className': 'button',
							'action': funcYes
						},
						{
							'name': top.bframe.message.getProperty('upload_confirm_dialog2'),
							'className': 'button',
							'action': funcYesToAll
						},
						{
							'name': top.bframe.message.getProperty('upload_confirm_dialog3'),
							'className': 'button',
							'action': funcNo
						},
						{
							'name': top.bframe.message.getProperty('upload_confirm_dialog4'),
							'className': 'button',
							'action': funcNoToAll
						}
					]
				};

				var dialog = new bframe.dialog(params);
			}

			// -------------------------------------------------------------------------
			// class progress
			// -------------------------------------------------------------------------
			function fileProgress(file, tree_id, pain_id, disp_type) {
				var tree = document.getElementById(tree_id);
				var pain = document.getElementById(pain_id);
				var id = file.id;
				var filename = file.name;
				var fileProgressWrapper;
				var fileProgressElement;
				var fileProgressTree;

				var overwriteList, overwriteTr;

				function object() {
					return fileProgressElement;
				}
				this.object = object;

				function reset() {
					fileProgressElement.className = 'progressContainer';

					fileProgressElement.childNodes[0].innerHTML = '&nbsp;';
					fileProgressElement.childNodes[0].className = 'progressBarStatus';

					fileProgressElement.childNodes[1].className = 'progressBarInProgress';
					fileProgressElement.childNodes[1].style.width = '0%';
				}
				this.reset = reset;

				function setProgress(percentage, animate) {
					if(!animate) animate = '';
					fileProgressElement.className = 'progressContainer green';
					fileProgressElement.childNodes[1].className = 'progressBarInProgress' + animate;
					fileProgressElement.childNodes[1].style.width = percentage + '%';
				}
				this.setProgress = setProgress;

				function setComplete(node_info) {
					var li;

					if(disp_type == 'thumbnail') {
						for(var i=0; i<node_info.length; i++) {
							if(node_info[i].node_type == 'folder') {
								li = _createNodeObject(node_info[i], 'tree', false);
								for(var n=tree.firstChild; n; n=n.nextSibling) {
									if(node_info[i].path.toLowerCase() == bframe.searchNodeByName(n, 'path').value.toLowerCase()) {
										var node_number = document.getElementById('nn'+n.id).value;
										tree.replaceChild(li, n);
										var nn = document.getElementById('nn'+li.id);
										nn.value = node_number;
										break;
									}
								}
								if(!n) {
									tree.appendChild(li);
								}
							}
							li = _createNodeObject(node_info[i], 'pane', false);

							for(var n=pain.firstChild; n; n=n.nextSibling) {
								var path = bframe.searchNodeByName(n, 'path').value;
								if(!path) continue;
								if(node_info[i].path.toLowerCase() == path.toLowerCase()) {
									var node_number = document.getElementById('nn'+n.id).value;
									pain.replaceChild(li, n);
									var nn = document.getElementById('nn'+li.id);
									nn.value = node_number;
									if(fileProgressWrapper) {
										pain.removeChild(fileProgressWrapper);
										fileProgressWrapper = '';
									}
									break;
								}
							}
							if(!n) {
								if(fileProgressWrapper) {
									pain.replaceChild(li, fileProgressWrapper);
									fileProgressWrapper = '';
								}
								else {
									pain.appendChild(li);
								}
							}
						}
					}
					else {
						for(var i=0; i<node_info.length; i++) {
							if(node_info[i].node_type == 'folder') {
								li = _createNodeObject(node_info[i], 'tree', false);
								for(var n=tree.firstChild; n; n=n.nextSibling) {
									if(node_info[i].path.toLowerCase() == bframe.searchNodeByName(n, 'path').value.toLowerCase()) {
										var node_number = document.getElementById('nn'+n.id).value;
										tree.replaceChild(li, n);
										var nn = document.getElementById('nn'+li.id);
										nn.value = node_number;
										break;
									}
								}
								if(!n) {
									tree.appendChild(li);
								}
							}
							tr = _createDetailNodeObject(node_info[i])

							for(var n=pain.firstChild; n; n=n.nextSibling) {
								var path = bframe.searchNodeByName(n, 'path');
								if(path && path.value.toLowerCase() == node_info[i].path.toLowerCase()) {
									var node_number = document.getElementById('nn'+n.id).value;
									pain.replaceChild(tr, n);
									var nn = document.getElementById('nn'+tr.id);
									nn.value = node_number;
									if(fileProgressWrapper) {
										pain.removeChild(fileProgressWrapper);
										fileProgressWrapper = '';
									}
									break;
								}
							}
							if(!n) {
								if(fileProgressWrapper) {
									pain.replaceChild(tr, fileProgressWrapper);
									fileProgressWrapper = '';
								}
								else {
									pain.appendChild(tr);
								}
							}
						}
					}
				}
				this.setComplete = setComplete;

				function setError() {
					fileProgressElement.className = 'progressContainer red';
					fileProgressElement.childNodes[1].className = 'progressBarError';
					fileProgressElement.childNodes[1].style.width = '';
					bframe.addEventListener(fileProgressElement, 'click', clickErrorObject);
				}
				this.setError = setError;

				function clickErrorObject(event) {
					if(overwriteList) {
						bframe.appendClass('fadein', overwriteList);
						overwriteList = '';
					}
					if(overwriteTr) {
						for(var i=0; i<overwriteTr.cells.length; i++) {
							bframe.appendClass('fadein', overwriteTr.cells[i].firstChild);
						}
						overwriteTr = '';
					}
					if(fileProgressWrapper) {
						bframe.appendClass('fadeout2', fileProgressWrapper);
						bframe.addEventListener(fileProgressWrapper, 'animationend', onAnimationEnd);
					}
					else {
						if(fileProgressElement) {
							fileProgressElement.className = 'progressContainer red fadeout2';
							bframe.addEventListener(fileProgressElement, 'animationend', onAnimationEnd);
						}
						if(fileProgressTree) {
							bframe.appendClass('fadeout2', fileProgressTree);
							bframe.addEventListener(fileProgressTree, 'animationend', onAnimationEnd);
						}
					}
				}

				function setCancelled() {
					if(overwriteList) {
						bframe.appendClass('fadein', overwriteList);
						overwriteList = '';
					}
					if(overwriteTr) {
						for(var i=0; i<overwriteTr.cells.length; i++) {
							bframe.appendClass('fadein', overwriteTr.cells[i].firstChild);
						}
						overwriteTr = '';
					}
					if(fileProgressWrapper) {
						bframe.appendClass('fadeout', fileProgressWrapper);
						bframe.addEventListener(fileProgressWrapper, 'animationend', onAnimationEnd);
					}
					else {
						if(fileProgressElement) {
							fileProgressElement.className = 'progressContainer cancelled fadeout';
							bframe.addEventListener(fileProgressElement, 'animationend', onAnimationEnd);
						}
						if(fileProgressTree) {
							bframe.appendClass('fadeout', fileProgressTree);
							bframe.addEventListener(fileProgressTree, 'animationend', onAnimationEnd);
						}
					}
				}
				this.setCancelled = setCancelled;

				onAnimationEnd = function(event) {
					var obj = bframe.getEventSrcElement(event);
					if(obj && obj.parentNode) {
						obj.parentNode.removeChild(obj);
						bframe.fireEvent(window, 'resize');
					}
				}

				setStatus = function(status) {
					fileProgressElement.childNodes[0].innerHTML = status;
				}
				this.setStatus = setStatus;

				var link, border, filename, img;
				var upload_filename = file.name;

				if(disp_type == 'thumbnail') {
					fileProgressTree = document.createElement('div');
					fileProgressTree.className = 'tree';

					link = document.createElement('a');

					border = document.createElement('div');
					border.className = 'img-border';

					filename = document.createElement('span');
					filename.className = 'node-name';

					fileProgressElement = document.createElement('div');
					fileProgressElement.className = 'progressContainer white';

					var progressStatus = document.createElement('div');
					progressStatus.className = 'progressBarStatus';
					progressStatus.innerHTML = '&nbsp;';

					var progressBar = document.createElement('div');
					progressBar.className = 'progressBarInProgress';

					fileProgressTree.appendChild(link);
					link.appendChild(border);
					link.appendChild(filename);
					border.appendChild(fileProgressElement);
					fileProgressElement.appendChild(progressStatus);
					fileProgressElement.appendChild(progressBar);

					for(var li=pain.firstChild; li; li=li.nextSibling) {
						var path = bframe.searchNodeByName(li, 'node_name').value;
						if(!path) continue;
						if(file.name.toLowerCase() == path.toLowerCase()) {
							upload_filename = bframe.searchNodeByName(li, 'node_name').value;
							if(li.childNodes.length > 1) {
								li.removeChild(li.childNodes[1]);
							}
							fileProgressTree.style.marginTop = '-' + li.firstChild.offsetHeight + 'px';
							li.appendChild(fileProgressTree);
							li.firstChild.style.opacity = 0.2;
							overwriteList = li.firstChild;
							bframe.removeClass('fadein', overwriteList);
							break;
						}
					}
					if(!li) {
						fileProgressWrapper = document.createElement('li');
						fileProgressWrapper.className = 'tree-list';
						fileProgressWrapper.appendChild(fileProgressTree);
						pain.appendChild(fileProgressWrapper);
					}

					filename.appendChild(document.createTextNode(shortenText(upload_filename)));
				}
				else {
					for(var tr=pain.firstChild; tr; tr=tr.nextSibling) {
						var node_name = bframe.searchNodeByName(tr, 'node_name');
						if(node_name && node_name.value.toLowerCase() == file.name.toLowerCase()) {
							upload_filename = bframe.searchNodeByName(tr, 'node_name').value;
							if(tr.childNodes[0].childNodes.length > 1) {
								tr.childNodes[0].removeChild(tr.childNodes[0].childNodes[1]);
							}
							fileProgressTree = document.createElement('div');
							fileProgressTree.className = 'tree upload-filename';

							link = document.createElement('a');

							border = document.createElement('span');
							border.className = 'img-border';

							img = document.createElement('img');
							img.src = property.icon.detail.upload.src;

							filename = document.createElement('span');
							filename.className = 'node-name';
							filename.appendChild(document.createTextNode(upload_filename));

							tr.childNodes[0].appendChild(fileProgressTree);
							fileProgressTree.appendChild(link);
							link.appendChild(border);
							link.appendChild(img);
							link.appendChild(filename);

							if(tr.childNodes[1].childNodes.length > 1) {
								tr.childNodes[1].removeChild(tr.childNodes[1].childNodes[1]);
							}
							fileProgressElement = document.createElement('div');
							fileProgressElement.className = 'progressContainer white';

							var progressStatus = document.createElement('div');
							progressStatus.className = 'progressBarStatus';
							progressStatus.innerHTML = '&nbsp;';

							var progressBar = document.createElement('div');
							progressBar.className = 'progressBarInProgress';

							tr.childNodes[1].appendChild(fileProgressElement);
							fileProgressElement.appendChild(progressStatus);
							fileProgressElement.appendChild(progressBar);

							for(var i=0; i<tr.cells.length; i++) {
								tr.childNodes[i].firstChild.style.opacity = 0;
								bframe.removeClass('fadein', tr.childNodes[i].firstChild);
							}

							overwriteTr = tr;

							break;
						}
					}
					if(!tr) {
						fileProgressWrapper = document.createElement('tr');

						var td = document.createElement('td');
						td.className = 'file-name';

						fileProgressTree = document.createElement('div');
						fileProgressTree.className = 'tree';

						link = document.createElement('a');

						border = document.createElement('span');
						border.className = 'img-border';

						img = document.createElement('img');
						img.src = property.icon.detail.upload.src;

						filename = document.createElement('span');
						filename.className = 'node-name';
						filename.appendChild(document.createTextNode(file.name));

						td2 = document.createElement('td');
						td2.className = 'progress-bar';

						fileProgressElement = document.createElement('div');
						fileProgressElement.className = 'progressContainer white';

						var progressStatus = document.createElement('div');
						progressStatus.className = 'progressBarStatus';
						progressStatus.innerHTML = '&nbsp;';

						var progressBar = document.createElement('div');
						progressBar.className = 'progressBarInProgress';

						fileProgressWrapper.appendChild(td);

						td.appendChild(fileProgressTree);
						fileProgressTree.appendChild(link);
						link.appendChild(border);
						link.appendChild(img);
						link.appendChild(filename);

						fileProgressWrapper.appendChild(td2);
						td2.appendChild(fileProgressElement);
						fileProgressElement.appendChild(progressStatus);
						fileProgressElement.appendChild(progressBar);

						pain.appendChild(fileProgressWrapper);
					}
				}
			}
		}

		// -------------------------------------------------------------------------
		// class createNodeObject
		// -------------------------------------------------------------------------
		function createNodeObject(parent, config, place, trash) {
			var li = _createNodeObject(config, place, trash)
			parent.appendChild(li);

			return li;
		}

		function _createNodeObject(config, place, trash) {
			if(!config.node_id) return;

			if(place == 'tree') {
				var node_id = 't'+config.node_id;
			}
			else {
				var node_id = 'p'+config.node_id;
			}
			var div, ul, li, control, a, obj_img, span, text, input;

			li = document.createElement('li');
			li.name = 'node';

			li.className = 'tree-list';
			li.id = node_id;

			li.node_class = config.node_class;
			li.node_type = config.node_type;
			li.utime = config.update_datetime_u;

			div = document.createElement('div');
			div.name = 'node_div';
			div.id = 'd' + node_id;
			div.className = 'tree';

			li.appendChild(div);

			control = document.createElement('img');
			control.id = 'c' + node_id;
			control.name = 'node_control';
			control.style.cursor = 'pointer';
			div.appendChild(control);

			control.className = 'control';

			a = document.createElement('a');
			a.style.cursor = 'pointer';
			a.id = 'a' + node_id;
			div.appendChild(a);

			if(place == 'tree') {
				a.onclick = selectNode;
				a.ondblclick = selectResourceNode;
				if((pane && config.folder_count > 0 ) || (!pane && config.node_count > 0)) {
					if(config.children) {
						control.src = property.icon.minus.src;
					}
					else {
						control.src = property.icon.plus.src;
					}
					control.onmousedown = openNode;
					control.onclick = nop;
				}
				else {
					control.src = property.icon.blank.src;
				}
			}
			else {
				a.onclick = selectObjectNode;
				control.src = property.icon.blank.src;
				if(config.node_class == 'folder') {
					a.ondblclick = selectNode;
				}
			}

			if(property.editable == 'true') {
				if(li.id.substr(1) == 'trash') {
					div.oncontextmenu=showTrashContextMenu;
				}
				else {
					div.oncontextmenu=showContextMenu;
				}
				div.onmousemove = onNodeMouseMove;
				div.onmouseup = onNodeMouseUp;
				a.onmousedown = onNodeMouseDown;
			}

			div.onmouseover = onNodeMouseOver;
			div.onmouseout = onNodeMouseOut;

			img_span = document.createElement('span');
			img_span.className = 'img-border';
			img_span.name = 'img_border';
			a.appendChild(img_span);

			obj_img = document.createElement('img');
			obj_img.id = 'i' + node_id;
			img_span.appendChild(obj_img);

			if(place == 'pane') {
				a.style.cursor = 'pointer';

				if(config.node_class == 'folder') {
					obj_img.src = property.icon.pane.folder.src;
				}
				else {
					a.ondblclick = selectResourceNode;
					var suffix = config.path.substring(config.path.lastIndexOf('.')+1, config.path.length);

					switch(suffix.toLowerCase()) {
					case 'js':
						obj_img.src = property.icon.pane.js.src;
						break;
					case 'swf':
						obj_img.src = property.icon.pane.swf.src;
						break;
					case 'css':
						obj_img.src = property.icon.pane.css.src;
						break;
					case 'pdf':
						obj_img.src = property.icon.pane.pdf.src;
						break;

					case 'jpg':
					case 'jpeg':
					case 'gif':
					case 'png':
					case 'bmp':
					case 'svg':
						if(property.icon.pane[suffix.toLowerCase()]) {
							obj_img.src = property.icon.pane[suffix.toLowerCase()].src;
						}
						else {
							if(config.thumbnail_image_path) {
								obj_img.src = config.thumbnail_image_path + '?' + config.update_datetime_u;
							}
							else {
								var file_name = config.path.substring(config.path.lastIndexOf('/')+1,config.path.length);
								var dir = config.path.substring(0, config.path.lastIndexOf('/')+1);
								var extension = file_name.substring(file_name.lastIndexOf('.')+1, file_name.length);
								if(suffix.toLowerCase() == 'svg') {
									obj_img.src = property.thumb_path + config.contents_id + '.' + extension + '?' + config.update_datetime_u;
								}
								else {
									obj_img.src = property.thumb_path + property.thumb_prefix + config.contents_id + '.' + extension + '?' + config.update_datetime_u;
								}
							}
							a.title = 'size:' + config.human_image_size + '\n' + 'date:' + config.create_datetime_t;
							if(suffix.toLowerCase() == 'svg') {
								obj_img.width = '80';
								obj_img.height = '80';
								if(config.human_image_size) {
									var size = config.human_image_size.split('x');
									if(size[0] <= 80 && size[1] <= 80) {
										obj_img.width = size[0];
										obj_img.height = size[1];
									}
								}
							}
						}
						break;

					case 'avi':
					case 'flv':
					case 'mov':
					case 'mp4':
					case 'mpeg':
					case 'mpg':
					case 'wmv':
						if(property.icon.pane[suffix.toLowerCase()]) {
							obj_img.src = property.icon.pane[suffix.toLowerCase()].src;
						}
						else {
							if(config.thumbnail_image_path) {
								obj_img.src = config.thumbnail_image_path + '?' + config.update_datetime_u;
							}
							else {
								var file_name = config.path.substring(config.path.lastIndexOf('/')+1,config.path.length);
								var dir = config.path.substring(0, config.path.lastIndexOf('/')+1);
								var extension = file_name.substring(file_name.lastIndexOf('.')+1, file_name.length);
								obj_img.src = property.thumb_path + property.thumb_prefix + config.contents_id + '.' + 'jpg' + '?' + config.update_datetime_u;
							}
							a.title = 'size:' + config.human_image_size + '\n' + 'date:' + config.create_datetime_t;
						}
						break;

					default:
						obj_img.src = property.icon.pane.misc.src;
						break;
					}
				}
			}
			else {
				if(config.node_type == 'folder' && config.children && ((pane && config.folder_count > 0 ) || (!pane && config.node_count > 0))) {
					obj_img.src = property.icon['folder_open'].src;
				}
				else {
					obj_img.src = property.icon[config.node_type].src;
				}
			}
			if(config.node_status) {
				if(place == 'pane') {
					var private = document.createElement('span');
					private.className = 'private-mode';
					img_span.appendChild(private);

					var private_message = document.createElement('span');
					private_message.className = 'private-msg';
					private_message.innerHTML = 'Private Mode';
					private.appendChild(private_message);

					var node_status_img = document.createElement('img');
					node_status_img.className = 'private-img';
					node_status_img.src = property.icon.pane['status' + config.node_status].src;
					private.appendChild(node_status_img);
				}
				else {
					var node_status_img = document.createElement('img');
					node_status_img.id = 'is' + node_id;
					node_status_img.className = 'node-status';
					node_status_img.src = property.icon['status' + config.node_status].src;
					img_span.appendChild(node_status_img);
				}
			}

			span = document.createElement('span');
			span.id = 's' + node_id;
			span.name = 'node_span';
			a.appendChild(span);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'nt' + node_id;
			input.name = 'node_type';
			input.value = config.node_type;
			a.appendChild(input);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'p' + node_id;
			input.name = 'path';
			input.value = config.path;
			a.appendChild(input);

			if(config.image_size) {
				input = document.createElement('input');
				input.type = 'hidden';
				input.id = 'is' + node_id;
				input.name = 'image_size';
				input.value = config.image_size;
				a.appendChild(input);
			}

			if(config.human_image_size) {
				input = document.createElement('input');
				input.type = 'hidden';
				input.id = 'his' + node_id;
				input.name = 'human_image_size';
				input.value = config.human_image_size;
				a.appendChild(input);
			}

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'ds' + node_id;
			input.name = 'disp_seq';
			input.value = config.disp_seq;
			a.appendChild(input);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'nn' + node_id;
			input.name = 'node_number';
			input.value = node_number++;
			a.appendChild(input);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'nm' + node_id;
			input.name = 'node_name';
			input.value = config.node_name;
			a.appendChild(input);

			span.className = 'node-name';
			text = document.createTextNode(shortenText(config.node_name));
			span.appendChild(text);

			return li;
		}

		// -------------------------------------------------------------------------
		// class createDetailTitle
		// -------------------------------------------------------------------------
		function createDetailTitle(parent, sort_key, sort_order) {
			var tr, th, span, text;
			tr = document.createElement('tr');
			parent.appendChild(tr);

			for(var i=0; i<property.detail.header.length; i++) {
				th = document.createElement('th');
				text = document.createTextNode(property.detail.header[i].title);
				th.className = property.detail.header[i].className;
				span = document.createElement('span');

				span.appendChild(text);
				th.appendChild(span);
				tr.appendChild(th);
				if(property.sort == 'auto' && property.detail.header[i].sort_key) {
					input = document.createElement('input');
					input.type = 'hidden';
					input.name = 'sort_key';
					input.value = property.detail.header[i].sort_key
					th.appendChild(input);
					th.className+= ' sortable';
					if(sort_key && sort_key == property.detail.header[i].sort_key) {
						if(sort_order) {
							th.className+= ' '
							th.className+= sort_order;
						}
					}
					th.onclick = sort;
				}
			}
		}

		// -------------------------------------------------------------------------
		// class createDetailNodeObject
		// -------------------------------------------------------------------------
		function createDetailNodeObject(parent, config) {
			if(!config.node_id) return;

			var tr = _createDetailNodeObject(config);
			parent.appendChild(tr);
		}

		function _createDetailNodeObject(config) {
			var tr, td, div, ul, li, control, a, obj_img, span, text, input;

			var node_id = 'p'+config.node_id;

			tr = document.createElement('tr');
			tr.id = node_id;
			tr.name = 'node';
			tr.utime = config.update_datetime_u;
			tr.node_class = config.node_class;

			td = document.createElement('td');
			td.className = 'file-name';
			tr.appendChild(td);

			div = document.createElement('div');
			div.name = 'node_div';
			div.id = 'd' + node_id;
			div.className = 'tree';
			td.appendChild(div);

			div.onSelectStart = function() {return false;};
 
			a = document.createElement('a');
			a.style.cursor = 'pointer';
			a.id = 'a' + node_id;
			div.appendChild(a);

			if(property.editable == 'true') {
				div.oncontextmenu=showContextMenu;
				div.onmousemove = onNodeMouseMove;
				div.onmouseup = onNodeMouseUp;
				a.onmousedown = onNodeMouseDown;
			}

			div.onmouseover = onNodeMouseOver;
			div.onmouseout = onNodeMouseOut;

			img_span = document.createElement('span');
			img_span.name = 'img_border';
			img_span.className = 'img-border';
			a.appendChild(img_span);

			obj_img = document.createElement('img');
			obj_img.id = 'i' + node_id;

			if(config.node_class == 'folder') {
				obj_img.src = property.icon.detail.folder.src;
			}
			else {
				suffix = config.path.substring(config.path.lastIndexOf('.')+1,config.path.length);

				if(property.icon.detail[suffix.toLowerCase()]) {
					obj_img.src = property.icon.detail[suffix.toLowerCase()].src;
				}
				else {
					obj_img.src = property.icon.detail.misc.src;
				}
			}
			img_span.appendChild(obj_img);
			if(config.node_status) {
				var node_status_img = document.createElement('img');
				node_status_img.id = 'is' + node_id;
				node_status_img.className = 'node-status';
				node_status_img.src = property.icon['status' + config.node_status].src;
				img_span.appendChild(node_status_img);
			}

			a.onclick = selectObjectNode;
			if(config.node_class == 'folder') {
				a.ondblclick = selectNode;
			}
			else {
				a.ondblclick = selectResourceNode;
			}
			a.style.cursor = 'pointer';

			span = document.createElement('span');
			span.id = 's' + node_id;
			span.name = 'node_span';
			span.className = 'node-name';
			a.appendChild(span);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'nt' + node_id;
			input.name = 'node_type';
			input.value = config.node_type;
			a.appendChild(input);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'p' + node_id;
			input.name = 'path';
			input.value = config.path;
			a.appendChild(input);

			if(config.image_size) {
				input = document.createElement('input');
				input.type = 'hidden';
				input.id = 'is' + node_id;
				input.name = 'images_size';
				input.value = config.image_size;
				a.appendChild(input);
			}

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'ds' + node_id;
			input.name = 'disp_seq';
			input.value = config.disp_seq;
			a.appendChild(input);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'nn' + node_id;
			input.name = 'node_number';
			input.value = node_number++;
			a.appendChild(input);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = 'nm' + node_id;
			input.name = 'node_name';
			input.value = config.node_name;
			a.appendChild(input);

			text = document.createTextNode(config.node_name);
			span.appendChild(text);

			for(var i=1; i<property.detail.header.length; i++) {
				td = setColumn(property.detail.header[i], config[property.detail.header[i].name]);
				tr.appendChild(td);
			}

			return tr;
		}

		function setColumn(config, value) {
			var td = document.createElement('td');
			td.className = config.className;

			span = document.createElement('span');
			span.className = config.className;
			td.appendChild(span);
			if(!value) value = '';
			text = document.createTextNode(value);
			span.appendChild(text);

			return td;
		}

		function openNode(event) {
			if(bframe.getButton(event) != 'L') return;

			var obj = bframe.getEventSrcElement(event);
			var node = bframe.searchParentByName(obj, 'node');
			var node_id = node.id;

			if(bframe.getFileName(obj.src) == bframe.getFileName(property.icon.plus.src)) {
				getNodeList(node_id, 'open');
			}
			else {
				var ul = document.getElementById('tu' + node_id.substr(1));
				ul.style.display='none';
				obj.src = property.icon.plus.src;
				closeNode(node_id);
			}
			bframe.stopPropagation(event);
		}

		function nop(event) {
			bframe.stopPropagation(event);
		}

		function selectObjectNode(event) {
			hideContextMenu(event);
			if(window.event) {
				var e = window.event;
			}
			else {
				var e = event;
			}
			// right button
			if(e.button == 2) return;

			var node = getEventNode(event);
			if(selected_node.id() && selected_node.place() == 'pane' && (e.ctrlKey || e.metaKey)) {
				addSelectedObject(node.id);
			}
			else if(selected_node.id() && selected_node.place() == 'pane' && e.shiftKey) {
				addRangeSelectedObject(node.id);
			}
			else {
				selectObject(node.id);
			}
			bframe.stopPropagation(event);
		}

		function selectNode(event) {
			hideContextMenu(event);
			if(window.event) {
				var e = window.event;
			}
			else {
				var e = event;
			}
			// right button
			if(e.button == 2) return;
			var node = getEventNode(event);
			var node_type = document.getElementById('nt' + node.id);

			if(property.selectable == 'true') {
				if(node_type.value != 'folder' && node.id.substr(1) != 'root' && node.id.substr(1) != 'trash') {
					if(current_node.id() && current_node.place() == 'tree' && (e.ctrlKey || e.metaKey)) {
						addCurrentObject(node.id);
					}
					else {
						currentObject(node.id);
					}
				}
			}
			else if(node != current_node.object() || node != selected_node.object()) {
				select(node.id);
			}
			bframe.stopPropagation(event);

			if(node.id == current_node.id() && tab_control && tab_control.isFolderOpen()) return;

			if(node_type.value == 'file' && property.editor_mode == 'true') {
//				selectResource(node.id, 'temporary');
				selectResource(node.id, 'permanent');
			}

			if(node_type.value != 'folder' && node.id.substr(1) != 'root' && node.id.substr(1) != 'trash') {
				return;
			}
			var control = document.getElementById('c' + node.id);
			if(pane) {
				current_node.set('t' + node.id.substr(1));
				getNodeList(node.id);
			}
			else if(control && bframe.getFileName(control.src) == bframe.getFileName(property.icon.plus.src)) {
				getNodeList(node.id);
			}
		}

		function selectResourceNode(event) {
			if(window.event) {
				var e = window.event;
			}
			else {
				var e = event;
			}
			// right button
			if(e.button == 2) return;

			var node = getEventNode(event);
			selectResource(node.id, 'permanent');
		}

		function onNodeMouseDown(event) {
			if(bframe.getButton(event) != 'L') return;

			// exception
			var obj = bframe.getEventSrcElement(event);
			if(obj.tagName.toLowerCase() == 'input') return;

			var node = getEventNode(event);
			if(node.id != 'troot' && node.id != 'ttrash') {
				drag_control.dragStart(event, node.id);
			}
			if(_isIE) {
				window.event.returnValue = false;
			}
			else {
				event.preventDefault();
			}
		}

		function onNodeMouseMove(event) {
			drag_control.dragging(event);
		}

		function onNodeMouseOver(event) {
			var span;

			var node = getEventNode(event);
			if(!node) return;

			span = document.getElementById('s' + node.id);
			if(property.editable == 'true') {
				drag_control.dragging(event);
			}
		}

		function onNodeMouseUp(event) {
			drag_control.dragStop();
		}

		function onNodeMouseOut(event) {
			var span;

			var node = getEventNode(event);
			if(!node) return;

			span = document.getElementById('s' + node.id);
			if(span) span.style.textDecoration = 'none';

			if(property.editable == 'true') {
				drag_control.dragging(event);
			}
		}

		function sort(event) {
			var event_obj = bframe.getEventSrcElement(event);
			var th = bframe.searchParentByTagName(event_obj, 'th');
			var sort_obj = bframe.searchNodeByName(th, 'sort_key');
			sort_key = sort_obj.value;
			reloadTree();
		}
	}
