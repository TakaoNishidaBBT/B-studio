/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeCompareInit);

	function bframeCompareInit(){
		var c = new bframe.compare();
	}

	// -------------------------------------------------------------------------
	// class bframe.compare
	// 
	// -------------------------------------------------------------------------
	bframe.compare = function() {
		var mode = document.getElementById('mode');
		var split = document.getElementById('split');
		var unified = document.getElementById('unified');
		var view_all = document.getElementById('view-all');
		var view_all_icon = view_all.getElementsByTagName('img')[0];
		var range = document.getElementById('range');

		view_all_icon.style.opacity = '0.5';

		showDiff();

		bframe.addEventListener(split, 'click', showSideDiff);
		bframe.addEventListener(unified, 'click', showLineDiff);
		bframe.addEventListener(view_all, 'click', toggleViewAll);

		function toggleViewAll(event) {
			if(range.checked) {
				range.checked = false;
				view_all_icon.style.opacity = '0.5';
			}
			else {
				range.checked = true;
				view_all_icon.style.opacity = '1.0';
			}
			showDiff();
		}

		function showSideDiff(event) {
			mode.value = 's';
			showDiff();
		}

		function showLineDiff(event) {
			mode.value = 'u';
			showDiff();
		}

		function showDiff() {
			var div = document.getElementsByTagName('div');
			for(var i=0; i<div.length; i++) {
				if(bframe.checkClassName('bframe_compare', div[i])) {
					diff(div[i], mode.value, range.checked);
				}
			}
		}

		function diff(target, mode, range) {
			var context_size = 3;
			var left_source = bframe.searchNodeByClassName(target, 'bframe_compare_left');
			var right_source = bframe.searchNodeByClassName(target, 'bframe_compare_right');

			if(range) context_size = 10000;
			var diffLine = xdiff_string_diff(left_source.value, right_source.value, context_size);

			var left_version_name = left_source.getAttribute('data-version-name');
			var right_version_name = right_source.getAttribute('data-version-name');

			var lineDiffExample =
				'diff --new core --extend\n' +
				'+++ b/<span class="left"></span><span class="right"></span>\n' +
				diffLine;
				"+\n";

			var diff2htmlUi = new Diff2HtmlUI({diff: lineDiffExample});

			var display_field = target.getElementsByClassName('bframe_compare_display_field');
			if(display_field && display_field[0].id) {
				var field_id = '#' + display_field[0].id;

				if(mode == 'u') {
					diff2htmlUi.draw(field_id, {
						inputFormat: 'json',
						showFiles: false,
						matching: 'lines'});
				}
				else {
					diff2htmlUi.draw(field_id, {
						inputFormat: 'json',
						matching: 'lines',
						outputFormat: 'side-by-side'});
				}
			}
		}
	}
