<body spellcheck="false" onload="bframe.effect.fadeIn(document.body, 100, 0, 100, 50);" style="opacity:0">
	<h2><span class="accent"></span>リソース管理<span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<div id="bframe_tree_container" class="bframe_tree_container bframe_splitter_pane bframe_adjustwindow" param="margin:10">
		<div id="tree_box" class="bframe_adjustparent" param="margin:0">
			<?php echo $this->tree->getHtml(); ?>
		</div>
	</div>
	<div id="resource_splitter" name="resource_splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<div id="resource_pane" class="bframe_splitter_pane bframe_adjustwindow" param="margin:10">
		<ul class="resource_control">
			<li class="upload">
				<form id="form1" method="post" enctype="multipart/form-data">
					<div>
						<input id="upload_file" type="file" multiple="multiple" name="Filedata[]" class="bframe_uploader" style="display:none;" />
						<a href="#" title="click here or drop images to this pane"><span id="upload_button" class="upload-button"><img src="images/common/upload.png" alt="アップロード" />アップロード</span></a>
					</div>
				</form>
			</li>
			<li id="display_detail" class="view detail"><a href="#" title="list view"><img src="images/common/view_detail.png" alt="view list" /></a></li>
			<li id="display_thumbnail" class="view thumbnail"><a href="#" title="thumbnail view"><img src="images/common/view_thumbnail3.png" alt="view list" /></a></li>
		</ul>
		<div class="pane_container">
			<div id="bframe_pane" class="bframe_pane bframe_adjustparent" param="margin:64"></div>
		</div>
	</div>
	<div class="footer"></div>
</body>
