<body onload="bframe.effect.fadeIn(document.body, 100, 0, 100, 50);" style="opacity:0">
	<script type="text/javascript">if(window == top) top.location.href='.';</script>
	<h2><span class="accent"></span><?php echo __('Contents'); ?><span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="contents_tree" name="contents_tree" class="tree bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=contents&page=tree"></iframe>
	<div id="contents_splitter" class="splitter bframe_splitter" data-param="margin:10"></div>
	<iframe id="contents_form" name="contents_form" class="form bframe_splitter_pane bframe_adjustwindow" data-param="margin:10"
		src="<?php echo DISPATCH_URL ?>&module=contents&page=form&method=init"></iframe>
	<div class="footer"></div>
</body>
