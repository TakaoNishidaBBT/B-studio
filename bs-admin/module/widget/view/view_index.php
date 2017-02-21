<body class="fadein">
	<h2><span class="accent"></span><?php echo __('Widgets'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="widget_tree" name="widget_tree" class="tree bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=widget&page=tree"></iframe>
	<div id="widget_splitter" class="splitter bframe_splitter" data-param="margin:10"></div>
	<iframe id="widget_form" name="widget_form" class="form bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=widget&page=form&method=init"></iframe>
	<div class="footer"></div>
</body>
