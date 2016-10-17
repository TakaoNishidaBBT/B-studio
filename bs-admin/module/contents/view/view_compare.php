<body>
	<h2><span class="accent"></span><?php echo __('Contents'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="contents_tree" name="contents_tree" class="tree bframe_splitter_pane bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=contents&page=compare_tree" align="top" scrolling="no" width="24%" height="100%"></iframe>
	<div id="contents_splitter" name="contents_splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<iframe id="contents_form" name="contents_form" class="bframe_splitter_pane bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=contents&page=compare_form&method=init" align="top" scrolling="no" height="100%"></iframe>
	<div class="footer"></div>
</body>
