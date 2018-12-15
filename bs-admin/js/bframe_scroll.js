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
		var barX, barY;
		var barContainerX, barContainerY;
		var barWidth, barHeight;
		var minBarHeight = 30;
		var minBarWidth = 30;
		var barScrollWidth, barScrollHeight;
		var scrollWidth, scrollHeight;
		var padding = 2;
		var paddingTop, paddingRight, paddingBottom, paddingLeft;
		var bgColor;
		var timerX, timerY, childNodesScrollTimer;
		var movingX, movingY, childNodesMoving;
		var speed;
		var wheel_ratio = 1;
		var momentam = 100;
		var lowestDeltaX, lowestDeltaY;
		var startScrollLeft, startScrollTop;
		var os = bframe.getOS();
		var parentStyle;

		var draggingX, draggingY;
		var draggStartMousePosition;
		var draggStartScrollTop;
		var isMouseOver;
		var scrollTarget;

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
			if(mode == 'ace') return;
			bouncescroll = true;
		}

		if(target == document.body) { // for body
			var bodyStyle = bframe.getStyle(document.body);

			var body = document.createElement('div');
			while(self.firstChild) {
				body.appendChild(self.firstChild);
			}
			self.appendChild(body);
			self = body;

			self.style.width = '100%';
			self.style.height = '100%';

			self.style.padding = bodyStyle.padding;
			self.style.paddingTop = bodyStyle.paddingTop;
			self.style.paddingRight = bodyStyle.paddingRight;
			self.style.paddingBottom = bodyStyle.paddingBottom;
			self.style.paddingLeft = bodyStyle.paddingLeft;

			document.body.style.padding = '0';
			document.body.style.overflow = 'hidden';

			bframe.adjustwindow(self);
		}

		self.style.overflow = 'hidden';
		self.style.boxSizing = 'border-box';

		var style = bframe.getStyle(self);

		// vertical
		paddingTop = parseInt(style.paddingTop);
		paddingBottom = parseInt(style.paddingBottom);
		barHeight = Math.round(self.clientHeight * self.clientHeight / self.scrollHeight);
		barScrollHeight = self.clientHeight - barHeight;
		scrollHeight = self.scrollHeight - self.clientHeight;

		// horizontal
		paddingLeft = parseInt(style.paddingLeft);
		paddingRight = parseInt(style.paddingBottom);
		barWidth = Math.round(self.clientWidth * self.clientWidth / self.scrollWidth);
		barScrollWidth = self.clientWidth - barWidth;
		scrollWidth = self.scrollWidth - self.clientWidth;

		// barContainerY
		barContainerY = document.createElement('div');
		barContainerY.className = 'ba-container';
		barContainerY.style.position = 'absolute';
		barContainerY.style.width = '11px';
		barContainerY.style.height = self.clientHeight + 'px';
		barContainerY.style.right = '0';
		barContainerY.style.top = '0';
		barContainerY.style.opacity = '0';
		barContainerY.style.borderRadius = '6px';
		barContainerY.style.boxSizing = 'border-box';
		barContainerY.style.zIndex = '9999';

		// barY
		barY = document.createElement('span');
		barY.style.position = 'absolute';
		barY.style.width = '8px';
		barY.style.height = barHeight - padding*2 + 'px';
		barY.style.opacity = '0';
		barY.style.right = '1px';
		barY.style.top = '0';
		barY.style.borderRadius = '4px';
		barY.style.boxSizing = 'border-box';
		barY.style.zIndex = '99999';
		barY.style.pointerEvents = 'auto';

		// barContainerX
		barContainerX = document.createElement('div');
		barContainerX.className = 'ba-container';
		barContainerX.style.position = 'absolute';
		barContainerX.style.height = '11px';
		barContainerX.style.width = self.clientWidth + 'px';
		barContainerX.style.left = '0';
		barContainerX.style.bottom = '0';
		barContainerX.style.opacity = '0';
		barContainerX.style.borderRadius = '6px';
		barContainerX.style.boxSizing = 'border-box';
		barContainerX.style.zIndex = '9999';

		// barX
		barX = document.createElement('span');
		barX.style.position = 'absolute';
		barX.style.height = '8px';
		barX.style.width = barWidth - padding*2 + 'px';
		barX.style.opacity = '0';
		barX.style.bottom = '1px';
		barX.style.left = '0';
		barX.style.borderRadius = '4px';
		barX.style.boxSizing = 'border-box';
		barX.style.zIndex = '99999';
		barX.style.pointerEvents = 'auto';

		// set parent positio to relative
		parentStyle = bframe.getStyle(self.parentNode);
		if(parentStyle.position.toLowerCase() == 'static') {
			self.parentNode.style.position = 'relative';
		}

		// wrapper
		wrapper = document.createElement('div');

		wrapper.className = 'wrapper';
		wrapper.style.position = 'absolute';

		position();

		wrapper.style.width = self.clientWidth + 'px';
		wrapper.style.height = self.clientHeight + 'px';
		wrapper.style.backgroundColor = 'transparent';
		wrapper.style.border = 'none';
		wrapper.style.opacity = '1';
		wrapper.style.zIndex = '999';
		wrapper.style.boxSizing = 'border-box';
		wrapper.style.pointerEvents = 'none';

		self.parentNode.appendChild(wrapper);

		wrapper.appendChild(barContainerY);
		wrapper.appendChild(barY);

		wrapper.appendChild(barContainerX);
		wrapper.appendChild(barX);

		bframe.addEventListener(barY, 'mousedown' , onMouseDownY);
		bframe.addEventListenerAllFrames(top, 'mousemove' , onMouseMoveY);
		bframe.addEventListenerAllFrames(top, 'mouseup' , onMouseUpY);

		bframe.addEventListener(barX, 'mousedown' , onMouseDownX);
		bframe.addEventListenerAllFrames(top, 'mousemove' , onMouseMoveX);
		bframe.addEventListenerAllFrames(top, 'mouseup', onMouseUpX);

		bframe.addEventListener(self, 'wheel', onWheel);

		bframe.addEventListener(self, 'mouseover', onMouseover);
		bframe.addEventListener(self, 'mouseout', onMouseout);
		bframe.addEventListener(self, 'resize', onResize);
		bframe.addEventListener(self, 'scroll', onScroll);

		bframe.addEventListener(barY, 'mouseover', onContainerMouseoverY);
		bframe.addEventListener(barContainerY, 'mouseover', onContainerMouseoverY);
		bframe.addEventListener(barContainerY, 'mouseout', onContainerMouseoutY);

		bframe.addEventListener(barX, 'mouseover', onContainerMouseoverX);
		bframe.addEventListener(barContainerX, 'mouseover', onContainerMouseoverX);
		bframe.addEventListener(barContainerX, 'mouseout', onContainerMouseoutX);

		if(mode == 'ace') {
			bframe.addEventListener(self.parentNode, 'wheel', onWheel);
			bframe.addEventListener(self.parentNode, 'resize', onResize);
			bframe.addEventListener(self.parentNode, 'mouseover', onMouseover);
			bframe.addEventListener(self.parentNode, 'mouseout', onMouseout);
		}

		bframe.addEventListener(self, 'click', onClick);
		bframe.addEventListenerAllFrames(top, 'mousedown', onMousedown);
		bframe.addEventListenerAllFrames(top, 'keydown', onKeydown);

		var bartop = self.scrollTop + Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
		barY.style.top = bartop + 'px';

		var hoverObj = document.querySelectorAll('.bframe_scroll:hover');
		for(var i=0; i < hoverObj.length; i++) {
			if(hoverObj[i] == self) {
				bframe.fireEvent(self, 'mouseover');
			}
		}

		if(bframe.resize_handler) {
			bframe.resize_handler.registerCallBackFunction(onResize);
		}
		if(bframe.ajaxSubmit) {
			bframe.ajaxSubmit.registerCallBackFunctionAfter(onResize);
		}

		onResize();

		function position() {
			if(style.position.toLowerCase() == 'absolute' || style.position.toLowerCase() == 'fixed') {
				wrapper.style.top = style.top;
				wrapper.style.right = style.right;
				wrapper.style.bottom = style.bottom;
				wrapper.style.left = style.left;
			}
			else {
				wrapper.style.top = self.offsetTop + parseInt(parentStyle.paddingTop) + 'px';
				wrapper.style.left = self.offsetLeft + parseInt(parentStyle.paddingLeft) + 'px';
			}
		}
		this.position = position;

		function onResize() {
			if(self.clientHeight >= self.scrollHeight && self.clientWidth >= self.scrollWidth) {
				wrapper.style.display = 'none';
				return;
			}
			else {
				wrapper.style.display = 'block';
			}


			var currentScrollTop = self.scrollTop;
			var currentScrollLeft = self.scrollLeft;

			wrapper.style.width = self.clientWidth + 'px';
			wrapper.style.height = self.clientHeight + 'px';

			// vertical
			self.scrollTop = 0;
			self.style.paddingBottom = 0;

			barY.style.top = 0;
			barY.style.height = 0;
			barContainerY.style.top = 0;
			barContainerY.style.height = 0;
			barContainerY.style.display = 'none';

			if(self.clientHeight >= self.scrollHeight) {
				barY.style.opacity = '0';
				barContainerY.style.opacity = '0';
			}

			// set barHeight
			barHeight = Math.round(self.clientHeight * self.clientHeight / self.scrollHeight);
			barHeight = !barHeight ? 1 : barHeight;
			barHeight = barHeight < minBarHeight ? minBarHeight : barHeight;
			barHeight = self.clientHeight < minBarHeight ? self.clientHeight / 2 : barHeight;

			barScrollHeight = self.clientHeight - barHeight;
			scrollHeight = self.scrollHeight - self.clientHeight;

			barContainerY.style.height = self.clientHeight + 'px';
			barY.style.height = barHeight - padding*2 + 'px';

			// horizontal
			self.scrollLeft = 0;
			self.style.paddingRight = 0;

			barX.style.left = 0;
			barX.style.width = 0;
			barContainerX.style.left = 0;
			barContainerX.style.width = 0;
			barContainerX.style.display = 'none';

			if(self.clientWidth >= self.scrollWidth) {
				barX.style.opacity = '0';
				barContainerX.style.opacity = '0';
			}

			// set width
			barWidth = Math.round(self.clientWidth * self.clientWidth / self.scrollWidth);
			barWidth = !barWidth ? 1 : barWidth;
			barWidth = barWidth < minBarWidth ? minBarWidth : barWidth;
			barWidth = self.clientWidth < minBarWidth ? self.clientWidth / 2 : barWidth;

			barScrollWidth = self.clientWidth - barWidth;
			scrollWidth = self.scrollWidth - self.clientWidth;

			barContainerX.style.width = self.clientWidth + 'px';
			barX.style.width = barWidth - padding*2 + 'px';

			var hoverObj = document.querySelectorAll('.bframe_scroll:hover');
			for(var i=0; i < hoverObj.length; i++) {
				if(hoverObj[i] == self) {
					bframe.fireEvent(self, 'mouseover');
				}
			}

			if(!bgColor && bframe.isVisible(self)) {
				bgColor = rgba(bframe.getBgColor(self));
				if(luminance(bgColor) < 120) {
					barY.style.backgroundColor = '#fff';
					barY.style.border = '1px solid #aaa';
					barContainerY.style.backgroundColor = '#555';

					barX.style.backgroundColor = '#fff';
					barX.style.border = '1px solid #aaa';
					barContainerX.style.backgroundColor = '#555';
				}
				else {
					barY.style.backgroundColor = '#000';
					barY.style.border = '1px solid #aaa';
					barContainerY.style.backgroundColor = '#ddd';

					barX.style.backgroundColor = '#000';
					barX.style.border = '1px solid #aaa';
					barContainerX.style.backgroundColor = '#ddd';
				}
			}

			if(currentScrollTop > scrollHeight) {
				self.scrollTop = scrollHeight;
			}
			else {
				self.scrollTop = currentScrollTop;
			}

			if(currentScrollLeft > scrollWidth) {
				self.scrollLeft = scrollWidth;
			}
			else {
				self.scrollLeft = currentScrollLeft;
			}

			var bartop = Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
			barY.style.top = bartop + 'px';

			var barleft = Math.round(barScrollWidth * self.scrollLeft / scrollWidth) + padding;
			barX.style.left = barleft + 'px';

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
						if(style.overflow != 'hidden' && (style.overflowY != 'hidden' || style.overflowX != 'hidden')) {
							callback(child);
						}
					}
					else if(style.overflow == 'scroll' || style.overflow == 'auto' ||
						style.overflowY == 'scroll' || style.overflowY == 'auto'  ||
						style.overflowX == 'scroll' || style.overflowX == 'auto') {
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
			onScrollX(event);
			onScrollY(event);
		}

		function onScrollX(event) {
			if(self.clientWidth >= self.scrollWidth) return;

			// set scroll bar top
			var barleft = Math.round(barScrollWidth * self.scrollLeft / scrollWidth) + padding;
			barX.style.left = barleft + 'px';
			barX.style.transition = '';
			barX.style.opacity = '0.6';

			clearTimeout(timerX);
			timerX = setTimeout(stopX, 500);
		}

		function onScrollY(event) {
			if(self.clientHeight >= self.scrollHeight) return;

			// set scroll bar top
			var bartop = Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;
			barY.style.top = bartop + 'px';
			barY.style.transition = '';
			barY.style.opacity = '0.6';

			clearTimeout(timerY);
			timerY = setTimeout(stopY, 500);
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
			onWheelX(event);
			onWheelY(event);
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
			onWheelX(event);
			onWheelY(event);
		}

		function onWheelX(event) {
			if(bframe.stopWheelEvent) {
				var obj = bframe.getEventSrcElement(event);
				if(!bframe.isChild(bframe.activeWheelElement, obj)) return;
			}

			if(self.clientWidth >= self.scrollWidth) return;

			// scroll vertical and horizontal are alternate
			if(Math.abs(event.deltaX) < Math.abs(event.deltaY)) return;

			// Look for lowest delta to normalize the delta values
			var deltaX = event.deltaX;
			var absDeltaX = Math.abs(event.deltaX);
			if(!lowestDeltaX || absDeltaX < lowestDeltaX) lowestDeltaX = absDeltaX;
			var fn = event.deltaX > 0 ? 'floor' : 'ceil';
			var directionX = event.deltaX > 0 ? 'right' : event.deltaX == 0 ? '' : 'left';

			if(lowestDeltaX !== 0) deltaX = Math[fn](event.deltaX / lowestDeltaX);

			speedX = deltaX * wheel_ratio;
			self.scrollLeft += speedX;

			// set padding (for bounce scroll)
			if(bouncescroll) {
				if(self.scrollLeft === 0) {
					self.style.paddingLeft = paddingLeft + Math.round(speedX * -1 / 2) + 'px';
				}
				if(self.scrollLeft >= scrollWidth) {
					self.style.paddingRight = paddingRight + Math.floor(speedX * 1 / 2) + 'px';
					self.scrollLeft = scrollWidth + speedX;
				}
				if(movingX && directionX == 'right') {
					self.style.paddingLeft = paddingLeft;
				}
				if(movingX && directionX == 'left') {
					self.style.paddingRight = paddingRight;
				}
			}

			if(!movingX && directionX == '') {
				return;
			}
			if(!movingX && directionX == 'left' && self.scrollLeft === 0) {
				return;
			}
			if(!movingX && directionX == 'right' && self.scrollLeft >= scrollWidth) {
				return;
			}

			event.stopPropagation();
			event.preventDefault();

			// set scroll bar left
			var barleft = Math.round(barScrollWidth * self.scrollLeft / scrollWidth) + padding;
			barX.style.left = barleft + 'px';
			barX.style.transition = '';
			barX.style.opacity = '0.6';

			// set scroll bar height (for bounce scroll)
			if(bouncescroll) {
				if(self.scrollLeft === 0) {
					barX.style.width = barWidth - Math.floor(speedX * -1 / 10) - padding*2 + 'px';
				}
				else if(barleft + barWidth >= self.clientWidth) {
					barX.style.width = self.clientWidth - barleft - padding + 'px';
				}
				else {
					barX.style.width = barWidth - padding*2 + 'px';
				}
			}

			if(os != 'mac' && mode != 'ace') {
				animate(
					function(t) {
						return (--t)*t*t+1;
					},
					function(progress) {
						if(directionX == 'right') {
							self.scrollLeft = startScrollLeft + Math.round(progress * momentam);
						}
						else {
							self.scrollLeft = startScrollLeft - Math.round(progress * momentam);
						}
						if(self.scrollLeft >= scrollWidth) self.scrollLeft = scrollWidth;

						var barleft = Math.round(barScrollWidth * self.scrollLeft / scrollWidth) + padding;

						// set scroll bar left
						barX.style.left = barleft + 'px';
						if(progress >= 1) {
							clearTimeout(timerX);
							timerX = setTimeout(stopX, 100);
						}
					},
					400
				);
			}
			else {
				clearTimeout(timerX);
				timerX = setTimeout(stopX, 500);
			}

			movingX = true;
		}

		function onWheelY(event) {
			if(bframe.stopWheelEvent) {
				var obj = bframe.getEventSrcElement(event);
				if(!bframe.isChild(bframe.activeWheelElement, obj)) return;
			}

			if(self.clientHeight >= self.scrollHeight) return;

			// scroll vertical and horizontal are alternate
			if(Math.abs(event.deltaY) < Math.abs(event.deltaX)) return;

			// Look for lowest delta to normalize the delta values
			var deltaY = event.deltaY;
			var absDeltaY = Math.abs(event.deltaY);
			if(!lowestDeltaY || absDeltaY < lowestDeltaY) lowestDeltaY = absDeltaY;
			var fn = event.deltaY > 0 ? 'floor' : 'ceil';
			var directionY = event.deltaY > 0 ? 'down' : event.deltaY == 0 ? '' : 'up';

			if(lowestDeltaY !== 0) deltaY = Math[fn](event.deltaY / lowestDeltaY);

			speedY = deltaY * wheel_ratio;
			self.scrollTop += speedY;

			// set padding (for bounce scroll)
			if(bouncescroll) {
				if(self.scrollTop === 0) {
					self.style.paddingTop = paddingTop + Math.round(speedY * -1 / 2) + 'px';
				}
				if(self.scrollTop >= scrollHeight) {
					self.style.paddingBottom = paddingBottom + Math.floor(speedY * 1 / 2) + 'px';
					self.scrollTop = scrollHeight + speedY;
				}
				if(movingY && directionY == 'down') {
					self.style.paddingTop = paddingTop;
				}
				if(movingY && directionY == 'up') {
					self.style.paddingBottom = paddingBottom;
				}
			}

			if(!movingY && directionY == '') {
				return;
			}
			if(!movingY && directionY == 'up' && self.scrollTop === 0) {
				return;
			}
			if(!movingY && directionY == 'down' && self.scrollTop >= scrollHeight) {
				return;
			}

			event.stopPropagation();
			event.preventDefault();

			// set scroll bar top
			var bartop = Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;

			barY.style.top = bartop + 'px';
			barY.style.transition = '';
			barY.style.opacity = '0.6';

			// set scroll bar height (for bounce scroll)
			if(bouncescroll) {
				if(self.scrollTop === 0) {
					barY.style.height = barHeight - Math.floor(speedY * -1 / 10) - padding*2 + 'px';
				}
				else if(bartop + barHeight >= self.clientHeight) {
					barY.style.height = self.clientHeight - bartop - padding + 'px';
				}
				else {
					barY.style.height = barHeight - padding*2 + 'px';
				}
			}

			startScrollTop = self.scrollTop;

			if(os != 'mac' && mode != 'ace') {
				animate(
					function(t) {
						return (--t)*t*t+1;
					},
					function(progress) {
						if(directionY == 'down') {
							self.scrollTop = startScrollTop + Math.round(progress * momentam);
						}
						else {
							self.scrollTop = startScrollTop - Math.round(progress * momentam);
						}
						if(self.scrollTop >= scrollHeight) self.scrollTop = scrollHeight;

						var bartop = Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;

						// set scroll bar top
						barY.style.top = bartop + 'px';
						if(progress >= 1) {
							clearTimeout(timerY);
							timerY = setTimeout(stopY, 100);
						}
					},
					400
				);
			}
			else {
				clearTimeout(timerY);
				timerY = setTimeout(stopY, 500);
			}

			movingY = true;
		}

		function stopX() {
			barX.style.transition = 'opacity 0.4s ease-out';

			if(self.clientWidth < self.scrollWidth && isMouseOver) {
				barX.style.opacity = '0.2';
			}
			else {
				barX.style.opacity = '0';
			}

			movingX = false;
		}

		function stopY() {
			barY.style.transition = 'opacity 0.4s ease-out';

			if(self.clientHeight < self.scrollHeight && isMouseOver) {
				barY.style.opacity = '0.2';
			}
			else {
				barY.style.opacity = '0';
			}

			movingY = false;
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

			if(self.clientHeight < self.scrollHeight) {
				barY.style.transition = 'opacity 0.4s ease-out';
				barY.style.opacity = '0.2';
			}

			if(self.clientWidth < self.scrollWidth) {
				barX.style.transition = 'opacity 0.4s ease-out';
				barX.style.opacity = '0.2';
			}
		}

		function onMouseout(event) {
			isMouseOver = false;

			if(!draggingY && self.clientHeight < self.scrollHeight) {
				barY.style.transition = 'opacity 0.4s ease-out';
				barY.style.opacity = '0';
				barContainerY.style.transition = 'opacity 0.2s ease-out';
				barContainerY.style.opacity = '0';
			}

			if(!draggingX && self.clientWidth < self.scrollWidth) {
				barX.style.transition = 'opacity 0.4s ease-out';
				barX.style.opacity = '0';
				barContainerX.style.transition = 'opacity 0.2s ease-out';
				barContainerX.style.opacity = '0';
			}
		}

		function onContainerMouseoverX(event) {
			onMouseover();

			if(self.clientWidth >= self.scrollWidth) return;

			barContainerX.style.transition = 'opacity 0.2s ease-out';
			barContainerX.style.opacity = '0.7';
			barX.style.opacity = '0.6';

			event.stopPropagation();
		}

		function onContainerMouseoverY(event) {
			onMouseover();

			if(self.clientHeight >= self.scrollHeight) return;

			barContainerY.style.transition = 'opacity 0.2s ease-out';
			barContainerY.style.opacity = '0.7';
			barY.style.opacity = '0.6';

			event.stopPropagation();
		}

		function onContainerMouseoutX(event) {
			if(draggingX) return;
			if(self.clientWidth >= self.scrollWidth) return;

			barContainerX.style.transition = 'opacity 0.2s ease-out';
			barContainerX.style.opacity = '0';
		}

		function onContainerMouseoutY(event) {
			if(draggingY) return;
			if(self.clientHeight >= self.scrollHeight) return;

			barContainerY.style.transition = 'opacity 0.2s ease-out';
			barContainerY.style.opacity = '0';
		}

		function onMouseDownX(event) {
			if(self.clientWidth >= self.scrollWidth) return;

			draggStartMousePosition = bframe.getMousePosition(event);
			draggStartScrollLeft = self.scrollLeft;
			draggingX = true;

			event.preventDefault();
		}

		function onMouseDownY(event) {
			if(self.clientHeight >= self.scrollHeight) return;

			draggStartMousePosition = bframe.getMousePosition(event);
			draggStartScrollTop = self.scrollTop;
			draggingY = true;

			event.preventDefault();
		}

		function onMouseMoveX(event) {
			if(!draggingX) return;
			if(self.clientWidth >= self.scrollWidth) return;

			mpos = bframe.getMousePosition(event);
			self.scrollLeft = draggStartScrollLeft + (mpos.x - draggStartMousePosition.x) * Math.round(scrollWidth / barScrollWidth);
			var barleft = Math.round(barScrollWidth * self.scrollLeft / scrollWidth) + padding;

			barX.style.left = barleft + 'px';
			barX.style.opacity = '0.6';
		}

		function onMouseMoveY(event) {
			if(!draggingY) return;
			if(self.clientHeight >= self.scrollHeight) return;

			mpos = bframe.getMousePosition(event);
			self.scrollTop = draggStartScrollTop + (mpos.y - draggStartMousePosition.y) * Math.round(scrollHeight / barScrollHeight);
			var bartop = Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;

			barY.style.top = bartop + 'px';
			barY.style.opacity = '0.6';
		}

		function onMouseUpX(event) {
			if(!draggingX) return;
			if(self.clientWidth >= self.scrollWidth) return;

			draggingX = false;

			if(isMouseOver) {
				barX.style.opacity = '0.2';
			}
			else {
				barX.style.opacity = '0';
			}
			barContainerX.style.opacity = '0';
		}

		function onMouseUpY(event) {
			if(!draggingY) return;
			if(self.clientHeight >= self.scrollHeight) return;

			draggingY = false;

			if(isMouseOver) {
				barY.style.opacity = '0.2';
			}
			else {
				barY.style.opacity = '0';
			}
			barContainerY.style.opacity = '0';
		}

		function onClick(event) {
			var obj = bframe.getEventSrcElement(event);
			if(obj != self && isScrollable(obj)) return;

			scrollTarget = true;
			event.stopPropagation();
		}

		function isScrollable(obj) {
			if(obj == self || obj == document.body) return false;
			if(obj.clientHeight < obj.scrollHeight || obj.clientWidth < obj.scrollWidth) return true;
			if(obj.tagName.toLowerCase() == 'select' && obj.length > 1) return true;
			if(obj.tagName.toLowerCase() == 'a' && obj.classList.contains('selectbox')) return true;
			if(obj.tagName.toLowerCase() == 'input') return true;

			return isScrollable(obj.parentNode);
		}

		function onMousedown(event) {
			scrollTarget = false;
		}

		function onKeydown(event) {
			if(self.tagName.toLowerCase() == 'textarea') return;
			if(document.activeElement != self) {
				if(!scrollTarget ||	isScrollable(document.activeElement)) return;
			}

			var keycode;
			var directionX;
			var directionY;
			var speedX = 14;
			var speedY = 14;

			if(window.event) {
				keycode = window.event.keyCode;
			}
			else {
				keycode = event.keyCode;
			}

			switch(keycode) {
			case 37:	// left
				directionX = 'left';
				self.scrollLeft -= speedX;
				break;

			case 38:	// up
				directionY = 'up';
				self.scrollTop -= speedY;
				break;

			case 39:	// right
				directionX = 'right';
				self.scrollLeft += speedX;
				break;

			case 40:	// down
				directionY = 'down';
				self.scrollTop += speedY;

				break;

			default:
				return;
			}

			// set scroll bar left
			var barleft = Math.round(barScrollWidth * self.scrollLeft / scrollWidth) + padding;

			// set scroll bar top
			var bartop = Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;

			if(directionY) {
				startScrollTop = self.scrollTop;

				animate(
					function(t) {
						return (--t)*t*t+1;
					},
					function(progress) {
						if(directionY == 'down') {
							self.scrollTop = startScrollTop + Math.round(progress * momentam);
						}
						else {
							self.scrollTop = startScrollTop - Math.round(progress * momentam);
						}
						if(self.scrollTop >= scrollHeight) self.scrollTop = scrollHeight;

						var bartop = Math.round(barScrollHeight * self.scrollTop / scrollHeight) + padding;

						// set scroll bar top
						barY.style.top = bartop + 'px';
						if(progress >= 1) {
							clearTimeout(timerY);
							timerY = setTimeout(stopY, 100);
						}
					},
					400
				);

				movingY = true;
			}

			if(directionX) {
				startScrollLeft = self.scrollLeft;

				animate(
					function(t) {
						return (--t)*t*t+1;
					},
					function(progress) {
						if(directionX == 'right') {
							self.scrollLeft = startScrollLeft + Math.round(progress * momentam);
						}
						else {
							self.scrollLeft = startScrollLeft - Math.round(progress * momentam);
						}
						if(self.scrollLeft >= scrollWidth) self.scrollLeft = scrollWidth;

						var barleft = Math.round(barScrollWidth * self.scrollLeft / scrollWidth) + padding;

						// set scroll bar left
						barX.style.left = barleft + 'px';
						if(progress >= 1) {
							clearTimeout(timerX);
							timerX = setTimeout(stopX, 100);
						}
					},
					400
				);

				movingX = true;
			}

			event.preventDefault();
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