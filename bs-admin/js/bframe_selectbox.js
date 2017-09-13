/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeSelectBoxInit);

	function bframeSelectBoxInit() {
		var sc = new bframe.selectbox_container();
		var select = document.getElementsByTagName('select');
		var s = new Array();

		for(var i=0, j=0; i < select.length; i++) {
			if(bframe.checkClassName('bframe_selectbox', select[i])) {
				s[j++] = new bframe.selectbox(select[i], sc);
			}
		}
		sc.setElements(s);

		bframe.selectBoxContainer = sc;
	}

	function bframeSelectBoxAdd() {
		var select = document.getElementsByTagName('select');
		var s = new Array();

		for(var i=0, j=0; i < select.length; i++) {
			if(bframe.checkClassName('bframe_selectbox', select[i])) {
				s[j++] = new bframe.selectbox(select[i], bframe.selectBoxContainer);
			}
		}
		bframe.selectBoxContainer.setElements(s);
	}

	// -------------------------------------------------------------------------
	// class bframe.selectbox_container
	// 
	// -------------------------------------------------------------------------
	bframe.selectbox_container = function() {
		var elements;

		this.setElements = function(obj) {
			elements = obj;
		}

		this.addElements = function(obj) {
			elements = array.concat(elements, obj);
		}

		this.closeAll = function() {
			for(var i=0; i < elements.length; i++) {
				if(elements[i].hidePullDownMenu) {
					elements[i].hidePullDownMenu();
				}
			}
		}

		this.reload = function() {
			for(var i=0; i < elements.length; i++) {
				elements[i].reload();
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.selectbox
	// 
	// -------------------------------------------------------------------------
	bframe.selectbox = function(_target, sc) {
		var self = this;
		var target = _target;
		var selectbox;
		var selectbox_container = sc;

		var context_menu;
		var context_menu_frame = window;
		var context_menu_frame_offset;
		var context_menu_width = 100;
		var opened;
		var focused;
		var padding_left, padding_right;
		var border_left_width, border_right_width;

		this.hidePullDownMenu = hidePullDownMenu;
		this.reload = init;

		function init() {
			setContextMenu();
			setEventHandler();
		}

		selectValue = function(param) {
			target.options[param.index].selected = 'selected';
			selectbox.innerHTML = target.options[target.selectedIndex].text;
			bframe.fireEvent(target, 'change');
			hidePullDownMenu();
		}
		self.selectValue = selectValue;

		function setEventHandler() {
			// set event handller
			bframe.addEventListenerAllFrames(top, 'load', hidePullDownMenuAllFrames);
			bframe.addEventListenerAllFrames(top, 'mousedown', hidePullDownMenu);
			bframe.addEventListenerAllFrames(top, 'keydown', keydown);
		}

		function hidePullDownMenuAllFrames(event) {
			bframe.addEventListenerAllFrames(top, 'mousedown', hidePullDownMenu);
			bframe.addEventListenerAllFrames(top, 'load', hidePullDownMenuAllFrames);
		}

		function setContextMenu() {
			var options = new Array();

			selectbox = document.createElement('a');
			selectbox.className = 'selectbox';
			selectbox.style.width = '99999px';
			selectbox.innerHTML = target.options[target.selectedIndex].text;
			target.parentNode.insertBefore(selectbox, target);
			selectbox.tabIndex = target.tabIndex;

			context_menu = new bframe.contextMenu(1000);
			context_menu.setDocument(window);
			context_menu.setCloseTimerValue(0);
			context_menu_width = target.offsetWidth;

			target.style.display = 'none';

			context_menu_frame_offset = bframe.getFrameOffset(window, context_menu_frame);
			context_menu.setWidth(context_menu_width);
			for(var i=0; i < target.length; i++) {
				options[i] = {};
				options[i].menu = target.options[i].text;
				options[i].param = {'index': i, 'value': target.options[i].value, 'text': target.options[i].text};
				options[i].func = 'selectValue';
			}

			context_menu.createElementFromObject(options, self);
			context_menu.setElementClassName('selectbox');
			context_menu.setOffsetHeight(selectbox.offsetHeight-1);
			padding_left = window.getComputedStyle(selectbox, null).getPropertyValue('padding-left').replace('px', '');
			padding_right = window.getComputedStyle(selectbox, null).getPropertyValue('padding-right').replace('px', '');
			border_left_width = window.getComputedStyle(selectbox, null).getPropertyValue('border-left-width').replace('px', '');
			border_right_width = window.getComputedStyle(selectbox, null).getPropertyValue('border-right-width').replace('px', '');

			var padding = parseInt(padding_left) + parseInt(padding_right);
			var border = parseInt(border_left_width) + parseInt(border_right_width);
			selectbox.style.width = (context_menu.getRealWidth() - (padding + border)) + 'px';

			bframe.addEventListener(target, 'change', onchange);
			bframe.addEventListener(selectbox, 'focus', onfocus);
			bframe.addEventListener(selectbox, 'blur', onblur);
			bframe.addEventListener(selectbox, 'mousedown', showContextMenu);
		}

		function onfocus(event) {
			focused = true;
		}

		function onblur(event) {
			focused = false;
			if(opened) hidePullDownMenu();
		}

		function onchange(event) {
			selectbox.innerHTML = target.options[target.selectedIndex].text;
		}

		function showContextMenu(event) {
			bframe.stopPropagation(event);
			if(opened) {
				hidePullDownMenu();
				return false;
			}

			selectbox_container.closeAll();

			var position = bframe.getElementPosition(selectbox);
			position.left += context_menu_frame_offset.left;
			position.top += context_menu_frame_offset.top + window.pageYOffset;
			context_menu.positionAbsolute(position);
			context_menu.show();
			context_menu.select(target.selectedIndex);
			bframe.addEventListener(document, 'mousewheel', bframe.cancelEvent);
			selectbox.className+= ' opened';
			opened = true;
			selectbox.focus();
			return false;
		}

		function hidePullDownMenu(event) {
			if(document.detachEvent) {
				document.detachEvent('onmousewheel', bframe.cancelEvent);
			}
			context_menu.hide();
			selectbox.className = selectbox.className.replace(' opened', '');
			opened = false;
		}

		function keydown(event) {
			if(!focused) return;

			if(window.event) {
				var keycode = window.event.keyCode;
			}
			else {
				var keycode = event.keyCode;
			}

			switch(keycode) {
			case 13: //enter
				if(opened) hidePullDownMenu();
				return;

			case 38: //ª
				var length = context_menu.getLength();
				var index = context_menu.getRowIndex();
				if(index <= 0) return;
				index--;
				context_menu.select(index);
				target.options[index].selected = 'selected';
				selectbox.innerHTML = target.options[target.selectedIndex].text;
				bframe.fireEvent(target, 'change');

				break;

			case 40: //«
				var length = context_menu.getLength();
				var index = context_menu.getRowIndex();
				if(index < 0) index = 0;
				index++;
				if(index >= length) return;
				context_menu.select(index);
				target.options[index].selected = 'selected';
				selectbox.innerHTML = target.options[target.selectedIndex].text;
				bframe.fireEvent(target, 'change');

				break;
			}
		}

		init();
	}
