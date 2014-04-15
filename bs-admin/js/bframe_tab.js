/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeTabInit);

	function bframeTabInit(){
		var tc = new bframe.tab_container();
	    var a = document.getElementsByTagName('a');
		var t = new Array();

	    for(var i=0, j=0; i<a.length; i++) {
			if(bframe.checkClassName('bframe_tab', a[i])) {
				t[j++] = new bframe.tab(a[i], tc);
			}
		}

		tc.setTabs(t);
	}

	// -------------------------------------------------------------------------
	// class bframe.tab_container
	// 
	// -------------------------------------------------------------------------
	bframe.tab_container = function() {
		var tabs;

		this.clickTab = function(event) {
			var evtSrc = bframe.getEventSrcElement(event);
			controlTab(evtSrc);

			bframe.cancelEvent(event);
			return false;
		}

		function controlTab(evtSrc) {
			for(var i=0 ; i<tabs.length ; i++) {
				if(bframe.isChild(tabs[i].getSelf(), evtSrc)) {
					tabs[i].show();
				}
				else {
					if(tabs[i].isVisible()) {
						tabs[i].hide();
					}
				}
			}
		}

		this.setTabs = function(t) {
			tabs = t;
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.tab
	// 
	// -------------------------------------------------------------------------
	bframe.tab = function(self, tc) {

		var rel = self.getAttribute('rel');
		var href = bframe.getFileName(self.href);
		var target = document.getElementById(href);
		var p = self.parentNode;
		var visible=true;

		if(target.style.display == 'none') {
			visible = false;
		}

		bframe.addEventListner(self, 'click', tc.clickTab);

		if(target.style.display != 'none') {
			bframe.appendClass('selected', self);
			bframe.appendClass('selected', p);
		}

		this.getSelf = function() {
			return self;
		}

		this.getTarget = function() {
			return target;
		}

		this.show = function() {
			target.style.display = 'block';
			bframe.appendClass('selected', self);
			bframe.appendClass('selected', p);
			bframe.fireEvent(target, 'focus');
			visible = true;
		}

		this.hide = function() {
			target.style.display = 'none';
			bframe.removeClass('selected', self);
			bframe.removeClass('selected', p);
			bframe.fireEvent(target, 'blur');
			visible = false;
		}

		this.isVisible = function() {
			return visible;
		}
	}
