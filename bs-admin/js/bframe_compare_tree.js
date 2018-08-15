/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeCompareTreeInit);

	function bframeCompareTreeInit(){
		var tc = new bframe.compare_tree_container();
		var t = new Array();

		var objects = document.getElementsByClassName('bframe_compare_tree');
		for(var i=0, j=0; i<objects.length; i++) {
			t[j++] = new bframe.compare_tree(tc, objects[i], j);
		}

		tc.setTrees(t);
	}

	// -------------------------------------------------------------------------
	// class bframe.compare_tree_container
	// 
	// -------------------------------------------------------------------------
	bframe.compare_tree_container = function() {
		var trees;

		this.setTrees = function(t) {
			trees = t;
		}

		this.openRelativeNode = function(target, path) {
			for(var i=0 ; i<trees.length ; i++) {
				if(trees[i].getTarget() == target) continue;

				var p = bframe.searchNodeByNameAndValue(trees[i].getTarget(), 'path', path);
				if(!p) return;

				var node = bframe.searchParentByName(p, 'node');
				var control = bframe.searchNodeByName(node, 'node_control');
				trees[i].openRelativeNode(control);
			}
		}

		this.selectRelativeNode = function(target, path) {
			for(var i=0 ; i<trees.length ; i++) {
				if(trees[i].getTarget() == target) continue;

				var p = bframe.searchNodeByNameAndValue(trees[i].getTarget(), 'path', path);
				if(!p) {
					trees[i].clearCurrentNode();
					return;
				}

				var node = bframe.searchParentByName(p, 'node');
				trees[i].selectRelativeNode(node);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.compare_tree
	// 
	// -------------------------------------------------------------------------
	bframe.compare_tree = function(container, target, index) {
		var tc = container;
		var self = this;
		var target_id = bframe.getID(target);
		var target_index = index;

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

		var new_node = new currentNodeControl;
		var current_node = new currentNodeControl;
		var selected_node = new currentNodeControl;

		var clipboard = {};

		var pane;
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
			var rel;

			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				getPane();
				response_wait = false;
				getNodeList('');
			}
		}

		function getPane() {
			if(property.relation && property.relation.pane) {
				pane = true;
			}
		}

		function getNodeList(id) {
			var param;

			param = 'terminal_id='+terminal_id+'&target_id='+target_id;
			if(id) {
				param+= '&node_id='+id.substr(2);
			}
			httpObj = createXMLHttpRequest(showNode);

			eventHandler(httpObj, property.module, property.file, property.method.getNodeList, 'POST', param);
			target.style.cursor = 'wait';
			if(obj = document.getElementById('a' + id)) {
				obj.style.cursor = 'wait';
			}

			response_wait = true;
		}

		function showNode() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				current_edit_node = '';
				response = eval('('+httpObj.responseText+')');
				node_info = response.node_info;

				if(response.current_node) {
					current_node.set('t'+response.current_node);
					if(!selected_node.id()) {
						selected_node.set('t'+response.current_node);
					}
				}

				if(target.hasChildNodes()) {
					target.removeChild(target.firstChild);
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
					_showNode(ul, node_info[1]);
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
				response_wait = false;
				node_info = null;

				if(response.message) {
					alert(response.message);
				}
			}
		}

		function _showNode(parent_node, node_info) {
			li = createNodeObject(parent_node, node_info, 'tree');

			if(node_info.children) {
				if(pane) {
					var ul = document.createElement('ul');
					ul.id = target_index + 'u' + node_info.node_id;
					ul.name = 'nodes';
					li.appendChild(ul);

					if(!node_info.opened) {
						ul.style.display = 'none';
					}

					for(var i=0 ; i < node_info.children.length ; i++) {
						if(node_info.children[i].node_type == 'file') {
							continue;
						}
						_showNode(ul, node_info.children[i]);
					}
				}
				else {
					var ul = document.createElement('ul');
					ul.id = target_index + 'u' + node_info.node_id;
					ul.name = 'nodes';
					li.appendChild(ul);

					if(!node_info.opened) {
						ul.style.display = 'none';
					}

					for(var i=0 ; i < node_info.children.length ; i++) {
						_showNode(ul, node_info.children[i]);
					}
				}
			}
		}

		function openNode(node_id) {
			var param;
			var path = document.getElementById(target_index + 'p' + node_id);

			param = 'terminal_id='+terminal_id+'&node_id='+node_id.substr(2)+'$path='+path.value;
			httpObj = createXMLHttpRequest(openNodeResponse);
			eventHandler(httpObj, property.module, property.file, property.method.openNode, 'POST', param);
			response_wait = true;
			if(current_node.id()) {
				node = document.getElementById(node_id);
				if(bframe.searchNodeById(node, current_node.id())) {
					selected_node.set(node_id);
					selected_node.setColor('current');
				}
			}
			var obj_img = document.getElementById(target_index + 'i' + node_id);
			var node_diff_status = document.getElementById(target_index + 'ns' + node_id);
			var node_type = document.getElementById(target_index + 'nt' + node_id);
			if(node_type.value == 'folder') {
				if(node_type.value != 'root' && node_type.value != 'trash' && node_diff_status.value) {
					var icon = node_type.value + '_open_' + node_diff_status.value;
				}
				else {
					var icon = node_type.value + '_open';
				}
				obj_img.src = property.icon[icon].src;
			}
		}

		function openNodeResponse() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				response_wait = false;
			}
		}

		function closeNode(node_id) {
			var param;
			var path = document.getElementById(target_index + 'p' + node_id);

			param = 'terminal_id='+terminal_id+'&node_id='+node_id.substr(2)+'$path='+path.value;
			httpObj = createXMLHttpRequest(closeNodeResponse);
			eventHandler(httpObj, property.module, property.file, property.method.closeNode, 'POST', param);
			response_wait = true;
			if(current_node.id()) {
				node = document.getElementById(node_id);
				if(bframe.searchNodeById(node, current_node.id())) {
					selected_node.set(node_id);
					selected_node.setColor('current');
				}
			}
			var obj_img = document.getElementById(target_index + 'i' + node_id);
			var node_diff_status = document.getElementById(target_index + 'ns' + node_id);
			var node_type = document.getElementById(target_index + 'nt' + node_id);
			if(node_type.value != 'root' && node_type.value != 'trash' && (node_type.value == 'folder' || node_type.value == 'template') && node_diff_status.value) {
				var icon = node_type.value + '_' + node_diff_status.value;
			}
			else {
				var icon = node_type.value;
			}

			obj_img.src = property.icon[icon].src;
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

		change_disp_mode = function() {
			getNodeList(current_node.id());
		}

		function select(node) {
			if(node.id != current_node.object()) {
				current_node.set(node.id);
				current_node.setColor('current');

				if(property.relation && property.relation.selectNode) {
					var rel = bframe.getFrameByName(top, property.relation.selectNode.frame);
					current_node.setNodeIdBeforeUnload(node.id);
					selected_node.setNodeIdBeforeUnload(node.id);

					if(document.all) {
						rel.document.body.onunload = setNodeIdAfterUnload;
					}
					else {
						rel.onunload = setNodeIdAfterUnload;
					}
					rel.location.href = property.relation.selectNode.url+'&node_id='+node.id.substr(2)+'&target_id='+target_id;
				}
			}

			if(property.onclick) {
				var func = property.onclick.script;
				var node_span = document.getElementById(target_index + 's'+node.id);
				var node_type = document.getElementById(target_index + 'nt'+node.id);
				var node_name = node_span.innerHTML;
				window[func](node.id.substr(2), node_name, node_type.value);
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

		this.getCurrentFolderId = function() {
			return current_node.id();
		}

		this.getNodeList = function(id) {
			getNodeList(id);
		}

		this.getTarget = function() {
			return target;
		}

		// -------------------------------------------------------------------------
		// class currentNodeControl
		// -------------------------------------------------------------------------
		function currentNodeControl() {
			var self = this;
			var current_place;
			var current_node_id;
			var before_unload_node_id;

			this.id = function() {
				return current_node_id;
			}

			this.setNodeIdBeforeUnload = function(node_id) {
				before_unload_node_id = node_id;
			}

			this.resetNodeIdBeforeUnload = function() {
				before_unload_node_id = '';
			}

			this.setNodeIdAfterUnload = function() {
				if(!before_unload_node_id) return;

				if(current_node_id) {
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
				if(current_node_id) {
					var node = self.object();
					if(node) {
						var span = bframe.searchNodeByName(node, 'node_span');
						span.className = 'node-name';
					}
				}
				current_node_id = node_id;
				current_node_obj = '';
			}

			this.place = function() {
				return current_node_id.substr(1, 1) == 't' ? 'tree' : 'pane';
			}

			this.setColor = function(mode) {
				if(!current_node_id) return;
				var node = self.object();
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

			this.resetColor = function() {
				if(!current_node_id) return;

				var node = self.object();
				if(node) {
					var span = bframe.searchNodeByName(node, 'node_span');
					span.className = 'node-name ';
				}
			}

			this.object = function() {
				if(current_node_id) {
					obj = document.getElementById(current_node_id);
					if(obj) return obj;
					return document.getElementById(target_index + 't'+current_node_id.substr(2));
				}
			}

			this.node_type = function() {
				if(current_node_id) {
					type = document.getElementById(target_index + 't' + current_node_id);
					return type.value;
				}
			}
		}

		// -------------------------------------------------------------------------
		// class createNodeObject
		// -------------------------------------------------------------------------
		function createNodeObject(parent, config, place) {
			if(!config.node_id) return;

			if(place == 'tree') {
				var node_id = target_index + 't' + config.node_id;
			}
			else {
				var node_id = target_index + 'p' + config.node_id;
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
			div.id = target_index + 'd' + node_id;
			div.className = 'tree';

			li.appendChild(div);
 
			control = document.createElement('img');
			control.id = target_index + 'c' + node_id;
			control.name = 'node_control';
			div.appendChild(control);

			control.className = 'control';

			a = document.createElement('a');
			a.style.cursor = 'pointer';
			a.id = target_index + 'a' + node_id;
			div.appendChild(a);

			a.onclick = selectNode;
			if((pane && config.folder_count > 0 ) || (!pane && config.node_count > 0)) {
				if(config.children && config.opened) {
					control.src = property.icon.minus.src;
				}
				else {
					control.src = property.icon.plus.src;
				}
				control.onmousedown = controlNode;
			}
			else {
				control.src = property.icon.blank.src;
			}

			img_span = document.createElement('span');
			img_span.className = 'img-border';
			a.appendChild(img_span);

			obj_img = document.createElement('img');
			obj_img.id = target_index + 'i' + node_id;
			img_span.appendChild(obj_img);
			if(config.node_type != 'root' && config.node_type != 'trash' && config.node_diff_status) {
				if(config.node_type == 'folder' && config.children && config.opened) {
					var icon = config.node_type + '_open_' + config.node_diff_status;
				}
				else {
					var icon = config.node_type + '_' + config.node_diff_status;
				}
			}
			else {
				if(config.node_type == 'folder' && config.children && config.opened) {
					var icon = config.node_type + '_open';
				}
				else {
					var icon = config.node_type;
				}
			}
			obj_img.src = property.icon[icon].src;

			span = document.createElement('span');
			span.id = target_index + 's' + node_id;
			span.name = 'node_span';
			a.appendChild(span);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = target_index + 'ns' + node_id;
			input.name = 'node_type';
			input.value = config.node_diff_status;
			a.appendChild(input);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = target_index + 'nt' + node_id;
			input.name = 'node_type';
			input.value = config.node_type;
			a.appendChild(input);

			input = document.createElement('input');
			input.type = 'hidden';
			input.id = target_index + 'p' + node_id;
			input.name = 'path';
			input.value = config.path;
			a.appendChild(input);

			if(config.image_size) {
				input = document.createElement('input');
				input.type = 'hidden';
				input.id = target_index + 'is' + node_id;
				input.name = 'path';
				input.value = config.image_size;
				a.appendChild(input);
			}

			span.className = 'node-name';
			text = document.createTextNode(config.node_name);
			span.appendChild(text);

			return li;
		}

		function controlNode(event) {
			if(bframe.getButton(event) != 'L') return;
			obj = bframe.getEventSrcElement(event);
			var node = bframe.searchParentByName(obj, 'node');
			var node_id = node.id;
			var p = bframe.searchNodeByName(node, 'path');
			if(bframe.getFileName(obj.src) == bframe.getFileName(property.icon.plus.src)) {
				var ul = document.getElementById(target_index + 'u' + node_id.substr(2));
				ul.style.display='block';
				obj.src = property.icon.minus.src;
				openNode(node_id);

				// relation with other tree
				tc.openRelativeNode(target, p.value);
			}
			else {
				var ul = document.getElementById(target_index + 'u' + node_id.substr(2));
				ul.style.display='none';
				obj.src = property.icon.plus.src;
				closeNode(node_id);

				// relation with other tree
				tc.openRelativeNode(target, p.value);
			}
			return false;
		}

		this.openRelativeNode = function(obj) {
			var node = bframe.searchParentByName(obj, 'node');
			var node_id = node.id;
			var p = bframe.searchNodeByName(node, 'path');
			if(bframe.getFileName(obj.src) == bframe.getFileName(property.icon.plus.src)) {
				var ul = document.getElementById(target_index + 'u' + node_id.substr(2));
				ul.style.display='block';
				obj.src = property.icon.minus.src;
				openNode(node_id);
				return false;
			}
			if(bframe.getFileName(obj.src) == bframe.getFileName(property.icon.minus.src)) {
				var ul = document.getElementById(target_index + 'u' + node_id.substr(2));
				ul.style.display='none';
				obj.src = property.icon.plus.src;
				closeNode(node_id);
				return false;
			}
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
			if(node != selected_node.object()) {
				selectObject(node.id);
			}
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

			// relation with other tree
			var p = bframe.searchNodeByName(node, 'path');
			tc.selectRelativeNode(target, p.value);

			if(node != current_node.object() || node != selected_node.object()) {
				select(node);
			}

			var node_type = document.getElementById(target_index + 'nt' + node.id);
			if(node_type.value != 'folder' && node.id.substr(2) != 'root' && node.id.substr(2) != 'trash') {
				return;
			}
			var control = document.getElementById(target_index + 'c' + node.id);

			if(control && bframe.getFileName(control.src) == bframe.getFileName(property.icon.plus.src)) {
				var ul = document.getElementById(target_index + 'u' + node.id.substr(2));
				ul.style.display='block';
				control.src = property.icon.minus.src;
				openNode(node.id);
			}
		}

		this.selectRelativeNode = function(node) {
			if(node != current_node.object() || node != selected_node.object()) {
				current_node.set(node.id);
				selected_node.set(node.id);
				current_node.setColor('current');
			}

			var node_type = document.getElementById(target_index + 'nt' + node.id);
			if(node_type.value != 'folder' && node.id.substr(2) != 'root' && node.id.substr(2) != 'trash') {
				return;
			}
			var control = document.getElementById(target_index + 'c' + node.id);
			if(control && bframe.getFileName(control.src) == bframe.getFileName(property.icon.plus.src)) {
				var ul = document.getElementById(target_index + 'u' + node.id.substr(2));
				ul.style.display='block';
				control.src = property.icon.minus.src;
				openNode(node.id);
			}
		}

		this.clearCurrentNode = function() {
			current_node.resetColor();
			current_node.set('');
			selected_node.set('');
		}
	}
