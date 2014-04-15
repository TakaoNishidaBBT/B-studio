/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeContextMenuInit);

	function bframeContextMenuInit(){
		bframe.context_menu = new bframe.contextMenu(2000);
	}

	// -------------------------------------------------------------------------
	// class bframe.contextMenu
	// 
	// -------------------------------------------------------------------------
	bframe.contextMenu = function(zindex) {
		if(!zindex) {
			zindex = 1;
		}
		var self = this;
		var popup;
		var row_index = -1;
		var element;

		var parent_menu;

		var disabled_index = new Array();
		var disabled = new Array();

		var call_back_array = new Array();

		var confirm_message_index = new Array();
		var confirm_message = new Array();

		var max_height;
		var element_width = 100;
		var offsetHeight = 0;
		var select_func;
		var onclick_func;
		var mouse_position = {x:0, y:0};
		var menu_position;
		var close_timer;

		var _filter;

		var submenu = new Array();
		var submenu_open_index = -1;

		var frame;
		var document;
		var arrow_img = 'images/common/arrow_right.png';

		this.setDocument = function(w) {
			frame = w;
			document = w.document;

			if(!popup) {
				popup = new bframe.popup(frame, zindex, true);
			}
		}

		bframe.addEventListner(window, 'beforeunload' ,cleanUp);

		function cleanUp() {
			if(popup) {
				popup.cleanUp();
			}
			for(var i=0 ; i<submenu.length ; i++) {
				if(submenu[i] && submenu[i].cleanUp) {
					submenu[i].cleanUp();
				}
			}
		}

		this.cleanUp = cleanUp();

		this.createElementFromXml = function(data) {
			element = _createElementFromXml(data);

			popup.removeChild();
			popup.appendChild(element);
			row_index = -1;

			for(i=0 ; i<element.rows.length ; i++) {
				element.rows[i].onmouseover = onMouseOver;
				element.rows[i].onmousemove = onMouseOver;
				element.rows[i].onclick = onClickFunc;
			}
		}

		function _createElementFromXml(xmlData) {
			var element = document.createElement('table');
			element.className = 'context_menu';

			var menu = xmlData.getElementsByTagName('menu');

			for(var i=0; i<menu.length; i++) {
				var tr = element.insertRow(i);
				var td = tr.insertCell(0);
				td.innerHTML = menu[i].firstChild.nodeValue;
			}
			return element;
		}

		this.createElementFromObject = function(data, obj, p) {
			element = _createElementFromObject(data, obj, p);
			popup.appendChild(element);
			row_index = -1;
		}

		function _createElementFromObject(data, obj, p) {
			parent_menu = p;
			var element = document.createElement('table');
			element.className = 'context_menu';

			for(var i=0; i<data.length; i++) {
				var tr = element.insertRow(i);
				var td = tr.insertCell(0);
				td.style.whiteSpace='nowrap';
				if(data[i].icon) {
					var icon = document.createElement('img');
					icon.src = data[i].icon;
					td.appendChild(icon);
				}
				if(data[i].menu) {
					var span = document.createElement('span');
					var menu = document.createTextNode(data[i].menu);
					td.appendChild(span);
					span.appendChild(menu);
					if(data[i].submenu) {
						span.style.display = 'block';
						span.style.backgroundImage = 'url('+arrow_img+')';
						span.style.backgroundRepeat = 'no-repeat';
						span.style.backgroundPosition = 'right center';
					}
				}
				if(data[i].submenu) {
					submenu[i] = new bframe.contextMenu(parseInt(zindex)+10);
					submenu[i].setDocument(frame);
					submenu[i].setWidth(data[i].submenu_width);
					submenu[i].createElementFromObject(data[i].submenu, obj, self);
					tr.onclick = clickSubmenuParent;
				}
				else {
					if(data[i].func) {
						var c = new callback(self);
						call_back_array[i] = c;

						if(data[i].confirm) {
							c.setConfirmMessage(data[i].confirm);
						}
						c.setCallBackFunc(obj[data[i].func]);
						if(data[i].param) {
							c.setParam(data[i].param);
						}
						tr.onclick = c.func;
					}
				}
				disabled_index[data[i].func] = i;
				tr.onmouseover = onMouseOver;
				tr.onmouseout = onMouseOut;
			}

			element.onmouseover = onMouseOverElement;
			element.onmouseout = onMouseOutElement;

			return element;
		}

		function clickSubmenuParent(event) {
			bframe.stopPropagation(event);
		}

		this.disableElement = function(index) {
			disabled[disabled_index[index]] = 'disabled';
			if(call_back_array[disabled_index[index]]) {
				call_back_array[disabled_index[index]].disable();
			}
		}

		this.enableElement = function(index) {
			disabled[disabled_index[index]] = '';
			if(call_back_array[disabled_index[index]]) {
				call_back_array[disabled_index[index]].enable();
			}
		}

		this.getCallBackObject = function(index) {
			return call_back_array[disabled_index[index]];
		}

		this.getElement = function() {
			return element;
		}

		this.getRowIndex = function() {
			return row_index;
		}

		this.setRowIndex = function(value) {
			row_index = value;
		}

		this.getLength = function() {
			return element.rows.length;
		}

		this.setElementWidth = function(width) {
			element.style.width = width;
		}

		this.setWidth = function(width) {
			element_width = width;
		}

		this.getWidth = function() {
			return element_width;
		}

		this.setOffsetHeight = function(height) {
			offsetHeight = height;
		}

		this.setMaxHeight = function(height) {
			max_height = height;
		}

		this.setBorder = function(value) {
			popup.setBorder(value);
		}

		this.getElementSize = function() {
			return {
				width:element.offsetWidth, height:element.offsetHeight
			}
		}

		this.setSelectFunction = function(func) {
			select_func = func;
		}

		this.setOnClickFunction = function(func) {
			onclick_func = func;
		}

		this.setFunc = function(index, func) {
			element.rows[index].onmouseup = func;
		}

		function setValue() {
			if(select_func) {
				select_func(row_index);
			}
		}

		function onMouseOver(event){
			if(window.event) {
				var	obj = window.event.srcElement;
			}
			else if(frame.event) {
				var obj = frame.event.srcElement;
			}
			else {
				var	obj = event.target;
			}
			p = bframe.searchParentByTagName(obj, 'tr');

			// disabled
			if(disabled[p.rowIndex] == 'disabled') return;

			select(p.rowIndex);
			element.style.cursor = 'pointer';

			pos = getMousePosition(event);
			if(Math.abs(mouse_position.x - pos.pageX) < 1 && Math.abs(mouse_position.y - pos.pageY) < 1) {
				mouse_position.x = pos.pageX;
				mouse_position.y = pos.pageY;
				return;
			}
			mouse_position.x = pos.pageX;
			mouse_position.y = pos.pageY;

			if(submenu_open_index != -1 && submenu_open_index != p.rowIndex) {
				submenu[submenu_open_index].hide();
				submenu_open_index = -1;
			}
			if(submenu[p.rowIndex]) {
				submenu_open_index = p.rowIndex;
				position = getSubmenuPosition();
				submenu[submenu_open_index].positionAbsolute(position);
				submenu[submenu_open_index].show();
			}

			return false;
		}

		function onMouseOut(event){
			if(window.event) {
				var	obj = window.event.srcElement;
			}
			else if(frame.event) {
				var obj = frame.event.srcElement;
			}
			else {
				var	obj = event.target;
			}
			p = bframe.searchParentByTagName(obj, 'tr');

			unselect(p.rowIndex);
			element.style.cursor = 'auto';

			return false;
		}

		function onMouseOverElement(event) {
			if(parent_menu) {
				parent_menu.clearTimer();
			}
			else {
				self.clearTimer();
			}
		}

		function onMouseOutElement(event) {
			if(parent_menu) {
				parent_menu.setTimer();
			}
			else {
				self.setTimer();
			}
		}

		this.setTimer = function(event) {
			if(parent_menu) {
				parent_menu.setTimer();
			}
			else {
				close_timer = setTimeout(self.hide, 2000);
			}
		}

		this.clearTimer = function(event) {
			if(parent_menu) {
				parent_menu.clearTimer();
			}
			else {
				clearTimeout(close_timer);
			}
		}

		function getMousePosition(event) {
			if (!event) {
				if(window.event) var event=window.event;
				if(frame.event) var event=frame.event;
			}
			if (!event.pageX) event.pageX = event.clientX + document.body.scrollLeft;
			if (!event.pageY) event.pageY = event.clientY + document.body.scrollTop;
			return event;
		}

		function onClickFunc(event) {
			if(onclick_func) {
				if(window.event) {
					var	obj = window.event.srcElement;
				}
				if(frame.event) {
					var	obj = frame.event.srcElement;
				}
				else {
					var	obj = event.target;
				}
				var p = obj.parentNode;
				row_index = p.rowIndex;

				onclick_func(row_index);
			}
		}

		this.select = function(row) {
			if(!element) return;
			select(row);
		}

		function select(index){
			if(!element) return;
			for(i=0 ; i < element.rows.length ; i++) {
				if(disabled[i] == 'disabled') continue;

				if(i == index) {
					element.rows[i].cells[0].className = 'selected';
				}
				else {
					element.rows[i].cells[0].className = '';
				}
			}
			row_index = index;
			if(index != -1) {
				scroll(index);
			}
		}

		function unselect(index){
			if(!element) return;
			if(disabled[index] == 'disabled') return;

			if(index != submenu_open_index){
				element.rows[index].cells[0].className = '';
			}

			if(row_index == index) {
				row_index = -1;
			}
		}

		function scroll(row) {
			var r = bframe.getElementPosition(element.rows[row]);
			var p = popup.getElementPosition();
			var h = element.rows[0].offsetHeight;
			var scrollTop = popup.getScrollTop();
			var offsetHeight = popup.offsetHeight();

			if((r.top - p.top) > offsetHeight-h) {
				t = r.top - p.top - offsetHeight + h;
				popup.setScrollTop(scrollTop + t + 2);
			}
			if(r.top < p.top+2) {
				var t = scrollTop - (p.top - r.top);
				if(t >= 0) {
					popup.setScrollTop(t-2);
				}
			}
		}

		this.setCursor = function(type) {
			element.style.cursor = type;
		}

		function getSubmenuPosition() {
			var rheight = element.rows[submenu_open_index].offsetHeight;

			position = bframe.getElementPosition(element.rows[submenu_open_index]);
			var top = position.top;
			var left = position.left + parseInt(element_width);

			var wsize = bframe.getWindowSize();
			var offset = bframe.getFrameOffset(frame, '');
			var w = wsize.width - offset.left;
			var h = wsize.height - offset.top;
			var submenu_element = submenu[submenu_open_index].getElement();
			var submenu_width = submenu[submenu_open_index].getWidth();

			if(((w - left) > parseInt(submenu_width) + 20) || parseInt(submenu_width) > parseInt(menu_position.left)) {
				p_left = left-10;
			}
			else {
				p_left = parseInt(menu_position.left) - parseInt(submenu_width);
			}
			if(((h - top) > parseInt(submenu_element.offsetHeight) + 20) || parseInt(submenu_element.offsetHeight) > top) {
				p_top = top-3;
			}
			else {
				p_top = top - parseInt(submenu_element.offsetHeight) + rheight;
			}

			return {left:p_left, top:p_top};
		}

		this.getPosition = function(event) {
			var p_top;
			var p_left;
			var wsize = bframe.getWindowSize();
			var offset = bframe.getFrameOffset(window, '');
			var w = wsize.width - offset.left;
			var h = wsize.height - offset.top;

			if(window.event) {
				var clientX = window.event.clientX;
				var clientY = window.event.clientY;
			}
			else if(frame.event) {
				var clientX = frame.event.clientX;
				var clientY = frame.event.clientY;
			}
			else {
				var clientX = event.clientX;
				var clientY = event.clientY;
			}
			if(((w - clientX) > parseInt(element.offsetWidth) + 20) || parseInt(element.offsetWidth) > clientX) {
				p_left = clientX - 2;
			}
			else {
				p_left = clientX - parseInt(element.offsetWidth) + 2;
			}
			if(((h - clientY) > parseInt(element.offsetHeight) + 20) || parseInt(element.offsetHeight) > clientY) {
				p_top = clientY - 2;
			}
			else {
				p_top = clientY - parseInt(element.offsetHeight) + 2;
			}
			p_left += document.body.scrollLeft;
			p_top += document.body.scrollTop;
			return {left:Math.round(p_left), top:Math.round(p_top)};
		}

		this.setPosition = function(position) {
			menu_position = position;
			position.top += offsetHeight;
			popup.position(position);
		}

		this.positionAbsolute = function(position) {
			menu_position = position;
			position.top += offsetHeight;
			popup.positionAbsolute(position);
		}

		this.show = function() {
			self.hide();
			filter();
			reset();

			if(max_height && max_height < element.offsetHeight) {
				size = {width:element_width, height:max_height};
				popup.overflowY('scroll');
			}
			else {
				size = {width:element_width, height:element.offsetHeight};
				popup.overflowY('hidden');
			}
			popup.size(size);

			if(!parent_menu && popup.visibility() != 'visible') {
				bframe.addEventListnerAllFrames(top, 'DOMMouseScroll', bframe.cancelEvent);
				bframe.addEventListnerAllFrames(top, 'mousewheel', bframe.cancelEvent);
			}
			popup.show();
		}

		this.hide = function() {
			if(!popup) return;

			if(submenu_open_index != -1) {
				submenu[submenu_open_index].hide();
				submenu_open_index = -1;
			}
			if(!parent_menu && popup.visibility() == 'visible') {
				bframe.removeEventListnerAllFrames(top, 'DOMMouseScroll', bframe.cancelEvent);
				bframe.removeEventListnerAllFrames(top, 'mousewheel', bframe.cancelEvent);
			}
			popup.hide();
			self.clearTimer();
		}

		function filter() {
			if(_filter) {
				_filter();
			}
		}

		this.setFilter = function(func) {
			_filter = func;
		}

		function reset() {
			if(!element) return;
			for(i=0 ; i < element.rows.length ; i++) {

				if(disabled[i] == 'disabled') {
					element.rows[i].cells[0].className = 'disabled';
				}
				else {
					element.rows[i].cells[0].className = '';
				}
			}
			row_index = -1;
		}

		// callback object
		function callback(context_menu) {
			var callback_func;
			var confirm_message;
			var confirm_message_key;
			var tmp_confirm_message;
			var menu = context_menu;
			var param;
			var disabled;

			this.setCallBackFunc = function(func) {
				callback_func = func;
			}

			this.setConfirmMessage = function(message) {
				if(typeof(confirm_message) == 'object') {
					confirm_message[confirm_message_key] = message;
				}
				else {
					confirm_message = message;
				}
			}

			this.setTmpConfirmMessage = function(message) {
				tmp_confirm_message = message;
			}

			this.setConfirmMessageKey = function(key) {
				confirm_message_key = key;
			}

			this.getConfirmMessage = function() {
				if(typeof(confirm_message) == 'object') {
					return confirm_message[confirm_message_key];
				}
				return confirm_message;
			}

			this.setParam = function(p) {
				param = p;
			}

			this.enable = function() {
				disabled = '';
			}

			this.disable = function() {
				disabled = 'disabled';
			}

			this.func = function(e) {
				if(disabled == 'disabled') return;

				if(confirm_message) {
					menu.hide();
					if(tmp_confirm_message) {
						if(!confirm(tmp_confirm_message)) {
							return;
						}
					}
					else if(typeof(confirm_message) == 'object') {
						if(!confirm(confirm_message[confirm_message_key])) {
							return;
						}
					}
					else {
						if(!confirm(confirm_message)) {
							return;
						}
					}
				}
				if(param) {
					callback_func(param);
				}
				else {
					callback_func();
				}
			}
			return false;
		}
	}
