<body>
	<h2><span class="accent"></span>テンプレート<span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="template_tree" name="template_tree" class="tree bframe_splitter_pain bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=template&page=compare_tree" align="top" scrolling="no" width="24%" height="100%"></iframe>
	<div id="template_splitter" name="template_splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<iframe id="template_form" name="template_form" class="bframe_splitter_pain bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=template&page=compare_form&method=init" align="top" scrolling="no" height="100%"></iframe>
	<div class="footer"></div>
</body>
