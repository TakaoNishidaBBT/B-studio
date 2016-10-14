/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeCalendarInit);

	function bframeCalendarInit() {
		var cc = new bframe.calendar_container();
		var a = document.getElementsByTagName('a');
		var c = new Array();
		var cnt=0;

		for(var i=0; i<a.length; i++) {
			if(bframe.checkClassName('bframe_calendar', a[i])) {
				c[cnt++] = new bframe.calendar(a[i], cc);
			}
		}

		var input = document.getElementsByTagName('input');

		for(i=0; i<input.length; i++) {
			if(bframe.checkClassName('bframe_calendar', input[i])) {
				c[cnt++] = new bframe.calendar(input[i], cc);
			}
		}

		cc.setCalendars(c);

		bframe.calendarContainer = cc;
	}

	// -------------------------------------------------------------------------
	// class bframe.calendar_container
	// 
	// -------------------------------------------------------------------------
	bframe.calendar_container = function() {
		var calendars;

		this.setCalendars = function(obj) {
			calendars = obj;
		}

		this.closeAll = function() {
			for(var i=0; i<calendars.length; i++) {
				if(calendars[i].hide) {
					calendars[i].hide();
				}
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.calendar
	// 
	// -------------------------------------------------------------------------
	bframe.calendar = function(target, cc) {
		var calendar_container = cc;
		var	response_wait = false;
		var property;
		var httpObj;
		var popup;

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

		var zindex = 10;
		var position;
		var size;

		var year = 0;
		var month = 0;
		var day = 0;
		var mode = '';

		var target;

		bframe.addEventListner(target, 'click' , show);
		bframe.addEventListnerAllFrames(top, 'click', hide);

		init();

		function init() {
			var param;

			param = 'terminal_id='+terminal_id+'&mode='+mode+'&class=bframe_calendar&id='+target.id;
			httpObj = createXMLHttpRequest(initResponse);
			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;
		}

		function initResponse() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				size = {width:property.width, height:property.height};

				target = document.getElementById(property.target);

				popup = new bframe.calendarPopup(100, property.drop_shadow, property.transparent);

				response_wait = false;
			}
		}

		function hide() {
			popup.hidePopUp();
		}
		this.hide = hide;

		function show(event) {
			if(popup.isOpen()) {
				hide();
				bframe.stopPropagation(event);
				return false;
			}
			var param;

			year = 0;
			month = 0;
			day = 0;
			mode = '';

			if(target.value) {
				var date = target.value.split('/');
				year = date[0];
				month = date[1];
				day = date[2];
			}

			param = 'terminal_id='+terminal_id+'&mode='+mode;
			param += '&year='+year+'&month='+month+'&day='+day;

			httpObj = createXMLHttpRequest(showResponse);

			eventHandler(httpObj, property.ajax.module, property.ajax.file, property.ajax.method, 'POST', param);
			response_wait = true;

			bframe.stopPropagation(event);
			bframe.fireEvent(document, 'click');

			position = bframe.getElementPosition(target);
			if(property.offsetLeft) {
				position.left += parseInt(property.offsetLeft);
			}

			return false;
		}

		function showResponse() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				xmlData = httpObj.responseXML;
				year = xmlData.getElementsByTagName('year')[0].firstChild.nodeValue;
				month = xmlData.getElementsByTagName('month')[0].firstChild.nodeValue;

				popup.setPopUpContents(xmlData.getElementsByTagName('innerHTML')[0].firstChild.nodeValue);
				popup.setPopUpPosition(position);
				popup.setPopUpSize(size);

				var element = popup.getContents();
				var table = element.getElementsByTagName('table')[0];
				var link = table.caption.getElementsByTagName('a');
				link[0].onclick = prevMonth;
				link[1].onclick = nextMonth;
				link[0].onfocus = bframe.blur;
				link[1].onfocus = bframe.blur;

				for(var i=1; i<table.rows.length; i++) {
					var tr = table.rows[i];
					for(var j=0; j<tr.cells.length; j++) {
						if(tr.cells[j].innerHTML) {
							tr.cells[j].style.cursor = 'pointer';
							tr.cells[j].onclick = setDate;
						}
					}
				}

				element.onclick = nop;

				calendar_container.closeAll();
				popup.showPopUp();

				response_wait = false;
			}
		}

		function setDate(event) {
			var src_obj = bframe.getEventSrcElement(event);
			day = src_obj.childNodes[0].nodeValue;

			month = ('0' + month).slice(-2);
			day = ('0' + day).slice(-2);

			if(target.value != year+'/'+month+'/'+day) {
				target.value = year+'/'+month+'/'+day;
				bframe.fireEvent(target, 'change');
			}

			hide();
		}

		function prevMonth(event) {
			mode = 'prev';
			changeMonth(event);
		}

		function nextMonth(event) {
			mode = 'next';
			changeMonth(event);
		}

		function changeMonth(event) {
			var param;

			param = 'terminal_id='+terminal_id+'&mode='+mode;
			param += '&year='+year+'&month='+month;

			httpObj = createXMLHttpRequest(showResponse);

			eventHandler(httpObj, property.ajax.module, property.ajax.file, property.ajax.method, 'POST', param);
			response_wait = true;

			bframe.stopPropagation(event);

			return false;
		}

		function nop(event) {
			bframe.stopPropagation(event);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.calendarPopup
	// 
	// -------------------------------------------------------------------------
	bframe.calendarPopup = function(zindex, drop_shadow, transparent) {
		var opened;

		if(!zindex) {
			zindex = 1;
		}
		var popup = new bframe.popup(window, zindex, drop_shadow, transparent);

		this.setPopUpPosition = function(position) {
			popup.position(position);
		}

		this.setPopUpSize = function(size) {
			popup.size(size);
		}

		this.setPopUpContents = function(contents) {
			popup.contents(contents);
		}

		this.getContents = function() {
			return popup.getContents();
		}

		this.showPopUp = function() {
			popup.show();
			opened = true;
		}

		this.hidePopUp = function() {
			popup.hide();
			opened = false;
		}

		this.isOpen = function() {
			return opened;
		}
	}
