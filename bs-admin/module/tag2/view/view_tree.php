<body>
	<div id="select_category_container" class="bframe_adjustparent" data-param="margin:28">
		<?php echo $this->tree->getHtml(); ?>
	</div>
	<div class="control">
		<div class="guidance"><span class="caution"><?php echo __('*'); ?></span><span class="message"><?php echo __('To select multiple items, use Ctrl-click'); ?></span></div>
		<ul>
			<li><input type="button" class="cancel-button" value="Cancel" onclick="window.frameElement.deactivate();" /></li>
			<li><input type="button" class="register-button" value="OK" onclick="bstudio.setTags(',', 'tags', ',', 'tag_id')" /></li>
		</ul>
	</div>
</body>
