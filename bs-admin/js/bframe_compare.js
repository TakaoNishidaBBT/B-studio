/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeCompareInit);

	function bframeCompareInit(){
	    var div = document.getElementsByTagName('div');

	    for(var i=0, j=0; i<div.length; i++) {
			if(bframe.checkClassName('bframe_compare', div[i])) {
				var c = new bframe.compare(div[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.compare
	// 
	// -------------------------------------------------------------------------
	bframe.compare = function(target) {
		var self = this;
		var target_id = bframe.getID(target);

		var left_source = bframe.serachNodeByClassName(target, 'bframe_compare_left');
		var right_source = bframe.serachNodeByClassName(target, 'bframe_compare_right');

		var ap = new bframe.adjustparent(target);

        $(document).ready(function () {
			$(target).mergely({
				width: 'auto',
				height: 'auto',
				vpcolor: 'rgba(0, 0, 256, 0.4)',
				fgcolor: {a:'#0000cc',c:'#889900',d:'#cc0000'},
				resize_timeout: '100',
				cmsettings: {
					readOnly: true, 
					lineWrapping: true,
				},
				lhs: function(setValue) {
					setValue(left_source.value);
					left_source.style.display = 'none';
				},
				rhs: function(setValue) {
					setValue(right_source.value);
					right_source.style.display = 'none';
				}
			});
		});

		bframe.addEventListner(target.parentNode, 'focus', resize);

		function resize() {
			$(target).mergely('resize');
		}
	}