/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.progressBar = function(params) {
		var overlay = document.createElement('div');
		var container = document.createElement('div');
		var containerHeader = document.createElement('div');
		var box = document.createElement('div');
		var title = document.createElement('div');
		var progress_icon = document.createElement('img');
		var complete_icon = document.createElement('img');
		var message = document.createElement('span');
		var progressElement = document.createElement('div');
		var progressStatus = document.createElement('div');
		var progressBar = document.createElement('div');

		// create container and overlay 
		overlay.className = 'progress-overlay';
		overlay.id = params.id;
		top.document.body.appendChild(overlay);

		container.className = 'progress-container';
		overlay.appendChild(container);

		containerHeader.className = 'progress-containerHeader';
		container.appendChild(containerHeader);

		box.className = 'progress-box';
		container.appendChild(box);

		title.className = 'progress-title';
		box.appendChild(title);

		progress_icon.className = 'progress-icon';
		progress_icon.src = params.icon;
		title.appendChild(progress_icon);

		if(params.complete_icon) {
			complete_icon.className = 'complete-icon';
			complete_icon.src = params.complete_icon;
			complete_icon.style.display = 'none';
			title.appendChild(complete_icon);
		}

		message.className = 'progress-message';
		message.innerHTML = params.message;
		title.appendChild(message);

		progressElement.appendChild(progressStatus);
		progressElement.appendChild(progressBar);
		box.appendChild(progressElement);

		reset();

		function reset() {
			overlay.style.display = 'none';
			progressElement.className = 'progressContainer';

			progressStatus.innerHTML = params.status;
			progressStatus.className = 'progressBarStatus';

			progressBar.className = 'progressBarInProgress';
			progressBar.style.width = '0%';
		}
		this.reset = reset;

		function show() {
			overlay.style.display = 'block';
		}
		this.show = show;

		function setClassName(className) {
			overlay.className = 'progress-overlay '+className;
		}
		this.setClassName = setClassName;

		function setIcon(src) {
			progress_icon.src = src
		}
		this.setIcon = setIcon;

		function setMessage(str) {
			message.innerHTML = str;
		}
		this.setMessage = setMessage;

		function setStatus(status) {
			progressStatus.innerHTML = status;
		}
		this.setStatus = setStatus;

		function setProgress(percentage, animate) {
			if(!animate) animate = '';
			progressElement.className = 'progressContainer';
			progressElement.childNodes[1].className = 'progressBarInProgress' + animate;
			progressElement.childNodes[1].style.width = percentage + '%';
		}
		this.setProgress = setProgress;

		function complete(str) {
			if(complete_icon) {
				progress_icon.style.display = 'none';
				complete_icon.style.display = 'inline';
			}
			if(str) message.innerHTML = str;
			overlay.className = 'progress-overlay complete';
		}
		this.complete = complete;

		function remove() {
			top.document.body.removeChild(overlay);
		}
		this.remove = remove;
	}
