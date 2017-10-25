/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class bframe.popup
	// 
	// -------------------------------------------------------------------------
	bframe.popup = function(w, zindex, drop_shadow, transparent) {
		var frame = w;
		var document = w.document;
		var body = new body(zindex+10, drop_shadow, transparent);

		this.getContents = function() {
			return body.getContents();
		}

		this.contents = function(contents) {
			body.contents(contents);
		}

		this.appendChild = function(obj) {
			body.appendChild(obj);
		}

		this.removeChild = function() {
			body.removeChild();
		}

		this.getElementPosition = function() {
			return body.getElementPosition();
		}

		this.getScrollTop = function() {
			return body.getScrollTop();
		}

		this.setScrollTop = function(s) {
			body.setScrollTop(s);
		}

		this.setBorder = function(value) {
			body.setBorder(value);
		}

		this.show = function(event) {
			body.show();
		}

		this.hide = function() {
			body.hide();
		}

		this.cleanUp = function() {
			body.cleanUp();
		}

		this.position = function(position) {
			if(typeof document.body.style.maxHeight == 'undefined') {
				// IE6, older browsers
				var scrollTop = document.body.scrollTop;
				var scrollLeft = document.body.scrollLeft;
			}
			else {
				// IE 7, mozilla, safari, opera 9
				var scrollTop = document.documentElement.scrollTop;
				var scrollLeft = document.documentElement.scrollLeft;
			}
			var p = {top: scrollTop + position.top, left: scrollLeft + position.left};

			body.position(p);
		}

		this.positionAbsolute = function(p) {
			body.position(p);
		}

		this.size = function(size, force)  {
			body.size(size, force);
		}

		this.overflowY = function(value) {
			body.overflowY(value);
		}

		this.offsetHeight = function() {
			return body.offsetHeight();
		}

		this.visibility = function() {
			return body.visibility();
		}

		function body(zindex, drop_shadow, transparent) {
			var contents;

			var element = document.createElement('div');
			element.name = 'popup';
			element.className = 'popup';
			element.style.display = 'table-cell';
			element.style.position = 'absolute';
			element.style.overflowX = 'visible';
			element.style.overflowY = 'visible';
			element.style.visibility = 'hidden';
			element.style.padding = 0;
			element.style.margin = 0;
			element.style.zIndex = parseInt(zindex);
			if(transparent) {
				element.style.backgroundColor = 'transparent';
			}
			if(drop_shadow) {
				element.className+= ' drop_shadow';
			}

			element.style.top = 0;
			element.style.left = 0;

			element.style.width = 0;
			element.style.height = 0;

			document.body.appendChild(element);

			if(typeof document.body.style.maxHeight == 'undefined') {
				var cv = new cover(zindex);
			}

			this.position = function(position) {
				element.style.top = position.top + 'px';
				element.style.left = position.left + 'px';
				if(cv) {
					cv.position(position);
				}
			}

			this.size = function(size, force) {
				if(force) {
					var width = size.width;
					var height = size.height;
				}
				else {
					var width = size.width > contents.offsetWidth ? size.width : contents.offsetWidth;
					var height = size.height > contents.offsetHeight ? size.height : contents.offsetHeight;
				}
				element.style.width = width + 'px';
				element.style.height = height + 'px';
				if(cv) {
					s = {height:parseInt(size.height), width:parseInt(size.width)};
					cv.size(s);
				}
			}

			this.overflowY = function(value) {
				element.style.overflowY = value;
			}

			this.setBorder = function(value) {
				element.style.border = value;
			}

			this.getElementPosition = function() {
				return bframe.getElementPosition(element);
			}

			this.getScrollTop = function() {
				return element.scrollTop;
			}

			this.setScrollTop = function(s) {
				element.scrollTop = s;
			}

			this.contents = function(html) {
				element.innerHTML = html;
				contents = element.childNodes[0];
			}

			this.appendChild = function(obj) {
				contents = obj;
				element.appendChild(obj);
			}

			this.removeChild = function() {
				for(var i=0; element.childNodes.length; i++) {
					element.removeChild(element.childNodes.item(i));
				}
			}

			this.getContents = function() {
				return element;
			}

			this.offsetHeight = function() {
				return element.offsetHeight;
			}

			this.backgroundColor = function(color) {
				element.style.backgroundColor = color;
			}

			this.show = function() {
				if(cv) cv.show();

				element.style.visibility='visible';
			}

			this.hide = function() {
				if(cv) cv.hide();

				element.style.visibility='hidden';
			}

			this.cleanUp = function() {
				bframe.removeElement(element);

				if(cv) cv.cleanUp();
			}

			this.visibility = function() {
				return element.style.visibility;
			}
		}

		function cover(zindex) {
			var element = document.createElement('iframe');

			element.style.position = 'absolute';
			element.style.overflow = 'hidden';
			element.style.visibility = 'hidden';
			element.style.padding = 0;
			element.style.margin = 0;
			element.style.zIndex = parseInt(zindex);
			element.style.filter = 'alpha(opacity=0)'; 

			element.style.top = 0;
			element.style.left = 0;
			element.style.width = 0;
			element.style.height = 0;

			element.frameBorder = 0;
			element.src = 'javascript:false;';

			document.body.appendChild(element);

			this.position = function(position) {
				element.style.top = position.top + 'px';
				element.style.left = position.left + 'px';
			}

			this.size = function(size) {
				element.style.width = size.width + 'px';
				element.style.height = size.height + 'px';
			}

			this.show = function() {
				element.style.visibility='visible';
			}

			this.hide = function() {
				element.style.visibility='hidden';
			}

			this.cleanUp = function() {
				bframe.removeElement(element);
			}
		}
	}
