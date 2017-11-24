/*
 * B-frame : php web application framework
 * Copyright (c) Bigbeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListener(window, 'load' , bframeUploaderInit);

	function bframeUploaderInit(){
		var objects = document.querySelectorAll('input.bframe_uploader');

		for(var i=0; i < objects.length; i++) {
			var type = objects[i].getAttribute('type');
			if(type && type.toLowerCase() == 'file') {
				new bframe.uploader(objects[i]);
			}
		}
	}

	// -------------------------------------------------------------------------
	// class bframe.uploader
	// 
	// -------------------------------------------------------------------------
	bframe.uploader = function(target) {
		var parent = target.parentNode;
		var info = bframe.getPageInfo();

		var action = target.getAttribute('data-action');
		if(!action) {
			var form = bframe.searchParentByTagName(target, 'form');
			action = form.getAttribute('action');
		}
		if(!action) action = window.location.href;
		var data_rel = target.getAttribute('data-rel');
		var data_value = target.getAttribute('data-value');
		var data_confirm = target.getAttribute('data-confirm');

		var file;
		var progress;

		target.style.display = 'none';

		var upload_table = document.createElement('table');
		var tr = document.createElement('tr');
		var td1 = document.createElement('td');
		var icon_field = document.createElement('div');
		var file_name = document.createElement('span');
		var td2 = document.createElement('td');
		var upload_button = document.createElement('a');
		var download_button = document.createElement('a');
		var delete_button = document.createElement('a');

		upload_table.className = 'upload-table';
		td1.className = 'icon-field'
		icon_field.className = 'file-upload-icon-field';
		file_name.className = 'upload-file-name';
		td2.className = 'button-field'
		upload_button.className = 'file-upload-button';
		download_button.className = 'file-download-button';
		delete_button.className = 'file-delete-button';

		upload_button.innerHTML = 'Upload';
		download_button.innerHTML = 'Download';
		delete_button.innerHTML = 'Delete';

		parent.appendChild(upload_table);
		upload_table.appendChild(tr);
		tr.appendChild(td1);
		td1.appendChild(icon_field);
		td1.appendChild(file_name);
		tr.appendChild(td2);
		if(!data_confirm) {
			td2.appendChild(upload_button);
			td2.appendChild(download_button);
			td2.appendChild(delete_button);
		}

		var hidden_file_name = document.createElement('input');
		hidden_file_name.type = 'hidden';
		hidden_file_name.name = data_rel;
		parent.appendChild(hidden_file_name);

		progress = new FileProgress(icon_field);

		if(!data_confirm) {
			upload_button.onclick = selectFiles;
			target.onchange = uploadFiles;
			download_button.onclick = download;
			delete_button.onclick = _delete;
		}

		if(data_value) {
			file_name.innerHTML = shortenText(data_value);
			hidden_file_name.value = data_value;
			progress.setComplete();
		}
		else {
			bframe.appendClass('disabled', download_button);
			bframe.appendClass('disabled', delete_button);
		}
		function selectFiles(event) {
			bframe.fireEvent(target, 'click');
		}

		function uploadFiles(event) {
			file = event.target.files[0];
			_confirm(event);
		}

		function _confirm(event) {
			httpObj = new XMLHttpRequest();

			if(httpObj.upload){
				httpObj.onreadystatechange = confirmResult;
			}

			var form_data = new FormData();

			// execute callback function
			form_data.append('terminal_id', info['terminal_id']);
			form_data.append('mode', 'confirm');
			form_data.append('filename', file['name']);
			form_data.append('filesize', file['size']);
			form_data.append('target', data_rel);
			httpObj.open('POST', action);
			httpObj.send(form_data);
		}

		function confirmResult() {
			if(httpObj.readyState == 4 && httpObj.status == 200) {
				try {
					var response = eval('('+httpObj.responseText+')');
				}
				catch(e) {
					var response = {status: false, message: 'error'};
				}

				if(response.status) {
					if(response.mode == 'confirm') {
						if(!confirm(response.message)) return;
					}
					upload();
				}
				else {
					alert(response.message);
				}
			}
		}

		function upload(index) {
			httpObj = new XMLHttpRequest();

			progress.reset();
			file_name.innerHTML = '';

			if(httpObj.upload){
				httpObj.onreadystatechange = setUploadResult;
				
				progress.setStatus('Uploading...');

				httpObj.upload.onprogress = function (e){
					var percent = Math.ceil((e.loaded / e.total) * 100);
					progress.setProgress(percent);
					progress.setStatus('Uploading...');
				};
			}

			var form_data = new FormData();
			form_data.append('mode', 'upload');
			form_data.append('target', data_rel);
			form_data.append('Filedata', file);

			httpObj.open('POST', action);
			httpObj.send(form_data);
		}

		function setUploadResult() {
			if(httpObj.readyState == 4 && httpObj.status == 200){
				var response = eval('('+httpObj.responseText+')');

				if(response.status == true) {
					progress.setComplete();
					progress.setStatus('');
					file_name.innerHTML = shortenText(response.file_name);
					hidden_file_name.value = response.file_name;
					bframe.removeClass('disabled', download_button);
					bframe.removeClass('disabled', delete_button);
				}
				else {
					alert(response.message);
				}
			}
		}

		function download() {
			if(bframe.checkClassName('disabled', download_button)) return;

			var save = window.onbeforeunload;
			window.onbeforeunload = '';

			var param = '?mode=download&target='+ data_rel + '&file_name=' + file_name.innerHTML;
			location.href = action + param;

			window.onbeforeunload = save;
		}

		function _delete() {
			if(bframe.checkClassName('disabled', delete_button)) return;

			if(!confirm('Are you sure you want to delete?')) return;

			httpObj = new XMLHttpRequest();

			if(httpObj.upload){
				httpObj.onreadystatechange = deleteResult;
			}

			var form_data = new FormData();
			form_data.append('mode', 'delete');
			form_data.append('target', data_rel);
			form_data.append('file_name', file_name.innerHTML);

			httpObj.open('POST', action);
			httpObj.send(form_data);
		}

		function deleteResult() {
			if(httpObj.readyState == 4 && httpObj.status == 200){
				var response = eval('('+httpObj.responseText+')');

				if(response.status == true) {
					progress.reset();
					progress.setStatus('');
					file_name.innerHTML = '';
					hidden_file_name.value = '';
					bframe.appendClass('disabled', download_button);
					bframe.appendClass('disabled', delete_button);
				}
				else {
					alert(response.message);
				}
			}
		}

		function shortenText(text) {
			var max = 30;
			if(text.length > max) {
				return text.substr(0, max-8) + '...' + text.substr(-7);
			}
			return text;
		}

		// -------------------------------------------------------------------------
		// class FileProgress
		// -------------------------------------------------------------------------
		function FileProgress(parent) {
			FileProgress.prototype.reset = function() {
				fileProgressWrapper.className = 'progressWrapper';
				fileProgressContainer.className = 'progressContainer';

				progressStatus = '&nbsp;';
				progressStatus = 'progressBarStatus';
				progressBar.className = 'progressBar';
				progressBar.style.width = '0%';
			};

			FileProgress.prototype.setProgress = function(percentage) {
				fileProgressContainer.className = 'progressContainer green';
				progressBar.className = 'progressBar';
				progressBar.style.width = percentage + '%';
			};

			FileProgress.prototype.setComplete = function() {
				fileProgressContainer.className = 'progressContainer blue';
				progressBar.className = 'progressBarComplete';
				progressBar.style.width = '';
				fileProgressWrapper.className = 'progressWrapper complete';
			};

			FileProgress.prototype.setStatus = function(status) {
				progressStatus.innerHTML = status;
			};

			// Show/Hide the cancel button
			FileProgress.prototype.toggleCancel = function(show, uploadInstance) {
				progressCancel.style.visibility = show ? 'visible' : 'hidden';
				if (uploadInstance) {
					var fileID = this.fileProgressID;
					progressCancel.onclick = function() {
						uploadInstance.cancelUpload(fileID);
						return false;
					};
				}
			};

			var fileProgressWrapper = document.createElement('div');
			fileProgressWrapper.className = 'progressWrapper';

			var fileProgressContainer = document.createElement('div');
			fileProgressContainer.className = 'progressContainer';

			var progressBar = document.createElement('div');
			progressBar.className = 'progressBar';

			var progressStatus = document.createElement('div');
			progressStatus.className = 'progressBarStatus';
			progressStatus.innerHTML = '&nbsp;';

			fileProgressContainer.appendChild(progressStatus);
			fileProgressContainer.appendChild(progressBar);
			fileProgressWrapper.appendChild(fileProgressContainer);

			parent.appendChild(fileProgressWrapper);
		}
	}
