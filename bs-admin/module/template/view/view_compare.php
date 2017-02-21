<body class="fadein">
	<h2><span class="accent"></span><?php echo __('Templates'); ?><span class="version-info"><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="template_tree" name="template_tree" class="tree bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=template&page=compare_tree" style="width:24%"></iframe>
	<div id="template_splitter" class="splitter bframe_splitter" data-param="margin:10"></div>
	<iframe id="template_form" name="template_form" class="form bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=template&page=compare_form&method=init"></iframe>
	<div class="footer"></div>
</body>
