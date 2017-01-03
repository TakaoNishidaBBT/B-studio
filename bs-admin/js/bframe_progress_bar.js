/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. all rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.progressBar = function(params) {
		var overlay = document.createElement('div');
		var container = document.createElement('div');
		var containerHeader = document.createElement('div');
		var box = document.createElement('div');
		var title = document.createElement('div');
		var icon = document.createElement('img');
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

		icon.className = 'progress-icon';
		icon.src = params.icon;
		title.appendChild(icon);

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

		function setComplete() {
			top.document.body.removeChild(overlay);
		}
		this.setComplete = setComplete;
	}
