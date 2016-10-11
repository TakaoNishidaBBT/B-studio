<body>
	<h2><span class="accent"></span><?php echo _('Resource manager'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="resource_tree" name="resource_tree" class="tree bframe_splitter_pane bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=resource&page=compare_tree" align="top" scrolling="no" width="24%" height="100%"></iframe>
	<div id="resource_splitter" name="resource_splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<iframe id="resource_form" name="resource_form" class="bframe_splitter_pane bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=resource&page=compare_pane&method=init" align="top" scrolling="no" height="100%"></iframe>
	<div class="footer"></div>
</body>
