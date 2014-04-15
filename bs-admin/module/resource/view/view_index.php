<body>
	<h2><span class="accent"></span>リソース管理<span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<div id="bframe_tree_container" class="bframe_tree_container bframe_splitter_pain bframe_adjustwindow" param="margin:10">
		<div id="tree_box" class="bframe_adjustparent" param="margin:0">
			<?php echo $this->tree->getHtml(); ?>
		</div>
	</div>
	<div id="resource_splitter" name="resource_splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<div id="resource_pain" class="bframe_splitter_pain bframe_adjustwindow" param="margin:10">
		<ul class="upload_control">
			<li>
				<div id="upload_button" class="input_container">
					<a href="index.php?terminal_id=<?php echo TERMINAL_ID ?>&module=resource&page=upload" title="アップロード" class="upload-button bframe_upload" onclick="activateModalWindow(this, 440, 500, reloadTree); return false;">アップロード</a>
				</div>
			</li>
			<li class="select"><span id="bframe_pain_disp_change">表示 ： </span></li>
		</ul>
		<div class="pain_container">
			<div id="bframe_pain" class="bframe_pain bframe_adjustparent" param="margin:56"></div>
		</div>
	</div>
	<div class="footer"></div>
</body>
