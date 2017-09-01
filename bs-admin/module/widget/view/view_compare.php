<body class="fadein">
	<h2><span class="accent"></span><?php echo __('Widget'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="widget_tree" name="widget_tree" class="tree bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=widget&page=compare_tree" style="width:24%"></iframe>
	<div id="widget_splitter" name="widget_splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<iframe id="widget_form" name="widget_form" class="form bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=widget&page=compare_form&method=init"></iframe>
	<div class="footer"></div>
</body>
