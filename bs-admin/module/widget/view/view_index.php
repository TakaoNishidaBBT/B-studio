<body>
	<h2><span class="accent"></span>ウィジェット<span><?php echo $this->version_info; ?></h2>
	<div class="header"></div>
	<iframe id="widget_tree" name="widget_tree" class="tree bframe_splitter_pain bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=widget&page=tree" align="top" scrolling="auto" width="15%" height="100%"></iframe>
	<div id="widget_splitter" name="widget_splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<iframe id="widget_form" name="widget_form" class="bframe_splitter_pain bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=widget&page=form&method=init" align="top" scrolling="auto" width="79%" height="100%"></iframe>
	<div class="footer"></div>
</body>
