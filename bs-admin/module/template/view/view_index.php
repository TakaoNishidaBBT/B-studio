<body onload="bframe.effect.fadeIn(document.body, 100, 0, 100, 50);" style="opacity:0">
	<h2><span class="accent"></span>テンプレート<span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe class="tree bframe_splitter_pain bframe_adjustwindow" param="margin:10" frameborder="0" src="<?php echo DISPATCH_URL ?>&module=template&page=tree"
			name="template_tree" id="template_tree" align="top" scrolling="auto" width="15%" height="100%"></iframe>
	<div class="splitter bframe_splitter" name="template_splitter" param="margin:10" id="template_splitter"></div>
	<iframe class="form bframe_splitter_pain bframe_adjustwindow" param="margin:10" frameborder="0" src="<?php echo DISPATCH_URL ?>&module=template&page=form&method=init"
			name="template_form" id="template_form" align="top" scrolling="auto" width="80%" height="100%"></iframe>
	<div class="footer"></div>
</body>
