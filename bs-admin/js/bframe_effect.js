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
		var delay_timer;
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

			if(delay) {
				delay_timer = setTimeout(fadeInStart, delay);
			}
			else {
				fadeInStart();
			}
		}

		function fadeInStart() {
			if(start < end) {
				opacity = start;
				var interval = millisec / (end - start);
				for(var i=start ; i<end ; i++) {
					timer = setTimeout(_fadeIn, i*interval);
				}
			}
		}

		function _fadeIn() {
			self.style.opacity = (++opacity / 100);
		}

		this.fadeOut = function(target, s, e, m) {
			self = target;
			start = s;
			end = e;
			millisec = m;

			fadeOutStart();
		}

		function fadeOutStart() {
			if(start > end) {
				opacity = start;
				var interval = millisec / (start - end);
				for(var i=start ; i>end ; i--) {
					timer = setTimeout(_fadeOut, i*interval);
				}
			}
		}

		function _fadeOut() {
			self.style.opacity = (--opacity / 100);
		}
	}

	bframe.effect = new bframe._effect;
