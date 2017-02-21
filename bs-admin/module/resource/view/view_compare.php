<body class="fadein">
	<h2><span class="accent"></span><?php echo __('Resource manager'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="resource_tree" name="resource_tree" class="tree bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=resource&page=compare_tree" style="width:24%"></iframe>
	<div id="resource_splitter" class="splitter bframe_splitter" data-param="margin:10"></div>
	<iframe id="resource_form" name="resource_form" class="form bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=resource&page=compare_pane&method=init"></iframe>
	<div class="footer"></div>
</body>
