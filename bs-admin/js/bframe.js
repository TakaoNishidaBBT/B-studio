/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	if(typeof bframe == 'undefined' || !bframe){
		var bframe = {};
	}

	// -------------------------------------------------------------------------
	// class bframe
	// 
	// -------------------------------------------------------------------------
	bframe.getPageInfo = function() {
		var arr = new Array();
		var node = document.getElementsByTagName('meta');

		for(var i in node) {
			arr[node[i].name] = node[i].content;
		}
		return arr;
	}

	bframe.submit_cb = new Array();

	bframe.registSubmitCallBackFunction = function(func) {
		bframe.submit_cb.push(func);
	}

	bframe.executeCallBack = function() {
		for(var i=0 ; i<bframe.submit_cb.length ; i++) {
			func = bframe.submit_cb[i];
			func();
		}
	}

	bframe.submit = function(fname, module, page, method, mode, nocheck) {
		// set form object
		if(fname) {
			var form = document.forms[fname];
		}
		else {
			var form = document.forms[0];
		}
		// set hidden object
		info = bframe.getPageInfo();
		bframe.appendHiddenElement(form, 'terminal_id', info['terminal_id']);
		if(module) bframe.appendHiddenElement(form, 'module', module);
		if(page)   bframe.appendHiddenElement(form, 'page', page);
		if(method) bframe.appendHiddenElement(form, 'method', method);
		if(mode)   bframe.appendHiddenElement(form, 'mode', mode);
		if(nocheck) {
			// detach onbeforeunload event
			window.onbeforeunload = '';
		}

		// execute callback function before submit
		bframe.executeCallBack();

		window.onunload = '';
		try {
			form.submit();
		}
		catch(e) {
			// do nothing (this is only for unspecified error)
		}
	}

	bframe.submitOverFrame = function(target, fname, module, page, method, mode, nocheck) {
		// set form object
		if(fname) {
			var form = document.forms[fname];
		}
		else {
			var form = document.forms[0];
		}
		form.target = target;

		// set hidden object
		info = bframe.getPageInfo();
		bframe.appendHiddenElement(form, 'terminal_id', info['terminal_id']);
		if(module) bframe.appendHiddenElement(form, 'module', module);
		if(page)   bframe.appendHiddenElement(form, 'page', page);
		if(method) bframe.appendHiddenElement(form, 'method', method);
		if(mode)   bframe.appendHiddenElement(form, 'mode', mode);
		if(nocheck) {
			// detach onbeforeunload event
			window.onbeforeunload = '';
		}
		window.onunload = '';
		try {
			form.submit();
		}
		catch(e) {
			// do nothing (this is only for unspecified error)
		}
	}

	bframe.checkAndSubmit = function(obj_id, msg, fname, module, page, method, mode, nocheck) {
		// set form object
		if(fname) {
			var form = document.forms[fname];
		}
		else {
			var form = document.forms[0];
		}
		target = document.getElementById(obj_id);

		if(target && target.value == ''){
			alert(msg);
			target.focus();
			return false;
		}
		bframe.submit(fname, module, page, method, mode, nocheck);
	}

	bframe.confirmSubmit = function(msg, fname, module, page, method, mode) {
		if(confirm(msg)) {
			bframe.submit(fname, module, page, method, mode, true);
		}
		return false;
	}

	bframe.appendHiddenElement = function(form, id, value) {
		var element = document.createElement('input');
		element.id = id;
		element.type = 'hidden';
		element.name = id;
		element.value = value;

		form.appendChild(element);
	}

	bframe.AjaxSubmit = function() {
		var cb = new Array;
		var sfname, smodule, spage, smethod, smode, snocheck;

		submit = function(fname, module, page, method, mode, nocheck) {
			sfname = fname;
			smodule = module;
			spage = page;
			smethod = method;
			smode = mode;
			snocheck = nocheck;

			// execute callback function
			AjaxSubmitExecuteCallBack();

			// set form object
			if(fname) {
				var form = document.forms[fname];
			}
			else {
				var form = document.forms[0];
			}
			info = bframe.getPageInfo();
			bframe.appendHiddenElement(form, 'terminal_id', info['terminal_id']);
			bframe.appendHiddenElement(form, 'mode', mode);
			var param = $(form).serialize(); // use jquery
			httpObj = createXMLHttpRequest(ajaxResponse);
			eventHandler(httpObj, module, page, method, 'POST', param);
			bframe.response_wait = true;
		}

		this.submit = submit;

		this.registCallBackFunction = function(func) {
			cb.push(func);
		}

		this.removeCallBackFunction = function(func) {
			for(var i=0 ; i<cb.length ; i++) {
				if(func == cb[i]) {
					cb.splice(i, 1);
				}
			}
		}

		AjaxSubmitExecuteCallBack = function() {
			for(var i=0 ; i<cb.length ; i++) {
				func = cb[i];
				func();
			}
		}

		this.executeCallBack = AjaxSubmitExecuteCallBack;

		ajaxResponse = function() {
			var obj;

			if(httpObj.readyState == 4 && httpObj.status == 200 && bframe.response_wait){
				response = eval('('+httpObj.responseText+')');
				if(response.status && response.mode && response.mode == 'confirm') {
					if(confirm(response.message)) {
						submit(sfname, smodule, spage, smethod, '', snocheck);
						return;
					}
				}
				if(response.message && response.message_obj) {
					if(obj = document.getElementById(response.message_obj)) {
						obj.innerHTML = response.message;
						bframe.effect.fadeOut(obj, 100, 0, 6000);
					}
				}
				if(response.values) {
					for(var obj_id in response.values) {
						if(obj = document.getElementById(obj_id)) {
							obj.value = response.values[obj_id];
						}
					}
				}
				bframe.response_wait = false;
				if(bframe.editCheck_handler) {
					bframe.editCheck_handler.resetEditFlag();
				}
			}
		}
	}

	bframe.ajaxSubmit = new bframe.AjaxSubmit;

	bframe.convert_number = function(src) {
		var str = new String;
		var len = src.length;

		for (var i = 0; i < len; i++) {
			var c = src.charCodeAt(i);
			if (c >= 65281 && c <= 65374 && c != 65340) {
				str += String.fromCharCode(c - 65248);
			} else if (c == 8217) {
				str += String.fromCharCode(39);
			} else if (c == 8221) {
				str += String.fromCharCode(34);
			} else if (c == 12288) {
				str += String.fromCharCode(32);
			} else if (c == 65507) {
				str += String.fromCharCode(126);
			} else if (c == 65509) {
				str += String.fromCharCode(92);
			} else {
				str += src.charAt(i);
			}
		}
		return str;
	}

	bframe.getID = function(obj) {
		if(obj.id.substring(obj.id.length-2, obj.id.length) == '[]') {
			return obj.id.substring(0, obj.id.length-2);
		}
		else {
			return obj.id;
		}
	}

	bframe.checkClassName = function(class_name, obj) {
		if(obj.className) {
			var arr = obj.className.split(' ');
			for(var j=0; j<arr.length; j++) {
		        if(arr[j] == class_name) {
					return true;
				}
			}
		}
		return false;
	}

	bframe.appendClass = function(class_name, obj) {
		bframe.removeClass(class_name, obj)
		if(obj.className) {
			obj.className += ' ';
		}
		obj.className += class_name;
	}

	bframe.removeClass = function(class_name, obj) {
		var space_and_class_name = ' ' + class_name
		obj.className = obj.className.replace(space_and_class_name, '');
		obj.className = obj.className.replace(class_name, '');
	}

	bframe.getParam = function(param, parameters) {
		var value;
		var parameterArray = parameters.split(',');
		var compareString = param+':';
		var compareLength = compareString.length;
		for (var i = 0; i < parameterArray.length; i++) {
			if (parameterArray[i].substr(0, compareLength) == compareString) {
				var currentParameter = parameterArray[i].split(':');
				value = currentParameter[1];
				break;
			}
		}
		if (!value) return false;
		else return unescape(value);
	}

	bframe.setLinkParam = function(a, key, value) {
		var href = a.href.split('?');
		if(href.length < 2) return;

		var params = href[1].split('&');
		for (var i = 0; i < params.length; i++) {
			var keyValue = params[i].split('=');
			if(keyValue[0] == key) {
				keyValue[1] = value;
				params[i] = keyValue.join('=');
				href[1] = params.join('&');
				a.href = href.join('?');
				return;
			}
		}
		a.href += '&' + key + '=' + value;
	}

	bframe.getLinkParam = function(link) {
		if(link.split('?').length < 2) return;
		var hash = new Array();
		var param = link.split('?')[1];
        var parray = param.split('&');
        for(var i=0;i<parray.length;i++){
            var n = parray[i].split('=');
            hash[n[0]] = n[1];
        }
		return hash;
	}

	bframe.getEventSrcElement = function(event) {
		if(window.event) {
			var	obj = window.event.srcElement;
		}
		else {
			var	obj = event.target;
		}
		return obj;
	}

	bframe.getZindex = function(element) {
		for(var e=element ; e ; e=e.parentNode) {
			if(e.style && e.style.zIndex) {
				return e.style.zIndex;
			}
		}
	}

	bframe.getStyle = function(element) {
		if(document.defaultView && document.defaultView.getComputedStyle) {
		    // firefox, opera
		    style = document.defaultView.getComputedStyle(element, '');
		}
		else if(element.currentStyle) {
		    // ie
		    style = element.currentStyle;
		}
		return style;
	}

	bframe.getWindowSize = function() {
		var w = top.document.documentElement.clientWidth  || top.document.body.clientWidth;
		var h = top.document.documentElement.clientHeight || top.document.body.clientHeight;
		return {width:w, height:h};
	}

	bframe.getScrollSize = function(obj) {
		var doc = obj.contentDocument || obj.contentWindow.document || obj.document;

		var w = doc.body.scrollWidth;
		var h = doc.body.scrollHeight;
		return {width:w, height:h};
	}

	bframe.getElementPosition = function(element) {
		if(element.getBoundingClientRect) {
			var position = element.getBoundingClientRect();
			return {left:Math.round(position.left), top:Math.round(position.top)};
		}
		else {
			var coords = {left:0, top:0, width: element.offsetWidth, height:element.offsetHeight};
			while(element) {
				coords.left += element.offsetLeft;
				coords.top += element.offsetTop;
				element = element.offsetParent;
			}
			return {left:Math.round(coords.left), top:Math.round(coords.top)};
		}
	}

	bframe.getScrollPosition = function() {
		if(typeof document.body.style.maxHeight != 'undefined') {
			var scrollLeft = document.documentElement.scrollLeft;
			var scrollTop = document.documentElement.scrollTop;
		}
		else {
			var scrollLeft = document.body.scrollLeft;
			var scrollTop = document.body.scrollTop;
		}

		return {left:scrollLeft, top:scrollTop};
	}

	bframe.getMousePosition = function(event) {
		var scrollLeft = document.body.scrollLeft || document.documentElement.scrollLeft;
		var scrollTop = document.body.scrollTop || document.documentElement.scrollTop;

		if(window.event) {
			var x = window.event.clientX;
			var y = window.event.clientY;
			var sx = window.event.screenX;
			var sy = window.event.screenY;
		}
		else {
			if(event.pageX) {
				var x = event.pageX;
				var y = event.pageY;
				var sx = event.screenX;
				var sy = event.screenY;
			}
			else {
				var x = event.screenX;
				var y = event.screenY;
				var sx = event.screenX;
				var sy = event.screenY;
			}
		}

		return {x:x, y:y, scrollLeft:scrollLeft, scrollTop: scrollTop, screenX: sx, screenY: sy};
	}

	bframe.getWindowEvent = function(obj) {
		if(obj.window.event) {
			return obj.window.event;
		}
		for(var i=0;  i < obj.frames.length; i++) {
			bframe.getWindowEvent(obj.frames[i]);
		}
	}

	bframe.stopPropagation = function(event) {
		if(event.stopPropagation) {
			event.stopPropagation();
			event.preventDefault();
		}
		else {
			window.event.returnValue = false;
			window.event.cancelBubble = true;
		}
	}

    bframe.cancelEvent = function(e) {
        e.preventDefault? e.preventDefault() : window.event.returnValue = false;
    }

	bframe.addEventListner = function(obj, event, func) {
		if(obj.addEventListener) {
			obj.removeEventListener(event, func, false);
			obj.addEventListener(event, func, false);
		}
		else if(obj.attachEvent) {
			obj.detachEvent('on'+event, func);
			obj.attachEvent('on'+event, func);
		}
	}

	bframe.removeEventListner = function(obj, event, func) {
		if(obj.removeEventListener) {
			obj.removeEventListener(event, func, false);
		}
		else if(obj.attachEvent) {
			obj.detachEvent('on'+event, func);
		}
	}

	bframe.addEventListnerAllFrames = function(obj, event, func) {
		try {
			if(!obj) return;

			if(event.toLowerCase() == 'load') {
				if(obj.parent && obj.name) {
					var iframe = obj.parent.document.getElementsByName(obj.name);
					if(iframe[0]) {
						bframe.addEventListner(iframe[0], event, func);
						if(document.all) {
							iframe[0].onreadystatechange = bframe.onReadyStateChange;
						}
					}
				}
			}
			else if(event.toLowerCase() == 'beforeunload') {
				obj.onbeforeunload = func;
			}
			else {
				var doc = obj.contentDocument || obj.contentWindow || obj.document;
				bframe.addEventListner(doc, event, func);
			}

			for(var i=0;  i < obj.frames.length; i++) {
				bframe.addEventListnerAllFrames(obj.frames[i], event, func);
			}
		}
		catch(e) {
		}
	}

	bframe.removeEventListnerAllFrames = function(obj, event, func) {
		try {
			if(event.toLowerCase() == 'load') {
				if(obj.parent && obj.name) {
					var iframe = obj.parent.document.getElementsByName(obj.name);
					if(iframe[0]) {
						bframe.removeEventListner(iframe[0], event, func);
					}
				}
			}
			else {
				var doc = obj.contentDocument || obj.contentWindow || obj.document;
				bframe.removeEventListner(doc, event, func);
			}

			for(var i=0;  i < obj.frames.length; i++) {
				bframe.removeEventListnerAllFrames(obj.frames[i], event, func);
			}
		}
		catch(e) {
		}
	}

	bframe.onReadyStateChange = function() {
		if(this.contentWindow.document.readyState == 'complete') {
			bframe.fireEvent(this, 'load');
		}
	}

	bframe.fireEvent = function(element, event) {
		if(!element) return;
		if(element.dispatchEvent) {
			var clickEvent = window.document.createEvent('MouseEvent'); 
			clickEvent.initEvent(event, false, true); 
			element.dispatchEvent(clickEvent);
		}
		else {
			element.fireEvent('on'+event);
		}
	}

	bframe.removeAllChild = function(id) {
		var node = document.getElementById(id);
		while(node.firstChild) {
			node.removeChild(node.firstChild);
		}
	}

	bframe.removeElement = function(element) {
		var p = element.parentNode;
		if(!p) return;

		var node = p.firstChild;
		while(node) {
			if(node == element) {
				p.removeChild(node);
				return;
			}
			node = node.nextSibling;
		}
	}

    bframe.getButton = function(event) {
		var status;
		if(_isIE) {
			var e = window.event;
			switch(e.button) {
			case 1:	status = 'L'; break;
			case 2:	status = 'R'; break;
			case 3:	status = 'B'; break;
			}
		}
		else {
			var e = event;
			switch(e.button) {
			case 0:	status = 'L'; break;
			case 1:	status = 'C'; break;
			case 2:	status = 'R'; break;
			}
		}
		return status;
	}

	bframe.isArray = function(obj) {
		if(obj.constructor.toString().indexOf('Array') == -1) {
			return false;
		}
		else {
			return true;
		}
	}

	bframe.isChild = function(node, obj) {
		if(!obj) return false;
		if(node == obj) {
			return true;
		}
		return this.isChild(node, obj.parentNode);

	}

	bframe.getFileName = function(url) {
    	file_name = url.substring(url.lastIndexOf('/')+1, url.length);
	    return file_name;
	}

	bframe.getRelationObject = function(node, id) {
		if(!node) return false;

		var object;
		if(node.tagName.toLowerCase() == 'tr' || !node.parentNode) {
			if(object = bframe.searchNodeById(node, id)) {
				return object;
			}
		}
		else if(node.parentNode) {
			if(object = bframe.getRelationObject(node.parentNode, id)) {
				return object;
			}
		}

		return false;
	};

	bframe.searchNodeById = function(node, id) {
		if(!node || !node.childNodes || !id) return false;

		var object;
		for(var i=0; i<node.childNodes.length; i++) {
			var child = node.childNodes[i];
			if(child.id == id || child.id == id+'[]') {
				return child;
			}
			else {
				if(object = this.searchNodeById(child, id)) {
					return object;
				}
			}
		}
		return false;
	};

	bframe.serachNodeByName = function(node, name) {
		if(!node.childNodes) return false;

		var object;
		for(var i=0; i<node.childNodes.length; i++) {
			var child = node.childNodes[i];
			if(child.name == name) {
				return child;
			}
			else {
				if(object = this.serachNodeByName(child, name)) {
					return object;
				}
			}
		}
		return false;
	};

	bframe.serachNodeByClassName = function(node, className) {
		if(!node.childNodes) return false;

		var object;
		for(var i=0; i<node.childNodes.length; i++) {
			var child = node.childNodes[i];
			if(bframe.checkClassName(className, child)) {
				return child;
			}
			else {
				if(object = this.serachNodeByName(child, className)) {
					return object;
				}
			}
		}
		return false;
	};

	bframe.serachNodeByNameAndValue = function(node, name, value) {
		if(!node.childNodes) return false;

		var object;
		for(var i=0; i<node.childNodes.length; i++) {
			var child = node.childNodes[i];
			if(child.name == name && child.value == value) {
				return child;
			}
			else {
				if(object = this.serachNodeByNameAndValue(child, name, value)) {
					return object;
				}
			}
		}
		return false;
	};

	bframe.serachNodeByTagName = function(node, tag) {
		if(!node.childNodes) return false;

		var object;
		for(var i=0; i<node.childNodes.length; i++) {
			var child = node.childNodes[i];
			if(!child.tagName) continue;
			if(child.tagName == tag || child.tagName.toLowerCase() == tag) {
				return child;
			}
			else {
				if(object = this.serachNodeByTagName(child, tag)) {
					return object;
				}
			}
		}
		return false;
	};

	bframe.searchParentById = function(obj, id) {
		if(!obj) return false;
		if(obj.id) {
			if(obj.id == id || obj.id.toLowerCase() == id) {
				return obj;
			}
		}
		return this.searchParentById(obj.parentNode, id);
	}

	bframe.searchParentByName = function(obj, name) {
		if(!obj) return false;
		if(obj.name) {
			if(obj.name == name || (obj.name.toLowerCase && obj.name.toLowerCase() == name)) {
				return obj;
			}
		}
		return this.searchParentByName(obj.parentNode, name);
	}

	bframe.searchParentByTagName = function(obj, tag) {
		if(!obj) return false;
		if(!obj.tagName) return false;
		if(obj.tagName == tag || obj.tagName.toLowerCase() == tag) {
			return obj;
		}
		return this.searchParentByTagName(obj.parentNode, tag);
	}

	bframe.getAbsoluteIndex = function(tr, id) {
		for(i=0 ; i<tr.cells.length ; i++) {
			cell=tr.cells[i];
			for(var j=0 ; j < cell.childNodes.length ; j++) {
				if(cell.childNodes[j].id && cell.childNodes[j].id == id) {
					return i;
				}
			}
		}
		return i;
	}

	bframe.getFrameByName = function(window, name) {
		if(window.name == name) {
			return window;
		}
		for(var i=0 ; i<window.frames.length; i++) {
			w = bframe.getFrameByName(window.frames[i], name);
			if(w) {
				return w;
			}
		}
	}

	bframe.getFrameOffset = function(w, target_frame) {
		if(!w.name || w == top || w == target_frame) {
			return {left: 0, top: 0};
		}

		var offset = bframe.getFrameOffset(w.parent, target_frame);
		var frames = w.parent.document.getElementsByName(w.name);

		pos = bframe.getAbsolutePosition(frames[0]);
		if(offset) {
			pos.left += offset.left;
			pos.top += offset.top;
		}

		return {left: pos.left, top: pos.top};
	}

	bframe.getAbsolutePosition = function(element) {
		var x = 0;
		var y = 0;
		while(element){
			x += element.offsetLeft;
			y += element.offsetTop;
			element = element.offsetParent;
		}
		return({left: x, top: y});
	}

	bframe.getUrlParam = function(paramName) {
		var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
		var match = window.location.search.match(reParam) ;

		return (match && match.length > 1) ? match[1] : '' ;
	}

	bframe.isObject = function(obj) {
		return obj instanceof Object;
	}

	bframe.setOpac = function(opacity, object) { 
	    object.style.opacity = (opacity / 100); 
	    object.style.MozOpacity = (opacity / 100); 
	    object.style.KhtmlOpacity = (opacity / 100); 
	    object.style.filter = 'alpha(opacity=' + opacity + ')'; 
	}

	bframe.printProperties = function(obj) {
		var i=0;
		var properties = '';
		for(var prop in obj) {
			if(i>=0 && i<=20) {
				properties += prop + '=' + obj[prop] + '\n';
			}
			i++;
		}
		alert(properties);
	}

	var _isFF = false;
	var _isIE = false;
	var _isIE8 = false;
	var _isIE9 = false;
	var _isIE10 = false;
	var _isIE11 = false;
	var _isChrome = false;
	var _isOpera = false;
	var _isSafari = false;

	aName = window.navigator.appName.toLowerCase();
	aVersion = window.navigator.appVersion.toLowerCase();
	uName = window.navigator.userAgent.toLowerCase();

	if(uName.match(/chrome/i)) _isChrome = true;
	else if(uName.match(/safari/i)) _isSafari = true;
	else if(uName.match(/opera/i)) _isOpera = true;

	else if(uName.match(/MSIE/i)) {
		_isIE=true;

		if(aVersion.match(/msie 8./i)) {
			_isIE8=true;
		}
		else if(aVersion.match(/msie 9./i)) {
			_isIE9=true;
		}
		else if(aVersion.match(/msie 10./i)) {
			_isIE10=true;
		}
		else if(aVersion.match(/trident\/7/i)) {
			_isIE11=true;
		}
	}
	else if(uName.match(/trident/i)) {
		_isIE11=true;
	}

	else _isFF=true;
