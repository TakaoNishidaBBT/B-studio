/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load', bframeScrollInit);

	function bframeScrollInit() {
		var objects = document.getElementsByClassName('bframe_scroll');
		for(var i=0; i < objects.length; i++) {
			var param = objects[i].getAttribute('data-param');
			if(param) var mode = bframe.getParam('mode', param);

			var s = new bframe.scroll(objects[i], mode);
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.scroll
	// 
	// -------------------------------------------------------------------------
	bframe.scroll = function(target, mode) {
		var self = target;

		var wrapper;
		var bar;
		var barConainer;
		var barHeight;
		var minBarHeight = 30;
		var barScrollHeight;
		var scrollHeight;
		var padding = 2;
		var paddingTop, paddingBottom;
		var bgColor;
		var timer, childNodesScrollTimer;
		var moving, childNodesMoving;
		var speed;
		var wheel_ratio = 1;
		var momentam = 100;
		var lowestDeltaY;
		var startScrollTop;
		var os = bframe.getOS();

		var dragging;
		var draggStartMousePosition;
		var draggStartScrollTop;
		var isMouseOver;

		var bouncescroll;

		if(os == 'windows') {
			if(mode == 'ace') {
				wheel_ratio = 60;
			}
			else {
				wheel_ratio = 14;
			}
		}
		else {
//			return;
			bouncescroll = true;
		}

		self.style.overflow = 'hidden';
		self.style.overflowY = 'hidden';
		self.style.boxSizing = 'border-box';

		var style = bframe.getStyle(self);
		if(style.position.toLowerCase() == 'static') {
			self.style.position = 'relative';
		}
		paddingTop = style.paddingTop.substring(0, style.paddingTop.length-2);
		paddingBottom = style.paddingBottom.substring(0, style.paddingBottom.length-2);

		barHeight = Math.round(self.clientHeight * self.clientHeight / self.scrollHeight);
		barScrollHeight = self.clientHeight - barHeight;
		scrollHeight = self.scrollHeight - self.clientHeight;
		barConainer = document.createElement('div');
		barConainer.style.position = 'absolute';
		barConainer.style.width = '11px';
		barConainer.style.height = self.clientHeight + 'px';
		barConainer.style.right = '0';
		barConainer.style.top = '0';
		barConainer.style.opacity = '0';
		barConainer.style.borderRadius = '6px';
		barConainer.style.boxSizing = 'border-box';

		bar = document.createElement('span');
		bar.style.position = 'absolute';
		bar.style.width = '8px';
		bar.style.height = barHeight - padding*2 + 'px';
		bar.style.opacity = '0';
		bar.style.right = '1px';
		bar.style.top = '0';
		bar.style.borderRadius = '4px';
		bar.style.boxSizing = 'border-box';

		self.appendChild(barConainer);
		self.appendChild(bar);

		bframe.addEventListener(bar, 'mousedown' , onMouseDown);
		bframe.addEventListener(window, 'mousemove' , onMouseMove);
		bframe.addEventListener(window, 'mouseup' , onMouseUp);

		if(self == document.body) {
			bframe.addEventListener(window, 'wheel', onWheel);
		}
		else {
			bframe.addEventListener(self, 'wheel', onWheel);
		}

		bframe.addEventListener(self, 'mouseover', onMouseover);
		bframe.addEventListener(self, 'mouseout', onMouseout);
		bframe.addEventListener(self, 'resize', onResize);
		bframe.addEventListener(self, 'scroll', onScroll);
		bframe.addEventListener(bar, 'mouseover', onContainerMouseover);
		bframe.addEventListener(barConainer, 'mouseover', onContainerMouseover);
		bframe.addEventListener(barConainer, 'mouseout', onContainerMouseout);

		if(mode == 'ace') {
			bframe.addEventListener(self.parentNode, 'wheel', onWheel);
			bframe.addEventListener(self.parentNode, 'resize', onResize);
			bframe.addEventListener(self.parentNode, 'mouseover', onMouseover);
			bframe.addEventListener(self.parentNode, 'mouseout', onMouseout);
		}

		var bartop = self.scrollTop + Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
		bar.style.top = bartop + 'px';

		var hoverObj = document.querySelectorAll('.bframe_scroll:hover');
		for(var i=0; i < hoverObj.length; i++) {
			if(hoverObj[i] == self) {
				bframe.fireEvent(self, 'mouseover');
			}
		}

		if(bframe.resize_handler) {
			bframe.resize_handler.registerCallBackFunction(onResize);
		}

		function onResize() {
			var currentScrollTop = self.scrollTop;

			self.scrollTop = 0;
			self.style.paddingBottom = 0;

			bar.style.top = 0;
			bar.style.height = 0;
			barConainer.style.top = 0;
			barConainer.style.height = 0;
			barConainer.style.display = 'none';

			if(self.clientHeight >= self.scrollHeight) {
				bar.style.opacity = '0';
				barConainer.style.opacity = '0';
			}

			// set barHeight
			barHeight = Math.round(self.clientHeight * self.clientHeight / self.scrollHeight);
			barHeight = !barHeight ? 1 : barHeight;
			barHeight = barHeight < minBarHeight ? minBarHeight : barHeight;
			barHeight = self.clientHeight < minBarHeight ? self.clientHeight / 2 : barHeight;

			barScrollHeight = self.clientHeight - barHeight;
			scrollHeight = self.scrollHeight - self.clientHeight;

			barConainer.style.height = self.clientHeight + 'px';
			bar.style.height = barHeight - padding*2 + 'px';
			var hoverObj = document.querySelectorAll('.bframe_scroll:hover');
			for(var i=0; i < hoverObj.length; i++) {
				if(hoverObj[i] == self) {
					bframe.fireEvent(self, 'mouseover');
				}
			}

			if(!bgColor && bframe.isVisible(self)) {
				bgColor = rgba(bframe.getBgColor(self));
				if(luminance(bgColor) < 120) {
					bar.style.backgroundColor = '#fff';
					bar.style.border = '1px solid #aaa';
					barConainer.style.backgroundColor = '#555';
				}
				else {
					bar.style.backgroundColor = '#000';
					bar.style.border = '1px solid #aaa';
					barConainer.style.backgroundColor = '#ddd';
				}
			}

			if(currentScrollTop > scrollHeight) {
				self.scrollTop = scrollHeight;
			}
			else {
				self.scrollTop = currentScrollTop;
			}

			var bartop = self.scrollTop + Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
			bar.style.top = bartop + 'px';
			// set event handler to child frames

			var iframes = document.getElementsByTagName('iframe');
			for(var i=0; i < iframes.length; i++) {
				if(bframe.isChild(self, iframes[i])) {
					bframe.addEventListenerAllFrames(iframes[i], 'wheel', onFrameWheel);
				}
			}

			// detect scrollable object and set event handler
			detectScrollableObject(self, _callback);
		}
		this.onResize = onResize;

		function detectScrollableObject(node, callback) {
			if(!node || !node.childNodes) return false;
	
			for(var i=0; i<node.childNodes.length; i++) {
				var child = node.childNodes[i];
				if(child.clientHeight && child.clientHeight != child.scrollHeight) {
					var style = bframe.getStyle(child);
					if(child.tagName.toLowerCase() == 'textarea') {
						if(style.overflow != 'hidden' && style.overflowY != 'hidden') {
							callback(child);
						}
					}
					else if(style.overflow == 'scroll' || style.overflow == 'auto' ||
						style.overflowY == 'scroll' || style.overflowY == 'auto') {
						callback(child);
					}
				}
				else {
					detectScrollableObject(child, callback);
				}
			}
		}

		function _callback(node) {
			bframe.addEventListener(node, 'wheel', onChildNodesWheel);
		}

		function onScroll(event) {
			if(self.clientHeight >= self.scrollHeight) return;

			// set scroll bar top
			var bartop = self.scrollTop + Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
			bar.style.top = bartop + 'px';
			bar.style.transition = '';
			bar.style.opacity = '0.6';

			clearTimeout(timer);
			timer = setTimeout(stop, 500);
		}

		function onChildNodesWheel(event) {
			var scrollHeight = this.scrollHeight - this.clientHeight;
			var direction = event.deltaY > 0 ? 'down' : 'up';

			if(!childNodesMoving &&
				((direction == 'up' && this.scrollTop == 0) || (direction == 'down' && this.scrollTop >= scrollHeight))) {
				onWheel(event);
			}
			else {
				event.stopPropagation();
				clearTimeout(childNodesScrollTimer);
				childNodesScrollTimer = setTimeout(onChildNodesScrollStop, 500);
				childNodesMoving = true;
			}
		}

		function onChildNodesScrollStop() {
			childNodesMoving = false;
		}

		function onFrameWheel(event) {
			onWheel(event);
		}

		function _onFrameWheel(event) {
			var obj = bframe.getEventSrcElement(event);
			var body = bframe.searchParentByTagName(obj, 'body');
			var html = bframe.searchParentByTagName(body, 'html');

			if(!html) return;

			var scrollHeight = html.scrollHeight - html.clientHeight;
			var direction = event.deltaY > 0 ? 'down' : 'up';

			if(os == 'windows') {
				if((direction == 'up' && html.scrollTop == 0) || (direction == 'down' && html.scrollTop >= scrollHeight)) {
					onWheel(event);
				}
			}
			else {
				if((direction == 'up' && body.scrollTop == 0) || (direction == 'down' && body.scrollTop >= scrollHeight)) {
					onWheel(event);
				}
			}
		}

		function onWheel(event) {
			if(bframe.stopWheelEvent) {
				var obj = bframe.getEventSrcElement(event);
				if(!bframe.isChild(bframe.activeWheelElement, obj)) return;
			}

			if(self.clientHeight >= self.scrollHeight) return;

			// Look for lowest delta to normalize the delta values
			var deltaY = event.deltaY;
			var absDeltaY = Math.abs(event.deltaY);
			if(!lowestDeltaY || absDeltaY < lowestDeltaY) lowestDeltaY = absDeltaY;
			var fn = event.deltaY > 0 ? 'floor' : 'ceil';
			var direction = event.deltaY > 0 ? 'down' : 'up';
			if(lowestDeltaY !== 0) deltaY = Math[fn](event.deltaY / lowestDeltaY);

			speed = deltaY * wheel_ratio;
			self.scrollTop += speed;
			barConainer.style.top = self.scrollTop + 'px';

			// set scroll bar height (for bounce scroll)
			if(bouncescroll) {
				if(self.scrollTop === 0) {
					self.style.paddingTop = parseInt(paddingTop) + Math.round(speed * -1 / 2) + 'px';
				}
				if(self.scrollTop >= scrollHeight) {
					self.style.paddingBottom = parseInt(paddingBottom) + Math.floor(speed * 1 / 2) + 'px';
					self.scrollTop = scrollHeight + speed;
				}
			}
			if(!moving && direction == 'up' && self.scrollTop === 0) {
				return;
			}
			if(!moving && direction == 'down' && self.scrollTop >= scrollHeight) {
				return;
			}

			event.stopPropagation();
			event.preventDefault();

			// set scroll bar top
			var bartop = self.scrollTop + Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
			bar.style.top = bartop + 'px';
			bar.style.transition = '';
			bar.style.opacity = '0.6';

			// set scroll bar height (for bounce scroll)
			if(bouncescroll) {
				if(self.scrollTop === 0) {
					bar.style.height = barHeight - Math.floor(speed * -1 / 10) - padding*2 + 'px';
				}
				else if(bartop + barHeight >= self.scrollTop + self.clientHeight) {
					bar.style.height = self.scrollTop + self.clientHeight - bartop - padding + 'px';
				}
				else {
					bar.style.height = barHeight - padding*2 + 'px';
				}
			}

			startScrollTop = self.scrollTop;

			if(os != 'mac' && mode != 'ace') {
				animate(
					function(t) {
						return (--t)*t*t+1;
					},
					function(progress) {
						if(direction == 'down') {
							self.scrollTop = startScrollTop + Math.round(progress * momentam);
						}
						else {
							self.scrollTop = startScrollTop - Math.round(progress * momentam);
						}
						if(self.scrollTop >= scrollHeight) self.scrollTop = scrollHeight;
						barConainer.style.top = self.scrollTop + 'px';

						// set scroll bar top
						var bartop = self.scrollTop + Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
						bar.style.top = bartop + 'px';
						if(progress >= 1) {
							clearTimeout(timer);
							timer = setTimeout(stop, 100);
						}
					},
					400
				);
			}
			else {
				clearTimeout(timer);
				timer = setTimeout(stop, 500);
			}

			moving = true;
		}

		function stop() {
			bar.style.transition = 'opacity 0.4s ease-out';

			if(self.clientHeight < self.scrollHeight && isMouseOver) {
				bar.style.opacity = '0.2';
			}
			else {
				bar.style.opacity = '0';
			}
			moving = false;
		}

		function animate(timing, callback, duration) {
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
			});
		}

		function onMouseover(event) {
			isMouseOver = true;

			if(self.clientHeight >= self.scrollHeight) return;

			bar.style.transition = 'opacity 0.4s ease-out';
			bar.style.opacity = '0.2';
		}

		function onMouseout(event) {
			isMouseOver = false;

			if(dragging) return;
			if(self.clientHeight >= self.scrollHeight) return;

			bar.style.transition = 'opacity 0.4s ease-out';
			bar.style.opacity = '0';
			barConainer.style.transition = 'opacity 0.2s ease-out';
			barConainer.style.opacity = '0';
		}

		function onContainerMouseover(event) {
			isMouseOver = true;

			if(self.clientHeight >= self.scrollHeight) return;

			barConainer.style.transition = 'opacity 0.2s ease-out';
			barConainer.style.opacity = '0.7';
			bar.style.opacity = '0.6';

			event.stopPropagation();
		}

		function onContainerMouseout(event) {
			if(dragging) return;
			if(self.clientHeight >= self.scrollHeight) return;

			barConainer.style.transition = 'opacity 0.2s ease-out';
			barConainer.style.opacity = '0';
		}

		function onMouseDown(event) {
			if(self.clientHeight >= self.scrollHeight) return;

			draggStartMousePosition = bframe.getMousePosition(event);
			draggStartScrollTop = self.scrollTop;
			dragging = true;

			event.preventDefault();
		}

		function onMouseMove(event) {
			if(!dragging) return;
			if(self.clientHeight >= self.scrollHeight) return;

			mpos = bframe.getMousePosition(event);
			self.scrollTop = draggStartScrollTop + (mpos.y - draggStartMousePosition.y) * Math.round(scrollHeight / barScrollHeight);
			barConainer.style.top = self.scrollTop + 'px';
			var bartop = self.scrollTop + Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
			bar.style.top = bartop + 'px';
			bar.style.opacity = '0.6';
		}

		function onMouseUp(event) {
			if(!dragging) return;
			if(self.clientHeight >= self.scrollHeight) return;

			dragging = false;

			if(isMouseOver) {
				bar.style.opacity = '0.2';
			}
			else {
				bar.style.opacity = '0';
			}
			barConainer.style.opacity = '0';
		}

		function rgba(color) {
			return color
			.replace(/^rgba?\(/, '')
			.replace(/\)$/, '')
			.split(', ');
		}

		function luminance(rgba) {
			var r = 0.298912;
			var g = 0.586611;
			var b = 0.114478;
			return Math.floor(r * rgba[0] + g * rgba[1] + b * rgba[2]);
		}

		function getMousePosition(e) {
			var obj = new Object();
			
			if(e) {
				obj.x = e.pageX;
				obj.y = e.pageY;
			}
			else {
				obj.x = event.x + document.body.scrollLeft;
				obj.y = event.y + document.body.scrollTop;
			}

			return obj;
		}
	}