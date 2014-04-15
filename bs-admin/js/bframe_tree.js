/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeTreeInit);

	function bframeTreeInit(){
	    var div = document.getElementsByTagName('div');

	    for(var i=0; i<div.length; i++) {
			if(bframe.checkClassName('bframe_tree', div[i])) {
				bframe_tree = new bframe.tree(div[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.tree
	// 
	// -------------------------------------------------------------------------
	bframe.tree = function(target) {
		var self = this;
		var target_id = bframe.getID(target);

		var	response_wait = false;
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
		var new_node = new currentNodeControl;
		var current_node = new currentNodeControl;
		var selected_node = new currentNodeControl;
		var current_edit_node;
		var eventSrcObject;

		var drag_control;

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

		var pain;
		var pain_disp_change;
		var pain_disp_change_select
		var upload_button;
		var upload_button_style_display;

		var opener = window.opener;

		init();

		function init() {
			var param;

			param = 'terminal_id='+terminal_id+'&class=bframe_tree&id='+target.id;
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse(){
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				response_wait = false;
				if(property.editable == 'true' || property.sortable == 'true') {
					setDragControl();
					setContextMenu();
					context_menu.setFilter(context_filter);
					setTrashContextMenu();
					setEventHandler();
				}
				getUploadButton();
				getPain();
				getNodeList('');
			}
		}

		function setDragControl() {
			drag_control = new dragControl(top.window);
		}

		function getPain() {
			if(property.relation && property.relation.pain) {
			    pain = document.getElementById(property.relation.pain.id);
				if(property.editable == 'true' || property.sortable == 'true') {
					pain.oncontextmenu=showContextMenu;
					pain.onclick = resetSelectedObject;
				}
				if(property.relation.disp_change) {
					pain_disp_change = document.getElementById(property.relation.disp_change.id);
					setDispChange();
				}
			}
		}

		function getUploadButton() {
			if(property.upload && property.upload.button) {
			    upload_button = document.getElementById(property.upload.button);
				if(property.editable == 'true' || property.sortable == 'true') {
					upload_button_style_display = upload_button.style.display;
				}
				else {
					upload_button.style.display = 'none';
				}
			}
		}

		function setDispChange() {
			pain_disp_change_select = document.createElement('select');

			pain_disp_change_select.options[0] = new Option(property.disp_change.options[0].title, property.disp_change.options[0].value);
			pain_disp_change_select.options[1] = new Option(property.disp_change.options[1].title, property.disp_change.options[1].value);

			pain_disp_change.appendChild(pain_disp_change_select);

			bframe.addEventListner(pain_disp_change_select, 'change', change_disp_mode);
			if(property.disp_change.selectedIndex) {
				pain_disp_change_select.selectedIndex = property.disp_change.selectedIndex;
			}
		}

		function setEventHandler() {
			bframe.addEventListner(window, 'beforeunload', cleanUp);

			// set event handller
			bframe.addEventListnerAllFrames(top, 'load', hideContextMenuAllFrames);
			bframe.addEventListnerAllFrames(top, 'click', hideContextMenu);
			bframe.addEventListnerAllFrames(top, 'mouseup', drag_control.onMouseUp);
			bframe.addEventListnerAllFrames(top, 'mousedown', saveName);
			bframe.addEventListnerAllFrames(top, 'keydown', keydown);
		}

		function keydown(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
			}
			else {
				var keycode = event.keyCode;
			}
			switch(keycode) {
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
				if(event.ctrlKey) {
					if(!current_edit_node) {
						selectAll();
					}
					bframe.stopPropagation(event);
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
			if(typeof bframe == 'undefined' || !bframe){
				return;
			}
			bframe.addEventListnerAllFrames(top, 'click', hideContextMenu);
			bframe.addEventListnerAllFrames(top, 'mouseup', drag_control.onMouseUp);
			bframe.addEventListnerAllFrames(top, 'mousedown', saveName);
			bframe.addEventListnerAllFrames(top, 'keydown', keydown);
		}

		function cleanUp() {
			if(typeof bframe == 'undefined' || !bframe){
				return;
			}
			//

			drag_control.cleanUp();
			bframe.removeEventListnerAllFrames(top, 'load', hideContextMenuAllFrames);
			bframe.removeEventListnerAllFrames(top, 'click', hideContextMenu);
			bframe.removeEventListnerAllFrames(top, 'mouseup', drag_control.onMouseUp);
			bframe.removeEventListnerAllFrames(top, 'mousedown', saveName);
			bframe.removeEventListnerAllFrames(top, 'keydown', keydown);
		}
		this.cleanUp = cleanUp;

		function setContextMenu(){
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
			context_menu.createElementFromObject(property.context_menu, this);
			context_menu_element.size = context_menu.getElementSize();
			context_menu_height = context_menu_element.size.height;

			context_menu.disableElement('pasteNode');
		}

		function showContextMenu(event) {
			if(context_menu.getLength() > 0 && !response_wait) {
				eventSrcObject = bframe.getEventSrcElement(event);
				if(eventSrcObject == pain) {
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
				if(!bframe.searchNodeById(trash, nodes.id) && !bframe.searchParentById(event_node, 'ttrash')) {
					var position = context_menu.getPosition(event);
					var frame_offset = bframe.getFrameOffset(window, context_menu_frame);
					position.left += frame_offset.left;
					position.top += frame_offset.top;

					context_menu.positionAbsolute(position);
					context_menu.show();
				}
			}
			bframe.addEventListner(document, 'DOMMouseScroll', bframe.cancelEvent);
			bframe.addEventListner(document, 'mousewheel', bframe.cancelEvent);

			trash_context_menu.hide();
			return false;
		}

		function setTrashContextMenu(){
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
			trash_context_menu.createElementFromObject(property.trash_context_menu, this);
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
			bframe.addEventListner(document, 'DOMMouseScroll', bframe.cancelEvent);
			bframe.addEventListner(document, 'mousewheel', bframe.cancelEvent);
			context_menu.hide();

			return false;
		}

		context_filter = function() {
			switch(selected_node.id()) {
			case 'troot':
				context_menu.disableElement('cutNode');
				context_menu.disableElement('copyNode');
				context_menu.disableElement('deleteNode');
				context_menu.disableElement('editName');
				context_menu.enableElement('createNode');
				break;

			default:
				context_menu.enableElement('cutNode');
				context_menu.enableElement('copyNode');
				context_menu.enableElement('deleteNode');
				context_menu.enableElement('editName');
				context_menu.enableElement('createNode');

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
			}

			if(node.node_type == 'page') {
				context_menu.enableElement('preview');
			}
			else {
				context_menu.disableElement('preview');
			}

			if(selected_node.place() == 'pain') {
				context_menu.disableElement('createNode');
			}

			if(eventSrcObject == pain) {
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
			var node_name = selected_node.name();
			message = message.replace('%NODE_NAME%', node_name);
			message = message.replace('%NODE_COUNT%', node_count);
			callback.setTmpConfirmMessage(message);
		}

		function hideContextMenu(event){
			if(!context_menu || !document || typeof bframe == 'undefined' || !bframe) return;

			bframe.removeEventListner(document, 'DOMMouseScroll', bframe.cancelEvent);
			bframe.removeEventListner(document, 'mousewheel', bframe.cancelEvent);

			context_menu.hide();
			trash_context_menu.hide();
		}

		function getNodeList(id) {
			var param;

			param = 'terminal_id='+terminal_id;
			if(id) {
				param+= '&node_id='+id.substr(1);
			}
			if(property.relation && property.relation.disp_change) {
				param+= '&disp_mode='+pain_disp_change_select.selectedIndex;
			}
			httpObj = createXMLHttpRequest(showNode);

			eventHandler(httpObj, property.module, property.file, property.method.getNodeList, 'POST', param);
			target.style.cursor = 'wait';
			if(pain) pain.style.cursor = 'wait';
			if(obj = document.getElementById('a' + id)) {
				obj.style.cursor = 'wait';
			}

			response_wait = true;
		}

		function showNode() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				try {
					node_number = 0;
					current_edit_node = '';
					response = eval('('+httpObj.responseText+')');
					var node_info = response.node_info;
				}
				catch(e) {
					alert(property.session_timeout);
					target.style.cursor = 'default';
					if(pain) pain.style.cursor = 'default';
					response_wait = false;
					return;
				}
				if(response.current_node) {
					current_node.set('t'+response.current_node);
					if(!selected_node.id()) {
						selected_node.set('t'+response.current_node);
					}
				}
				if(response.selected_node) {
					selected_node.set('p'+response.selected_node);
				}

				if(target.hasChildNodes()) {
					target.removeChild(target.firstChild);
				}

				if(pain && pain.hasChildNodes()) {
					pain.removeChild(pain.firstChild);
				}

				var ul = document.createElement('ul');
				target.appendChild(ul);
				ul.className = 'root';

				if(property.root_name) {
					node_info[0].node_name = property.root_name;
				}
				_showNode(ul, node_info[0]);

				if(node_info[1]) {
					//trash box
					if(property.trash_name) {
						node_info[1].node_name = property.trash_name;
					}
					_showNode(ul, node_info[1], true);
				}

				if(!selected_node.object()) {
					selected_node.set(current_node.id());
				}
				selected_node.setColor('selected');
				current_node.setColor('current');

				if(upload_button) {
					var node = current_node.object();
					if(bframe.searchParentById(node, 'ttrash')) {
						upload_button.style.display = 'none';
					}
					else {
						upload_button.style.display = upload_button_style_display;
					}
				}

				target.style.cursor = 'default';
				if(pain) pain.style.cursor = 'default';
				response_wait = false;
				node_info = null;

				if(response.message) {
					alert(response.message);
				}
			}
		}

		function _showNode(parent_node, node_info, trash) {
			li = createNodeObject(parent_node, node_info, 'tree', trash);
			if(node_info['new_node']) {
				if(eventSrcObject == pain) {
					selected_node.set('p'+node_info.node_id);
					new_node.set('p'+node_info.node_id);
				}
				else {
					selected_node.set('t'+node_info.node_id);
					new_node.set('t'+node_info.node_id);
				}
				editName();
			}

			if(node_info.children) {
				if(pain) {
					if(current_node.id() && node_info.node_id == current_node.id().substr(1)) {
						// create div
						var div = document.createElement('div');
						pain.appendChild(div);

						if(pain_disp_change && pain_disp_change_select.options[pain_disp_change_select.selectedIndex].value == 'detail') {
							// detail
							div.className = 'detail';
							var ptable = document.createElement('table');
							div.appendChild(ptable);

							var ptbody = document.createElement('tbody');
							ptable.appendChild(ptbody);

							ptbody.id = 'tt' + node_info.node_id;
							ptbody.name = 'nodes';
							ptbody.className = 'pain';

							// title
							createDetailTitle(ptbody);

							for(var i=0 ; i < node_info.children.length ; i++) {
								createDetailNodeObject(ptbody, node_info.children[i]);
							}
						}
						else {
							// thumb nail
							div.className = 'thumbs';
							var pul = document.createElement('ul');
							pul.id = 'uu' + node_info.node_id;
							pul.name = 'nodes';
							div.appendChild(pul);
							for(var i=0 ; i < node_info.children.length ; i++) {
								createNodeObject(pul, node_info.children[i], 'pain', trash);
							}
						}
					}
					var ul = document.createElement('ul');
					ul.id = 'tu' + node_info.node_id;
					ul.name = 'nodes';
					li.appendChild(ul);

					for(var i=0 ; i < node_info.children.length ; i++) {
						if(node_info.children[i].node_type == 'file') {
							continue;
						}
						_showNode(ul, node_info.children[i], trash);
					}
				}
				else {
					var ul = document.createElement('ul');
					ul.id = 'tu' + node_info.node_id;
					ul.name = 'nodes';
					li.appendChild(ul);

					for(var i=0 ; i < node_info.children.length ; i++) {
						_showNode(ul, node_info.children[i], trash);
					}
				}
			}
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
		}

		function closeNodeResponse() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
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
				var child = bframe.serachNodeByName(li, 'nodes');
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

		change_disp_mode = function() {
			getNodeList(current_node.id());
		}

		cutNode = function() {
			if(clipboard.target) delete clipboard.target;
			clipboard.target = new Array();

			for(var i=0 ; i<selected_node.length() ; i++) {
				clipboard.target[i] = selected_node.id(i);
			}
			clipboard.mode = 'cut';
			context_menu.enableElement(context_paste_index);
		}

		copyNode = function() {
			if(clipboard.target) delete clipboard.target;
			clipboard.target = new Array();

			for(var i=0 ; i<selected_node.length() ; i++) {
				clipboard.target[i] = selected_node.id(i);
			}
			clipboard.mode = 'copy';
			context_menu.enableElement(context_paste_index);
		}

		pasteNode = function() {
			if(clipboard.target) {
				var param;

				param = 'terminal_id='+terminal_id+'&mode='+clipboard.mode;
				for(var i=0 ; i < clipboard.target.length ; i++) {
					param+= '&source_node_id[' + i + ']=' + encodeURIComponent(clipboard.target[i].substr(1));
				}
				param+= '&destination_node_id='+encodeURIComponent(selected_node.id().substr(1));
				httpObj = createXMLHttpRequest(showNode);
				eventHandler(httpObj, property.module, property.file, property.method.pasteNode, 'POST', param);
				response_wait = true;
			}
		}

		pasteAriasNode = function() {
			if(clipboard.target) {
				var param;

				param = 'terminal_id='+terminal_id+'&mode=arias';
				for(var i=0 ; i < clipboard.target.length ; i++) {
					param+= '&source_node_id='+encodeURIComponent(clipboard.target[i].substr(1));
				}
				param+= '&destination_node_id='+encodeURIComponent(selected_node.id().substr(1));
				httpObj = createXMLHttpRequest(showNode);
				eventHandler(httpObj, property.module, property.file, property.method.pasteAriasNode, 'POST', param);
				response_wait = true;
			}
		}

		deleteNode = function() {
			var param;

			if(property.relation && property.relation.deleteNode) {
				if(selected_node.id() == current_node.id()) {
					var rel = bframe.getFrameByName(top, property.relation.deleteNode.frame);
					rel.location.href = property.relation.deleteNode.url+'&node_id='+encodeURIComponent(selected_node.id().substr(1))+'&in_trash=true';
				}
			}

			param = 'terminal_id='+terminal_id;
			for(var i=0 ; i < selected_node.length() ; i++) {
				param+= '&delete_node_id[' + i + ']='+encodeURIComponent(selected_node.id(i).substr(1));
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

		truncateNode = function() {
			var param;

			if(property.relation && property.relation.truncateNode) {
				if(current_node.isParent('ttrash')) {
					var rel = bframe.getFrameByName(top, property.relation.truncateNode.frame);
					rel.location.href = property.relation.truncateNode.url+'&node_id='+encodeURIComponent(selected_node.id().substr(1));
				}
			}

			param = 'terminal_id='+terminal_id+'&node_id='+encodeURIComponent(selected_node.id().substr(1));
			httpObj = createXMLHttpRequest(showNode);
			eventHandler(httpObj, property.module, property.file, property.method.truncateNode, 'POST', param);
			response_wait = true;
		}

		editName = function() {
			var sn = selected_node.object();
			if(!sn) return;
			if(sn.id == 'troot' || sn.id == 'ttrash') return;
			if(!current_edit_node) {
				current_edit_node = sn;
			}

			var span = bframe.serachNodeByName(current_edit_node, 'node_span');

			var input = document.createElement('input');
			input.name = 'node_input';

			if(span.offsetWidth < 60) {
				input.style.width = (span.offsetWidth + 10) + 'px';
			}
			else {
				input.style.width = (span.offsetWidth - 10) + 'px';
			}

			var node_type = document.getElementById('nt' + current_edit_node.id);

			// ime mode
			if(!property.imeMode) {
				input.style.imeMode = 'disabled';
			}
			if(property.icon[node_type.value] && property.icon[node_type.value].ime == 'true') {
				input.style.imeMode = '';
			}

			current_edit_save_value = span.firstChild.nodeValue;
			input.value = span.firstChild.nodeValue;
			span.firstChild.nodeValue = '';
			span.className = 'edit';
			span.appendChild(input);
			input.select();
			input.focus();
			bframe.addEventListner(input, 'keydown', enterName);
		}

		enterName = function(event) {
			if(window.event) {
				var keycode = window.event.keyCode;
			}
			else {
				var keycode = event.keyCode;
			}

			switch(keycode) {
			case 9:  //tab
			case 13: //enter
				_saveName(event);
				break;
			}
		}

		function saveName(event) {
			if(typeof bframe == 'undefined' || !bframe) return;

			// exception
			var obj = bframe.getEventSrcElement(event);
			if(obj && obj.tagName.toLowerCase() == 'input') return;

			_saveName(event);
		}

		function _saveName(event) {
			if(!current_edit_node) return;

			var span = bframe.serachNodeByName(current_edit_node, 'node_span');
			var input = bframe.serachNodeByName(current_edit_node, 'node_input');

			if(current_edit_save_value == input.value.trim()) {
				if(current_edit_node.id == current_node.id()) {
					current_edit_node = '';
					selected_node.setColor('current');
				}
				else if(current_edit_node.id == selected_node.id()) {
					current_edit_node = '';
					selected_node.setColor('selected');
				}
				span.firstChild.nodeValue = current_edit_save_value;
				span.removeChild(input);
			}
			else {
				var param;

				param = 'terminal_id='+terminal_id+'&node_id='+encodeURIComponent(current_edit_node.id.substr(1));
				param+= '&node_name='+encodeURIComponent(input.value);
				httpObj = createXMLHttpRequest(showNode);
				eventHandler(httpObj, property.module, property.file, property.method.saveName, 'POST', param);
				response_wait = true;
			}
		}

		createNode = function(p) {
			var param;

			param = 'terminal_id='+terminal_id;
			param+= '&'+p+'&destination_node_id='+encodeURIComponent(selected_node.id().substr(1));
			httpObj = createXMLHttpRequest(showNode);
			eventHandler(httpObj, property.module, property.file, property.method.createNode, 'POST', param);
			response_wait = true;
		}

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

			if(pain || !property.relation) {
				selected_node.set(node_id);
				selected_node.setColor('selected');
			}

			if(property.onclick) {
				var func = property.onclick.script;
				var node_span = document.getElementById('s'+node_id);
				var node_type = document.getElementById('nt'+node_id);
				var node_name = node_span.innerHTML;
				window[func](node_id.substr(1), node_name, node_type.value);
			}
		}

		function setNodeIdAfterUnload() {
			current_node.setNodeIdAfterUnload();
			selected_node.setNodeIdAfterUnload();
		}

		function selectObject(node_id) {
			selected_node.set(node_id);
			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function selectAll() {
			var node_id = current_node.id().substr(1);
			var pain = document.getElementById('uu'+node_id);
			if(!pain) return;

			for(var n=pain.firstChild; n; n = n.nextSibling) {
				if(n == pain.firstChild) {
					selected_node.set(n.id);
				}
				else {
					selected_node.add(n.id);
				}
			}

			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function resetSelectedObject() {
			selected_node.set();
			current_node.setColor('current');
		}

		function addSelectedObject(node_id) {
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

		function addRangeSelectedObject(node_id) {
			var top_id = selected_node.id(0);
			if(!top_id) return;
			var last_id = selected_node.last_id();
			if(!last_id) return;

			var top_ds = document.getElementById('ds'+top_id);
			var last_ds = document.getElementById('ds'+last_id);
			var node_ds = document.getElementById('ds'+node_id);

			if(parseInt(node_ds.value) < parseInt(top_ds.value)) {
				from_node_id = node_id;
				to_node_id = last_id;
			}
			else if(parseInt(node_ds.value) > parseInt(last_ds.value)) {
				from_node_id = top_id;
				to_node_id = node_id;
			}
			else {
				from_node_id = top_id;
				to_node_id = node_id;
			}

			var from_node = document.getElementById(from_node_id);
			var to_node = document.getElementById(to_node_id);

			selected_node.set();

			for(var n=from_node; n; n = n.nextSibling) {
				selected_node.add(n.id);
				if(n == to_node) break;
			}

			selected_node.setColor('selected');
			current_node.setColor('current');
		}

		function selectResource(node_id) {
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
							var  suffix = node_name.substring(node_name.lastIndexOf('.')+1,node_name.length);
							func = property.method.selectFile[suffix];
							if(!func) {
								func = property.method.selectFile.default;
							}
							switch(func) {
							case 'download':
								download();
								break;

							default:
								openEditor(node_id);
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
				window[func](node_id.substr(1), node_name, node_type.value);
			}
		}

		function openEditor(node_id) {
			var url = 'index.php';
			var param = '?module='+property.editor.module+
						'&page='+property.editor.file+
						'&method='+property.editor.method+
						'&terminal_id='+terminal_id+
						'&node_id='+encodeURIComponent(node_id.substr(1));

			var settings='width=1000,height=600,scrollbars=yes,resizable=yes,menubar=no,location=no,toolbar=no,directories=no,status=no,dependent=no';
			var editor = window.open(url+param, node_id, settings);
			editor.focus();
		}

		function insertImageToCKEditor(node_id) {
			var funcNum = bframe.getUrlParam('CKEditorFuncNum');
			var fileUrl = property.root_url + document.getElementById('p' + node_id).value;
			opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
			window.close();
		}

		function insertResourceFile(node_id) {
			if(property.target_id || property.target) {
				var image_size_obj = document.getElementById('is' + node_id);
				var img_size;
				if(image_size_obj) {
					img_size = image_size_obj.value;
				}
				var path = document.getElementById('p' + node_id).value;
				insertIMG(property.root_path, path, img_size, property.target, property.target_id);
			}
		}

		function insertFile(node_id) {
			if(property.target_id || property.target) {
				var image_size_obj = document.getElementById('is' + node_id);
				var img_size;
				if(image_size_obj) {
					img_size = image_size_obj.value;
				}

				insertIMG(property.root_path, node_id.substr(1), img_size, property.target, property.target_id);
			}
		}

		this.getCurrentFolderId = function() {
			return current_node.id();
		}

		this.getNodeList = function(id) {
			getNodeList(id);
		}

		download = function() {
			var id;

			if(id = selected_node.id()) {
				iframe = document.getElementById('download_iframe');
				if(!iframe) {
					var iframe = document.createElement('iframe');
					iframe.id = 'download_iframe';
					iframe.name = 'download_iframe';
					document.body.appendChild(iframe);
				}
				download_iframe.location.href = property.relation.download.url+'&node_id='+encodeURIComponent(id.substr(1));
			}
		}

		preview = function() {
			var id;

			if(id = selected_node.id()) {
				woption = 'menubar=yes,toolbar=yes,directories=yes,status=yes,scrollbars=yes,resizable=yes';
				var w = window.open(property.relation.preview.url+'&node_id='+encodeURIComponent(id.substr(1)), 'preview', woption);
				if(w) {
					w.focus();
				}
			}
		}

		this.getSelecteNodes = function() {
			return selected_node.nodes();
		}

		// -------------------------------------------------------------------------
		// class currentNodeControl
		// -------------------------------------------------------------------------
		function currentNodeControl() {
			var self = this;
			var current_place;
			var current_node = new Array();
			var before_unload_node_id;

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

			this.name = function() {
				if(!current_node[0]) return;

				var node = self.object();
				if(node) {
					var span = bframe.serachNodeByName(node, 'node_span');
					return span.innerHTML;
				}
			}

			this.exists = function(node_id) {
				for(var i=0 ; i < current_node.length ; i++) {
					if(current_node[i].id.substr(1) == node_id.substr(1)) {
						return true;
					}
				}

				return false;
			}

			this.isChild = function(node_id) {
				for(var i=0 ; i < current_node.length ; i++) {
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
						var span = bframe.serachNodeByName(node, 'node_span');
						span.className = 'node-name';
					}
				}
				self.set(before_unload_node_id);
				self.setColor('current');
			}

			this.set = function(node_id) {
				for(var i=0 ; i < current_node.length ; i++) {
					if(current_edit_node.id == current_node[i].id) return;
					var node = self.object(i);
					if(node) {
						var span = bframe.serachNodeByName(node, 'node_span');
						span.className = 'node-name';
					}
				}
				current_node.length = 0;
				if(node_id) {
					var pt = document.getElementById('p' + node_id);
					var file_path = pt ? pt.value : '';
					var nn = document.getElementById('nn' + node_id);
					var n = nn ? nn.value : 0;
					current_node[0] = {id: node_id, path: file_path, node_number: n};
				}
			}

			this.add = function(node_id) {
				var pt = document.getElementById('p' + node_id);
				var file_path = pt ? pt.value : '';
				var nn = document.getElementById('nn' + node_id);
				var n = nn ? nn.value : 0;
				for(var i=0 ; i < current_node.length ; i++) {
					if(current_node[i].id == node_id) {
						return;
					}
					if(parseInt(current_node[i].node_number) > parseInt(n)) {
						break;
					}
				}
				current_node.splice(i, 0, {id: node_id, path: file_path, node_number: n});
			}

			this.del = function(node_id) {
				for(var i=0 ; i < current_node.length ; i++) {
					if(current_node[i].id == node_id) {
						current_node.splice(i, 1);
						break;
					}
				}
			}

			this.place = function() {
				return current_node[0].id.substr(0, 1) == 't' ? 'tree' : 'pain';
			}

			this.setColor = function(mode) {
				if(!current_node[0]) return;

				for(var i=0 ; i < current_node.length ; i++) {
					if(current_edit_node.id == current_node[i].id) continue;
					var node = self.object(i);
					if(node) {
						var span = bframe.serachNodeByName(node, 'node_span');

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
					var span = bframe.serachNodeByName(node, 'node_span');
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
			var clone_node;
			var drop_forbidden;
			var start_position = {};
			var frame_offset;
			var window_offset;
			var pain_flag = false;
			var div_overwrap, div_tree, div_pain;
			var clone_control;
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
			}

			div_tree = frame.document.getElementById('bframe_tree_drag_tree_div');
			if(!div_tree) {
				div_tree = frame.document.createElement('div');
				div_tree.id = 'bframe_tree_drag_tree_div';
				frame.document.body.appendChild(div_tree);
			}

			div_pain = frame.document.getElementById('bframe_tree_drag_pain_div');
			if(!div_pain) {
				div_pain = frame.document.createElement('div');
				div_tree.id = 'bframe_tree_drag_pain_div';
				div_pain.className = 'bframe_pain';
				frame.document.body.appendChild(div_pain);
			}

			clone_node = frame.document.createElement('div');
			clone_img_span = frame.document.createElement('span');
			clone_img_span.className = 'img-border';

			clone_img = frame.document.createElement('img');
			clone_span = frame.document.createElement('span');
			clone_span.className = 'node-name';
			clone_text = frame.document.createTextNode('');
			clone_span.appendChild(clone_text);

			clone_node.appendChild(clone_img_span);
			clone_img_span.appendChild(clone_img);
			clone_node.appendChild(clone_span);
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
				if(div_overwrap) parent.document.body.removeChild(div_overwrap);
			}

			function setEventHandler() {
				bframe.addEventListner(window, 'beforeunload', cleanUp);

				// set event handller
				bframe.addEventListnerAllFrames(top, 'load', setEventHandlerAllFrames);
				bframe.addEventListnerAllFrames(top, 'mousemove', onMouseMove);
				bframe.addEventListnerAllFrames(top, 'mouseup', onMouseUp);
			}

			function setEventHandlerAllFrames() {
				if(typeof bframe == 'undefined' || !bframe){
					return;
				}
				bframe.addEventListnerAllFrames(top, 'mousemove', onMouseMove);
				bframe.addEventListnerAllFrames(top, 'mouseup', onMouseUp);
			}

			function cleanUp() {
				if(typeof bframe == 'undefined' || !bframe){
					return;
				}
				bframe.removeEventListnerAllFrames(top, 'load', setEventHandlerAllFrames);
				bframe.removeEventListnerAllFrames(top, 'mousemove', onMouseMove);
				bframe.removeEventListnerAllFrames(top, 'mouseup', onMouseUp);
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
				if(bframe.searchParentById(event_obj, 'bframe_pain') && pain_disp_change_select.options[pain_disp_change_select.selectedIndex].value != 'detail') {
					pain_flag = true;
					div_pain.appendChild(clone_node);
					clone_node.className = 'clone_node_pain';
					drop_forbidden.src = property.icon.forbidden_big.src;
				}
				else {
					pain_flag = false;
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
				if(!e.ctrlKey && !e.shiftKey && !selected_node.exists(node_id)) {
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

				var div = bframe.serachNodeByName(node, 'node_div');
				var span = bframe.serachNodeByName(node, 'node_span');
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

				if(property.sortable == 'true') {
					if(property.relation && property.relation.pain && bframe.searchParentById(node, property.relation.pain.id) &&
						pain_disp_change_select.options[pain_disp_change_select.selectedIndex].value != 'detail') {
						// destination is in pain and display mode is icon style
						if(node.node_class == 'leaf') {
							if(pageX < position.left) {
								destination_node_id = '';
								destination_node = '';
							}
							else if(pageX < position.left + div.offsetWidth/2) {
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
								else {
									destination_node_id = '';
									destination_node = '';
								}
							}
							else {
								destination_node_id = '';
								destination_node = '';
							}
						}
						else {
							if(pageX < position.left) {
								destination_node_id = '';
								destination_node = '';
							}
							else if(pageX < position.left + div.offsetWidth/3) {
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
								else {
									destination_node_id = '';
									destination_node = '';
								}
							}
							else {
								destination_node_id = '';
								destination_node = '';
							}
						}
					}
					else {
						// destination is in tree or detail list in pain
						if(node.node_class == 'leaf') {
							if(pageY < position.top) {
								destination_node_id = '';
								destination_node = '';
							}
							else if(pageY < position.top + div.offsetHeight/2) {
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
								else {
									destination_node_id = '';
									destination_node = '';
								}
							}
							else {
								destination_node_id = '';
								destination_node = '';
							}
						}
						else {
							if(pageY < position.top) {
								destination_node_id = '';
								destination_node = '';
							}
							else if(pageY < position.top + div.offsetHeight/3 && node.id != 'troot') {
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
								else {
									destination_node_id = '';
									destination_node = '';
								}
							}
							else {
								destination_node_id = '';
								destination_node = '';
							}
						}
					}
				}
				else {
					// sort is disabled
					if(property.relation && property.relation.pain && bframe.searchParentById(node, property.relation.pain.id)) {
						// destination is in pain
						if(node.node_class != 'leaf') {
							if(pageX < position.left) {
								destination_node_id = '';
								destination_node = '';
							}
							else if(pageX < position.left + div.offsetWidth) {
								destination_node_id = node.id;
								destination_node = node;
								drag_type = 'move';
							}
							else {
								destination_node_id = '';
								destination_node = '';
							}
						}
					}
					else {
						// destination is in tree
						if(node.node_class != 'leaf') {
							if(pageY < position.top) {
								destination_node_id = '';
								destination_node = '';
							}
							else if(pageY < position.top + div.offsetHeight) {
								destination_node_id = node.id;
								destination_node = node;
								drag_type = 'move';
							}
							else {
								destination_node_id = '';
								destination_node = '';
							}
						}
					}
				}

				if(destination_node && !selected_node.exists(destination_node_id)) {
					if(drag_type == 'move') {
						span.className = 'node-name selected';
					}
					else {
						var destination_div = bframe.serachNodeByName(destination_node, 'node_div');
						destination_div.className = 'tree selected';
					}
				}

				// if the destination node is source node's child and in trash, drag is disabled
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

				if(drag_type == 'move' && selected_node.place() == 'pain' && destination_node_id == current_node.id()) return false;
				if(destination_node_id == 'ttrash') {
					if(bframe.searchNodeById(trash, source_node_id)){
						return false;
					}
					return true;
				}
				if(bframe.searchParentById(destination_node, 'ttrash')) return false;
				if(bframe.searchParentById(destination_node, 'utrash')) return false;
				if(selected_node.exists(destination_node_id)) return false;
				if(selected_node.isChild(destination_node_id)) return false;

				return true;
			}

			this.dragStop = function() {
				clone_node.style.top = 0;
				clone_node.style.left = 0;
				clearBorder();

				if(!drag_status) return;

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

					for(var i=0 ; i < selected_node.length() ; i++) {
						param+= '&source_node_id[' + i + ']='+encodeURIComponent(selected_node.id(i).substr(1));
					}

					param+= '&destination_node_id='+encodeURIComponent(destination_node_id.substr(1));
					httpObj = createXMLHttpRequest(showNode);
					eventHandler(httpObj, property.module, property.file, property.method.pasteNode, 'POST', param);
					target.style.cursor = 'wait';
					if(pain) pain.style.cursor = 'wait';
					response_wait = true;
				}

				if(drag_type == 'sort') {
					var param, p;

					if(destination_node_id == 'trash') {
						var source = document.getElementById(source_node_id);
						var parent = document.getElementById('uroot');

						p='';
						var i, j;
						for(i=0, j=0 ; i< parent.childNodes.length; i++) {
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
						for(j=0 ; i< parent.childNodes.length; i++) {
							if(parent.childNodes[i].id == destination_node_id) {
								for(k=0 ; k< selected_node.length() ; k++) {
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
					for(var i=0 ; i < selected_node.length() ; i++) {
						param+= '&source_node_id[' + i + ']='+encodeURIComponent(selected_node.id(i).substr(1));
					}

					httpObj = createXMLHttpRequest(showNode);
					eventHandler(httpObj, property.module, property.file, property.method.updateDispSeq, 'POST', param);
					target.style.cursor = 'wait';
					if(pain) pain.style.cursor = 'wait';
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
				var div = bframe.serachNodeByName(node, 'node_div');
				var span = bframe.serachNodeByName(node, 'node_span');

				div.className = 'tree';
				span.className = 'node-name';
			}
		}

		// -------------------------------------------------------------------------
		// class createNodeObject
		// -------------------------------------------------------------------------
		function createNodeObject(parent, config, place, trash) {
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
			parent.appendChild(li);

			li.className = 'tree-list';
			li.id = node_id;

			li.node_class = config.node_class;
			li.node_type = config.node_type;
			li.utime = config.update_datetime;

			div = document.createElement('div');
			div.name = 'node_div';
			div.id = 'd' + node_id;
			div.className = 'tree';

			li.appendChild(div);
 
			control = document.createElement('img');
			control.id = 'c' + node_id;
			control.name = 'node_control';
			div.appendChild(control);

			control.className = 'control';

			a = document.createElement('a');
			a.style.cursor = 'pointer';
			a.id = 'a' + node_id;
			div.appendChild(a);

			if(place == 'tree') {
				a.onclick = selectNode;
				a.ondblclick = selectResourceNode;
				if((pain && config.folder_count > 0 ) || (!pain && config.node_count > 0)) {
					if(config.children) {
						control.src = property.icon.minus.src;
					}
					else {
						control.src = property.icon.plus.src;
					}
					control.onmousedown = openNode;
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
			a.appendChild(img_span);

			obj_img = document.createElement('img');
			obj_img.id = 'i' + node_id;
			img_span.appendChild(obj_img);

			if(place == 'pain') {
				a.style.cursor = 'pointer';

				if(config.node_class == 'folder') {
					obj_img.src = property.icon.pain.folder.src;
				}
				else {
					a.ondblclick = selectResourceNode;
					suffix = config.path.substring(config.path.lastIndexOf('.')+1,config.path.length);

					switch(suffix.toLowerCase()) {
					case 'js':
						obj_img.src = property.icon.pain.js.src;
						break;
					case 'swf':
						obj_img.src = property.icon.pain.swf.src;
						break;
					case 'css':
						obj_img.src = property.icon.pain.css.src;
						break;
					case 'pdf':
						obj_img.src = property.icon.pain.pdf.src;
						break;

					case 'jpg':
					case 'jpeg':
					case 'gif':
					case 'png':
						if(property.icon.pain[suffix.toLowerCase()]) {
							obj_img.src = property.icon.pain[suffix.toLowerCase()].src;
						}
						else {
							if(config.thumbnail_image_path) {
								obj_img.src = config.thumbnail_image_path + '?' + config.update_datetime;
							}
							else {
								var file_name = config.path.substring(config.path.lastIndexOf('/')+1,config.path.length);
								var dir = config.path.substring(0, config.path.lastIndexOf('/')+1);
								var extension = file_name.substring(file_name.lastIndexOf('.')+1, file_name.length);
								obj_img.src = property.thumb_path + property.thumb_prefix + config.contents_id + '.' + extension + '?' + config.update_datetime;
							}
							a.title = 'size:' + config.image_size + '\n' + 'date:' + config.create_datetime;
						}
						break;
					default:
						obj_img.src = property.icon.pain.misc.src;
						break;
					}
				}
			}
			else {
				if(config.node_type == 'folder' && config.children && ((pain && config.folder_count > 0 ) || (!pain && config.node_count > 0))) {
					obj_img.src = property.icon['folder_open'].src;
				}
				else {
					obj_img.src = property.icon[config.node_type].src;
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

			span.className = 'node-name';
			text = document.createTextNode(config.node_name);
			span.appendChild(text);

			return li;
		}

		// -------------------------------------------------------------------------
		// class createDetailTitle
		// -------------------------------------------------------------------------
		function createDetailTitle(parent) {
			var tr, th, span, text;

			tr = document.createElement('tr');
			parent.appendChild(tr);

			for(var i=0 ; i<property.detail.header.length ; i++) {
				th = document.createElement('th');
				text = document.createTextNode(property.detail.header[i].title);
				th.className = property.detail.header[i].className;
				span = document.createElement('span');

				span.appendChild(text);
				th.appendChild(span);
				tr.appendChild(th);
			}
		}

		// -------------------------------------------------------------------------
		// class createDetailNodeObject
		// -------------------------------------------------------------------------
		function createDetailNodeObject(parent, config) {
			if(!config.node_id) return;

			var tr, div, ul, li, control, a, obj_img, span, text, input;

			var node_id = 'p'+config.node_id;

			tr = document.createElement('tr');
			tr.id = node_id;
			tr.name = 'node';
			tr.utime = config.update_datetime;
			tr.node_class = config.node_class;
			parent.appendChild(tr);

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

			text = document.createTextNode(config.node_name);
			span.appendChild(text);

			// update-datetime
			td = document.createElement('td');
			td.className = 'update-datetime';
			tr.appendChild(td);

			span = document.createElement('span');
			span.className = 'update-datetime';
			td.appendChild(span);
			text = document.createTextNode(config.create_datetime);
			span.appendChild(text);

			// file-size
			td = document.createElement('td');
			td.className = 'file-size';
			tr.appendChild(td);

			span = document.createElement('span');
			span.className = 'file-size';
			td.appendChild(span);
			if(config.file_size) {
				text = document.createTextNode(config.file_size);
				span.appendChild(text);
			}

			// image-size
			td = document.createElement('td');
			tr.appendChild(td);

			span = document.createElement('span');
			span.className = 'image-size';
			td.appendChild(span);
			if(config.image_size) {
				text = document.createTextNode(config.image_size);
				span.appendChild(text);
			}

			return;
		}

		function openNode(event) {
			if(bframe.getButton(event) != 'L') return;

			var obj = bframe.getEventSrcElement(event);
			var node = bframe.searchParentByName(obj, 'node');
			var node_id = node.id;

			if(bframe.getFileName(obj.src) == bframe.getFileName(property.icon.plus.src)) {
				getNodeList(node_id);
			}
			else {
				var ul = document.getElementById('tu' + node_id.substr(1));
				ul.style.display='none';
				obj.src = property.icon.plus.src;
				closeNode(node_id);
			}

			return false;
		}

		function selectObjectNode(event) {
			if(window.event) {
				var e = window.event;
			}
			else {
				var e = event;
			}
			// right button
			if(e.button == 2) return;

			var node = getEventNode(event);
			if(selected_node.id() && selected_node.place() == 'pain' && e.ctrlKey) {
				addSelectedObject(node.id);
			}
			else if(selected_node.id() && selected_node.place() == 'pain' && e.shiftKey) {
				addRangeSelectedObject(node.id);
			}
			else {
				selectObject(node.id);
			}
			bframe.stopPropagation(event);
		}

		function selectNode(event) {
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
					if(selected_node.id() && selected_node.place() == 'tree' && e.ctrlKey) {
						addSelectedObject(node.id);
					}
					else {
						selectObject(node.id);
					}
				}
			}
			else if(node != current_node.object() || node != selected_node.object()) {
				select(node.id);
			}

			if(node.id == current_node.id()) return;

			if(node_type.value != 'folder' && node.id.substr(1) != 'root' && node.id.substr(1) != 'trash') {
				return;
			}
			var control = document.getElementById('c' + node.id);
			if(pain) {
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
			selectResource(node.id);
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
			span.style.textDecoration = 'none';

			if(property.editable == 'true') {
				drag_control.dragging(event);
			}
		}
	}
