/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	// -------------------------------------------------------------------------
	// class bframe._effect
	// 
	// -------------------------------------------------------------------------
	bframe._effect = function() {
		var self;
		var opacity;
		var timer;
		var speed;
		var start;
		var end;
		var millisec;

		this.fadeIn = function(target, delay, s, e, m) {
			self = target;
			start = s;
			end = e;
			millisec = m;

			if(timer) {
				clearInterval(timer);
				timer = false;
			}

			if(delay) {
	            timer = setTimeout(fadeInStart, delay);
			}
			else {
				fadeInStart();
			}
		}

		function fadeInStart() {
		    if(start < end) {
				opacity = start;
				interval = millisec / (end - start);
	            timer = setInterval(_fadeIn, parseInt(interval));
		    }
		}

		function _fadeIn() {
			if(opacity >= 100) {
				clearInterval(timer);
				timer = false;
			}
			self.style.opacity = (opacity / 100);
			opacity++;
		}

		this.fadeOut = function(target, start, end, millisec) {
			if(timer) {
				clearInterval(timer);
				timer = false;
			}

			self = target;
		    if(start > end) {
				opacity = start;
				interval = millisec / (start - end);
	            timer = setInterval(_fadeOut, interval);
		    }
		}

		function _fadeOut() {
			if(opacity < 0) {
				clearInterval(timer);
				timer = false;
			}
			self.style.opacity = (opacity / 100); 
			opacity--;
		}
	}

	bframe.effect = new bframe._effect;
