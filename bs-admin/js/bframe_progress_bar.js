/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeProgressBarInit);

	function bframeProgressBarInit() {
		var div = document.getElementsByTagName('span');

		for(var i=0; i < div.length; i++) {
			if(bframe.checkClassName('bframe_progress_bar', div[i])) {
				var s = new bframe.progressBar(div[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.progressBar
	// 
	// -------------------------------------------------------------------------
	bframe.progressBar = function(obj) {
		var	response_wait = false;
		var property;
		var httpObj;

		var ret = bframe.getPageInfo();
		var terminal_id = ret.terminal_id;
		var module = ret.source_module;
		var page = ret.source_page;

		var timer;

		var image_box;
		var image_bar;

		var span_image;
		var span_text;
		var span_text_value;

		var initialPos;				// Initial postion of the background in the progressbar (0% is the middle of our image!)
		var pxPerPercent = 1;		// Define how much pixels go into 1%
		var initialPerc = 0;		// Store this, we'll need it later.

		var text_element;
		init();

		function init() {
			var param;

			param = 'terminal_id='+terminal_id+'&class=bframe_progress_bar&id='+obj.id;
			httpObj = createXMLHttpRequest(initResponse);

			eventHandler(httpObj, module, page, 'initScript', 'POST', param);
			response_wait = true;

			span_image = document.createElement('span');
			obj.appendChild(span_image);

			span_text = document.createElement('span');
			obj.appendChild(span_text);

			span_text_value = document.createTextNode('');
			span_text.appendChild(span_text_value);

			obj.style.marginTop = '20px';
		}

		function initResponse(){
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				property = eval('('+httpObj.responseText+')');
				response_wait = false;
				if(property.image) {
					image_box = createImage(property.image.box);
					image_bar = createImage(property.image.bar);

					if(property.image.width) {
						pxPerPercent = property.image.width / 100;
						initialPos = property.image.width * (-1);
						image_box.width = property.image.width;
					}
				}
				start();
			}
		}

		function createImage(p) {
			var img = new Image();
			img.src = p.src;
			img.hspace = 10;
			return img;
		}

		function start() {
			span_image.appendChild(image_box);
			image_box.style.backgroundPosition = initialPos + 'px 10%';
			image_box.style.backgroundImage = 'url(' + property.image.bar.src + ')';
			timer = setInterval(getProgress, 500);
		}

		function finish() {
			clearInterval(timer);

			if(property.finish) {
				bframe.submit('', property.finish.module, property.finish.file, property.finish.method, '');
			}
		}

		function getProgress() {
			if(!response_wait) {
				var param = 'terminal_id='+terminal_id;

				var obj = document.getElementById('table_name');
				param += '&table=' + obj.value;

				httpObj = createXMLHttpRequest(setProgress);
				eventHandler(httpObj, property.module, property.file, property.method, 'POST', param);
				response_wait = true;
			}
		}

		function setProgress() {
			if(httpObj.readyState == 4 && httpObj.status == 200 && response_wait){
				var response = eval('('+httpObj.responseText+')');
				setPercentage(response.status);
				span_text_value.nodeValue = response.status+'%';

				response_wait = false;
			}
		}

		function setPercentage(p) {
			var position = (initialPos + (p * pxPerPercent)) + 'px 50%';

			image_box.style.backgroundPosition = position;
			if(p >= 100) {
				finish();
			}
		}
	}
