<body>
	<div id="select_category_container" class="bframe_adjustparent" data-param="margin:16">
		<div id="tree_box" class="bframe_adjustparent bframe_scroll" data-param="margin:8">
			<?php echo $this->tree->getHtml(); ?>
		</div>
	</div>
	<p class="guidance"><span class="caution"><?php echo __('*'); ?></span><?php echo __('Double-click to set'); ?></p>
</body>
