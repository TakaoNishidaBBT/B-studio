/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeElasticTableInit);

	function bframeElasticTableInit() {
		var objects = document.querySelectorAll('table.bframe_elastic');

		for(var i=0; i < objects.length; i++) {
			var e = new bframe.elastic_table(objects[i]);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.elastic_table
	// 
	// -------------------------------------------------------------------------
	bframe.elastic_table = function(target) {
		var self = this;
		var table = target;
		var drag_control = new dragControl(target);
		var padding = 20;

		function init() {
			let th = table.querySelectorAll('th');
			for(let i=1; i < th.length; i++) {
				initTableHeader(th[i]);
			}
		}

		function initTableHeader(th) {
			var span = document.createElement('span');
			span.style.height = '100%';
			span.style.width = '10px';
			span.style.zIndex = '999';
			span.style.position = 'absolute';
			span.style.top = '0';
			span.style.left = '-6px';
			span.style.padding = '0';

			th.style.boxSizing = 'border-box';
			th.style.width = th.clientWidth + 'px';
			th.appendChild(span);

			bframe.addEventListener(span, 'mouseover', onMouseOver);
			bframe.addEventListener(span, 'mouseout', onMouseOut);
			bframe.addEventListener(span, 'dblclick', onDoubleClick);

			bframe.addEventListener(span, 'mousedown', onMouseDown);
			bframe.addEventListener(span, 'mousemove', onMouseMove);
			bframe.addEventListener(span, 'mouseup', onMouseUp);
		}

		function onMouseOver(event) {
			var obj = bframe.getEventSrcElement(event);
			obj.style.cursor = 'col-resize';
		}

		function onMouseOut(event) {
			var obj = bframe.getEventSrcElement(event);
			obj.style.cursor = 'auto';
		}

		function onDoubleClick(event) {
			var obj = bframe.getEventSrcElement(event);
			var p = obj.parentNode;
			var th = table.rows[0].cells[p.cellIndex -1];
			var maxWidth;
			var start_width = th.clientWidth;
			var start_table_width = table.clientWidth;

			for(let i=1; i < table.rows.length; i++) {
				var scrollWidth = table.rows[i].cells[th.cellIndex].scrollWidth;
				maxWidth = maxWidth > table.rows[i].cells[th.cellIndex].scrollWidth ? maxWidth : table.rows[i].cells[th.cellIndex].scrollWidth;
			}
			if(start_width < maxWidth) {
				th.style.width = maxWidth + padding + 'px';
				table.style.width = start_table_width + maxWidth - start_width + padding + 'px';
				bframe.fireEvent(window, 'resize');
			}
		}

		function onMouseDown(event) {
			drag_control.dragStart(event);
		}

		function onMouseMove(event) {
			drag_control.dragging(event);
		}

		function onMouseUp(event) {
			drag_control.dragStop();
		}

		init();

		// -------------------------------------------------------------------------
		// class dragControl
		// -------------------------------------------------------------------------
		function dragControl(table) {
			var self = this;
			var button_status;
			var drag_status;
			var start_x, start_width, start_table_width;
			var start_position;
			var target;
			var th;

			bframe.addEventListener(window, 'mousemove', onMouseMove);
			bframe.addEventListener(window, 'mouseup', onMouseUp);
			bframe.addEventListener(window, 'mousemove', onMouseMove);
			bframe.addEventListener(window, 'mousemove', dragging);

			this.dragStart = function(event) {
				if(bframe.getButton(event) != 'L') return;

				var e = window.event ? window.event : event;
				button_status = true;

				target = bframe.getEventSrcElement(event);
				var p = target.parentNode;
				th = table.rows[0].cells[p.cellIndex -1];

				var m = bframe.getMousePosition(event);
				start_position = m;

				start_x = m.screenX;
				start_width = th.clientWidth;
				start_table_width = table.clientWidth;

				if(_isIE) {
					window.event.returnValue = false;
				}
				else {
					event.preventDefault();
				}
			}

			function dragging(event) {
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
			}
			this.dragging = dragging;

			function onMouseMove(event) {
				if(!drag_status) return;

				var m = bframe.getMousePosition(event);

				setWidth(event);
			}

			this.dragStop = function() {
				if(!drag_status) return;

				drag_status = false;
				button_status = false;
				target = false;
			}

			function onMouseUp(event) {
				drag_status = false;
				button_status = false;
			}
			this.onMouseUp = onMouseUp;

			function setWidth(event) {
				var m = bframe.getMousePosition(event);
				th.style.width = parseInt(start_width + m.screenX - start_x) + 'px';
				table.style.width = parseInt(start_table_width + m.screenX - start_x) + 'px';

				bframe.fireEvent(window, 'resize');
			}
		}
	}
