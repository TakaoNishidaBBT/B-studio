<body class="fadein">
	<h2><span class="accent"></span><?php echo __('Contents'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="contents_tree" name="contents_tree" class="tree bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=contents&page=compare_tree" style="width:24%"></iframe>
	<div id="contents_splitter" class="splitter bframe_splitter" data-param="margin:10"></div>
	<iframe id="contents_form" name="contents_form" class="form bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=contents&page=compare_form&method=init"></iframe>
	<div class="footer"></div>
</body>
