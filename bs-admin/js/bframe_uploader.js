/*
 * B-frame : php web application framework
 * Copyright (c) BigBeat Inc. All rights reserved. (http://www.bigbeat.co.jp)
 *
 * Licensed under the GPL, LGPL and MPL Open Source licenses.
*/
	bframe.addEventListner(window, 'load' , bframeUploaderInit);

	function bframeUploaderInit(){
		bframe_uploader = new bframe.uploader();
	}

	// -------------------------------------------------------------------------
	// class bframe.uploader
	// 
	// -------------------------------------------------------------------------
	bframe.uploader = function() {
		var page_info = bframe.getPageInfo();
		var terminal_id = page_info.terminal_id;
		var module = page_info.source_module;
		var page = page_info.source_page;

		var upload_file = document.getElementById('uploadFile');
		var select_button = document.getElementById('selectButton');
		var upload_status = document.getElementById('divStatus');
		var progressFieldId = 'fsUploadProgress';
		var httpObj;
		var files;
		var upload_count;
		var progress;
		var upload_queue= new Array();
		var index;
		var mode;
		var extract_mode;
		var form_data = new FormData();

		select_button.onclick = selectFiles;
		upload_file.onchange = uploadFiles;

		queueComplete(0);

		function selectFiles(event) {
			if(upload_queue[index]) return false;

			bframe.fireEvent(upload_file, 'click');
		}

		function uploadFiles(event) {
			index = 0;
			upload_queue.length = 0;
			upload_count = 0;
			queueComplete(0);
			mode = 'confirm';
			extract_mode = 'confirm';
			var files = event.target.files;

			bframe.removeAllChild(progressFieldId);

			for(var i=0; i < files.length; i++){
				files[i].id = i;
				var progress = new FileProgress(files[i], progressFieldId);
				progress.setStatus('Pending...');
				progress.toggleCancel(true, this);
				upload_queue[i] = {'file' : files[i], 'progress' : progress};
			}

			confirm(0);
			true;
		}

		function removeAllChild(id) {
			var node = document.getElementById(id);
			while(node.childNodes) {
				node.removeChild(node.firstChild);
			}
		}

		function confirm(index) {
			var info = upload_queue[index];
			if(!info) return;

			httpObj = new XMLHttpRequest();

			if(httpObj.upload){
				httpObj.onreadystatechange = confirmResult;
				progress = upload_queue[index].progress;
				progress.setStatus('Uploading...');
				progress.toggleCancel(true, this);
			}

			var form_data = new FormData();

			form_data.append('terminal_id', terminal_id);
			form_data.append('module', module);
			form_data.append('page', page);
			form_data.append('method', 'confirm');
			form_data.append('mode', mode);
			form_data.append('extract_mode', extract_mode);
			form_data.append('filename', upload_queue[index].file['name']);
			form_data.append('filesize', upload_queue[index].file['size']);

			httpObj.open('POST','index.php');
			httpObj.send(form_data);
		}

		function confirmResult() {
			if(httpObj.readyState == 4 && httpObj.status == 200) {
				try {
					var response = eval('('+httpObj.responseText+')');
				}
				catch(e) {
					var response = {status: false, message: top.bframe.message.getProperty('session_time_out')};
				}

				if(response.status) {
					if(response.mode == 'zipConfirm') {
						showZipConfirmDialog(response.message, extract, extractAll, noextract, cancelAll);
					}
					else if(response.mode == 'confirm') {
						showConfirmDialog(response.message, overwrite, overwriteAll, cancel, cancelAll);
					}
					else {
						overwrite();
					}
				}
				else {
					progress.setError();
					progress.setStatus(response.message);
					confirm(++index);
				}
			}
		}

		function upload(index) {
			var info = upload_queue[index];
			if(!info) return;

			httpObj = new XMLHttpRequest();

			if(httpObj.upload){
				httpObj.onreadystatechange = setUploadResult;
				progress = upload_queue[index].progress;
				progress.setStatus('Uploading...');
				progress.toggleCancel(true, this);

				httpObj.upload.onprogress = function (e){
					var percent = Math.ceil((e.loaded / e.total) * 100);
					progress.setProgress(percent);
					progress.setStatus('Uploading...');
				};
			}

			var form_data = new FormData();

			form_data.append('terminal_id', terminal_id);
			form_data.append('module', module);
			form_data.append('page', page);
			form_data.append('method', 'upload');
			form_data.append('mode', 'register');

			form_data.append('extract_mode', extract_mode);
			form_data.append('Filedata', upload_queue[index].file);

			httpObj.open('POST','index.php');
			httpObj.send(form_data);
		}

		function setUploadResult() {
			if(httpObj.readyState == 4 && httpObj.status == 200){
				var response = eval('('+httpObj.responseText+')');

				result(response);
				confirm(++index);
			}
		}

		function result(responseObj) {
			if(responseObj.status == true) {
				progress.setComplete();
				progress.setStatus('Complete.');
				queueComplete(++upload_count);
			}
			else {
				progress.setError();
				progress.setStatus(responseObj.message);
			}
		}

		function extract() {
			extract_mode = 'extract';
			upload(index);
			extract_mode = 'confirm';
		}

		function extractAll() {
			extract_mode = 'extract';
			upload(index);
		}

		function noextract() {
			extract_mode = 'noextract';
			confirm(index);
			extract_mode = 'confirm';
		}

		function overwrite() {
			upload(index);
		}

		function overwriteAll() {
			mode = 'overwrite';
			upload(index);
		}

		function cancel() {
			progress.setCancelled();
			progress.setStatus('Cancelled.');
			confirm(++index);
		}

		function cancelAll() {
			for(; upload_queue[index]; index++){
				cancelUpload();
			}
		}

		function cancelUpload() {
			upload_queue[index].progress.setCancelled();
			upload_queue[index].progress.setStatus('Cancelled.');

		}

		function queueComplete(numFilesUploaded) {
			upload_status.innerHTML = numFilesUploaded + ' file' + (numFilesUploaded < 2 ? '' : 's') + ' uploaded.';
		}

		function showZipConfirmDialog(msg, funcExtract, funcExtractAll, funcNoExtract, cancel) {
			var params = {
				'id': 'confirmDialog',
				'title': '',
				'message': msg,
				'buttons': [
					{
						'name': top.bframe.message.getProperty('uplado_zip_confirm_dialog1'),
						'className': 'button',
						'action': funcExtract
					},
					{
						'name': top.bframe.message.getProperty('uplado_zip_confirm_dialog2'),
						'className': 'button',
						'action': funcExtractAll
					},
					{
						'name': top.bframe.message.getProperty('uplado_zip_confirm_dialog3'),
						'className': 'button',
						'action': funcNoExtract
					},
					{
						'name': top.bframe.message.getProperty('uplado_zip_confirm_dialog4'),
						'className': 'button',
						'action': cancel
					}
				]
			};

			var dialog = new bframe.dialog(params);
		}

		function showConfirmDialog(msg, funcYes, funcYesToAll, funcNo, funcNoToAll) {
			var params = {
				'id': 'confirmDialog',
				'title': '',
				'message': msg,
				'buttons': [
					{
						'name': top.bframe.message.getProperty('uplado_confirm_dialog1'),
						'className': 'button',
						'action': funcYes
					},
					{
						'name': top.bframe.message.getProperty('uplado_confirm_dialog2'),
						'className': 'button',
						'action': funcYesToAll
					},
					{
						'name': top.bframe.message.getProperty('uplado_confirm_dialog3'),
						'className': 'button',
						'action': funcNo
					},
					{
						'name': top.bframe.message.getProperty('uplado_confirm_dialog4'),
						'className': 'button',
						'action': funcNoToAll
					}
				]
			};

			var dialog = new bframe.dialog(params);
		}

		// -------------------------------------------------------------------------
		// class FileProgress
		// -------------------------------------------------------------------------
		function FileProgress(file, targetID) {
			this.fileProgressID = file.id;
			this.opacity = 100;
			this.height = 0;

			this.fileProgressWrapper = document.getElementById(this.fileProgressID);

			FileProgress.prototype.setTimer = function(timer) {
				this.fileProgressElement['FP_TIMER'] = timer;
			};

			FileProgress.prototype.getTimer = function(timer) {
				return this.fileProgressElement['FP_TIMER'] || null;
			};

			FileProgress.prototype.reset = function() {
				this.fileProgressElement.className = 'progressContainer';

				this.fileProgressElement.childNodes[2].innerHTML = '&nbsp;';
				this.fileProgressElement.childNodes[2].className = 'progressBarStatus';
				this.fileProgressElement.childNodes[3].className = 'progressBarInProgress';
				this.fileProgressElement.childNodes[3].style.width = '0%';
				this.appear();
			};

			FileProgress.prototype.setProgress = function(percentage) {
				this.fileProgressElement.className = 'progressContainer green';
				this.fileProgressElement.childNodes[3].className = 'progressBarInProgress';
				this.fileProgressElement.childNodes[3].style.width = percentage + '%';

				this.appear();
			};

			FileProgress.prototype.setComplete = function() {
				this.fileProgressElement.className = 'progressContainer blue';
				this.fileProgressElement.childNodes[3].className = 'progressBarComplete';
				this.fileProgressElement.childNodes[3].style.width = '';

				var oSelf = this;
				this.setTimer(setTimeout(function() {
					oSelf.disappear();
				}, 10000));
			};

			FileProgress.prototype.setError = function() {
				this.fileProgressElement.className = 'progressContainer red';
				this.fileProgressElement.childNodes[3].className = 'progressBarError';
				this.fileProgressElement.childNodes[3].style.width = '';

				var oSelf = this;
				this.setTimer(setTimeout(function () {
					oSelf.disappear();
				}, 10000));
			};

			FileProgress.prototype.setCancelled = function() {
				this.fileProgressElement.className = 'progressContainer';
				this.fileProgressElement.childNodes[3].className = 'progressBarError';
				this.fileProgressElement.childNodes[3].style.width = '';

				var oSelf = this;
				this.setTimer(setTimeout(function() {
					oSelf.disappear();
				}, 10000));
			};

			FileProgress.prototype.setStatus = function(status) {
				this.fileProgressElement.childNodes[2].innerHTML = status;
			};

			// Show/Hide the cancel button
			FileProgress.prototype.toggleCancel = function(show, uploadInstance) {
				this.fileProgressElement.childNodes[0].style.visibility = show ? 'visible' : 'hidden';
				if (uploadInstance) {
					var fileID = this.fileProgressID;
					this.fileProgressElement.childNodes[0].onclick = function() {
						uploadInstance.cancelUpload(fileID);
						return false;
					};
				}
			};

			FileProgress.prototype.appear = function() {
				if(this.getTimer() !== null) {
					clearTimeout(this.getTimer());
					this.setTimer(null);
				}

				if(this.fileProgressWrapper.filters) {
					try {
						this.fileProgressWrapper.filters.item('DXImageTransform.Microsoft.Alpha').opacity = 100;
					} catch (e) {
						// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
						this.fileProgressWrapper.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=100)';
					}
				}
				else {
					this.fileProgressWrapper.style.opacity = 1;
				}

				this.fileProgressWrapper.style.height = '';

				this.height = this.fileProgressWrapper.offsetHeight;
				this.opacity = 100;
				this.fileProgressWrapper.style.display = '';

			};

			// Fades out and clips away the FileProgress box.
			FileProgress.prototype.disappear = function () {

				var reduceOpacityBy = 15;
				var reduceHeightBy = 4;
				var rate = 30;	// 15 fps

				if(this.opacity > 0) {
					this.opacity -= reduceOpacityBy;
					if(this.opacity < 0) {
						this.opacity = 0;
					}

					if(this.fileProgressWrapper.filters) {
						try {
							this.fileProgressWrapper.filters.item('DXImageTransform.Microsoft.Alpha').opacity = this.opacity;
						} catch (e) {
							// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
							this.fileProgressWrapper.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + this.opacity + ')';
						}
					}
					else {
						this.fileProgressWrapper.style.opacity = this.opacity / 100;
					}
				}

				if(this.height > 0) {
					this.height -= reduceHeightBy;
					if(this.height < 0) {
						this.height = 0;
					}

					this.fileProgressWrapper.style.height = this.height + 'px';
				}

				if(this.height > 0 || this.opacity > 0) {
					var oSelf = this;
					this.setTimer(setTimeout(function () {
						oSelf.disappear();
					}, rate));
				}
				else {
					this.fileProgressWrapper.style.display = 'none';
					this.setTimer(null);
				}
			};

			if(!this.fileProgressWrapper) {
				this.fileProgressWrapper = document.createElement('div');
				this.fileProgressWrapper.className = 'progressWrapper';
				this.fileProgressWrapper.id = this.fileProgressID;

				this.fileProgressElement = document.createElement('div');
				this.fileProgressElement.className = 'progressContainer';

				var progressCancel = document.createElement('a');
				progressCancel.className = 'progressCancel';
				progressCancel.href = '#';
				progressCancel.style.visibility = 'hidden';
				progressCancel.appendChild(document.createTextNode(' '));

				var progressText = document.createElement('div');
				progressText.className = 'progressName';
				progressText.appendChild(document.createTextNode(file.name));

				var progressBar = document.createElement('div');
				progressBar.className = 'progressBarInProgress';

				var progressStatus = document.createElement('div');
				progressStatus.className = 'progressBarStatus';
				progressStatus.innerHTML = '&nbsp;';

				this.fileProgressElement.appendChild(progressCancel);
				this.fileProgressElement.appendChild(progressText);
				this.fileProgressElement.appendChild(progressStatus);
				this.fileProgressElement.appendChild(progressBar);

				this.fileProgressWrapper.appendChild(this.fileProgressElement);

				document.getElementById(targetID).appendChild(this.fileProgressWrapper);
			}
			else {
				this.fileProgressElement = this.fileProgressWrapper.firstChild;
				this.reset();
			}

			this.height = this.fileProgressWrapper.offsetHeight;
			this.setTimer(null);
		}
	}