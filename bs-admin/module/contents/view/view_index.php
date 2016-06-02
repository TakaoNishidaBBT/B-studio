<body onload="bframe.effect.fadeIn(document.body, 100, 0, 100, 50);" style="opacity:0">
	<script type="text/javascript">if(window == top) top.location.href='.';</script>
	<h2><span class="accent"></span>コンテンツ<span><?php echo $this->version_info; ?></span></h2>
	<div class="header"></div>
	<iframe id="contents_tree" name="contents_tree" class="tree bframe_splitter_pane bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=contents&page=tree" align="top" scrolling="no" width="15%" height="100%"></iframe>
	<div id="contents_splitter" name="contents_splitter" class="splitter bframe_splitter" param="margin:10"></div>
	<iframe id="contents_form" name="contents_form" class="bframe_splitter_pane bframe_adjustwindow" param="margin:10" frameborder="0"
		src="<?php echo DISPATCH_URL ?>&module=contents&page=form&method=init" align="top" scrolling="no" height="100%"></iframe>
	<div class="footer"></div>
</body>
