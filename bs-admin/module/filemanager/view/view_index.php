<body spellcheck="false" onload="bframe.effect.fadeIn(document.body, 100, 0, 100, 50);" style="opacity:0">
	<h2><span class="accent"></span>ファイル管理</h2>
	<div class="header"></div>
	<div id="bframe_tree_container" class="bframe_tree_container bframe_splitter_pane bframe_adjustwindow" param="margin:10">
		<div id="tree_box" class="bframe_adjustparent" param="margin:1">
			<?php echo $this->tree->getHtml(); ?>
		</div>
	</div>
	<div id="splitter" name="splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<div id="filemanager_pane" class="bframe_splitter_pane bframe_adjustwindow" param="margin:10">
		<ul class="upload_control">
			<li class="select"><span id="bframe_pane_disp_change">表示 ： </span></li>
			<li>
				<div id="upload_button" class="input_container">
					<a href="index.php?terminal_id=<?php echo TERMINAL_ID ?>&module=filemanager&page=upload&method=init&session=<?php echo $this->module ?>" title="アップロード" class="upload-button bframe_upload" onclick="bstudio.activateModalWindow(this, 440, 500, bstudio.reloadTree); return false;"><img src="images/common/upload.png" alt="アップロード" />アップロード</a>
				</div>
			</li>
		</ul>
		<div class="pane_container">
			<div id="bframe_pane" class="bframe_pane bframe_adjustparent" param="margin:62"></div>
		</div>
	</div>
	<div class="footer"></div>
</body>
