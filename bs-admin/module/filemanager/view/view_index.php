<body>
	<h2><span class="accent"></span>ファイル管理</h2>
	<div class="header"></div>
	<div id="bframe_tree_container" class="bframe_tree_container bframe_splitter_pain bframe_adjustwindow" param="margin:12">
		<div id="tree_box" class="bframe_adjustparent" param="margin:1">
			<?php echo $this->tree->getHtml(); ?>
		</div>
	</div>
	<div id="splitter" name="splitter" class="splitter bframe_splitter" param="margin:12"></div>
	<div id="filemanager_pain" class="bframe_splitter_pain bframe_adjustwindow" param="margin:12">
		<ul class="upload_control">
			<li>
				<div id="upload_button" class="input_container">
					<a href="index.php?terminal_id=<?php echo TERMINAL_ID ?>&module=filemanager&page=upload&method=init&session=<?php echo $this->module ?>" title="アップロード" class="upload-button bframe_upload" onclick="activateModalWindow(this, 440, 500, reloadTree); return false;">アップロード</a>
				</div>
			</li>
			<li class="select"><span id="bframe_pain_disp_change">表示 ： </span></li>
		</ul>
		<div class="pain_container">
			<div id="bframe_pain" class="bframe_pain bframe_adjustparent" param="margin:74"></div>
		</div>
	</div>
	<div class="footer"></div>
</body>
